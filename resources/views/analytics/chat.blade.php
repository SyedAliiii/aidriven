<x-app-layout>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
/* Kill Breeze layout padding */
main{padding:0!important;margin:0!important;}
main>*{padding-top:0!important;margin-top:0!important;}
body>*>main,body>main{padding:0!important;}

/* ══════════════════════════════════════════
   COLOR TOKENS
══════════════════════════════════════════ */
:root{
  --nav-h:3.5rem; /* must match navbar h-14 = 56px = 3.5rem */
  --bg:#ffffff;
  --bg-sub:#f8f9fc;
  --bg-panel:#f0f3f8;
  --border:#e2e6ee;
  --text:#0f1623;
  --text-muted:#5a6480;
  --text-faint:#9ba3bc;
  --accent:#4f46e5;
  --accent-2:#7c3aed;
  --accent-3:#06b6d4;
  --accent-bg:#eef2ff;
  --accent-border:#c7d2fe;
  --send-bg:linear-gradient(135deg,#4f46e5,#7c3aed);
  --send-color:#fff;
  --chip-bg:#f8f9fc;
  --chip-border:#e2e6ee;
  --chip-hover-bg:#eef2ff;
  --chip-hover-border:#c7d2fe;
  --bubble-user-bg:#f0f3f8;
  --bubble-user-border:#e2e6ee;
  --bubble-ai-color:#374151;
  --dot-color:#10b981;
  --warn-bg:#fffbeb;
  --warn-border:#fcd34d;
  --warn-color:#92400e;
  --topbar-bg:#ffffff;
  --sql-bg:#f0f3f8;
  --table-head:#f8f9fc;
  --scrollbar:#e2e6ee;
  --sb-bg:#0b0f1e;
  --sb-text:#e2e8f0;
  --sb-text-muted:#94a3b8;
  --sb-border:rgba(255,255,255,.07);
  --sb-hover:rgba(255,255,255,.06);
  --sb-active:rgba(99,102,241,.22);
  --rail-bg:#070b17;
  --rail-icon:#64748b;
  --rail-icon-active:#a5b4fc;
  --rail-width:60px;
  --sb-full-width:240px;
}
[data-theme="dark"]{
  --bg:#0d111c;
  --bg-sub:#121827;
  --bg-panel:#1a2338;
  --border:#1e2a42;
  --text:#dde4f5;
  --text-muted:#7a8db5;
  --text-faint:#dddde0;
  --accent:#818cf8;
  --accent-2:#a78bfa;
  --accent-3:#22d3ee;
  --accent-bg:#1e1b4b;
  --accent-border:#312e81;
  --send-bg:linear-gradient(135deg,#4338ca,#6d28d9);
  --send-color:#e0e7ff;
  --chip-bg:#121827;
  --chip-border:#1e2a42;
  --chip-hover-bg:#1e1b4b;
  --chip-hover-border:#3730a3;
  --bubble-user-bg:#121827;
  --bubble-user-border:#1e2a42;
  --bubble-ai-color:#94a3b8;
  --dot-color:#34d399;
  --warn-bg:#1c1500;
  --warn-border:#3d3000;
  --warn-color:#fbbf24;
  --topbar-bg:#0d111c;
  --sql-bg:#0d111c;
  --table-head:#0d111c;
  --scrollbar:#1e2a42;
  --sb-bg:#060b18;
  --sb-border:rgba(255,255,255,.05);
  --rail-bg:#04070f;
}

/* ══════════════════════════════════════════
   KEYFRAMES
══════════════════════════════════════════ */
@keyframes gem-shift{
  0%{background-position:0% 50%;}
  50%{background-position:100% 50%;}
  100%{background-position:0% 50%;}
}
@keyframes orb-float{
  0%,100%{transform:translate(0,0) scale(1);}
  33%{transform:translate(18px,-12px) scale(1.08);}
  66%{transform:translate(-10px,8px) scale(.95);}
}
@keyframes orb2-float{
  0%,100%{transform:translate(0,0) scale(1);}
  40%{transform:translate(-14px,10px) scale(1.05);}
  75%{transform:translate(10px,-6px) scale(.97);}
}
@keyframes orb3-float{
  0%,100%{transform:translate(0,0) scale(1);}
  30%{transform:translate(8px,14px) scale(1.06);}
  70%{transform:translate(-12px,-8px) scale(.94);}
}
@keyframes pulse-dots{
  0%,100%{opacity:.3;transform:scale(.8);}
  50%{opacity:1;transform:scale(1.2);}
}
@keyframes slide-in-left{
  from{opacity:0;transform:translateX(-10px);}
  to{opacity:1;transform:translateX(0);}
}
@keyframes fade-up{
  from{opacity:0;transform:translateY(8px);}
  to{opacity:1;transform:translateY(0);}
}
@keyframes shimmer{
  0%{background-position:-200% 0;}
  100%{background-position:200% 0;}
}
@keyframes glow-ring{
  0%,100%{box-shadow:0 0 0 0 rgba(99,102,241,0);}
  50%{box-shadow:0 0 16px 4px rgba(99,102,241,.25);}
}
@keyframes icon-bounce{
  0%,100%{transform:translateY(0);}
  50%{transform:translateY(-2px);}
}
@keyframes bar-flow{
  0%{background-position:0% 50%;}
  100%{background-position:200% 50%;}
}
@keyframes pop-in{
  0%{opacity:0;transform:scale(.85);}
  100%{opacity:1;transform:scale(1);}
}
@keyframes spin-gem{
  from{transform:rotate(0deg);}
  to{transform:rotate(360deg);}
}
@keyframes search-flash{
  0%{box-shadow:0 0 0 0 rgba(99,102,241,.55);}
  100%{box-shadow:0 0 0 8px rgba(99,102,241,0);}
}

/* ══ BREEZE LAYOUT OVERRIDES ══
   Breeze's x-app-layout wraps content in a <main> with py-12 padding.
   We need zero padding so the shell fills flush under the navbar. */
body > main,
main.mt-0,
[x-app-layout] > main,
.page-content,
#app > main{
  padding:0 !important;
  margin:0 !important;
}
/* neutralize any layout wrapper padding that causes gap */
#aq-shell{margin-top:0 !important;padding-top:0 !important;}
.tdot{animation:pulse-dots 1.4s infinite both;display:inline-block;width:5px;height:5px;border-radius:50%;background:var(--text-muted);}
.tdot:nth-child(2){animation-delay:.2s}.tdot:nth-child(3){animation-delay:.4s}

/* ══════════════════════════════════════════
   SHELL
══════════════════════════════════════════ */
#aq-shell{
  display:flex;
  height:calc(100vh - 3.5rem);
  background:var(--bg);
  color:var(--text);
  font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',sans-serif;
  position:relative;
  overflow:hidden;
  transition:background .25s,color .25s;
  /* remove any inherited margin/gap from layout */
  margin-top:0 !important;
}

