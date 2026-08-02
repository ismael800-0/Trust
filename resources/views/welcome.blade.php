<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'TRUST') }} — Tontine Management, Built on Trust</title>
<meta name="description" content="TRUST replaces the paper ledger and the single trusted treasurer with a shared, auditable record — so your tontine runs on transparency, not memory.">

<style>
/* ============ TOKENS ============ */
:root{
  --ink:#0F2A22;
  --ink-2:#173A2F;
  --paper:#F6F1E4;
  --paper-dim:#EDE6D3;
  --paper-soft:rgba(246,241,228,.68);
  --gold:#C99A3B;
  --gold-bright:#E3B94D;
  --emerald:#1F8A6F;
  --rust:#A23B2E;
  --ink-soft:rgba(15,42,34,.64);
  --ink-faint:rgba(15,42,34,.38);
  --line:rgba(15,42,34,.14);
  --line-on-ink:rgba(246,241,228,.16);

  --f-display: Georgia, 'Iowan Old Style', 'Palatino Linotype', 'Book Antiqua', ui-serif, serif;
  --f-body: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
  --f-mono: ui-monospace, SFMono-Regular, 'SF Mono', Menlo, Consolas, 'Liberation Mono', monospace;

  --container: 1180px;
}

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{
  font-family:var(--f-body);
  background:var(--paper);
  color:var(--ink);
  line-height:1.55;
  -webkit-font-smoothing:antialiased;
  overflow-x:hidden;
}
img,svg{display:block;max-width:100%}
a{color:inherit;text-decoration:none}
ul{list-style:none}
button{font:inherit;background:none;border:none;cursor:pointer;color:inherit}
input,textarea{font:inherit}

.wrap{max-width:var(--container);margin:0 auto;padding:0 28px}
@media (max-width:640px){.wrap{padding:0 20px}}

.eyebrow{
  font-family:var(--f-mono);
  font-size:11.5px;
  letter-spacing:.14em;
  text-transform:uppercase;
  color:var(--gold);
  font-weight:600;
  display:inline-flex;
  align-items:center;
  gap:8px;
}
.eyebrow::before{
  content:"";
  width:14px;height:1px;
  background:var(--gold);
  display:inline-block;
}

h1,h2,h3{font-family:var(--f-display);font-weight:400;line-height:1.08;letter-spacing:-.01em}
.italic{font-style:italic;color:var(--emerald)}

a:focus-visible,button:focus-visible,input:focus-visible,textarea:focus-visible{
  outline:2px solid var(--emerald);
  outline-offset:3px;
  border-radius:2px;
}

.skip-link{
  position:absolute;left:-999px;top:0;
  background:var(--ink);color:var(--paper);
  padding:12px 18px;z-index:200;font-family:var(--f-mono);font-size:13px;
}
.skip-link:focus{left:16px;top:16px}

/* ============ REVEAL ============ */
.reveal{opacity:0;transform:translateY(22px) scale(.98);transition:opacity .7s cubic-bezier(.22,1,.36,1), transform .7s cubic-bezier(.22,1,.36,1)}
.reveal.in{opacity:1;transform:translateY(0) scale(1)}
.reveal-stagger > *{transition-delay:calc(var(--i,0) * 90ms)}

@media (prefers-reduced-motion: reduce){
  *,*::before,*::after{
    animation-duration:.001ms !important;
    animation-iteration-count:1 !important;
    transition-duration:.15s !important;
    scroll-behavior:auto !important;
  }
  .reveal{opacity:1;transform:none}
}

/* ============ BUTTONS ============ */
.btn{
  display:inline-flex;align-items:center;justify-content:center;gap:8px;
  padding:14px 26px;
  font-family:var(--f-body);
  font-size:14.5px;
  font-weight:600;
  border-radius:3px;
  transition:transform .25s cubic-bezier(.2,.8,.2,1), box-shadow .25s ease, background .25s ease, border-color .25s ease;
  white-space:nowrap;
}
.btn-primary{
  background:var(--gold);
  color:var(--ink);
  box-shadow:0 1px 0 rgba(15,42,34,.2);
}
.btn-primary:hover{background:var(--gold-bright);transform:translateY(-2px);box-shadow:0 10px 24px -8px rgba(201,154,59,.55)}
.btn-ghost{
  border:1px solid var(--line);
  color:var(--ink);
  background:transparent;
}
.btn-ghost:hover{border-color:var(--ink);transform:translateY(-2px)}
.btn-ghost.on-ink{border-color:var(--line-on-ink);color:var(--paper)}
.btn-ghost.on-ink:hover{border-color:var(--paper)}

/* ============ HEADER ============ */
header{
  position:fixed;top:0;left:0;right:0;z-index:100;
  padding:22px 0;
  transition:padding .35s ease, background .35s ease, box-shadow .35s ease, border-color .35s ease;
  border-bottom:1px solid transparent;
}
header.solid{
  padding:14px 0;
  background:rgba(246,241,228,.92);
  backdrop-filter:blur(10px);
  border-bottom-color:var(--line);
}
.nav-row{display:flex;align-items:center;justify-content:space-between;gap:24px}
.brand{display:flex;align-items:center;gap:10px;font-family:var(--f-display);font-size:21px;letter-spacing:.01em}
.brand-mark{width:30px;height:30px;flex-shrink:0}
.brand-logo-img{height:46px;width:auto;display:block;border-radius:50%;transition:transform .4s cubic-bezier(.34,1.56,.64,1)}
.brand:hover .brand-logo-img{transform:scale(1.08) rotate(-4deg)}
.footer-brand .brand-logo-img{height:64px}
.brand small{display:block;font-family:var(--f-mono);font-size:9px;letter-spacing:.16em;color:var(--ink-soft);font-weight:600;margin-top:1px}

