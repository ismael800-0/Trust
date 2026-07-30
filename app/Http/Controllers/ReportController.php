<?php

namespace App\Http\Controllers;

use App\Models\Tontine;
use App\Models\Contribution;
use App\Models\Payout;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    /**
     * Financial summary for a single tontine (organizer only).
     */
    public function tontineSummary($tontineId)
    {
        $tontine = Tontine::findOrFail($tontineId);

        if (Auth::id() !== $tontine->creator_id) {
            abort(403, 'Only the organizer can view this report.');
        }

        $data = $this->buildTontineSummaryData($tontine);

        return view('reports.tontine-summary', $data);
    }

    public function tontineSummaryPdf($tontineId)
    {
        $tontine = Tontine::findOrFail($tontineId);

        if (Auth::id() !== $tontine->creator_id) {
            abort(403);
        }

        $data = $this->buildTontineSummaryData($tontine);

        $pdf = Pdf::loadView('reports.tontine-summary-pdf', $data);

        return $pdf->download("tontine-{$tontine->id}-summary.pdf");
    }

    public function tontineSummaryExcel($tontineId)
    {
        $tontine = Tontine::findOrFail($tontineId);

        if (Auth::id() !== $tontine->creator_id) {
            abort(403);
        }

        $data = $this->buildTontineSummaryData($tontine);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Tontine Report: ' . $tontine->name);
        $sheet->setCellValue('A3', 'Total Contributions');
        $sheet->setCellValue('B3', $data['totalContributions']);
        $sheet->setCellValue('A4', 'Total Payouts');
        $sheet->setCellValue('B4', $data['totalPayouts']);
        $sheet->setCellValue('A5', 'Current Round');
        $sheet->setCellValue('B5', $tontine->current_round);
        $sheet->setCellValue('A6', 'Rounds Completed');
        $sheet->setCellValue('B6', $tontine->total_rounds_completed);

        $sheet->setCellValue('A8', 'Member');
        $sheet->setCellValue('B8', 'Total Contributed');
        $sheet->setCellValue('C8', 'Total Received');

        $row = 9;
        foreach ($data['memberBreakdown'] as $member) {
            $sheet->setCellValue("A{$row}", $member['name']);
            $sheet->setCellValue("B{$row}", $member['contributed']);
            $sheet->setCellValue("C{$row}", $member['received']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = "tontine-{$tontine->id}-summary.xlsx";

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }

    /**
     * Personal report across all tontines the user belongs to.
     */
   public function myReport()
{
    $data = $this->buildMyReportData();
    return view('reports.my-report', $data);
}

private function buildMyReportData()
{
    $userId = Auth::id();

    $totalContributed = Contribution::where('user_id', $userId)->sum('amount');
    $totalReceived = Payout::where('beneficiary_id', $userId)->sum('amount');

    $tontineBreakdown = Tontine::whereHas('members', function ($q) use ($userId) {
        $q->where('user_id', $userId)->where('status', 'active');
    })->get()->map(function ($tontine) use ($userId) {
        return [
            'name' => $tontine->name,
            'contributed' => Contribution::where('tontine_id', $tontine->id)->where('user_id', $userId)->sum('amount'),
            'received' => Payout::where('tontine_id', $tontine->id)->where('beneficiary_id', $userId)->sum('amount'),
        ];
    });

    return compact('totalContributed', 'totalReceived', 'tontineBreakdown');
}

    private function buildTontineSummaryData(Tontine $tontine)
    {
        $totalContributions = Contribution::where('tontine_id', $tontine->id)->sum('amount');
        $totalPayouts = Payout::where('tontine_id', $tontine->id)->sum('amount');

        $activeMembers = $tontine->members()->wherePivot('status', 'active')->get();

        $memberBreakdown = $activeMembers->map(function ($member) use ($tontine) {
            return [
                'name' => $member->name,
                'contributed' => Contribution::where('tontine_id', $tontine->id)->where('user_id', $member->id)->sum('amount'),
                'received' => Payout::where('tontine_id', $tontine->id)->where('beneficiary_id', $member->id)->sum('amount'),
            ];
        });

        return [
            'tontine' => $tontine,
            'totalContributions' => $totalContributions,
            'totalPayouts' => $totalPayouts,
            'memberBreakdown' => $memberBreakdown,
        ];
    }

    public function myReportPdf()
{
    $data = $this->buildMyReportData();

    $pdf = Pdf::loadView('reports.my-report-pdf', $data);

    return $pdf->download('my-financial-report.pdf');
}

public function myReportExcel()
{
    $data = $this->buildMyReportData();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'My Financial Report');
    $sheet->setCellValue('A3', 'Total Contributed');
    $sheet->setCellValue('B3', $data['totalContributed']);
    $sheet->setCellValue('A4', 'Total Received');
    $sheet->setCellValue('B4', $data['totalReceived']);

    $sheet->setCellValue('A6', 'Tontine');
    $sheet->setCellValue('B6', 'Contributed');
    $sheet->setCellValue('C6', 'Received');

    $row = 7;
    foreach ($data['tontineBreakdown'] as $t) {
        $sheet->setCellValue("A{$row}", $t['name']);
        $sheet->setCellValue("B{$row}", $t['contributed']);
        $sheet->setCellValue("C{$row}", $t['received']);
        $row++;
    }

    $writer = new Xlsx($spreadsheet);

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, 'my-financial-report.xlsx');
}
}