/* ══════════════════════════════════════════
   ICON RAIL (collapsed sidebar) — like Gemini/Claude
══════════════════════════════════════════ */
#aq-rail{
  width:var(--rail-width);
  min-width:var(--rail-width);
  background:var(--rail-bg);
  border-right:1px solid rgba(255,255,255,.04);
  display:flex;
  flex-direction:column;
  align-items:center;
  padding:14px 0 12px;
  gap:4px;
  z-index:25;
  flex-shrink:0;
  position:relative;
  overflow:hidden;
  transition:width .22s cubic-bezier(.4,0,.2,1);
}
/* subtle aurora bleed behind rail */
#aq-rail::before{
  content:'';position:absolute;width:80px;height:180px;
  background:radial-gradient(ellipse at center,rgba(79,70,229,.18) 0%,transparent 70%);
  top:40px;left:-20px;pointer-events:none;z-index:0;
  animation:orb-float 11s ease-in-out infinite;
}
#aq-rail::after{
  content:'';position:absolute;width:80px;height:120px;
  background:radial-gradient(ellipse at center,rgba(6,182,212,.14) 0%,transparent 70%);
  bottom:80px;right:-20px;pointer-events:none;z-index:0;
  animation:orb2-float 14s ease-in-out infinite;
}

.rail-logo{
  width:34px;height:34px;border-radius:10px;
  background:linear-gradient(135deg,#4f46e5,#7c3aed);
  display:flex;align-items:center;justify-content:center;
  margin-bottom:8px;flex-shrink:0;z-index:1;
  box-shadow:0 4px 14px rgba(79,70,229,.4);
  cursor:pointer;
  transition:transform .18s,box-shadow .18s;
  animation:pop-in .35s ease both;
}
.rail-logo:hover{transform:scale(1.08);box-shadow:0 6px 20px rgba(79,70,229,.55);}
.rail-logo svg{width:18px;height:18px;stroke:#fff;fill:none;stroke-width:1.8;}

.rail-divider{width:28px;height:1px;background:rgba(255,255,255,.08);margin:4px 0;z-index:1;}

.rail-btn{
  width:42px;height:42px;border-radius:12px;
  display:flex;align-items:center;justify-content:center;
  background:transparent;border:none;cursor:pointer;
  position:relative;z-index:1;
  transition:background .15s,transform .15s;
  flex-shrink:0;
}
.rail-btn svg{width:20px;height:20px;stroke:var(--rail-icon);fill:none;stroke-width:1.8;transition:stroke .15s;}
.rail-btn:hover{background:rgba(255,255,255,.07);}
.rail-btn:hover svg{stroke:#a5b4fc;}
.rail-btn:hover{animation:icon-bounce .3s ease;}
.rail-btn.active{background:rgba(99,102,241,.18);}
.rail-btn.active svg{stroke:var(--rail-icon-active);}

/* Tooltip on hover */
.rail-btn[data-tip]{position:relative;}
.rail-btn[data-tip]::after{
  content:attr(data-tip);
  position:absolute;left:calc(100% + 10px);top:50%;transform:translateY(-50%);
  background:#1e293b;color:#e2e8f0;font-size:11.5px;white-space:nowrap;
  padding:5px 11px;border-radius:7px;pointer-events:none;
  opacity:0;transition:opacity .15s .05s;
  border:1px solid rgba(255,255,255,.1);
  font-family:-apple-system,BlinkMacSystemFont,'Inter',sans-serif;
  z-index:200;
}
.rail-btn[data-tip]:hover::after{opacity:1;}

.rail-spacer{flex:1;z-index:1;}

.rail-avatar{
  width:32px;height:32px;border-radius:50%;
  background:linear-gradient(135deg,#4f46e5,#7c3aed);
  display:flex;align-items:center;justify-content:center;
  font-size:11px;font-weight:700;color:#fff;
  border:2px solid rgba(165,180,252,.3);
  box-shadow:0 0 10px rgba(99,102,241,.35);
  cursor:pointer;z-index:1;flex-shrink:0;
  transition:transform .15s,box-shadow .15s;
}
.rail-avatar:hover{transform:scale(1.08);box-shadow:0 0 16px rgba(99,102,241,.55);}

/* ══════════════════════════════════════════
   FULL SIDEBAR (slides out from rail)
══════════════════════════════════════════ */
#aq-sidebar{
  width:var(--sb-full-width);
  min-width:var(--sb-full-width);
  background:var(--sb-bg);
  border-right:1px solid var(--sb-border);
  display:flex;flex-direction:column;
  /* slide LEFT off-screen by default */
  transform:translateX(-100%);
  transition:transform .28s cubic-bezier(.4,0,.2,1),
             box-shadow .28s cubic-bezier(.4,0,.2,1);
  z-index:20;flex-shrink:0;
  position:absolute;
  top:0;left:var(--rail-width);
  height:100%; /* relative to shell which already accounts for nav */
  overflow:hidden;
  box-shadow:none;
}
#aq-sidebar.open{
  transform:translateX(0);
  box-shadow:4px 0 32px rgba(0,0,0,.35);
}
/* push main content when sidebar is open on desktop */
@media(min-width:768px){
  #aq-sidebar{
    position:relative;
    left:auto;top:auto;height:auto;
    transform:translateX(-100%);
    /* remove from flow when closed */
    margin-right:calc(-1 * var(--sb-full-width));
    transition:transform .28s cubic-bezier(.4,0,.2,1),
               margin-right .28s cubic-bezier(.4,0,.2,1),
               box-shadow .28s cubic-bezier(.4,0,.2,1);
  }
  #aq-sidebar.open{
    transform:translateX(0);
    margin-right:0;
    box-shadow:none;
  }
}