nav.primary-links{display:flex;align-items:center;gap:36px}
nav.primary-links a{
  font-size:14px;font-weight:500;color:var(--ink-soft);
  position:relative;padding:4px 0;
  transition:color .25s ease;
}
nav.primary-links a::after{
  content:"";position:absolute;left:0;right:100%;bottom:0;height:1px;background:var(--emerald);
  transition:right .3s cubic-bezier(.2,.8,.2,1);
}
nav.primary-links a:hover{color:var(--ink)}
nav.primary-links a:hover::after,nav.primary-links a.active::after{right:0}
nav.primary-links a.active{color:var(--ink)}

.header-cta{display:flex;align-items:center;gap:14px}
.header-cta a.login{font-size:14px;font-weight:600;color:var(--ink)}

.burger{display:none;flex-direction:column;gap:5px;width:26px}
.burger span{height:2px;background:var(--ink);border-radius:2px;transition:transform .3s ease, opacity .3s ease}
.burger.open span:nth-child(1){transform:translateY(7px) rotate(45deg)}
.burger.open span:nth-child(2){opacity:0}
.burger.open span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}

.mobile-panel{
  display:none;
  position:fixed;top:0;right:0;bottom:0;
  width:min(320px,84vw);
  background:var(--ink);
  color:var(--paper);
  z-index:150;
  padding:100px 32px 40px;
  transform:translateX(100%);
  box-shadow:-24px 0 60px -20px rgba(0,0,0,.5);
  transition:transform .4s cubic-bezier(.2,.8,.2,1);
}
.mobile-panel.open{transform:translateX(0)}
.mobile-panel a{display:block;padding:16px 0;font-family:var(--f-display);font-size:24px;border-bottom:1px solid var(--line-on-ink)}
.scrim{display:none;position:fixed;inset:0;background:rgba(15,42,34,.5);z-index:140;opacity:0;transition:opacity .35s ease}
.scrim.open{display:block;opacity:1}

@media (max-width:900px){
  nav.primary-links,.header-cta a.login{display:none}
  .burger{display:flex}
  .mobile-panel{display:block}
}

/* ============ HERO ============ */
.hero{
  padding:168px 0 100px;
  position:relative;
  overflow:hidden;
}
.hero::before{
  content:"";
  position:absolute;top:-200px;right:-160px;width:640px;height:640px;
  background:radial-gradient(circle, rgba(201,154,59,.14), transparent 70%);
  pointer-events:none;
}
.hero-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:64px;align-items:center}
@media (max-width:960px){.hero-grid{grid-template-columns:1fr;gap:56px}.hero{padding-top:132px}}

.hero h1{font-size:clamp(38px,5.4vw,60px);margin:20px 0 22px}
.hero p.lede{font-size:17.5px;color:var(--ink-soft);max-width:480px;margin-bottom:34px}
.hero-ctas{display:flex;gap:14px;flex-wrap:wrap;margin-bottom:46px}

.stat-row{display:flex;gap:14px;flex-wrap:wrap;border-top:1px solid var(--line);padding-top:16px}
.stat-chip{
  padding:14px 22px;margin-right:0;border:1px solid var(--line);border-radius:6px;
  background:var(--paper-dim);
  box-shadow:0 10px 24px -18px rgba(15,42,34,.3);
  transition:transform .4s cubic-bezier(.34,1.56,.64,1), box-shadow .4s ease, border-color .4s ease;
}
.stat-chip:hover{
  transform:translateY(-5px);
  border-color:var(--gold);
  box-shadow:0 22px 40px -18px rgba(15,42,34,.35);
}
.stat-chip:last-child{border-right:1px solid var(--line)}
.stat-chip .num{font-family:var(--f-mono);font-size:12px;color:var(--emerald);font-weight:700;letter-spacing:.03em}
.stat-chip .lbl{font-size:13px;color:var(--ink-soft);margin-top:2px}

/* ---- Rotation ring (signature element) ---- */
.ring-wrap{position:relative;display:flex;align-items:center;justify-content:center}
.ring-svg{width:min(440px,100%);height:auto;overflow:visible}
.ring-track{fill:none;stroke:var(--line);stroke-width:1.5}
.ring-sweep{
  fill:none;stroke:var(--gold);stroke-width:3;stroke-linecap:round;
  stroke-dasharray:70 930;
  transform-origin:200px 200px;
  animation:spin 11s linear infinite;
  filter:drop-shadow(0 0 6px rgba(201,154,59,.55));
}
@keyframes spin{to{transform:rotate(360deg)}}

.node{transition:transform .3s ease}
.node circle.base{fill:var(--paper);stroke:var(--line);stroke-width:1.5}
.node.active circle.base{fill:var(--gold);stroke:var(--gold)}
.node.active circle.pulse{
  fill:none;stroke:var(--gold);stroke-width:1.5;opacity:.55;
  animation:pulse 2.4s ease-out infinite;
  transform-origin:center;
}
@keyframes pulse{0%{stroke-opacity:.6;r:22}100%{stroke-opacity:0;r:38}}
.node text{font-family:var(--f-mono);font-size:12px;fill:var(--ink);font-weight:600}
.node.active text{fill:var(--ink)}

