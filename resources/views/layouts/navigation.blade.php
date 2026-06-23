{{--
  NAVBAR — mobile-first version
  • Hamburger LEFT → slides open sidebar (mobile only)
  • Menu button RIGHT → smooth dropdown
  • Gradient borders everywhere
  • Light/dark sidebar
  • Mobile: aq-rail + aq-sidebar hidden
--}}

@once
<style>
  :root{ --nav-height: 3.5rem; }
  main{ padding: 0 !important; margin: 0 !important; }

  /* ══ MOBILE: hide old rail + sidebar, show main full width ══ */
  @media(max-width:639px){
  #aq-rail    { display:none !important; }
  #aq-sidebar { display:none !important; }
  #aq-mobile-overlay,
  #aq-mobile-drawer { display:none !important; }
  #aq-main{
    display:flex !important;
    flex-direction:column !important;
    width:100vw !important;
    max-width:100vw !important;
    min-width:0 !important;
    margin:0 !important;
    padding:0 !important;
    left:0 !important;
    position:relative !important;
  }
  #aq-shell{
    display:block !important;
  }
  /* Analytics page pe hamburger show karo */
  body.analytics-page #aq-nav-hamburger {
    display:flex !important;
  }
}

/* ══ DESKTOP: rail visible ══ */
@media(min-width:640px){
  #aq-rail{ display:flex !important; }
  #aq-nav-hamburger{ display:none !important; visibility:hidden !important; pointer-events:none !important; }
  #aq-nav-menu-right{ display:none !important; }
}

  /* ── theme toggle ── */
  #aq-nav-theme{
    width:34px;height:34px;border-radius:9px;
    display:flex;align-items:center;justify-content:center;
    background:transparent;border:none;cursor:pointer;
    transition:background .15s;flex-shrink:0;
  }
  #aq-nav-theme:hover{background:#f3f4f6;}
  .dark #aq-nav-theme:hover{background:rgba(255,255,255,.07);}
  #aq-nav-theme svg{
    width:18px;height:18px;stroke:#6b7280;fill:none;stroke-width:1.8;transition:stroke .15s;
  }
  .dark #aq-nav-theme svg{stroke:#94a3b8;}
  #aq-nav-theme:hover svg{stroke:#4f46e5;}
  .dark #aq-nav-theme:hover svg{stroke:#a5b4fc;}

  /* ── user avatar ── */
  #aq-nav-user-btn{
    width:32px;height:32px;border-radius:50%;flex-shrink:0;
    background:linear-gradient(135deg,#4f46e5,#7c3aed);
    border:2px solid rgba(165,180,252,.3);
    display:flex;align-items:center;justify-content:center;
    font-size:11px;font-weight:700;color:#fff;
    box-shadow:0 0 10px rgba(99,102,241,.35);
    cursor:pointer;transition:transform .15s,box-shadow .15s;
  }
  #aq-nav-user-btn:hover{transform:scale(1.06);box-shadow:0 0 16px rgba(99,102,241,.5);}

 /* ── left hamburger (mobile only) ── */