/* three aurora orb blobs inside sidebar */
#aq-sidebar::before{
  content:'';position:absolute;width:180px;height:180px;
  background:radial-gradient(circle,#4f46e5 0%,#7c3aed 55%,transparent 100%);
  top:-40px;left:-40px;border-radius:50%;
  filter:blur(52px);opacity:.28;z-index:0;pointer-events:none;
  animation:orb-float 9s ease-in-out infinite;
}
#aq-sidebar::after{
  content:'';position:absolute;width:150px;height:150px;
  background:radial-gradient(circle,#06b6d4 0%,#3b82f6 55%,transparent 100%);
  bottom:80px;right:-30px;border-radius:50%;
  filter:blur(44px);opacity:.22;z-index:0;pointer-events:none;
  animation:orb2-float 13s ease-in-out infinite;
}
.sb-orb3{
  position:absolute;width:120px;height:120px;
  background:radial-gradient(circle,#ec4899 0%,#8b5cf6 55%,transparent 100%);
  top:45%;left:20%;border-radius:50%;
  filter:blur(40px);opacity:.16;z-index:0;pointer-events:none;
  animation:orb3-float 16s ease-in-out infinite;
}

/* animated rainbow bar at top of sidebar */
.sb-gem-bar{
  height:3px;flex-shrink:0;position:relative;z-index:2;
  background:linear-gradient(90deg,#4f46e5,#7c3aed,#ec4899,#f59e0b,#06b6d4,#10b981,#4f46e5);
  background-size:300% 100%;
  animation:bar-flow 3s linear infinite;
}

.sb-inner{position:relative;z-index:1;display:flex;flex-direction:column;height:100%;min-width:var(--sb-full-width);}

.sb-top{padding:16px 14px 12px;border-bottom:1px solid var(--sb-border);}
.sb-brand{display:flex;align-items:center;gap:10px;margin-bottom:14px;}
.brand-name{
  font-size:14px;font-weight:700;white-space:nowrap;letter-spacing:-.025em;
  background:linear-gradient(90deg,#fff 0%,#a5b4fc 50%,#67e8f9 100%);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}

.new-qbtn{
  display:flex;align-items:center;gap:8px;width:100%;
  padding:8px 13px;border-radius:10px;
  background:rgba(255,255,255,.06);
  border:1px solid rgba(255,255,255,.1);
  font-size:13px;color:rgba(255,255,255,.6);cursor:pointer;
  transition:all .18s;white-space:nowrap;font-family:inherit;
  position:relative;overflow:hidden;
}
.new-qbtn::before{
  content:'';position:absolute;inset:0;
  background:linear-gradient(90deg,transparent,rgba(99,102,241,.12),transparent);
  background-size:200% 100%;opacity:0;transition:opacity .2s;
}
.new-qbtn:hover{border-color:rgba(99,102,241,.55);color:#a5b4fc;background:rgba(99,102,241,.12);}
.new-qbtn:hover::before{opacity:1;animation:shimmer 1.4s ease infinite;}
.new-qbtn svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2.3;flex-shrink:0;}

.sb-section{
  padding:13px 16px 4px;font-size:10px;font-weight:700;
  color:rgba(255,255,255,.35);letter-spacing:.15em;text-transform:uppercase;
  position:relative;z-index:1;
}
.sb-scroll{flex:1;overflow-y:auto;padding:4px 8px 8px;position:relative;z-index:1;}
.sb-scroll::-webkit-scrollbar{width:3px;}
.sb-scroll::-webkit-scrollbar-thumb{background:rgba(255,255,255,.1);border-radius:3px;}

.hbtn-sb{
  display:block;width:100%;text-align:left;
  padding:8px 11px;border-radius:9px;
  font-size:12.5px;color:var(--sb-text-muted);
  background:transparent;border:none;cursor:pointer;
  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
  transition:background .15s,color .15s;line-height:1.4;
  animation:slide-in-left .25s ease both;
  position:relative;
}
.hbtn-sb::before{
  content:'';position:absolute;left:0;top:20%;bottom:20%;width:2px;border-radius:2px;
  background:linear-gradient(to bottom,#4f46e5,#7c3aed);
  opacity:0;transition:opacity .15s;
}
.hbtn-sb:hover{background:rgba(255,255,255,.06);color:#e2e8f0;}
.hbtn-sb:hover::before{opacity:.6;}
.hbtn-sb.active{background:var(--sb-active);color:#a5b4fc;}
.hbtn-sb.active::before{opacity:1;}
.hbtn-sb .htime{display:block;font-size:10.5px;color:rgba(255,255,255,.3);margin-top:3px;}

/* ══════════════════════════════════════════
   MAIN
══════════════════════════════════════════ */
#aq-main{flex:1;display:flex;flex-direction:column;overflow:hidden;min-width:0;position:relative;}
/* pink orb top-left of main */
#aq-main::before{
  content:'';pointer-events:none;position:absolute;
  width:320px;height:280px;
  background:radial-gradient(ellipse at center,rgba(236,72,153,.09) 0%,transparent 68%);
  top:18%;left:3%;z-index:0;filter:blur(14px);
  animation:orb3-float 19s ease-in-out infinite;
}
#aq-topbar,#aq-bottom{position:relative;z-index:2;}
#aq-messages,#aq-welcome{position:relative;z-index:1;}

#aq-topbar{
  display:flex;align-items:center;gap:8px;padding:0 20px;height:50px;
  border-bottom:none;background:var(--topbar-bg);
  flex-shrink:0;transition:background .25s;
  position:relative;
}
#aq-topbar::after{
  content:'';position:absolute;bottom:0;left:0;right:0;height:2px;
  background:linear-gradient(90deg,#4f46e5,#7c3aed,#ec4899,#f59e0b,#06b6d4,#10b981,#4f46e5);
  background-size:300% 100%;
  animation:bar-flow 3s linear infinite;
  pointer-events:none;
}

.ib{
  width:34px;height:34px;border-radius:9px;background:transparent;
  border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;
  transition:background .14s,transform .12s;flex-shrink:0;
}
.ib svg{width:18px;height:18px;stroke:var(--text-muted);fill:none;stroke-width:2;transition:stroke .14s;}
.ib:hover{background:var(--bg-panel);}
.ib:hover svg{stroke:var(--accent);}
.ib:active{transform:scale(.93);}

.topbar-title{font-size:13.5px;font-weight:600;color:var(--text);letter-spacing:-.02em;}
.topbar-sep{width:1px;height:14px;background:var(--border);margin:0 2px;}

.org-pill{
  display:flex;align-items:center;gap:7px;padding:5px 13px;
  border-radius:20px;
  background:linear-gradient(135deg,var(--accent-bg),rgba(124,58,237,.08));
  border:1px solid var(--accent-border);
  font-size:11.5px;color:var(--accent);margin-left:auto;
  position:relative;overflow:hidden;
}
.org-pill::after{
  content:'';position:absolute;inset:0;
  background:linear-gradient(90deg,transparent,rgba(99,102,241,.08),transparent);
  background-size:200% 100%;
  animation:shimmer 2.5s ease infinite;
}
.org-pill-dot{
  width:7px;height:7px;border-radius:50%;background:var(--dot-color);flex-shrink:0;
  box-shadow:0 0 6px var(--dot-color);
  animation:glow-ring 2.5s ease-in-out infinite;
}

/* ── chat scroll ── */
#aq-scroll{flex:1;overflow-y:auto;padding:0 0 24px;position:relative;}
#aq-scroll::-webkit-scrollbar{width:4px;}
#aq-scroll::-webkit-scrollbar-thumb{background:var(--scrollbar);border-radius:4px;}
#aq-scroll::-webkit-scrollbar-track{background:transparent;}
/* ambient glow — purple top-center */
#aq-scroll::before{
  content:'';pointer-events:none;position:fixed;
  width:600px;height:500px;
  background:radial-gradient(ellipse at center,rgba(124,58,237,.12) 0%,rgba(79,70,229,.06) 45%,transparent 70%);
  top:8%;left:50%;transform:translateX(-50%);
  z-index:0;filter:blur(10px);
  animation:orb-float 13s ease-in-out infinite;
}
/* ambient glow — cyan bottom-right */
#aq-scroll::after{
  content:'';pointer-events:none;position:fixed;
  width:420px;height:360px;
  background:radial-gradient(ellipse at center,rgba(6,182,212,.1) 0%,rgba(59,130,246,.06) 50%,transparent 72%);
  bottom:12%;right:6%;
  z-index:0;filter:blur(12px);
  animation:orb2-float 16s ease-in-out infinite;
}

/* welcome */
#aq-welcome{
  display:flex;flex-direction:column;align-items:center;
  justify-content:center;min-height:56vh;padding:32px 20px;text-align:center;
}
.wlc-gem{
  width:60px;height:60px;border-radius:18px;
  background:linear-gradient(135deg,#4f46e5,#7c3aed,#ec4899);
  background-size:200% 200%;
  animation:gem-shift 4s ease infinite,pop-in .4s ease both;
  display:flex;align-items:center;justify-content:center;
  margin-bottom:22px;
  box-shadow:0 8px 28px rgba(79,70,229,.35),0 0 0 1px rgba(165,180,252,.2);
  position:relative;
}
.wlc-gem::after{
  content:'';position:absolute;inset:-1px;border-radius:19px;
  background:linear-gradient(135deg,rgba(255,255,255,.2),transparent);
  pointer-events:none;
}
.wlc-gem svg{width:28px;height:28px;stroke:#fff;fill:none;stroke-width:1.6;}
#aq-welcome h1{
  font-size:23px;font-weight:700;color:var(--text);margin-bottom:10px;
  letter-spacing:-.04em;animation:fade-up .4s .08s ease both;
}
#aq-welcome .sub{
  font-size:14px;color:var(--text-muted);line-height:1.7;
  max-width:360px;margin-bottom:30px;animation:fade-up .4s .14s ease both;
}
.chip-grid{
  display:flex;flex-wrap:wrap;gap:9px;justify-content:center;max-width:540px;
  animation:fade-up .4s .2s ease both;
}
.q-chip{
  display:flex;align-items:center;gap:8px;padding:9px 16px;border-radius:11px;
  border:1px solid var(--chip-border);background:var(--chip-bg);
  font-size:12.5px;color:var(--text-muted);cursor:pointer;
  transition:all .18s;font-family:inherit;
  position:relative;overflow:hidden;
}
.q-chip::before{
  content:'';position:absolute;inset:0;
  background:linear-gradient(90deg,transparent,rgba(99,102,241,.07),transparent);
  background-size:200% 100%;opacity:0;transition:opacity .2s;
}
.q-chip:hover{border-color:var(--chip-hover-border);color:var(--accent);background:var(--chip-hover-bg);transform:translateY(-1px);}
.q-chip:hover::before{opacity:1;animation:shimmer 1.2s ease infinite;}
.q-chip svg{width:13px;height:13px;stroke:var(--accent);fill:none;stroke-width:2;flex-shrink:0;}

/* messages */
#aq-messages{max-width:740px;margin:0 auto;padding:0 24px;display:flex;flex-direction:column;}
.mrow{display:flex;gap:12px;padding:13px 0;align-items:flex-start;animation:fade-up .22s ease both;}
.mrow.user-r{flex-direction:row-reverse;}
.mavatar{
  width:30px;height:30px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:10px;font-weight:700;flex-shrink:0;
}
.mavatar.u{
  background:linear-gradient(135deg,#4f46e5,#7c3aed);
  border:2px solid rgba(165,180,252,.3);color:#fff;
  box-shadow:0 2px 8px rgba(99,102,241,.3);
}
.mavatar.a{
  background:var(--bg-panel);border:1px solid var(--border);
  color:var(--text-muted);position:relative;overflow:hidden;
}
.mavatar.a::after{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,rgba(99,102,241,.15),rgba(124,58,237,.1));
}
.mbubble{font-size:14px;line-height:1.75;max-width:86%;}
.user-r .mbubble{
  background:var(--bubble-user-bg);border:1px solid var(--bubble-user-border);
  border-radius:16px 16px 4px 16px;padding:11px 16px;color:var(--text);
}
.mrow:not(.user-r) .mbubble{padding:2px 0;color:var(--bubble-ai-color);}

/* ── BOTTOM ── */
#aq-bottom{
  background:var(--bg);padding:0 20px 20px;flex-shrink:0;
  border-top:1px solid var(--border);transition:background .25s,border-color .25s;
}

#aq-adv{max-width:740px;margin:13px auto 11px;border:1px solid var(--border);border-radius:10px;overflow:hidden;}
#aq-adv summary{
  display:flex;align-items:center;gap:7px;cursor:pointer;
  font-size:12px;color:var(--text-faint);list-style:none;
  padding:9px 14px;background:var(--bg-sub);user-select:none;transition:color .12s;
}
#aq-adv summary:hover{color:var(--text-muted);}
#aq-adv[open] summary{color:var(--accent);border-bottom:1px solid var(--border);}
#aq-chev{transition:transform .22s cubic-bezier(.4,0,.2,1);}
#aq-adv[open] #aq-chev{transform:rotate(90deg);}
.adv-body{padding:13px 15px;background:var(--bg);display:flex;flex-direction:column;gap:11px;}
#aq-sql{
  width:100%;background:var(--sql-bg);border:1px solid var(--border);
  border-radius:8px;color:var(--text-muted);
  font-family:'SFMono-Regular','Menlo',monospace;font-size:12px;
  padding:10px 13px;resize:none;outline:none;display:block;line-height:1.65;
  transition:background .2s,border-color .2s;
}
#aq-sql:focus{border-color:var(--accent);}
.adv-actions{display:flex;align-items:center;flex-wrap:wrap;gap:9px;}
.abtn{
  display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;
  font-size:12.5px;font-weight:500;border:1px solid var(--border);
  background:var(--bg-sub);color:var(--text-muted);cursor:pointer;
  transition:all .14s;font-family:inherit;
}
.abtn:hover:not(:disabled){border-color:var(--accent);color:var(--accent);background:var(--accent-bg);}
.abtn:disabled{opacity:.38;cursor:default;}
.abtn-p{
  background:linear-gradient(135deg,rgba(79,70,229,.1),rgba(124,58,237,.1));
  border-color:var(--accent-border);color:var(--accent);
}
.abtn-p:hover:not(:disabled){
  background:linear-gradient(135deg,#4f46e5,#7c3aed);
  color:#fff;border-color:transparent;
}
.abtn svg{width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2;}
#aq-rowbadge{
  display:none;margin-left:auto;padding:3px 11px;border-radius:20px;
  background:linear-gradient(135deg,var(--accent-bg),rgba(124,58,237,.1));
  border:1px solid var(--accent-border);font-size:11px;color:var(--accent);
}
#aq-rtable-wrap{border-radius:8px;border:1px solid var(--border);overflow:auto;max-height:240px;display:none;}
#aq-rtable-wrap table{width:100%;border-collapse:collapse;font-size:12.5px;}
#aq-rtable-wrap thead th{
  background:var(--table-head);padding:8px 13px;text-align:left;
  font-size:10.5px;font-weight:700;color:var(--text-faint);
  border-bottom:1px solid var(--border);position:sticky;top:0;
  white-space:nowrap;text-transform:uppercase;letter-spacing:.08em;
}
#aq-rtable-wrap tbody td{padding:8px 13px;color:var(--text-muted);border-bottom:1px solid var(--bg-sub);white-space:nowrap;}
#aq-rtable-wrap tbody tr:hover td{background:var(--bg-sub);color:var(--text);}
#aq-empty{display:none;font-size:12.5px;color:var(--text-faint);padding:4px 0;}