.ring-center-card{
  position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);
  background:var(--paper);border:1px solid var(--line);border-radius:6px;
  padding:20px 22px;width:190px;text-align:center;
  box-shadow:0 30px 60px -20px rgba(15,42,34,.4), 0 6px 16px -6px rgba(201,154,59,.25);
  animation:cardFloat 4.5s ease-in-out infinite;
  transition:box-shadow .4s ease;
}
.ring-center-card:hover{box-shadow:0 40px 80px -18px rgba(15,42,34,.5), 0 10px 22px -6px rgba(201,154,59,.4)}
@keyframes cardFloat{0%,100%{transform:translate(-50%,-50%) translateY(0)}50%{transform:translate(-50%,-50%) translateY(-8px)}}
.ring-center-card .k{font-family:var(--f-mono);font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:var(--ink-faint)}
.ring-center-card .name{font-family:var(--f-display);font-size:20px;margin:6px 0 4px}
.ring-center-card .amt{font-family:var(--f-mono);font-size:14px;color:var(--emerald);font-weight:700}
.ring-center-card .ref{font-family:var(--f-mono);font-size:9.5px;color:var(--ink-faint);margin-top:8px}

/* ============ SECTIONS GENERAL ============ */
section{padding:108px 0}
@media (max-width:640px){section{padding:74px 0}}
.section-head{max-width:640px;margin-bottom:56px}
.section-head h2{font-size:clamp(28px,3.6vw,40px);margin-top:16px}
.section-head p{color:var(--ink-soft);font-size:16px;margin-top:16px}

.on-ink{background:var(--ink);color:var(--paper)}
.on-ink .section-head p{color:var(--paper-soft)}
.on-ink .eyebrow{color:var(--gold-bright)}

/* ============ HOW IT WORKS ============ */
.steps{
  display:grid;grid-template-columns:repeat(4,1fr);gap:0;
  position:relative;
}
.steps::before{
  content:"";position:absolute;top:38px;left:0;right:0;height:1px;background:var(--line-on-ink);
}
@media (max-width:860px){.steps{grid-template-columns:1fr;gap:40px}.steps::before{display:none}}
.step{position:relative;padding-right:24px}
.step .n{
  font-family:var(--f-mono);font-size:12px;color:var(--gold-bright);
  background:var(--ink);display:inline-block;padding-right:14px;position:relative;z-index:2;
  font-weight:700;letter-spacing:.04em;
}
.step .dot{
  width:9px;height:9px;border-radius:50%;background:var(--gold);
  position:absolute;top:33px;left:0;box-shadow:0 0 0 5px var(--ink);
}
@media (max-width:860px){.step .dot{display:none}}
.step h3{font-size:20px;margin:18px 0 10px;font-family:var(--f-body);font-weight:700}
.step p{color:var(--paper-soft);font-size:14.5px}

/* ============ FEATURE CARDS ============ */
.feature-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
@media (max-width:860px){.feature-grid{grid-template-columns:1fr}}
.feature-card{
  position:relative;
  overflow:hidden;
  border:1px solid var(--line);border-radius:6px;padding:32px 28px;
  background:var(--paper-dim);
  box-shadow:0 14px 34px -22px rgba(15,42,34,.32), 0 2px 6px -2px rgba(15,42,34,.1);
  transition:transform .5s cubic-bezier(.34,1.56,.64,1), box-shadow .5s cubic-bezier(.34,1.56,.64,1), border-color .35s ease;
}
.feature-card::before{
  content:"";
  position:absolute;top:0;left:-60%;width:40%;height:100%;
  background:linear-gradient(120deg, transparent, rgba(255,255,255,.5), transparent);
  transform:skewX(-20deg);
  transition:left .8s ease;
  pointer-events:none;
}
.feature-card:hover::before{left:130%}
.feature-card:hover{
  transform:translateY(-10px) scale(1.02) rotate(-0.4deg);
  border-color:var(--gold);
  box-shadow:0 40px 70px -24px rgba(15,42,34,.42), 0 8px 18px -6px rgba(201,154,59,.25);
}
.feature-card .icon{transition:transform .5s cubic-bezier(.34,1.56,.64,1)}
.feature-card:hover .icon{transform:rotate(-8deg) scale(1.12)}
.feature-card .icon{
  width:42px;height:42px;border-radius:50%;
  background:var(--ink);color:var(--gold-bright);
  display:flex;align-items:center;justify-content:center;
  margin-bottom:22px;
}
.feature-card .icon svg{width:20px;height:20px}
.feature-card h3{font-size:19px;font-family:var(--f-body);font-weight:700;margin-bottom:10px}
.feature-card p{font-size:14.5px;color:var(--ink-soft)}

/* ============ ABOUT ============ */
.about-grid{display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:start}
@media (max-width:860px){.about-grid{grid-template-columns:1fr;gap:36px}}
.about-grid p{color:var(--ink-soft);font-size:16px;margin-bottom:18px}
.fact-list{display:flex;flex-direction:column;gap:0;border-top:1px solid var(--line)}
.fact-list li{
  display:flex;justify-content:space-between;gap:20px;
  padding:20px 0;border-bottom:1px solid var(--line);
  font-size:14.5px;
}
.fact-list li span:first-child{color:var(--ink-soft)}
.fact-list li span:last-child{font-family:var(--f-mono);font-weight:600;text-align:right}

