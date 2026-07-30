<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TontineController as AdminTontineController;
use App\Http\Controllers\TontineController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Personal dashboard
|--------------------------------------------------------------------------
| Fixed bug: previously the bare "DashboardController" import resolved to
| Admin\DashboardController (imported without alias), so this route was
| silently serving the admin dashboard instead of the personal one.
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::post('/dashboard/dismiss-activity/{transactionId}', function ($transactionId) {
    \DB::table('dismissed_activities')->insertOrIgnore([
        'user_id' => auth()->id(),
        'wallet_transaction_id' => $transactionId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    return response()->json(['status' => 'dismissed']);
})->name('dashboard.dismiss-activity');

/*
|--------------------------------------------------------------------------
| Authenticated member/organizer routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // --- Tontines: creation, browsing, joining, membership management ---
    Route::get('/my-tontines', [TontineController::class, 'index'])->name('tontines.index');
    Route::get('/tontines/create', [TontineController::class, 'create'])->name('tontines.create');
    Route::post('/tontines', [TontineController::class, 'store'])->name('tontines.store');
    Route::get('/tontines/browse', [TontineController::class, 'browse'])->name('tontines.browse');
    Route::get('/tontines/{id}', [TontineController::class, 'show'])->name('tontines.show');
    Route::get('/tontines/{id}/edit', [TontineController::class, 'edit'])->name('tontines.edit');
    Route::put('/tontines/{id}', [TontineController::class, 'update'])->name('tontines.update');
    Route::post('/tontines/{id}/join', [TontineController::class, 'join'])->name('tontines.join');
    Route::get('/tontines/{id}/manage-members', [TontineController::class, 'manageMembers'])->name('tontines.manage-members');
    Route::post('/tontines/{id}/approve/{userId}', [TontineController::class, 'approveMember'])->name('tontines.approve-member');
    Route::delete('/tontines/{id}/reject/{userId}', [TontineController::class, 'rejectMember'])->name('tontines.reject-member');
    Route::delete('/tontines/{id}/remove/{userId}', [TontineController::class, 'removeMember'])->name('tontines.remove-member');
    Route::post('/tontines/{tontineId}/update-positions', [TontineController::class, 'updatePositions'])->name('tontines.update-positions');
    Route::post('/tontine-flags/{flag}/resolve', [TontineController::class, 'resolveFlag'])->name('tontines.resolve-flag');
    Route::post('/tontines/{id}/renew', [TontineController::class, 'renew'])->name('tontines.renew');
    Route::delete('/tontines/{id}', [TontineController::class, 'destroy'])->name('tontines.destroy');
    
    // --- Wallet: deposit, withdraw, balance/history ---
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');

    // --- Contributions ---
    Route::get('/tontines/{id}/contribute', [TontineController::class, 'contributeForm'])->name('tontines.contribute');
    Route::post('/tontines/{tontine}/contributions', [ContributionController::class, 'store'])->name('contributions.store');
    Route::get('/contributions', [ContributionController::class, 'mycontributions'])->name('contributions.index');
    Route::get('/organizer/approvals', [ContributionController::class, 'approvals'])->name('contributions.approvals');

    // --- Payouts ---
    Route::get('/my-payouts', [PayoutController::class, 'index'])->name('payouts.index');
    Route::get('/tontines/{tontine}/payouts', [PayoutController::class, 'showTontinePayouts'])->name('tontines.payouts');

    // --- Reports (personal + per-tontine, with PDF/Excel export) ---
    Route::get('/my-report', [ReportController::class, 'myReport'])->name('reports.my-report');
    Route::get('/my-report/pdf', [ReportController::class, 'myReportPdf'])->name('reports.my-report-pdf');
    Route::get('/my-report/excel', [ReportController::class, 'myReportExcel'])->name('reports.my-report-excel');
    Route::get('/tontines/{id}/report', [ReportController::class, 'tontineSummary'])->name('reports.tontine-summary');
    Route::get('/tontines/{id}/report/pdf', [ReportController::class, 'tontineSummaryPdf'])->name('reports.tontine-summary-pdf');
    Route::get('/tontines/{id}/report/excel', [ReportController::class, 'tontineSummaryExcel'])->name('reports.tontine-summary-excel');

    // --- Notifications ---
    Route::post('/notifications/mark-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'ok']);
    })->name('notifications.mark-read');
    Route::delete('/notifications/{id}', function ($id) {
    auth()->user()->notifications()->where('id', $id)->delete();
    return response()->json(['status' => 'deleted']);
    })->name('notifications.destroy');

    // --- Profile (Breeze default) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Temporary dev-only route, kept for role-check sanity testing ---
    // TODO: remove before any real deployment
    Route::middleware(['auth', 'role:super_admin'])->get('/test-admin', function () {
        return 'You are a super admin!';
    });
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Webhooks (no auth — called by NotchPay's servers directly)
|--------------------------------------------------------------------------
*/
Route::post('/webhooks/notchpay', [WebhookController::class, 'handle'])->name('webhooks.notchpay');

/*
|--------------------------------------------------------------------------
| Super Admin routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::post('/users/{user}/update-role', [AdminUserController::class, 'updateRole'])->name('users.update-role');

    Route::get('/tontines', [AdminTontineController::class, 'index'])->name('tontines.index');
    Route::post('/tontines/{tontine}/suspend', [AdminTontineController::class, 'suspend'])->name('tontines.suspend');
    Route::post('/tontines/{tontine}/reactivate', [AdminTontineController::class, 'reactivate'])->name('tontines.reactivate');
});