/* input bar */
#aq-inputwrap{max-width:740px;margin:0 auto;}
#aq-org-sel{
  width:100%;padding:8px 12px;border-radius:8px;border:1px solid var(--border);
  background:var(--bg-sub);color:var(--text-muted);font-size:13px;outline:none;
  margin-bottom:9px;font-family:inherit;transition:border-color .14s;
}
#aq-org-sel:focus{border-color:var(--accent);}
#aq-box{
  display:flex;align-items:flex-end;gap:10px;padding:12px 15px;
  border-radius:14px;border:1px solid var(--border);background:var(--bg-sub);
  transition:border-color .18s,background .2s,box-shadow .18s;
  position:relative;
}
#aq-box:focus-within{
  border-color:var(--accent);background:var(--bg);
  box-shadow:0 0 0 3px rgba(99,102,241,.12),0 0 20px rgba(99,102,241,.06);
}
#aq-q{
  flex:1;background:transparent;border:none;outline:none;
  font-size:14px;color:var(--text);line-height:1.65;
  resize:none;min-height:22px;max-height:160px;overflow-y:auto;font-family:inherit;
}
#aq-q::placeholder{color:var(--text-faint);}
#aq-sendbtn{
  width:36px;height:36px;border-radius:10px;border:none;
  background:var(--send-bg);color:var(--send-color);
  cursor:pointer;display:flex;align-items:center;justify-content:center;
  flex-shrink:0;transition:opacity .14s,transform .12s,box-shadow .14s;
  box-shadow:0 4px 14px rgba(79,70,229,.38);
}
#aq-sendbtn:hover:not(:disabled){opacity:.9;transform:translateY(-1px);box-shadow:0 6px 20px rgba(79,70,229,.5);}
#aq-sendbtn:active:not(:disabled){transform:translateY(0);box-shadow:0 2px 8px rgba(79,70,229,.3);}
#aq-sendbtn:disabled{opacity:.32;cursor:default;box-shadow:none;}
#aq-sendbtn svg{width:15px;height:15px;stroke:currentColor;fill:none;stroke-width:2.2;}
.hint-row{text-align:center;font-size:11px;color:var(--text-faint);margin-top:8px;}
#aq-msg{font-size:12px;color:#dc2626;min-height:15px;padding-top:5px;}
#aq-status{font-size:11.5px;color:var(--text-faint);margin-left:auto;}
.warn-bar{
  max-width:740px;margin:0 auto 11px;padding:10px 15px;border-radius:9px;
  background:var(--warn-bg);border:1px solid var(--warn-border);
  font-size:12.5px;color:var(--warn-color);
}
.warn-bar code{background:rgba(0,0,0,.06);padding:1px 6px;border-radius:4px;font-family:monospace;}