/* ============ SUPPORT / FAQ ============ */
.faq{border-top:1px solid var(--line)}
.faq-item{border-bottom:1px solid var(--line);transition:background .3s ease}
.faq-item:hover{background:rgba(246,241,228,.04)}
.faq-q{
  width:100%;display:flex;align-items:center;justify-content:space-between;
  padding:26px 4px;font-size:17px;font-weight:600;text-align:left;
  transition:padding-left .3s cubic-bezier(.34,1.56,.64,1);
}
.faq-item:hover .faq-q{padding-left:10px}
.faq-q .plus{
  width:22px;height:22px;flex-shrink:0;position:relative;margin-left:20px;
}
.faq-q .plus::before,.faq-q .plus::after{
  content:"";position:absolute;background:var(--ink);
  transition:transform .3s ease;
}
.faq-q .plus::before{top:50%;left:0;right:0;height:1.5px;transform:translateY(-50%)}
.faq-q .plus::after{left:50%;top:0;bottom:0;width:1.5px;transform:translateX(-50%)}
.faq-item.open .plus::after{transform:translateX(-50%) rotate(90deg);opacity:0}
.faq-a{max-height:0;overflow:hidden;transition:max-height .4s ease}
.faq-a p{color:var(--ink-soft);font-size:15px;padding-bottom:26px;max-width:600px}
.support-cta{
  margin-top:44px;padding:28px 30px;border:1px solid var(--line);border-radius:6px;
  display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;
  background:var(--paper-dim);
  box-shadow:0 20px 46px -20px rgba(0,0,0,.45);
  transition:transform .4s cubic-bezier(.34,1.56,.64,1), box-shadow .4s ease, border-color .4s ease;
}
.support-cta:hover{
  transform:translateY(-6px);
  border-color:var(--gold);
  box-shadow:0 32px 64px -18px rgba(0,0,0,.5);
}
.support-cta p{font-size:15px;color:var(--ink-soft)}

/* ============ LOCATION ============ */
.loc-grid{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center}
@media (max-width:860px){.loc-grid{grid-template-columns:1fr}}
.loc-visual{
  aspect-ratio:1/1;max-width:380px;
  border-radius:50%;
  background:radial-gradient(circle at center, var(--paper-dim) 0%, var(--paper-dim) 30%, transparent 31%),
             repeating-radial-gradient(circle at center, transparent 0 44px, var(--line) 45px 46px);
  position:relative;
  display:flex;align-items:center;justify-content:center;
  margin:0 auto;
}
.loc-visual .pin{
  width:16px;height:16px;border-radius:50%;background:var(--rust);
  box-shadow:0 0 0 8px rgba(162,59,46,.14);
  position:relative;
}
.loc-visual .pin::after{
  content:"";position:absolute;inset:-30px;border-radius:50%;
  border:1px solid rgba(162,59,46,.35);
  animation:ripple 2.6s ease-out infinite;
}
@keyframes ripple{0%{transform:scale(.4);opacity:.9}100%{transform:scale(1.6);opacity:0}}
.loc-card{border-top:1px solid var(--line);padding-top:18px}
.loc-card .row{
  display:flex;justify-content:space-between;padding:14px 16px;border-bottom:1px solid var(--line);font-size:14.5px;
  border-radius:4px;transition:transform .3s cubic-bezier(.34,1.56,.64,1), background .3s ease, padding .3s ease;
}
.loc-card .row:hover{background:var(--paper-dim);transform:translateX(6px)}
.loc-card .row span:first-child{color:var(--ink-soft)}
.loc-card .row span:last-child{font-weight:600;text-align:right}

/* ============ CONTACT ============ */
.contact-grid{display:grid;grid-template-columns:1.1fr .9fr;gap:56px}
@media (max-width:860px){.contact-grid{grid-template-columns:1fr}}
.field{margin-bottom:20px}
.field label{display:block;font-family:var(--f-mono);font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--ink-soft);margin-bottom:8px}
.field input,.field textarea{
  width:100%;border:1px solid var(--line);background:var(--paper-dim);
  border-radius:4px;padding:13px 14px;font-size:15px;color:var(--ink);
  transition:border-color .25s ease, background .25s ease;
}
.field input:focus,.field textarea:focus{border-color:var(--emerald);background:var(--paper)}
.field textarea{resize:vertical;min-height:120px}
.form-note{font-size:12.5px;color:var(--ink-faint);margin-top:6px}
.form-success{
  display:none;align-items:center;gap:10px;
  padding:14px 16px;border:1px solid var(--emerald);border-radius:4px;
  color:var(--emerald);font-size:14px;font-weight:600;margin-top:16px;
}
.form-success.show{display:flex}

.contact-info-list{display:flex;flex-direction:column;gap:26px}
.contact-info-list .item .k{font-family:var(--f-mono);font-size:11px;letter-spacing:.08em;text-transform:uppercase;color:var(--ink-faint);margin-bottom:6px}
.contact-info-list .item .v{font-size:17px}
.contact-info-list .item .v a{border-bottom:1px solid transparent;transition:border-color .25s ease}
.contact-info-list .item .v a:hover{border-color:var(--ink)}