#aq-nav-hamburger{
  width:36px;height:36px;border-radius:9px;
  display:none;align-items:center;justify-content:center;
  background:transparent;border:none;cursor:pointer;
  transition:background .15s;flex-shrink:0;
}
  #aq-nav-hamburger:hover{background:#f3f4f6;}
  .dark #aq-nav-hamburger:hover{background:rgba(255,255,255,.07);}
  #aq-nav-hamburger svg{
     width: 26px;height: 26px;border-radius: 6px;stroke:#6b7280;fill:none;
    stroke-width:2;stroke-linecap:round;
  }
  .dark #aq-nav-hamburger svg{stroke:#94a3b8;}

  /* ── right menu button (mobile only) ── */
  #aq-nav-menu-right{
    width:32px;height:32px;border-radius:8px;
    display:inline-flex;align-items:center;justify-content:center;
    background:transparent;border:none;cursor:pointer;
    color:#6b7280;transition:background .15s;
  }
  #aq-nav-menu-right:hover{background:#f3f4f6;}
  .dark #aq-nav-menu-right{color:#9ca3af;}
  .dark #aq-nav-menu-right:hover{background:rgba(255,255,255,.07);}

  /* ══ SIDEBAR OVERLAY ══ */
  #aq-nav-sidebar-overlay{
    display:none;position:fixed;inset:0;
    background:rgba(0,0,0,.45);backdrop-filter:blur(1px);z-index:9998;
  }
  #aq-nav-sidebar-overlay.on{ display:block; }

  /* ══ SIDEBAR DRAWER ══ */
  #aq-nav-sidebar-drawer{
    position:fixed;top:0;left:0;bottom:0;
    width:300px;max-width:85vw;
    background:#ffffff;
    z-index:9999;
    transform:translateX(-100%);
    transition:transform .25s cubic-bezier(.4,0,.2,1);
    display:flex;flex-direction:column;
    box-shadow:4px 0 32px rgba(0,0,0,.12);
    /* gradient border on right */
    border-right:none;
  }
  .dark #aq-nav-sidebar-drawer{
    background:#0f172a;
    box-shadow:4px 0 32px rgba(0,0,0,.55);
  }
  #aq-nav-sidebar-drawer.open{ transform:translateX(0); }

  /* ══ SIDEBAR animated moving border ══ */
  @keyframes aq-border-slide{
    0%   { background-position: 0% 0%; }
    100% { background-position: 0% 200%; }
  }

  #aq-nav-sidebar-drawer::after{
    content:'';position:absolute;top:0;right:0;
    width:3px;height:100%;
    background: linear-gradient(
      180deg,
      #f97316, #ec4899, #8b5cf6, #06b6d4,
      #f97316, #ec4899, #8b5cf6, #06b6d4
    );
    background-size: 100% 200%;
    animation: aq-border-slide 3s linear infinite;
    pointer-events:none;z-index:10;
  }

  /* sidebar header */
  .nav-sb-header{
    padding:16px 16px 14px;
    border-bottom:1px solid #e5e7eb;
    display:flex;align-items:center;justify-content:space-between;flex-shrink:0;
  }
  .dark .nav-sb-header{border-bottom-color:rgba(255,255,255,.07);}
  .nav-sb-header-title{font-size:13.5px;font-weight:600;color:#111827;}
  .dark .nav-sb-header-title{color:#e2e8f0;}
  .nav-sb-header-sub{font-size:11px;color:#6b7280;margin-top:1px;}
  .dark .nav-sb-header-sub{color:rgba(255,255,255,.35);}
  .nav-sb-close{
    width:30px;height:30px;display:flex;align-items:center;justify-content:center;
    border-radius:7px;border:none;background:#f3f4f6;color:#6b7280;
    cursor:pointer;font-size:16px;transition:background .13s;
  }
  .dark .nav-sb-close{background:rgba(255,255,255,.06);color:rgba(255,255,255,.6);}
  .nav-sb-close:hover{background:#e5e7eb;}
  .dark .nav-sb-close:hover{background:rgba(255,255,255,.12);}

  /* new query btn */
  .nav-sb-newbtn{
    width:100%;padding:9px 14px;margin:10px 0 2px;border-radius:9px;
    background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.25);
    color:#4f46e5;font-size:13px;font-weight:500;font-family:inherit;cursor:pointer;
    transition:background .14s,border-color .14s;
    display:flex;align-items:center;gap:8px;
  }
  .dark .nav-sb-newbtn{background:rgba(99,102,241,.15);border-color:rgba(99,102,241,.3);color:#a5b4fc;}
  .nav-sb-newbtn:hover{background:rgba(99,102,241,.18);border-color:rgba(99,102,241,.4);}
  .dark .nav-sb-newbtn:hover{background:rgba(99,102,241,.25);border-color:rgba(99,102,241,.5);}
  .nav-sb-newbtn svg{width:15px;height:15px;stroke:currentColor;fill:none;stroke-width:2.2;flex-shrink:0;}

  /* search */
  .nav-sb-search-wrap{
    margin:8px 14px 4px;display:flex;align-items:center;gap:8px;
    padding:7px 12px;border-radius:9px;
    background:#f9fafb;border:1px solid #e5e7eb;transition:border-color .15s;
  }
  .dark .nav-sb-search-wrap{background:rgba(255,255,255,.05);border-color:rgba(255,255,255,.08);}
  .nav-sb-search-wrap:focus-within{border-color:#a5b4fc;}
  .dark .nav-sb-search-wrap:focus-within{border-color:rgba(255,255,255,.18);}
  .nav-sb-search-wrap svg{width:13px;height:13px;stroke:#9ca3af;fill:none;stroke-width:2;flex-shrink:0;}
  .dark .nav-sb-search-wrap svg{stroke:rgba(255,255,255,.4);}
  #aq-nav-sb-search{
    background:transparent;border:none;outline:none;box-shadow:none;
    font-size:12.5px;color:#374151;width:100%;font-family:inherit;
  }
  .dark #aq-nav-sb-search{color:rgba(255,255,255,.7);}
  #aq-nav-sb-search::placeholder{color:#9ca3af;}
  .dark #aq-nav-sb-search::placeholder{color:rgba(255,255,255,.3);}

  /* section label */
  .nav-sb-section{
    font-size:10px;font-weight:600;letter-spacing:.08em;
    text-transform:uppercase;color:#9ca3af;padding:12px 16px 4px;
  }
  .dark .nav-sb-section{color:rgba(255,255,255,.3);}

  /* scroll */
  .nav-sb-scroll{flex:1;overflow-y:auto;padding:4px 8px 8px;}
  .nav-sb-scroll::-webkit-scrollbar{width:3px;}
  .nav-sb-scroll::-webkit-scrollbar-thumb{background:#e5e7eb;border-radius:2px;}
  .dark .nav-sb-scroll::-webkit-scrollbar-thumb{background:rgba(255,255,255,.1);}

  /* history items */
  .nav-hbtn{
    display:block;width:100%;text-align:left;
    padding:9px 10px;border-radius:8px;
    background:transparent;border:none;color:#374151;font-size:12.5px;
    font-family:inherit;cursor:pointer;
    transition:background .13s,color .13s;
    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;line-height:1.4;
  }
  .dark .nav-hbtn{color:rgba(255,255,255,.65);}
  .nav-hbtn:hover{background:#f3f4f6;color:#111827;}
  .dark .nav-hbtn:hover{background:rgba(255,255,255,.07);color:#e2e8f0;}
  .nav-hbtn.active{background:#eef2ff;color:#4f46e5;}
  .dark .nav-hbtn.active{background:rgba(99,102,241,.18);color:#a5b4fc;}
  .nav-htime{
    display:block;font-size:10.5px;color:#9ca3af;
    margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
  }
  .dark .nav-htime{color:rgba(255,255,255,.3);}

  /* nav links desktop */
  .aq-nav-link{
    padding:6px 12px;border-radius:7px;font-size:13.5px;font-weight:500;
    color:#374151;text-decoration:none;transition:all .15s;
  }
  .aq-nav-link:hover{background:#f3f4f6;color:#4f46e5;}
  .dark .aq-nav-link{color:#9ca3af;}
  .dark .aq-nav-link:hover{background:rgba(255,255,255,.07);color:#a5b4fc;}
  .aq-nav-link.active{background:#eef2ff;color:#4f46e5;font-weight:600;}
  .dark .aq-nav-link.active{background:rgba(99,102,241,.2);color:#a5b4fc;}

  /* sidebar footer */
  .nav-sb-profile{
    display:flex;align-items:center;gap:9px;
    padding:10px 14px;font-size:12.5px;color:#6b7280;
    text-decoration:none;transition:background .13s,color .13s;
  }
  .dark .nav-sb-profile{color:#94a3b8;}
  .nav-sb-profile:hover{background:#f9fafb;color:#111827;}
  .dark .nav-sb-profile:hover{background:rgba(255,255,255,.05);color:#e2e8f0;}
  .nav-sb-profile svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;}

  .nav-sb-logout{
    display:flex;align-items:center;gap:9px;width:100%;
    padding:10px 14px;font-size:12.5px;color:#ef4444;
    background:transparent;border:none;cursor:pointer;
    font-family:inherit;transition:background .13s;text-align:left;
  }
  .nav-sb-logout:hover{background:#fef2f2;}
  .dark .nav-sb-logout:hover{background:rgba(248,113,113,.08);}
  .nav-sb-logout svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;}

  .nav-sb-footer-divider{height:1px;background:#e5e7eb;margin:0 10px;}
  .dark .nav-sb-footer-divider{background:rgba(255,255,255,.06);}

  .nav-sb-empty-txt{color:#9ca3af;}
  .dark .nav-sb-empty-txt{color:rgba(255,255,255,.28);}

  /* ══ RIGHT MENU — smooth slide + animated moving gradient top border ══ */
  #aq-nav-right-menu{
    overflow:hidden;
    max-height:0;
    opacity:0;
    transition:max-height .3s cubic-bezier(.4,0,.2,1), opacity .25s ease;
    position:relative;
  }
  #aq-nav-right-menu.open{
    max-height:520px;
    opacity:1;
  }
  /* animated moving gradient top border */
  #aq-nav-right-menu::before{
    content:'';
    position:absolute;top:0;left:0;right:0;height:2px;
    background: linear-gradient(
      90deg,
      #f97316, #ec4899, #8b5cf6, #06b6d4,
      #f97316, #ec4899, #8b5cf6, #06b6d4
    );
    background-size: 200% 100%;
    animation: aq-border-slide-h 3s linear infinite;
    z-index:1;
  }
  @keyframes aq-border-slide-h{
    0%   { background-position: 0% 0%; }
    100% { background-position: 200% 0%; }
  }


  #aq-ham-l1, #aq-ham-l2, #aq-ham-l3 {
  transition: width 0.25s cubic-bezier(.4,0,.2,1), 
              x 0.25s cubic-bezier(.4,0,.2,1);
  transform-origin: left center;
}
#aq-ham-l1, #aq-ham-l2, #aq-ham-l3 {
  transform-box: fill-box;
}
#aq-nav-menu-right circle {
  transform-box: fill-box;
  transform-origin: center center;
}
</style>
@endonce

@php
  $navUser = auth()->user();
  $navUserName = $navUser?->name ?? 'User';
  $navIsSuperAdmin = $navUser && method_exists($navUser,'hasRole') ? $navUser->hasRole('super-admin') : false;
  $navUserOrg = $navUser && !$navIsSuperAdmin ? $navUser->organization : null;
  $navInitials = collect(explode(' ', $navUserName))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->join('');
  $navSessions = \App\Models\AnalyticsSession::query()
    ->when(!$navIsSuperAdmin, fn($q) => $q->where('user_id', $navUser?->id))
    ->orderByDesc('created_at')
    ->limit(30)
    ->get();
@endphp

{{-- Sidebar overlay --}}
<div id="aq-nav-sidebar-overlay"></div>

{{-- Sidebar drawer --}}
<div id="aq-nav-sidebar-drawer" aria-label="Sidebar navigation">

  {{-- Header --}}
  <div class="nav-sb-header">
    <div>
      <div class="nav-sb-header-title">History</div>
      <div class="nav-sb-header-sub">Your recent queries</div>
    </div>
    <button type="button" class="nav-sb-close" id="aq-nav-sb-close" aria-label="Close sidebar">✕</button>
  </div>

  {{-- New query btn --}}
  <div style="padding:0 12px;">
    <button type="button" class="nav-sb-newbtn" id="aq-nav-sb-newbtn">
      <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      New query
    </button>
  </div>

  {{-- Search --}}
  <div class="nav-sb-search-wrap">
    <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
    <input id="aq-nav-sb-search" type="text" placeholder="Search history…">
  </div>

  {{-- Section label --}}
  <div class="nav-sb-section">Recent</div>

  {{-- History list --}}
  <div class="nav-sb-scroll">
    <div id="aq-nav-sb-hist">
      @forelse($navSessions as $item)
        <button type="button" class="nav-hbtn"
          data-session-id="{{ $item->id }}"
          data-q="{{ $item->title ?? $item->question ?? '' }}"
          title="{{ $item->title ?? $item->question ?? '' }}">
          {{ \Illuminate\Support\Str::limit($item->title ?? $item->question ?? '', 46) }}
          <span class="nav-htime">
            {{ optional($item->created_at)->format('d M, H:i') }}
            @if(isset($item->organization)) · {{ \Illuminate\Support\Str::limit($item->organization->name, 12) }}@endif
          </span>
        </button>
      @empty
        <p id="aq-nav-sb-empty" class="nav-sb-empty-txt" style="font-size:12px;padding:10px 11px;">No queries yet.</p>
      @endforelse
    </div>
    <p id="aq-nav-sb-search-empty" class="nav-sb-empty-txt" style="display:none;font-size:12px;padding:10px 11px;">No matches found.</p>
  </div>

  {{-- Footer --}}
  <div style="flex-shrink:0;">
    <div class="nav-sb-footer-divider"></div>
    <a href="{{ route('profile.edit') }}" class="nav-sb-profile">
      <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      Profile
    </a>
    <div class="nav-sb-footer-divider"></div>
    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
      @csrf
      <button type="submit" class="nav-sb-logout">
        <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
        Log Out
      </button>
    </form>
    <div style="height:8px;"></div>
  </div>
</div>

{{-- ══ NAVBAR ══ --}}
<nav id="aq-navbar" class="sticky top-0 z-30 bg-white dark:bg-gray-950 border-b border-gray-200 dark:border-gray-800" style="position:relative;">

  <div class="mx-auto px-3 sm:px-4">
    <div class="flex items-center h-14 gap-2">

      {{-- LEFT: Hamburger (mobile only — opens history sidebar) --}}
<button type="button" id="aq-nav-hamburger" aria-label="Open sidebar">
  <svg id="aq-ham-svg" viewBox="0 0 24 24" fill="none" 
       xmlns="http://www.w3.org/2000/svg" width="24" height="24"
       style="overflow:visible;">
    <rect id="aq-ham-l1" x="3" y="4"     width="18" height="3.5" rx="1.75"
          stroke="#6b7280" stroke-width="1.2" fill="none"
          style="transform-origin:3px 5.75px; transition: transform 0.35s cubic-bezier(.4,0,.2,1);"/>
    <rect id="aq-ham-l2" x="3" y="10.25" width="18" height="3.5" rx="1.75"
          stroke="#6b7280" stroke-width="1.2" fill="none"
          style="transform-origin:3px 12px; transition: transform 0.35s cubic-bezier(.4,0,.2,1);"/>
    <rect id="aq-ham-l3" x="3" y="16.5"  width="12" height="3.5" rx="1.75"
          stroke="#6b7280" stroke-width="1.2" fill="none"
          style="transform-origin:3px 18.25px; transition: transform 0.35s cubic-bezier(.4,0,.2,1);"/>
  </svg>
</button>
      {{-- Logo --}}
      <a href="{{ route('dashboard') }}" class="flex items-center shrink-0">
        <x-application-logo class="block h-8 w-auto fill-current text-gray-800 dark:text-gray-100" />
      </a>

      {{-- Desktop Nav Links centered --}}
      <div class="hidden sm:flex items-center gap-1 absolute left-1/2 -translate-x-1/2">
        <a href="{{ route('dashboard') }}"
          class="aq-nav-link {{ request()->routeIs('dashboard') && !request()->routeIs('dashboard.analytics.*') ? 'active' : '' }}">
          Dashboard
        </a>
        <a href="{{ route('dashboard.analytics') }}"
          class="aq-nav-link {{ request()->routeIs('dashboard.analytics.*') ? 'active' : '' }}">
          Analytics
        </a>
        @if(Auth::user()?->hasRole('super-admin'))
          <a href="{{ route('admin.organizations.index') }}"
            class="aq-nav-link {{ request()->routeIs('admin.organizations.*') ? 'active' : '' }}">
            Organizations
          </a>
          <a href="{{ route('admin.users.index') }}"
            class="aq-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            Users
          </a>
          <a href="{{ route('admin.ai-settings.edit') }}"
            class="aq-nav-link {{ request()->routeIs('admin.ai-settings.*') ? 'active' : '' }}">
            AI Settings
          </a>
          <a href="{{ route('admin.ai-guide.edit') }}"
            class="aq-nav-link {{ request()->routeIs('admin.ai-guide.*') ? 'active' : '' }}">
            AI Guide
          </a>
        @endif
      </div>

      {{-- RIGHT: theme + avatar + mobile menu btn --}}
      <div class="flex items-center gap-2 ml-auto">

        {{-- Theme toggle --}}
        <button type="button" id="aq-nav-theme" title="Toggle theme" aria-label="Toggle theme">
          <svg id="aq-nav-theme-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
          <svg id="aq-nav-theme-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="display:none;"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
        </button>

        {{-- User avatar + dropdown --}}
        <div class="relative" id="aq-nav-user-wrap">
          <button type="button" id="aq-nav-user-btn" title="{{ $navUserName }}">{{ $navInitials ?: 'U' }}</button>

          <div id="aq-nav-user-menu" style="
            display:none;position:absolute;top:calc(100% + 8px);right:0;
            min-width:190px;background:#1e293b;
            border:1px solid rgba(255,255,255,.1);border-radius:12px;
            overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,.35);z-index:9999;
          ">
            <div style="padding:12px 14px 10px;border-bottom:1px solid rgba(255,255,255,.07);">
              <div style="display:flex;align-items:center;gap:9px;">
                <div style="width:30px;height:30px;border-radius:50%;flex-shrink:0;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;border:2px solid rgba(165,180,252,.3);">{{ $navInitials ?: 'U' }}</div>
                <div style="min-width:0;">
                  <div style="font-size:12.5px;font-weight:600;color:#e2e8f0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:110px;">{{ $navUserName }}</div>
                  @if($navUserOrg)
                    <div style="font-size:10.5px;color:rgba(255,255,255,.38);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:110px;">{{ $navUserOrg->name }}</div>
                  @endif
                </div>
              </div>
            </div>
            <a href="{{ route('profile.edit') }}" style="display:flex;align-items:center;gap:9px;padding:10px 14px;font-size:12.5px;color:#94a3b8;text-decoration:none;transition:background .13s,color .13s;"
               onmouseover="this.style.background='rgba(255,255,255,.06)';this.style.color='#e2e8f0'"
               onmouseout="this.style.background='';this.style.color='#94a3b8'">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              Profile
            </a>
            <div style="height:1px;background:rgba(255,255,255,.06);margin:0 10px;"></div>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
              @csrf
              <button type="submit" style="display:flex;align-items:center;gap:9px;width:100%;padding:10px 14px;font-size:12.5px;color:#f87171;background:transparent;border:none;cursor:pointer;font-family:inherit;transition:background .13s;text-align:left;"
                onmouseover="this.style.background='rgba(248,113,113,.08)'"
                onmouseout="this.style.background=''">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                Log Out
              </button>
            </form>
          </div>
        </div>

        {{-- Mobile right menu button --}}
        <button id="aq-nav-menu-right" aria-label="Open nav menu">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
        <circle cx="5" cy="12" r="1.5"/>
        <circle cx="12" cy="12" r="1.5"/>
        <circle cx="19" cy="12" r="1.5"/>
        </svg>
        </button>
      </div>

    </div>
  </div>

  {{-- Mobile nav links — smooth slide with gradient top border --}}
  <div id="aq-nav-right-menu">
    <div class="px-3 py-3 space-y-1">
      <a href="{{ route('dashboard') }}"
        class="block px-3 py-2 rounded-lg text-sm font-medium transition
          {{ request()->routeIs('dashboard') && !request()->routeIs('dashboard.analytics.*')
            ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
            : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
        Dashboard
      </a>
      <a href="{{ route('dashboard.analytics') }}"
        class="block px-3 py-2 rounded-lg text-sm font-medium transition
          {{ request()->routeIs('dashboard.analytics.*')
            ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
            : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
        Analytics
      </a>
      @if(Auth::user()?->hasRole('super-admin'))
        <a href="{{ route('admin.organizations.index') }}"
          class="block px-3 py-2 rounded-lg text-sm font-medium transition
            {{ request()->routeIs('admin.organizations.*')
              ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
              : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
          Organizations
        </a>
        <a href="{{ route('admin.users.index') }}"
          class="block px-3 py-2 rounded-lg text-sm font-medium transition
            {{ request()->routeIs('admin.users.*')
              ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
              : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
          Users
        </a>
        <a href="{{ route('admin.ai-settings.edit') }}"
          class="block px-3 py-2 rounded-lg text-sm font-medium transition
            {{ request()->routeIs('admin.ai-settings.*')
              ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
              : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
          AI Settings
        </a>
        <a href="{{ route('admin.ai-guide.edit') }}"
          class="block px-3 py-2 rounded-lg text-sm font-medium transition
            {{ request()->routeIs('admin.ai-guide.*')
              ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
              : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
          AI Guide
        </a>
      @endif
    </div>
  </div>
</nav>

<script>
(function(){
  /* ── Theme ── */
  function applyNavIcons(){
    const isDark=document.documentElement.classList.contains('dark');
    const sun=document.getElementById('aq-nav-theme-sun');
    const moon=document.getElementById('aq-nav-theme-moon');
    if(sun&&moon){sun.style.display=isDark?'none':'block';moon.style.display=isDark?'block':'none';}
  }
  function toggleTheme(){
    const isDark=document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme',isDark?'dark':'light');
    applyNavIcons();
  }
  document.getElementById('aq-nav-theme')?.addEventListener('click',toggleTheme);
  applyNavIcons();
  new MutationObserver(applyNavIcons).observe(document.documentElement,{attributes:true,attributeFilter:['class']});

  /* ── User dropdown ── */
  const userBtn=document.getElementById('aq-nav-user-btn');
  const userMenu=document.getElementById('aq-nav-user-menu');
  const userWrap=document.getElementById('aq-nav-user-wrap');
  userBtn?.addEventListener('click',(e)=>{
    e.stopPropagation();
    userMenu.style.display=userMenu.style.display==='block'?'none':'block';
  });
  document.addEventListener('click',(e)=>{
    if(userMenu&&!userWrap?.contains(e.target)) userMenu.style.display='none';
  });

  /* ── Right mobile menu — smooth CSS class toggle ── */
  const rightMenuBtn=document.getElementById('aq-nav-menu-right');
  const rightMenu=document.getElementById('aq-nav-right-menu');
rightMenuBtn?.addEventListener('click',(e)=>{
  e.stopPropagation();

  const dots = rightMenuBtn.querySelectorAll('circle');
  if(!dots.length){ rightMenu.classList.toggle('open'); return; }

  // already open ho tou seedha close
  if(rightMenu.classList.contains('open')){
    rightMenu.classList.remove('open');
    return;
  }

  rightMenuBtn.disabled = true;

  // Step 1: dots scatter bahar
  dots[0].style.transition = 'transform 0.12s cubic-bezier(.4,0,.2,1)';
  dots[1].style.transition = 'transform 0.12s cubic-bezier(.4,0,.2,1)';
  dots[2].style.transition = 'transform 0.12s cubic-bezier(.4,0,.2,1)';

  setTimeout(()=>{
    dots[0].style.transform = 'translateY(-5px)';
    dots[1].style.transform = 'scale(1.6)';
    dots[2].style.transform = 'translateY(5px)';
  }, 0);

  // Step 2: wapas normal
  setTimeout(()=>{
    dots[0].style.transform = 'translateY(0)';
    dots[1].style.transform = 'scale(1)';
    dots[2].style.transform = 'translateY(0)';
  }, 150);

  // Step 3: menu open
  setTimeout(()=>{
    rightMenuBtn.disabled = false;
    rightMenu.classList.add('open');
  }, 280);
});
  document.addEventListener('click',(e)=>{
    if(rightMenu&&rightMenuBtn&&!rightMenuBtn.contains(e.target)&&!rightMenu.contains(e.target)){
      rightMenu.classList.remove('open');
    }
  });

  /* ── Sidebar drawer ── */
  const drawer=document.getElementById('aq-nav-sidebar-drawer');
  const overlay=document.getElementById('aq-nav-sidebar-overlay');
  const hamBtn=document.getElementById('aq-nav-hamburger');
  const closeBtn=document.getElementById('aq-nav-sb-close');
  const newBtn=document.getElementById('aq-nav-sb-newbtn');

  function openSidebar(){
    drawer?.classList.add('open');
    overlay?.classList.add('on');
    document.body.style.overflow='hidden';
  }
  function closeSidebar(){
    drawer?.classList.remove('open');
    overlay?.classList.remove('on');
    document.body.style.overflow='';
  }

  /* ── Hamburger animation then open ── */
hamBtn?.addEventListener('click', () => {
  const l1 = document.getElementById('aq-ham-l1');
  const l2 = document.getElementById('aq-ham-l2');
  const l3 = document.getElementById('aq-ham-l3');

  hamBtn.disabled = true;

  l1.style.transition = 'width 0.1s cubic-bezier(.4,0,.2,1)';
  l2.style.transition = 'width 0.1s cubic-bezier(.4,0,.2,1)';
  l3.style.transition = 'width 0.1s cubic-bezier(.4,0,.2,1)';

  // CLOSE: 1 → 2 → 3
  setTimeout(() => { l1.style.width = '0px'; }, 0);
  setTimeout(() => { l2.style.width = '0px'; }, 80);
  setTimeout(() => { l3.style.width = '0px'; }, 160);

  // OPEN: 3 → 2 → 1
  setTimeout(() => { l3.style.width = '12px'; }, 280);
  setTimeout(() => { l2.style.width = '18px'; }, 360);
  setTimeout(() => { l1.style.width = '18px'; }, 440);

  // SIDEBAR OPEN
  setTimeout(() => {
    hamBtn.disabled = false;
    openSidebar();
    
  }, 520);
});
  closeBtn?.addEventListener('click',closeSidebar);
  overlay?.addEventListener('click',closeSidebar);

  newBtn?.addEventListener('click',()=>{
    closeSidebar();
    window.dispatchEvent(new CustomEvent('aq-new-chat'));
  });

  /* ── Sidebar search ── */
  const sbSearch=document.getElementById('aq-nav-sb-search');
  const sbSearchEmpty=document.getElementById('aq-nav-sb-search-empty');
  sbSearch?.addEventListener('input',()=>{
    const v=sbSearch.value.toLowerCase().trim();
    let any=false;
    document.querySelectorAll('#aq-nav-sb-hist .nav-hbtn').forEach(b=>{
      const match=(b.dataset.q||'').toLowerCase().includes(v);
      b.style.display=match?'block':'none';
      if(match)any=true;
    });
    const emptyEl=document.getElementById('aq-nav-sb-empty');
    if(emptyEl) emptyEl.style.display='none';
    if(sbSearchEmpty) sbSearchEmpty.style.display=(v&&!any)?'block':'none';
  });

  /* ── History clicks ── */
  document.querySelectorAll('#aq-nav-sb-hist .nav-hbtn').forEach(b=>{
    b.addEventListener('click',()=>{
      const sid=b.dataset.sessionId;
      closeSidebar();
      if(sid){
        window.dispatchEvent(new CustomEvent('aq-load-session',{detail:{sessionId:parseInt(sid,10)}}));
        document.querySelectorAll('.nav-hbtn').forEach(x=>x.classList.remove('active'));
        b.classList.add('active');
      }
    });
  });

  window.addEventListener('aq-session-changed',e=>{
    const sid=e.detail?.sessionId;
    document.querySelectorAll('.nav-hbtn').forEach(x=>{
      x.classList.toggle('active', sid && x.dataset.sessionId===String(sid));
    });
  });

  document.addEventListener('keydown',(e)=>{
    if(e.key==='Escape'){ closeSidebar(); rightMenu?.classList.remove('open'); }
  });
/* ── Analytics page pe hamburger force-show/hide (mobile) ── */
 function updateHamburger(){
    const ham = document.getElementById('aq-nav-hamburger');
    if(!ham) return;
    if(window.innerWidth < 640){
      ham.style.removeProperty('visibility');
      ham.style.removeProperty('pointer-events');
      ham.style.setProperty('display', document.getElementById('aq-shell') ? 'flex' : 'none', 'important');
    }
  }
  updateHamburger();
  setTimeout(updateHamburger, 100);
  setTimeout(updateHamburger, 500);
})();


// Jab main chat mein naya session bane, navbar history update karo
  window.addEventListener('aq-session-prepend', (e) => {
    const { sessionId, title, createdAt, orgName } = e.detail || {};
    if (!sessionId) return;
    const hist = document.getElementById('aq-nav-sb-hist');
    if (!hist) return;
    hist.querySelector('p')?.remove();
    const existing = hist.querySelector(`.nav-hbtn[data-session-id="${sessionId}"]`);
    if (existing) existing.remove();
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'nav-hbtn';
    btn.dataset.sessionId = sessionId;
    btn.dataset.q = title || '';
    btn.title = title || '';
    const short = (title || '').length > 46 ? title.substring(0, 46) + '…' : (title || '');
    btn.innerHTML = short.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[m])
      + `<span class="nav-htime">${createdAt || ''}${orgName ? ' · ' + orgName : ''}</span>`;
    btn.addEventListener('click', () => {
      closeSidebar();
      window.dispatchEvent(new CustomEvent('aq-load-session', { detail: { sessionId: parseInt(sessionId, 10) } }));
      document.querySelectorAll('.nav-hbtn').forEach(x => x.classList.remove('active'));
      btn.classList.add('active');
    });
    hist.insertAdjacentElement('afterbegin', btn);
  });

</script>