/* mobile overlay */
#aq-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:15;backdrop-filter:blur(3px);}
#aq-overlay.on{display:block;}

@media(max-width:767px){
  #aq-sidebar{position:fixed;left:var(--rail-width);top:3.5rem;height:calc(100vh - 3.5rem);z-index:22;}
  #aq-rail{position:fixed;left:0;top:3.5rem;height:calc(100vh - 3.5rem);padding-top:14px;}
  #aq-main{margin-left:var(--rail-width);}
}

/* flash highlight used when jumping to the search field */
.search-flash{animation:search-flash .5s ease;}
</style>

@php
  $user = auth()->user();
  $isSuperAdmin = $user && method_exists($user,'hasRole') ? $user->hasRole('super-admin') : false;
  $orgs = \App\Models\Organization::query()->orderBy('name')->get();
  $organizationMissing = $user && !$isSuperAdmin && empty($user->organization_id);
  $userOrganization = $user && !$isSuperAdmin ? $user->organization : null;
  $userName = $user?->name ?? 'User';
  $firstName = explode(' ',$userName)[0];
  $initials = collect(explode(' ',$userName))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->join('');
@endphp

<div id="aq-shell">

  {{-- ════ ICON RAIL ════ --}}
  <nav id="aq-rail" aria-label="Quick actions">

    {{-- Logo --}}
    <div class="rail-logo" title="Analytics">
      <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
    </div>

    <div class="rail-divider"></div>

    {{-- New query --}}
    <button type="button" class="rail-btn active" id="aq-rail-new" data-tip="New query" aria-label="New query">
      <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
    </button>

    {{-- History --}}
    <button type="button" class="rail-btn" id="aq-rail-hist" data-tip="History" aria-label="History">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    </button>

    {{-- Search --}}
    <button type="button" class="rail-btn" id="aq-rail-search" data-tip="Search queries" aria-label="Search queries">
      <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
    </button>

    <div class="rail-divider"></div>

    {{-- SQL panel --}}
    <button type="button" class="rail-btn" id="aq-rail-sql" data-tip="SQL & Results" aria-label="Toggle SQL panel">
      <svg viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3"/></svg>
    </button>

    {{-- Export --}}
    <button type="button" class="rail-btn" id="aq-rail-export" data-tip="Export Excel" aria-label="Export to Excel">
      <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
    </button>

    <div class="rail-spacer"></div>

    {{-- Theme toggle --}}
    <button type="button" class="rail-btn" id="aq-rail-theme" data-tip="Toggle theme" aria-label="Toggle theme">
      <svg id="aq-theme-sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
      <svg id="aq-theme-moon" viewBox="0 0 24 24" style="display:none;"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
    </button>

    {{-- Avatar + Dropdown (only user identity element — no duplicate row in the full sidebar) --}}
    <div style="position:relative;z-index:1;" id="aq-user-wrap">
      <div class="rail-avatar" id="aq-user-btn" title="{{ $userName }}">{{ $initials ?: 'U' }}</div>

      {{-- Dropdown menu --}}
      <div id="aq-user-menu" style="
        display:none;
        position:fixed;
        bottom:12px;
        left:calc(var(--rail-width) + 8px);
        min-width:190px;
        background:#1e293b;
        border:1px solid rgba(255,255,255,.1);
        border-radius:12px;
        overflow:hidden;
        box-shadow:0 8px 32px rgba(0,0,0,.55);
        z-index:9999;
        animation:fade-up .15s ease both;
      ">
        {{-- User info header --}}
        <div style="padding:12px 14px 10px;border-bottom:1px solid rgba(255,255,255,.07);">
          <div style="display:flex;align-items:center;gap:9px;">
            <div style="
              width:30px;height:30px;border-radius:50%;flex-shrink:0;
              background:linear-gradient(135deg,#4f46e5,#7c3aed);
              display:flex;align-items:center;justify-content:center;
              font-size:11px;font-weight:700;color:#fff;
              border:2px solid rgba(165,180,252,.3);
            ">{{ $initials ?: 'U' }}</div>
            <div style="min-width:0;">
              <div style="font-size:12.5px;font-weight:600;color:#e2e8f0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:110px;">{{ $userName }}</div>
              @if($userOrganization)
                <div style="font-size:10.5px;color:rgba(255,255,255,.38);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:110px;">{{ $userOrganization->name }}</div>
              @endif
            </div>
          </div>
        </div>

        {{-- Menu items --}}
        <a href="{{ route('profile.edit') }}" style="
          display:flex;align-items:center;gap:9px;
          padding:10px 14px;font-size:12.5px;color:#94a3b8;
          text-decoration:none;transition:background .13s,color .13s;
        " onmouseover="this.style.background='rgba(255,255,255,.06)';this.style.color='#e2e8f0'"
           onmouseout="this.style.background='';this.style.color='#94a3b8'">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Profile
        </a>

        <div style="height:1px;background:rgba(255,255,255,.06);margin:0 10px;"></div>

        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
          @csrf
          <button type="submit" style="
            display:flex;align-items:center;gap:9px;width:100%;
            padding:10px 14px;font-size:12.5px;color:#f87171;
            background:transparent;border:none;cursor:pointer;
            font-family:inherit;transition:background .13s;
            text-align:left;
          " onmouseover="this.style.background='rgba(248,113,113,.08)'"
             onmouseout="this.style.background=''">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
            Log Out
          </button>
        </form>
      </div>
    </div>

  </nav>

  {{-- ════ FULL SIDEBAR ════ --}}
  <aside id="aq-sidebar" aria-label="Sidebar">
    <div class="sb-orb3"></div>
    <div class="sb-gem-bar"></div>
    <div class="sb-inner">

      <div class="sb-top">
        <div class="sb-brand">
          <a href="{{ route('dashboard') }}" style="display:flex;align-items:center;flex-shrink:0;">
            <x-application-logo class="block h-7 w-auto fill-current text-white" />
          </a>
          <span class="brand-name">Analytics</span>
        </div>
        <button type="button" id="aq-newbtn" class="new-qbtn">
          <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
          New query
        </button>
      </div>

      {{-- Search bar inside sidebar --}}
      <div style="padding:10px 14px 0;position:relative;z-index:1;">
        <div id="aq-search-wrap" style="display:flex;align-items:center;gap:8px;padding:7px 12px;border-radius:9px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);transition:border-color .15s,background .15s;">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.4)" stroke-width="2" style="flex-shrink:0;"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          <input id="aq-search" type="text" placeholder="Search history…"
            style="background:transparent;border:none;outline:none;font-size:12.5px;color:rgba(255,255,255,.7);width:100%;font-family:inherit;">
        </div>
      </div>

      <div class="sb-section">Recent</div>
      <div class="sb-scroll">
        <div id="aq-hist-cont">
          @forelse($sessions as $item)
            <button type="button" class="hbtn-sb" data-q="{{ $item->question }}" title="{{ $item->question }}">
              {{ \Illuminate\Support\Str::limit($item->question,48) }}
              <span class="htime">
                {{ optional($item->created_at)->format('d M, H:i') }}
                @if($item->organization) · {{ \Illuminate\Support\Str::limit($item->organization->name,12) }}@endif
              </span>
            </button>
          @empty
            <p id="aq-hist-empty" style="font-size:12px;color:rgba(255,255,255,.28);padding:10px 11px;">No queries yet.</p>
          @endforelse
        </div>
        <p id="aq-search-empty" style="display:none;font-size:12px;color:rgba(255,255,255,.28);padding:10px 11px;">No matches found.</p>
      </div>

      {{-- NOTE: the duplicate "avatar + Test User" row that used to sit here has been
           removed — the rail avatar above (with its Profile / Log Out dropdown) is now
           the single source of truth for the signed-in user, so it isn't shown twice. --}}

    </div>
  </aside>

  <div id="aq-overlay"></div>

  {{-- ════ MAIN ════ --}}
  <div id="aq-main">

    <div id="aq-topbar">
      <button class="ib" id="aq-hbtn" title="Toggle sidebar">
        <svg viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
      </button>
      <div class="topbar-sep"></div>
      <span class="topbar-title">AI Multi-DB Analytics</span>
      @if(!$isSuperAdmin && $userOrganization)
        <div class="org-pill">
          <span class="org-pill-dot"></span>
          {{ $userOrganization->name }}
        </div>
      @endif
    </div>

    <div id="aq-scroll">
      <div id="aq-welcome">
        <div class="wlc-gem">
          <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
        </div>
        <h1>What would you like to know, {{ $firstName }}?</h1>
        <p class="sub">Ask anything about sales, TSOs, or revenue — in plain English.</p>
        <div class="chip-grid">
          <button class="q-chip" data-p="What was the total executed sale today?">
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
            Today's total sales
          </button>
          <button class="q-chip" data-p="Who were the top 5 TSOs by revenue in the last 7 days?">
            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            Top TSOs — last 7 days
          </button>
          <button class="q-chip" data-p="What is the total revenue for this month?">
            <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
            This month's revenue
          </button>
          <button class="q-chip" data-p="Compare Padel and Arena sales for this week.">
            <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            Arena vs Padel
          </button>
        </div>
      </div>
      <div id="aq-messages"></div>
    </div>

    <div id="aq-bottom">
      <details id="aq-adv">
        <summary>
          <svg id="aq-chev" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 6l6 6-6 6"/></svg>
          SQL &amp; Results
          <span id="aq-rowbadge"></span>
        </summary>
        <div class="adv-body">
          <div style="position:relative;">
            <textarea id="aq-sql" rows="3" readonly placeholder="Generated SQL will appear here…"></textarea>
            <button type="button" id="aq-copybtn" disabled
              style="position:absolute;top:8px;right:8px;padding:3px 11px;border-radius:6px;font-size:11px;background:var(--bg);border:1px solid var(--border);color:var(--text-muted);cursor:pointer;font-family:inherit;transition:all .14s;">
              Copy
            </button>
          </div>
          <div class="adv-actions">
            <form id="aq-export-form" method="POST" action="{{ route('dashboard.analytics.export') }}" style="display:inline;">
              @csrf
              <input type="hidden" name="organization_id" id="aq-exp-org">
              <input type="hidden" name="sql" id="aq-exp-sql">
              <button type="submit" id="aq-expbtn" disabled class="abtn abtn-p">
                <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                Export to Excel
              </button>
            </form>
            <span id="aq-status"></span>
          </div>
          <div id="aq-rtable-wrap">
            <table><thead id="aq-thead"></thead><tbody id="aq-tbody"></tbody></table>
          </div>
          <p id="aq-empty"></p>
        </div>
      </details>

      @if(!$isSuperAdmin && $organizationMissing)
        <div class="warn-bar">
          No organization assigned. Ask your Super Admin to set your <code>organization_id</code>.
        </div>
      @endif

      <div id="aq-inputwrap">
        @if($isSuperAdmin)
          <select id="organization_id" id="aq-org-sel">
            @foreach($orgs as $org)
              <option value="{{ $org->id }}">{{ $org->name }}</option>
            @endforeach
          </select>
        @else
          <input type="hidden" id="organization_id" value="{{ $user?->organization_id }}">
        @endif
        <div id="aq-box">
          <textarea id="aq-q" rows="1"
            placeholder="Ask anything — sales, revenue, KPIs…"
            @if(!$isSuperAdmin && $organizationMissing) disabled @endif></textarea>
          <button type="button" id="aq-sendbtn"
            @if(!$isSuperAdmin && $organizationMissing) disabled @endif
            title="Send (Enter)">
            <svg viewBox="0 0 24 24"><path d="M22 2L11 13M22 2L15 22l-4-9-9-4 20-7z"/></svg>
          </button>
        </div>
        <div class="hint-row">Enter to send &nbsp;·&nbsp; Shift+Enter for new line</div>
        <div id="aq-msg"></div>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const Q=id=>document.getElementById(id);
  const shell=Q('aq-shell');
  const qEl=Q('aq-q'),orgEl=Q('organization_id'),sendBtn=Q('aq-sendbtn');
  const status=Q('aq-status'),msgEl=Q('aq-msg'),msgs=Q('aq-messages');
  const scrl=Q('aq-scroll'),welc=Q('aq-welcome'),newBtn=Q('aq-newbtn');
  const adv=Q('aq-adv'),sqlEl=Q('aq-sql'),copyBtn=Q('aq-copybtn');
  const expBtn=Q('aq-expbtn'),expOrg=Q('aq-exp-org'),expSql=Q('aq-exp-sql');
  const thead=Q('aq-thead'),tbody=Q('aq-tbody'),empty=Q('aq-empty');
  const rtable=Q('aq-rtable-wrap'),badge=Q('aq-rowbadge'),histC=Q('aq-hist-cont');
  const sidebar=Q('aq-sidebar'),overlay=Q('aq-overlay'),hbtn=Q('aq-hbtn');
  const searchEl=Q('aq-search'),searchWrap=Q('aq-search-wrap'),searchEmpty=Q('aq-search-empty');

  const csrf=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')||'';
  const askUrl=@json(route('dashboard.analytics.ask'));
  let cd=null,typing=null,sbOpen=false;

  /* ── theme sync ── */
  const sunIcon=Q('aq-theme-sun'),moonIcon=Q('aq-theme-moon');
  function syncTheme(){
    const isDark=document.documentElement.classList.contains('dark');
    shell.setAttribute('data-theme',isDark?'dark':'');
    if(sunIcon&&moonIcon){
      sunIcon.style.display=isDark?'none':'block';
      moonIcon.style.display=isDark?'block':'none';
    }
  }
  syncTheme();
  new MutationObserver(syncTheme).observe(document.documentElement,{attributes:true,attributeFilter:['class']});

  /* ── theme toggle rail btn ── */
  Q('aq-rail-theme')?.addEventListener('click',()=>{
    const isDark=document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme',isDark?'dark':'light');
    syncTheme();
  });

  /* ── user avatar dropdown ── */
  const userBtn=Q('aq-user-btn'),userMenu=Q('aq-user-menu');
  userBtn?.addEventListener('click',(e)=>{
    e.stopPropagation();
    const isOpen=userMenu.style.display==='block';
    userMenu.style.display=isOpen?'none':'block';
  });
  document.addEventListener('click',(e)=>{
    if(userMenu&&!Q('aq-user-wrap')?.contains(e.target)){
      userMenu.style.display='none';
    }
  });

  /* ── sidebar toggle ── */
  function applySB(){
    sidebar.classList.toggle('open',sbOpen);
    overlay.classList.toggle('on',sbOpen&&window.innerWidth<768);
  }
  function openSidebar(){sbOpen=true;applySB();}
  hbtn?.addEventListener('click',()=>{sbOpen=!sbOpen;applySB();});
  Q('aq-rail-hist')?.addEventListener('click',openSidebar);

  /* ── search rail icon opens the sidebar and focuses the search field ── */
  Q('aq-rail-search')?.addEventListener('click',()=>{
    openSidebar();
    setTimeout(()=>{
      searchEl?.focus();
      if(searchWrap){
        searchWrap.classList.remove('search-flash');
        void searchWrap.offsetWidth; // restart animation
        searchWrap.classList.add('search-flash');
      }
    },120); // wait for the sidebar slide-out transition to start
  });

  Q('aq-rail-new')?.addEventListener('click',()=>{reset();});
  Q('aq-rail-sql')?.addEventListener('click',()=>{adv.open=!adv.open;});
  Q('aq-rail-export')?.addEventListener('click',()=>{if(!expBtn.disabled)Q('aq-export-form')?.submit();});
  overlay?.addEventListener('click',()=>{sbOpen=false;applySB();});
  newBtn?.addEventListener('click',reset);

  /* ── search filter ── */
  searchEl?.addEventListener('input',()=>{
    const v=searchEl.value.toLowerCase().trim();
    let anyVisible=false;
    document.querySelectorAll('.hbtn-sb').forEach(b=>{
      const match=(b.dataset.q||'').toLowerCase().includes(v);
      b.style.display=match?'block':'none';
      if(match)anyVisible=true;
    });
    Q('aq-hist-empty')?.style && (Q('aq-hist-empty').style.display='none');
    if(searchEmpty)searchEmpty.style.display=(v&&!anyVisible)?'block':'none';
  });
  searchEl?.addEventListener('focus',()=>{if(searchWrap)searchWrap.style.borderColor='var(--accent,#4f46e5)';});
  searchEl?.addEventListener('blur',()=>{if(searchWrap)searchWrap.style.borderColor='rgba(255,255,255,.08)';});

  /* ── textarea resize ── */
  function resize(){qEl.style.height='auto';qEl.style.height=Math.min(qEl.scrollHeight,160)+'px';}
  qEl?.addEventListener('input',resize);
  qEl?.addEventListener('keydown',e=>{if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();if(!sendBtn.disabled)doSend();}});

  /* ── chips ── */
  document.querySelectorAll('.q-chip').forEach(c=>{
    c.addEventListener('click',()=>{if(!qEl)return;qEl.value=c.dataset.p||'';resize();qEl.focus();});
  });

  /* ── helpers ── */
  const esc=v=>String(v??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[m]);
  const setEnabled=v=>{if(sendBtn)sendBtn.disabled=!v;};
  const setMsg=(h,err)=>{msgEl.innerHTML=h;msgEl.style.color=err?'#dc2626':'var(--text-faint)';};

  /* ── reset ── */
  function reset(){
    msgs.innerHTML='';welc.style.display='flex';
    sqlEl.value='';expSql.value='';expOrg.value='';
    copyBtn.disabled=true;expBtn.disabled=true;
    renderTable([],[]);
    setMsg('',false);status.textContent='';badge.style.display='none';
    if(qEl){qEl.value='';resize();qEl.focus();}
    document.querySelectorAll('.hbtn-sb').forEach(b=>b.classList.remove('active'));
  }

  /* ── messages ── */
  const initials=@json($initials?:'U');
  function appendMsg(role,text){
    welc.style.display='none';
    const row=document.createElement('div');
    row.className='mrow '+(role==='user'?'user-r':'');
    const av=document.createElement('div');
    av.className='mavatar '+(role==='user'?'u':'a');
    av.textContent=role==='user'?initials:'AI';
    const bub=document.createElement('div');
    bub.className='mbubble';bub.textContent=text;
    row.appendChild(av);row.appendChild(bub);
    msgs.appendChild(row);scrl.scrollTop=scrl.scrollHeight;
  }
  function showTyping(){
    removeTyping();welc.style.display='none';
    const row=document.createElement('div');
    row.id='aq-typing-row';row.className='mrow';
    row.innerHTML='<div class="mavatar a">AI</div><div class="mbubble" style="padding:10px 15px;background:var(--bg-panel);border:1px solid var(--border);border-radius:14px 14px 14px 4px;"><span class="tdot"></span> <span class="tdot"></span> <span class="tdot"></span></div>';
    msgs.appendChild(row);typing=row;scrl.scrollTop=scrl.scrollHeight;
  }
  function removeTyping(){typing?.remove();typing=null;}

  /* ── table ── */
  function renderTable(cols,rows){
    thead.innerHTML='';tbody.innerHTML='';
    empty.style.display='none';rtable.style.display='none';badge.style.display='none';
    if(!rows?.length){if(cols.length){empty.textContent='Query returned no rows.';empty.style.display='block';}return;}
    rtable.style.display='block';
    badge.textContent=rows.length+' row'+(rows.length!==1?'s':'');
    badge.style.display='inline-block';
    const tr=document.createElement('tr');
    cols.forEach(c=>{const th=document.createElement('th');th.textContent=c;tr.appendChild(th);});
    thead.appendChild(tr);
    rows.forEach(row=>{const r=document.createElement('tr');cols.forEach(c=>{const td=document.createElement('td');td.textContent=row[c]??'';r.appendChild(td);});tbody.appendChild(r);});
    if(!adv.open)adv.open=true;
  }

  /* ── history ── */
  function addHist(item){
    if(!item?.question)return;
    const btn=document.createElement('button');
    btn.type='button';btn.className='hbtn-sb';
    btn.dataset.q=item.question;btn.title=item.question;
    btn.innerHTML=esc(item.question.substring(0,48))+(item.question.length>48?'…':'')
      +'<span class="htime">'+esc(item.created_at||'')+'</span>';
    bindHistBtn(btn);
    Q('aq-hist-empty')?.remove();
    histC?.insertBefore(btn,histC.firstChild);
  }
  function bindHistBtn(b){
    b.addEventListener('click',()=>{
      if(qEl){qEl.value=b.dataset.q||'';resize();qEl.focus();}
      document.querySelectorAll('.hbtn-sb').forEach(x=>x.classList.remove('active'));
      b.classList.add('active');
      if(window.innerWidth<768){sbOpen=false;applySB();}
    });
  }
  document.querySelectorAll('.hbtn-sb').forEach(bindHistBtn);

  /* ── copy SQL ── */
  copyBtn?.addEventListener('click',async()=>{
    if(!sqlEl.value)return;
    try{await navigator.clipboard.writeText(sqlEl.value);copyBtn.textContent='Copied!';setTimeout(()=>copyBtn.textContent='Copy',2000);}
    catch{setMsg('Could not copy.',true);}
  });

  /* ── cooldown ── */
  function startCD(s){
    let r=Math.max(1,+s);setEnabled(false);
    if(cd)clearInterval(cd);
    status.textContent='Please wait '+r+'s…';
    cd=setInterval(()=>{r--;if(r<=0){clearInterval(cd);cd=null;status.textContent='';setEnabled(true);return;}status.textContent='Please wait '+r+'s…';},1000);
  }

  /* ── send ── */
  sendBtn?.addEventListener('click',doSend);
  async function doSend(){
    const q=qEl.value?.trim()||'',org=orgEl?.value||'';
    if(!q){setMsg('Please enter a question.',true);return;}
    setMsg('',false);setEnabled(false);status.textContent='';
    appendMsg('user',q);qEl.value='';resize();showTyping();
    try{
      const res=await fetch(askUrl,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':csrf},body:JSON.stringify({question:q,organization_id:org||null})});
      let d;try{d=await res.json();}catch{d={message:res.status===403?'Unauthorized.':'Request failed.'};}
      removeTyping();
      if(!res.ok){
        setMsg(d.message||'Request failed.',true);
        sqlEl.value='';copyBtn.disabled=true;expBtn.disabled=true;renderTable([],[]);
        const rs=+(d.retry_after_seconds||0);
        if(res.status===429&&rs>0)startCD(rs);
        else{status.textContent='';setEnabled(true);}
        return;
      }
      status.textContent='';setMsg('',false);
      const cols=d.columns||[],rows=d.rows||[],raw=d.raw_sql||'';
      if(d.is_conversation){
        sqlEl.value='';expOrg.value='';expSql.value='';
        copyBtn.disabled=true;expBtn.disabled=true;renderTable([],[]);
        appendMsg('assistant',d.message);
      }else{
        sqlEl.value=raw;expOrg.value=org||'';expSql.value=raw;
        copyBtn.disabled=false;expBtn.disabled=false;
        renderTable(cols,rows);
        appendMsg('assistant',(d.message||'Done')+(rows.length?' — '+rows.length+' row(s)':''));
      }
      if(d.history_item)addHist(d.history_item);
      setEnabled(true);
    }catch(err){
      console.error(err);removeTyping();status.textContent='';
      setMsg('Could not reach the server. Please try again.',true);setEnabled(true);
    }
  }
  /* ── kill layout wrapper gap ── */
  (function(){
    var el=document.getElementById('aq-shell');
    if(!el)return;
    var p=el.parentElement;
    while(p&&p.tagName!=='BODY'){
      p.style.padding='0';
      p.style.margin='0';
      p=p.parentElement;
    }
  })();
})();
</script>
</x-app-layout>