/* ============ FOOTER ============ */
footer{background:var(--ink);color:var(--paper);padding:88px 0 0;position:relative;overflow:hidden}
.footer-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr 1fr;gap:40px;padding-bottom:64px;border-bottom:1px solid var(--line-on-ink)}
@media (max-width:860px){.footer-grid{grid-template-columns:1fr 1fr;row-gap:44px}}
@media (max-width:520px){.footer-grid{grid-template-columns:1fr}}
.footer-brand .brand{color:var(--paper)}
.footer-brand p{color:var(--paper-soft);font-size:14.5px;margin:18px 0 26px;max-width:280px}
.footer-col h4{font-family:var(--f-mono);font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:var(--gold-bright);margin-bottom:18px}
.footer-col a,.footer-col p{display:block;font-size:14.5px;color:var(--paper-soft);margin-bottom:12px;transition:color .25s ease, transform .25s ease}
.footer-col a:hover{color:var(--paper);transform:translateX(3px)}
.badge-row{display:flex;flex-wrap:wrap;gap:8px;margin-top:6px}
.badge{
  font-family:var(--f-mono);font-size:10.5px;letter-spacing:.03em;
  border:1px solid var(--line-on-ink);border-radius:20px;padding:6px 12px;color:var(--paper-soft);
  transition:transform .35s cubic-bezier(.34,1.56,.64,1), border-color .35s ease, background .35s ease, color .35s ease;
  display:inline-block;
}
.badge:hover{transform:translateY(-4px) scale(1.05);border-color:var(--gold);background:rgba(201,154,59,.14);color:var(--paper)}
.social-row{display:flex;gap:14px;margin-top:8px}
.social-row a{
  width:36px;height:36px;border:1px solid var(--line-on-ink);border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  box-shadow:0 8px 20px -12px rgba(0,0,0,.6);
  transition:border-color .35s cubic-bezier(.34,1.56,.64,1), transform .35s cubic-bezier(.34,1.56,.64,1), background .35s ease, box-shadow .35s ease;
}
.social-row a:hover{border-color:var(--gold);background:rgba(201,154,59,.14);transform:translateY(-5px) scale(1.1) rotate(-6deg);box-shadow:0 14px 28px -10px rgba(201,154,59,.4)}
.social-row svg{width:15px;height:15px}

.footer-bottom{
  display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap;
  padding:26px 0;font-size:12.5px;color:var(--ink-faint);
  color:var(--paper-soft);
}
.footer-bottom .ledger{font-family:var(--f-mono);display:flex;align-items:center;gap:8px}
.footer-bottom .ledger .dot{width:6px;height:6px;border-radius:50%;background:var(--gold);animation:blink 2s ease-in-out infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.25}}
</style>
</head>
<body>

<a href="#main" class="skip-link">Skip to content</a>
<div class="scroll-progress" id="scrollProgress"></div>

<!-- ============ HEADER ============ -->
<header id="siteHeader">
  <div class="wrap nav-row">
    <a href="#home" class="brand">
      <img src="{{ asset('images/trust-logo.png') }}" alt="TRUST — Community. Savings. Unity." class="brand-logo-img">
    </a>

    <nav class="primary-links" aria-label="Primary">
      <a href="#home" class="active">Home</a>
      <a href="#about">About Us</a>
      <a href="#support">Support</a>
      <a href="#location">Location</a>
      <a href="#contact">Contact</a>
    </nav>

    <div class="header-cta">
      @if (Route::has('login'))
        @auth
          <a href="{{ url('/dashboard') }}" class="btn btn-ghost">Dashboard</a>
        @else
          <a href="{{ route('login') }}" class="login">Log in</a>
          @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
          @endif
        @endauth
      @else
        <a href="#contact" class="btn btn-primary">Get started</a>
      @endif
      <button class="burger" id="burgerBtn" aria-label="Open menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>

<div class="scrim" id="scrim"></div>
<div class="mobile-panel" id="mobilePanel" aria-hidden="true">
  <a href="#home">Home</a>
  <a href="#about">About Us</a>
  <a href="#support">Support</a>
  <a href="#location">Location</a>
  <a href="#contact">Contact</a>
  <div style="margin-top:32px;display:flex;flex-direction:column;gap:12px">
    @if (Route::has('login'))
      @auth
        <a href="{{ url('/dashboard') }}" class="btn btn-primary" style="width:100%;border-bottom:none">Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="btn btn-ghost on-ink" style="width:100%;border-bottom:none">Log in</a>
        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="btn btn-primary" style="width:100%;border-bottom:none">Register</a>
        @endif
      @endauth
    @else
      <a href="#contact" class="btn btn-primary" style="width:100%;border-bottom:none">Get started</a>
    @endif
  </div>
</div>

<main id="main">

<!-- ============ HERO ============ -->
<section class="hero" id="home">
  <div class="wrap hero-grid">
    <div>
      <span class="eyebrow reveal">Digital njangi, built on trust</span>
      <h1 class="reveal">Every contribution tracked.<br>Every turn, <span class="italic">fair</span>.</h1>
      <p class="lede reveal">TRUST replaces the paper ledger and the single trusted treasurer with a shared, auditable record — so your tontine runs on transparency, not memory.</p>
      <div class="hero-ctas reveal">
        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="btn btn-primary">Start a tontine</a>
        @else
          <a href="#contact" class="btn btn-primary">Start a tontine</a>
        @endif
        <a href="#how-it-works" class="btn btn-ghost">See how rotation works</a>
      </div>
      <div class="stat-row reveal">
        <div class="stat-chip"><div class="num">MTN &amp; ORANGE</div><div class="lbl">Mobile money, both networks</div></div>
        <div class="stat-chip"><div class="num">IMMUTABLE</div><div class="lbl">Ledger, every transaction</div></div>
        <div class="stat-chip"><div class="num">AUTOMATED</div><div class="lbl">Fair rotation, no favorites</div></div>
      </div>
    </div>

    <div class="ring-wrap reveal">
      <svg class="ring-svg" viewBox="0 0 400 400">
        <circle class="ring-track" cx="200" cy="200" r="160"/>
        <circle class="ring-sweep" cx="200" cy="200" r="160"/>

        <g class="node"><circle class="base" cx="200" cy="40" r="20"/><text x="200" y="45" text-anchor="middle">J.T</text></g>
        <g class="node"><circle class="base" cx="313" cy="87" r="20"/><text x="313" y="92" text-anchor="middle">A.N</text></g>
        <g class="node active"><circle class="pulse" cx="360" cy="200" r="22"/><circle class="base" cx="360" cy="200" r="20"/><text x="360" y="205" text-anchor="middle">M.N</text></g>
        <g class="node"><circle class="base" cx="313" cy="313" r="20"/><text x="313" y="318" text-anchor="middle">R.E</text></g>
        <g class="node"><circle class="base" cx="200" cy="360" r="20"/><text x="200" y="365" text-anchor="middle">S.B</text></g>
        <g class="node"><circle class="base" cx="87" cy="313" r="20"/><text x="87" y="318" text-anchor="middle">P.O</text></g>
        <g class="node"><circle class="base" cx="40" cy="200" r="20"/><text x="40" y="205" text-anchor="middle">L.F</text></g>
        <g class="node"><circle class="base" cx="87" cy="87" r="20"/><text x="87" y="92" text-anchor="middle">D.M</text></g>
      </svg>
      <div class="ring-center-card">
        <div class="k">Next in rotation</div>
        <div class="name">Marie N.</div>
        <div class="amt">25,000 CFA</div>
        <div class="ref">REF · TR-04471-KX</div>
      </div>
    </div>
  </div>
</section>

<!-- ============ HOW IT WORKS (on ink) ============ -->
<section class="on-ink" id="how-it-works">
  <div class="wrap">
    <div class="section-head reveal">
      <span class="eyebrow">The rotation</span>
      <h2>From contribution to payout, in four turns</h2>
      <p>Every cycle follows the same fixed sequence — no discretion, no shortcuts, nothing to remember.</p>
    </div>
    <div class="steps reveal-stagger">
      <div class="step reveal" style="--i:0"><span class="dot"></span><span class="n">POSITION 01</span><h3>Contribute</h3><p>Members send a fixed amount by MTN or Orange Money on the group's schedule.</p></div>
      <div class="step reveal" style="--i:1"><span class="dot"></span><span class="n">POSITION 02</span><h3>Record</h3><p>Every deposit lands in an immutable ledger no single admin can quietly edit.</p></div>
      <div class="step reveal" style="--i:2"><span class="dot"></span><span class="n">POSITION 03</span><h3>Rotate</h3><p>The rotation engine decides who's next — automatically, without favorites.</p></div>
      <div class="step reveal" style="--i:3"><span class="dot"></span><span class="n">POSITION 04</span><h3>Receive</h3><p>Funds are paid out straight to that member's TRUST account.</p></div>
    </div>
  </div>
</section>

<!-- ============ TRUST FEATURES ============ -->
<section>
  <div class="wrap">
    <div class="section-head reveal">
      <span class="eyebrow">Why members trust it</span>
      <h2>Safeguards a treasurer's memory can't offer</h2>
      <p>Built specifically for the trust problems that break tontines — not a generic payments checklist.</p>
    </div>
    <div class="feature-grid reveal-stagger">
      <div class="feature-card reveal" style="--i:0">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 4h16v16H4z"/><path d="M8 9h8M8 13h8M8 17h4"/></svg></div>
        <h3>Immutable ledger</h3>
        <p>Every deposit, withdrawal, and fee is timestamped and referenced — nothing is quietly rewritten after the fact.</p>
      </div>
      <div class="feature-card reveal" style="--i:1">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="8" r="3"/><path d="M5 20c0-3.9 3.1-7 7-7s7 3.1 7 7"/><path d="M2 4l20 16"/></svg></div>
        <h3>Conflict-of-interest safeguards</h3>
        <p>An administrator can never unilaterally approve their own payout ahead of the agreed rotation.</p>
      </div>
      <div class="feature-card reveal" style="--i:2">
        <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 12h5l2-3 4 6 2-3h3"/></svg></div>
        <h3>Real-time payouts</h3>
        <p>MTN Mobile Money and Orange Money withdrawals confirm in seconds, with a full audit trail attached.</p>
      </div>
    </div>
  </div>
</section>

<!-- ============ ABOUT ============ -->
<section id="about">
  <div class="wrap about-grid">
    <div class="reveal">
      <span class="eyebrow">About us</span>
      <h2 style="margin-top:16px;font-size:clamp(26px,3.2vw,34px)">Built for the circles that already trust each other</h2>
      <p style="margin-top:20px">TRUST started with a simple observation: tontines rarely fail because members stop trusting one another. They fail because that trust has nowhere to leave a paper trail — one missed entry, one disputed memory, and the whole circle absorbs the cost.</p>
      <p>So we built a system that remembers correctly, every time, whether your circle has five members or fifty — across a single neighbourhood or scattered through the diaspora.</p>
    </div>
    <ul class="fact-list reveal">
      <li><span>Origin</span><span>Cameroon, for community savings groups </span></li>
      <li><span>Governance</span><span>Three roles, one shared ledger</span></li>
      <li><span>Payments</span><span>MTN Mobile Money &amp; Orange Money</span></li>
      <li><span>Currency</span><span>XAF</span></li>
    </ul>
  </div>
</section>

<!-- ============ SUPPORT / FAQ (on ink) ============ -->
<section class="on-ink" id="support">
  <div class="wrap">
    <div class="section-head reveal">
      <span class="eyebrow">Support</span>
      <h2>Here when your rotation needs a hand</h2>
      <p>Answers to what comes up most during a contribution cycle.</p>
    </div>

    <div class="faq reveal">
      <div class="faq-item">
        <button class="faq-q"><span>What happens if a payout fails?</span><span class="plus"></span></button>
        <div class="faq-a"><p>The member's wallet debit is automatically reversed the moment the gateway reports a failure, so no balance is ever lost mid-transaction. The failure is logged, and the member is notified immediately with the reason.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-q"><span>How is a member's turn decided?</span><span class="plus"></span></button>
        <div class="faq-a"><p>Rotation order is fixed when the tontine is created, following the rule your group agrees on — sequence, draw, or need-based priority. No administrator can move a member up or down once the cycle has started.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-q"><span>Can we move from a manual tontine to TRUST mid-cycle?</span><span class="plus"></span></button>
        <div class="faq-a"><p>Yes. An administrator can create the group, import each member's current position and contribution total, and continue the existing rotation from wherever it left off.</p></div>
      </div>
    </div>

    <div class="support-cta reveal">
      <p>Still stuck on something specific to your group?</p>
      <a href="#contact" class="btn btn-ghost on-ink">Contact support</a>
    </div>
  </div>
</section>

<!-- ============ LOCATION ============ -->
<section id="location">
  <div class="wrap loc-grid">
    <div class="loc-visual reveal"><div class="pin"></div></div>
    <div class="reveal">
      <span class="eyebrow">Location</span>
      <h2 style="margin-top:16px;font-size:clamp(26px,3.2vw,34px)">Where we operate</h2>
      <p style="color:var(--ink-soft);margin-top:14px">Serving community savings groups across Cameroon anywhere MTN Mobile Money or Orange Money can reach.</p>
      <div class="loc-card">
        <div class="row"><span>Office</span><span>Douala, Littoral, Cameroon</span></div>
        <div class="row"><span>Coverage</span><span>All ten regions, via mobile money</span></div>
        <div class="row"><span>Hours</span><span>Mon – Sat, 8:00 – 18:00H</span></div>
      </div>
    </div>
  </div>
</section>

<!-- ============ CONTACT ============ -->
<section id="contact">
  <div class="wrap contact-grid">
    <div class="reveal">
      <span class="eyebrow">Contact</span>
      <h2 style="margin-top:16px;font-size:clamp(26px,3.2vw,34px);margin-bottom:28px">Tell us about your tontine</h2>
      <form id="contactForm">
        <div class="field"><label for="cf-name">Name</label><input id="cf-name" type="text" required placeholder="Your full name"></div>
        <div class="field"><label for="cf-email">Email</label><input id="cf-email" type="email" required placeholder="you@example.com"></div>
        <div class="field"><label for="cf-msg">Message</label><textarea id="cf-msg" required placeholder="How many members, which network, what you need help with"></textarea></div>
        <button type="submit" class="btn btn-primary">Send message</button>
        <p class="form-note">We typically reply within one business day.</p>
        <div class="form-success" id="formSuccess">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M4 12l5 5L20 6"/></svg>
          Message received — we'll be in touch shortly.
        </div>
      </form>
    </div>
    <div class="reveal">
      <div class="contact-info-list">
        <div class="item"><div class="k">Email</div><div class="v"><a href="platform@trust.cm">platform@gmail.com</a></div></div>
        <div class="item"><div class="k">Phone</div><div class="v"><a href="tel:+237600000000">+237 671693951</a></div></div>
        <div class="item"><div class="k">Office</div><div class="v">Bepanda, Douala, Cameroon</div></div>
      </div>
    </div>
  </div>
</section>

</main>

<!-- ============ FOOTER ============ -->
<footer>
  <div class="wrap">
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="#home" class="brand">
          <img src="{{ asset('images/trust-logo.png') }}" alt="TRUST — Community. Savings. Unity." class="brand-logo-img">
        </a>
        <p>Transparent Rotational Universal Savings &amp; Trust — a shared ledger for the circles that already trust each other.</p>
        <div class="social-row">
          <a href="#" aria-label="WhatsApp"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 00-8.6 15L2 22l5.2-1.4A10 10 0 1012 2zm0 18a8 8 0 01-4.1-1.1l-.3-.2-3 .8.8-2.9-.2-.3A8 8 0 1112 20zm4.4-5.9c-.2-.1-1.4-.7-1.6-.8s-.4-.1-.5.1-.6.8-.7.9-.3.2-.5.1a6.6 6.6 0 01-1.9-1.2A7.2 7.2 0 0110 11.3c-.1-.2 0-.3.1-.4l.4-.4a1.7 1.7 0 00.2-.4.4.4 0 000-.4c-.1-.1-.5-1.3-.7-1.7s-.4-.4-.5-.4h-.5a.9.9 0 00-.6.3 2.7 2.7 0 00-.8 2 4.7 4.7 0 001 2.5 10.6 10.6 0 004.1 3.6c1.5.6 1.5.4 1.8.4a1.6 1.6 0 001-.7 1.3 1.3 0 000-1c-.1-.1-.2-.2-.4-.3z"/></svg></a>
          <a href="#" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-7.5H16l.4-3H13.5V8.4c0-.9.2-1.5 1.5-1.5H16.5V4.2A20 20 0 0014.2 4C11.9 4 10.4 5.3 10.4 7.9v2.6H8v3h2.4V21h3.1z"/></svg></a>
          <a href="#" aria-label="X"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 3l7.4 9.8L3.4 21H6l5.9-6.6L16.8 21H21l-7.8-10.3L20.6 3H18l-5.5 6.1L8 3H3z"/></svg></a>
        </div>
      </div>

      <div class="footer-col">
        <h4>Product</h4>
        <a href="#home">Home</a>
        <a href="#about">About Us</a>
        <a href="#support">Support</a>
        <a href="#location">Location</a>
        <a href="#contact">Contact</a>
      </div>

      <div class="footer-col">
        <h4>Contact</h4>
        <a href="mailto:hello@trust.cm">platform@gmail.com</a>
        <a href="tel:+237600000000">+237 671693951</a>
        <p style="margin-bottom:0">Bepanda, Douala, Cameroon</p>
      </div>

      <div class="footer-col">
        <h4>Payment channels</h4>
        <p style="margin-bottom:10px">Contributions and payouts run on:</p>
        <div class="badge-row">
          <span class="badge">MTN MOBILE MONEY</span>
          <span class="badge">ORANGE MONEY</span>
          <span class="badge">NOTCHPAY SECURED</span>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <span>© 2026 TRUST. Built for community savings, not against it.</span>
      <span class="ledger"><span class="dot"></span> LEDGER STATUS · LIVE · REF TR-0001</span>
    </div>
  </div>
</footer>

<script>
// ---------- header solid on scroll + scroll progress bar ----------
const header = document.getElementById('siteHeader');
const scrollProgress = document.getElementById('scrollProgress');
let ticking = false;
window.addEventListener('scroll', () => {
  if (!ticking) {
    requestAnimationFrame(() => {
      header.classList.toggle('solid', window.scrollY > 40);
      const scrollable = document.documentElement.scrollHeight - window.innerHeight;
      const pct = scrollable > 0 ? (window.scrollY / scrollable) * 100 : 0;
      scrollProgress.style.width = pct + '%';
      ticking = false;
    });
    ticking = true;
  }
});

// ---------- mobile menu ----------
const burger = document.getElementById('burgerBtn');
const panel = document.getElementById('mobilePanel');
const scrim = document.getElementById('scrim');
function toggleMenu(open) {
  const isOpen = open ?? !panel.classList.contains('open');
  panel.classList.toggle('open', isOpen);
  scrim.classList.toggle('open', isOpen);
  burger.classList.toggle('open', isOpen);
  burger.setAttribute('aria-expanded', isOpen);
  document.body.style.overflow = isOpen ? 'hidden' : '';
}
burger.addEventListener('click', () => toggleMenu());
scrim.addEventListener('click', () => toggleMenu(false));
panel.querySelectorAll('a').forEach(a => a.addEventListener('click', () => toggleMenu(false)));

// ---------- scroll reveal ----------
const revealEls = document.querySelectorAll('.reveal');
const io = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('in');
      io.unobserve(entry.target);
    }
  });
}, { threshold: 0.14, rootMargin: '0px 0px -60px 0px' });
revealEls.forEach(el => io.observe(el));

// ---------- active nav link on scroll ----------
const sections = ['home','how-it-works','about','support','location','contact']
  .map(id => document.getElementById(id)).filter(Boolean);
const navLinks = document.querySelectorAll('nav.primary-links a');
const navIO = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const id = entry.target.id;
      navLinks.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + id));
    }
  });
}, { threshold: 0.4, rootMargin: '-40% 0px -40% 0px' });
sections.forEach(s => navIO.observe(s));

// ---------- FAQ accordion ----------
document.querySelectorAll('.faq-item').forEach(item => {
  const q = item.querySelector('.faq-q');
  const a = item.querySelector('.faq-a');
  q.addEventListener('click', () => {
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(o => {
      o.classList.remove('open');
      o.querySelector('.faq-a').style.maxHeight = null;
    });
    if (!isOpen) {
      item.classList.add('open');
      a.style.maxHeight = a.scrollHeight + 'px';
    }
  });
});


// ---------- contact form ----------
const form = document.getElementById('contactForm');
const success = document.getElementById('formSuccess');
const submitBtn = form.querySelector('button[type="submit"]');

form.addEventListener('submit', async (e) => {
  e.preventDefault();

  const name = document.getElementById('cf-name').value;
  const email = document.getElementById('cf-email').value;
  const message = document.getElementById('cf-msg').value;

  submitBtn.disabled = true;
  const originalText = submitBtn.textContent;
  submitBtn.textContent = 'Sending...';

  try {
    const response = await fetch("{{ route('contact.send') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ name, email, message }),
    });

    if (response.ok) {
      success.classList.add('show');
      form.querySelectorAll('input,textarea').forEach(f => f.value = '');
    } else {
      alert('Something went wrong. Please try again.');
    }
  } catch (err) {
    alert('Something went wrong. Please try again.');
  } finally {
    submitBtn.disabled = false;
    submitBtn.textContent = originalText;
  }
});
</script>

</body>
</html>
