<x-app-layout>
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@php
  $user = auth()->user();
  $isSuperAdmin = $user && method_exists($user,'hasRole') ? $user->hasRole('super-admin') : false;
  $orgs = \App\Models\Organization::query()->orderBy('name')->get();
  $organizationMissing = $user && !$isSuperAdmin && empty($user->organization_id);
  $userOrganization = $user && !$isSuperAdmin ? $user->organization : null;
  $userName = $user?->name ?? 'User';
  $firstName = explode(' ',$userName)[0];
  $initials = collect(explode(' ',$userName))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->join('');
  $sessionUrl       = route('dashboard.analytics.session', ['session' => '__ID__']);
  $deleteSessionUrl = route('dashboard.analytics.session.destroy', ['session' => '__ID__']);
  $exportUrl        = route('dashboard.analytics.export');
  $pdfUrl           = route('dashboard.analytics.pdf');
@endphp
<div id="aq-shell">

  {{-- ════ ICON RAIL ════ --}}
  <nav id="aq-rail" aria-label="Quick actions">

    {{-- Hamburger (replaces old chat logo) --}}
    <button type="button" class=" rail-logo"  id="aq-rail-hamburgertop"
      aria-label="Toggle sidebar"
      style="margin-bottom:2px;"
      onclick="document.getElementById('aq-hbtn').click()">
      <svg viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
    </button>

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

    @if($isSuperAdmin)
    {{-- SQL panel --}}
    <button type="button" class="rail-btn" id="aq-rail-sql" data-tip="SQL & Results" aria-label="Toggle SQL panel">
      <svg viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3"/></svg>
    </button>
    @endif

    @if($isSuperAdmin)
    {{-- Export --}}
    <button type="button" class="rail-btn" id="aq-rail-export" data-tip="Export Excel" aria-label="Export to Excel">
      <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
    </button>
    @endif

    <div class="rail-spacer"></div>

    {{-- Theme toggle --}}
    <button type="button" class="rail-btn" id="aq-rail-theme" data-tip="Toggle theme" aria-label="Toggle theme">
      <svg id="aq-theme-sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
      <svg id="aq-theme-moon" viewBox="0 0 24 24" style="display:none;"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
    </button>

    {{-- Avatar + Dropdown --}}
    <div style="position:relative;z-index:1;" id="aq-user-wrap">
      <div class="rail-avatar" id="aq-user-btn" title="{{ $userName }}">{{ $initials ?: 'U' }}</div>

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
        {{-- <div class="sb-brand">
          <a href="{{ route('dashboard') }}" style="display:flex;align-items:center;flex-shrink:0;">
             <x-application-logo class="block h-7 w-auto fill-current text-gray-900 dark:text-white" />
          </a>
          <span class="brand-name">Analytics</span>
        </div> --}}
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
            style="background:transparent;border:none;outline:none;box-shadow:none;font-size:12.5px;color:rgba(255,255,255,.7);width:100%;font-family:inherit;">
        </div>
      </div>

      <div class="sb-section">Recent</div>
      <div class="sb-scroll">
        <div id="aq-hist-cont">
          @forelse($sessions as $item)
            <button type="button" class="hbtn-sb"
              data-session-id="{{ $item->id }}"
              data-q="{{ $item->title ?? $item->question ?? '' }}"
              title="{{ $item->title ?? $item->question ?? '' }}">
              {{ \Illuminate\Support\Str::limit($item->title ?? $item->question ?? '', 48) }}
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
      @if($isSuperAdmin)
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
      @endif

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

{{-- Mobile history drawer --}}
<div id="aq-mobile-overlay"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(1px);z-index:40;"></div>

<div id="aq-mobile-drawer"
     style="display:flex;flex-direction:column;position:fixed;inset-y:0;left:0;width:310px;max-width:85vw;background:#0f172a;border-right:1px solid rgba(255,255,255,.08);z-index:50;transform:translateX(-100%);transition:transform .2s ease-out;">
  <div style="padding:14px 16px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
    <div>
      <div style="font-size:13px;font-weight:600;color:#e2e8f0;">History</div>
      <div style="font-size:11px;color:rgba(255,255,255,.35);">Your recent chats</div>
    </div>
    <button type="button" id="aq-mob-close"
            style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:7px;border:none;background:rgba(255,255,255,.06);color:rgba(255,255,255,.6);cursor:pointer;font-size:16px;">✕</button>
  </div>
  <div style="padding:10px 12px;flex-shrink:0;">
    <button type="button" id="aq-mob-newbtn"
            style="width:100%;padding:8px 12px;border-radius:8px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.1);color:#e2e8f0;font-size:12.5px;font-family:inherit;cursor:pointer;transition:background .14s;">+ New chat</button>
  </div>
  <div id="aq-mob-hist" style="flex:1;overflow-y:auto;padding:6px 8px;">
    @forelse($sessions as $item)
      <button type="button" class="hbtn-sb"
        data-session-id="{{ $item->id }}"
        data-q="{{ $item->title ?? $item->question ?? '' }}"
        title="{{ $item->title ?? $item->question ?? '' }}">
        {{ \Illuminate\Support\Str::limit($item->title ?? $item->question ?? '', 48) }}
        <span class="htime">
          {{ optional($item->created_at)->format('d M, H:i') }}
          @if($item->organization) · {{ \Illuminate\Support\Str::limit($item->organization->name,12) }}@endif
        </span>
      </button>
    @empty
      <p id="aq-mob-hist-empty" style="font-size:12px;color:rgba(255,255,255,.28);padding:10px 11px;">No queries yet.</p>
    @endforelse
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
  const mobHistC=Q('aq-mob-hist');
  const sidebar=Q('aq-sidebar'),overlay=Q('aq-overlay'),hbtn=Q('aq-hbtn');
  const searchEl=Q('aq-search'),searchWrap=Q('aq-search-wrap'),searchEmpty=Q('aq-search-empty');
  const mobOverlay=Q('aq-mobile-overlay'),mobDrawer=Q('aq-mobile-drawer');
  const mobClose=Q('aq-mob-close'),mobNewBtn=Q('aq-mob-newbtn');

  const csrf=document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')||'';
  const askUrl=@json(route('dashboard.analytics.ask'));
  const sessionUrlTpl=@json($sessionUrl);
  const deleteSessionUrlTpl=@json($deleteSessionUrl);
  const exportUrl=@json($exportUrl);
  const pdfUrl=@json($pdfUrl);

  let cd=null,typing=null,sbOpen=false,isLoading=false,currentSessionId=null;

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
        void searchWrap.offsetWidth;
        searchWrap.classList.add('search-flash');
      }
    },120);
  });

  Q('aq-rail-new')?.addEventListener('click',()=>{startNewChat();});
  Q('aq-rail-sql')?.addEventListener('click',()=>{if(adv)adv.open=!adv.open;});
  Q('aq-rail-export')?.addEventListener('click',()=>{if(expBtn && !expBtn.disabled)Q('aq-export-form')?.submit();});
  overlay?.addEventListener('click',()=>{sbOpen=false;applySB();});
  newBtn?.addEventListener('click',startNewChat);

  /* ── mobile drawer ── */
  function openMobileDrawer(){
    if(mobDrawer)mobDrawer.style.transform='translateX(0)';
    if(mobOverlay)mobOverlay.style.display='block';
  }
  function closeMobileDrawer(){
    if(mobDrawer)mobDrawer.style.transform='translateX(-100%)';
    if(mobOverlay)mobOverlay.style.display='none';
  }
  mobClose?.addEventListener('click',closeMobileDrawer);
  mobOverlay?.addEventListener('click',closeMobileDrawer);
  mobNewBtn?.addEventListener('click',()=>{closeMobileDrawer();startNewChat();});

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
  /* neutral focus — no blue border */
  searchEl?.addEventListener('focus',()=>{if(searchWrap)searchWrap.style.borderColor='rgba(255,255,255,.15)';});
  searchEl?.addEventListener('blur', ()=>{if(searchWrap)searchWrap.style.borderColor='rgba(255,255,255,.08)';});

  /* ── textarea resize ── */
  function resize(){qEl.style.height='auto';qEl.style.height=Math.min(qEl.scrollHeight,160)+'px';}
  qEl?.addEventListener('input',resize);
  qEl?.addEventListener('keydown',e=>{if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();if(!sendBtn.disabled&&!isLoading)doSend();}});

  /* ── chips ── */
  document.querySelectorAll('.q-chip').forEach(c=>{
    c.addEventListener('click',()=>{if(!qEl)return;qEl.value=c.dataset.p||'';resize();qEl.focus();});
  });

  /* ── helpers ── */
  const esc=v=>String(v??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[m]);
  const setEnabled=v=>{if(sendBtn)sendBtn.disabled=!v;isLoading=!v;};
  const setMsg=(h,err)=>{msgEl.innerHTML=h;msgEl.style.color=err?'#dc2626':'var(--text-faint)';};

  /* ── reset / new chat ── */
  function clearChat(){
    msgs.innerHTML='';welc.style.display='flex';
    if(sqlEl) sqlEl.value='';
    if(expSql) expSql.value='';
    if(expOrg) expOrg.value='';
    if(copyBtn) copyBtn.disabled=true;
    if(expBtn) expBtn.disabled=true;
    renderTable([],[]);
    setMsg('',false);if(status) status.textContent='';
    if(badge) badge.style.display='none';
  }
  function reset(){clearChat();if(qEl){qEl.value='';resize();qEl.focus();}document.querySelectorAll('.hbtn-sb').forEach(b=>b.classList.remove('active'));}
  function startNewChat(){
    currentSessionId=null;
    reset();
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('session')) {
      history.pushState(null, '', window.location.pathname);
    }
  }

  /* ── markdown parser ── */
  function parseMarkdown(md) {
    if (!md) return '';
    // Escape HTML for safety
    let text = md
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');
      
    const lines = text.split('\n');
    let inList = false;
    let htmlLines = [];

    for (let i = 0; i < lines.length; i++) {
      let line = lines[i].trim();
      
      // Check for list items
      const listMatch = line.match(/^(?:\*|-|•)\s+(.*)/);
      if (listMatch) {
        if (!inList) {
          htmlLines.push('<ul>');
          inList = true;
        }
        let content = parseInlineMarkdown(listMatch[1]);
        htmlLines.push(`<li>${content}</li>`);
      } else {
        if (inList) {
          htmlLines.push('</ul>');
          inList = false;
        }
        
        // Headings
        if (line.startsWith('### ')) {
          htmlLines.push(`<h3>${parseInlineMarkdown(line.substring(4))}</h3>`);
        } else if (line.startsWith('## ')) {
          htmlLines.push(`<h2>${parseInlineMarkdown(line.substring(3))}</h2>`);
        } else if (line.startsWith('# ')) {
          htmlLines.push(`<h1>${parseInlineMarkdown(line.substring(2))}</h1>`);
        } else if (line !== '') {
          // Paragraph
          htmlLines.push(`<p>${parseInlineMarkdown(line)}</p>`);
        }
      }
    }
    if (inList) {
      htmlLines.push('</ul>');
    }
    return htmlLines.join('');
  }

  function parseInlineMarkdown(text) {
    // Bold
    let html = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    // Italic
    html = html.replace(/\*(.*?)\*/g, '<em>$1</em>');
    // Inline code
    html = html.replace(/`(.*?)`/g, '<code>$1</code>');
    return html;
  }

  /* ── rich message bubble ── */
  const initials=@json($initials?:'U');

  function appendMsg(role,text,opts){
    welc.style.display='none';
    const row=document.createElement('div');
    row.className='mrow '+(role==='user'?'user-r':'');
    const av=document.createElement('div');
    av.className='mavatar '+(role==='user'?'u':'a');
    av.textContent=role==='user'?initials:'AI';
    const bub=document.createElement('div');
    bub.className='mbubble aq-ai-bubble';

    if(role==='user'){
      const textSpan=document.createElement('span');
      textSpan.textContent=text;
      bub.appendChild(textSpan);
      
      const actions=document.createElement('div');
      actions.className='user-msg-actions';
      
      const copyBtn=document.createElement('button');
      copyBtn.type='button';
      copyBtn.className='msg-action-btn';
      copyBtn.title='Copy message';
      copyBtn.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>';
      copyBtn.addEventListener('click',async()=>{
        try{
          await navigator.clipboard.writeText(text);
          const oldHtml = copyBtn.innerHTML;
          copyBtn.style.color='var(--dot-color)';
          copyBtn.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
          setTimeout(()=>{
            copyBtn.style.color='';
            copyBtn.innerHTML=oldHtml;
          },1500);
        }catch(err){
          console.error(err);
        }
      });
      
      const editBtn=document.createElement('button');
      editBtn.type='button';
      editBtn.className='msg-action-btn';
      editBtn.title='Edit message';
      editBtn.innerHTML='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>';
      editBtn.addEventListener('click',()=>{
        qEl.value=text;
        resize();
        qEl.focus();
      });
      
      actions.appendChild(copyBtn);
      actions.appendChild(editBtn);
      bub.appendChild(actions);
    } else {
      const rows=opts?.rows||[];
      const columns=opts?.columns||[];
      const aiText=opts?.ai_response||text||'';
      const sql=opts?.sql||'';
      const orgId=opts?.orgId||orgEl?.value||'';
      const question=opts?.question||'';
      const wantsChart=/\b(chart|graph|trend|compare|comparison|breakdown|pie|bar)\b|graph bna|chart bna/i.test(question);
      const visualizationType=opts?.visualizationType||(wantsChart?'chart':'table');
      const visualizationData=opts?.visualizationData||null;

      if(aiText){
        const div=document.createElement('div');
        div.className='ai-response-text';
        div.innerHTML=parseMarkdown(aiText);
        bub.appendChild(div);
      }

      if(visualizationType==='chart'&&visualizationData){
        bub.appendChild(buildChartElement(visualizationData));
        bub.appendChild(buildPdfBtn(visualizationData,orgId,question||aiText||''));
      } else if(rows.length>0){
        const displayCols=columns.includes('S.No')||columns.includes('serial_number')?columns:['S.No',...columns];
        const wrap=document.createElement('div');wrap.className='ai-table-wrap';
        const table=document.createElement('table');table.className='ai-data-table';
        const thd=document.createElement('thead');const hrow=document.createElement('tr');
        displayCols.forEach(c=>{const th=document.createElement('th');th.textContent=c;hrow.appendChild(th);});
        thd.appendChild(hrow);table.appendChild(thd);
        const tbd=document.createElement('tbody');
        rows.forEach((row,i)=>{const tr=document.createElement('tr');displayCols.forEach(c=>{const td=document.createElement('td');td.textContent=c==='S.No'?i+1:(row[c]??'');tr.appendChild(td);});tbd.appendChild(tr);});
        table.appendChild(tbd);wrap.appendChild(table);bub.appendChild(wrap);
        const meta=document.createElement('div');meta.className='ai-table-meta';
        meta.textContent=`Showing ${rows.length} row(s) and ${columns.length} data column(s). Scroll sideways if more columns are off-screen.`;
        bub.appendChild(meta);
        if(sql)bub.appendChild(buildExcelBtn(sql,orgId));
      } else if(!aiText) {
        bub.innerHTML=parseMarkdown('No results found.');
      }
    }

    row.appendChild(av);row.appendChild(bub);
    msgs.appendChild(row);scrl.scrollTop=scrl.scrollHeight;
  }

  function buildExcelBtn(sql,orgId){
    const btn=document.createElement('button');btn.type='button';btn.className='aq-export-btn';
    btn.textContent='Export Excel';
    btn.addEventListener('click',()=>submitExcel(sql,orgId));
    return btn;
  }
  function buildPdfBtn(chartData,orgId,question){
    const btn=document.createElement('button');btn.type='button';btn.className='aq-export-btn';
    btn.style.marginLeft='6px';btn.textContent='Download PDF';
    btn.addEventListener('click',()=>submitPdf(chartData,orgId,question));
    return btn;
  }
  function buildChartElement(chartData){
    const wrap=document.createElement('div');wrap.className='ai-chart-wrap';
    const canvas=document.createElement('canvas');wrap.appendChild(canvas);
    requestAnimationFrame(()=>renderChart(canvas,chartData));
    return wrap;
  }
  function renderChart(canvas,chartData){
    if(!window.Chart){const f=document.createElement('div');f.style.fontSize='.75rem';f.style.color='#6b7280';f.textContent='Chart library unavailable.';canvas.replaceWith(f);return;}
    new Chart(canvas,{type:chartData.kind||'bar',data:{labels:chartData.labels||[],datasets:[{label:chartData.value_column||'Value',data:chartData.values||[],borderColor:'#111827',backgroundColor:['#111827','#2563eb','#059669','#d97706','#7c3aed','#dc2626','#0891b2','#4b5563'],tension:.35}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:true},title:{display:true,text:chartData.title||'Analytics chart'}},scales:chartData.kind==='pie'?{}:{y:{beginAtZero:true}}}});
  }
  function submitExcel(sql,orgId){
    if(!sql||!orgId){setMsg('Excel export not available for this result.',true);return;}
    const form=document.createElement('form');form.method='POST';form.action=exportUrl;form.style.display='none';
    [['_token',csrf],['organization_id',orgId],['sql',sql]].forEach(([n,v])=>{const i=document.createElement('input');i.type='hidden';i.name=n;i.value=v;form.appendChild(i);});
    document.body.appendChild(form);form.submit();form.remove();
  }
  function submitPdf(chartData,orgId,question){
    if(!chartData||!orgId){setMsg('PDF download not available for this chart.',true);return;}
    const form=document.createElement('form');form.method='POST';form.action=pdfUrl;form.style.display='none';
    [['_token',csrf],['organization_id',orgId],['question',question||''],['chart',JSON.stringify(chartData)]].forEach(([n,v])=>{const i=document.createElement('input');i.type='hidden';i.name=n;i.value=v;form.appendChild(i);});
    document.body.appendChild(form);form.submit();form.remove();
  }

  function showTyping(){
    removeTyping();welc.style.display='none';
    const row=document.createElement('div');
    row.id='aq-typing-row';row.className='mrow';
    row.innerHTML='<div class="mavatar a">AI</div><div class="mbubble" style="padding:10px 15px;background:var(--bg-panel);border:1px solid var(--border);border-radius:14px 14px 14px 4px;"><span class="tdot"></span> <span class="tdot"></span> <span class="tdot"></span></div>';
    msgs.appendChild(row);typing=row;scrl.scrollTop=scrl.scrollHeight;
  }
  function removeTyping(){typing?.remove();typing=null;}

  /* ── SQL-panel table ── */
  function renderTable(cols,rows){
    if(!thead || !tbody) return;
    thead.innerHTML='';tbody.innerHTML='';
    if(empty) empty.style.display='none';
    if(rtable) rtable.style.display='none';
    if(badge) badge.style.display='none';
    if(!rows?.length){if(cols.length && empty){empty.textContent='Query returned no rows.';empty.style.display='block';}return;}
    if(rtable) rtable.style.display='block';
    if(badge) {
      badge.textContent=rows.length+' row'+(rows.length!==1?'s':'');
      badge.style.display='inline-block';
    }
    const tr=document.createElement('tr');
    cols.forEach(c=>{const th=document.createElement('th');th.textContent=c;tr.appendChild(th);});
    thead.appendChild(tr);
    rows.forEach(row=>{const r=document.createElement('tr');cols.forEach(c=>{const td=document.createElement('td');td.textContent=row[c]??'';r.appendChild(td);});tbody.appendChild(r);});
    if(adv && !adv.open)adv.open=true;
  }

  /* ── load session from history ── */
  function loadSession(sessionId){
    if(isLoading)return;
    clearChat();
    currentSessionId=sessionId;
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('session') !== String(sessionId)) {
      history.pushState({ sessionId: sessionId }, '', '?session=' + sessionId);
    }
    showTyping();
    setEnabled(false);
    const url=sessionUrlTpl.replace('__ID__',sessionId);
    fetch(url,{headers:{'Accept':'application/json','X-CSRF-TOKEN':csrf}})
      .then(r=>r.json())
      .then(data=>{
        removeTyping();
        if(orgEl&&data.organization_id)orgEl.value=data.organization_id;
        let lastSql='';
        (data.messages||[]).forEach(msg=>{
          if(msg.role==='user'){
            appendMsg('user',msg.text);
          } else {
            if(msg.sql)lastSql=msg.sql;
            if(msg.is_conversation){
              appendMsg('assistant',msg.text||msg.ai_response);
            } else if(msg.row_count>0 || (msg.rows && msg.rows.length>0)){
              appendMsg('assistant',null,{
                columns:msg.columns||[],
                rows:msg.rows||[],
                ai_response:msg.ai_response||msg.text,
                sql:msg.sql||'',
                orgId:data.organization_id||'',
                visualizationType:msg.visualization_type||'table',
                visualizationData:msg.visualization_data||null,
                question:msg.text||'',
              });
            } else {
              appendMsg('assistant',msg.ai_response||msg.text||'No results found.');
            }
          }
        });
        if(lastSql && sqlEl){
          sqlEl.value=lastSql;
          if(expSql) expSql.value=lastSql;
          if(expOrg) expOrg.value=data.organization_id||'';
          if(copyBtn) copyBtn.disabled=false;
          if(expBtn) expBtn.disabled=false;
        }
        setEnabled(true);
      })
      .catch(()=>{
        removeTyping();
        appendMsg('assistant','Failed to load session. Please try again.');
        setEnabled(true);
      });
  }

  /* ── history sidebar items ── */
  function prependSession(sessionId,title,createdAt,orgName){
    [histC,mobHistC].forEach(container=>{
      if(!container)return;
      container.querySelector('p')?.remove();
      container.querySelector(`.hbtn-sb[data-session-id="${sessionId}"]`)?.remove();
      const btn=buildHistBtn(sessionId,title,createdAt,orgName);
      container.insertAdjacentElement('afterbegin',btn);
    });
  }

  function buildHistBtn(sessionId,title,createdAt,orgName){
    const btn=document.createElement('button');
    btn.type='button';btn.className='hbtn-sb';
    btn.dataset.sessionId=sessionId;
    const shortTitle=title&&title.length>48?title.substring(0,48)+'…':title||'';
    btn.dataset.q=title||'';
    btn.title=title||'';
    btn.innerHTML=esc(shortTitle)+(orgName?'<span class="htime">'+esc(createdAt||'')+(orgName?' · '+esc(orgName):'')+'</span>':'<span class="htime">'+esc(createdAt||'')+'</span>');
    bindHistBtnClick(btn);
    return btn;
  }

  function bindHistBtnClick(b){
    b.addEventListener('click',()=>{
      const sid=b.dataset.sessionId;
      if(sid){
        currentSessionId=parseInt(sid,10);
        loadSession(currentSessionId);
        closeMobileDrawer();
        document.querySelectorAll('.hbtn-sb').forEach(x=>x.classList.remove('active'));
        document.querySelectorAll(`.hbtn-sb[data-session-id="${sid}"]`).forEach(x=>x.classList.add('active'));
        if(window.innerWidth<768){sbOpen=false;applySB();}
      } else {
        if(qEl){qEl.value=b.dataset.q||'';resize();qEl.focus();}
        document.querySelectorAll('.hbtn-sb').forEach(x=>x.classList.remove('active'));
        b.classList.add('active');
        if(window.innerWidth<768){sbOpen=false;applySB();}
      }
    });
  }
  document.querySelectorAll('.hbtn-sb').forEach(bindHistBtnClick);

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
    if(status) status.textContent='Please wait '+r+'s…';
    cd=setInterval(()=>{r--;if(r<=0){clearInterval(cd);cd=null;if(status) status.textContent='';setEnabled(true);return;}if(status) status.textContent='Please wait '+r+'s…';},1000);
  }

  /* ── send ── */
  sendBtn?.addEventListener('click',doSend);
  async function doSend(){
    const q=qEl.value?.trim()||'',org=orgEl?.value||'';
    if(!q){setMsg('Please enter a question.',true);return;}
    if(isLoading)return;
    setMsg('',false);setEnabled(false);if(status) status.textContent='';
    appendMsg('user',q);qEl.value='';resize();showTyping();
    try{
      const res=await fetch(askUrl,{
        method:'POST',
        headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':csrf},
        body:JSON.stringify({question:q,organization_id:org||null,session_id:currentSessionId||null})
      });
      let d;try{d=await res.json();}catch{d={message:res.status===403?'Unauthorized.':'Request failed.'};}
      removeTyping();
      if(!res.ok){
        setMsg(d.message||'Request failed.',true);
        const rs=+(d.retry_after_seconds||0);
        if(res.status===429&&rs>0)startCD(rs);
        else{if(status) status.textContent='';setEnabled(true);}
        return;
      }
      if(status) status.textContent='';setMsg('',false);

      if(d.session_id) {
        currentSessionId=d.session_id;
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('session') !== String(d.session_id)) {
          history.pushState({ sessionId: d.session_id }, '', '?session=' + d.session_id);
        }
      }
      if(d.is_new_session&&d.session_id){
        prependSession(d.session_id,d.session_title,d.session_created_at,d.organization_name);
      }

      if(d.is_conversation){
        if(sqlEl) sqlEl.value='';
        if(expOrg) expOrg.value='';
        if(expSql) expSql.value='';
        if(copyBtn) copyBtn.disabled=true;
        if(expBtn) expBtn.disabled=true;
        renderTable([],[]);
        appendMsg('assistant',d.message);
      } else {
        const cols=d.columns||[],rows=d.rows||[],raw=d.raw_sql||'';
        const aiResponse=d.ai_response||'';
        if(sqlEl) sqlEl.value=raw;
        if(expOrg) expOrg.value=org||'';
        if(expSql) expSql.value=raw;
        if(copyBtn) copyBtn.disabled=false;
        if(expBtn) expBtn.disabled=false;
        renderTable(cols,rows);
        appendMsg('assistant',null,{
          columns:cols,
          rows:rows,
          ai_response:aiResponse,
          sql:raw,
          orgId:org||d.organization_id||'',
          visualizationType:d.visualization_type||d.visualizationType||'table',
          visualizationData:d.visualization_data||d.visualizationData||null,
          question:q,
        });
      }
      setEnabled(true);
      qEl?.focus();
    }catch(err){
      console.error(err);removeTyping();if(status) status.textContent='';
      setMsg('Could not reach the server. Please try again.',true);setEnabled(true);
    }
  }

  /* ── kill layout wrapper gap ── */
  (function(){
    var el=document.getElementById('aq-shell');
    if(!el)return;
    var p=el.parentElement;
    while(p&&p.tagName!=='BODY'){p.style.padding='0';p.style.margin='0';p=p.parentElement;}
  })();

  /* ── popstate & onload session restore ── */
  function getUrlSessionId() {
    return new URLSearchParams(window.location.search).get('session');
  }

  function handleUrlSession() {
    const urlSessionId = getUrlSessionId();
    if(urlSessionId) {
      const sid = parseInt(urlSessionId, 10);
      if(!isNaN(sid) && sid !== currentSessionId) {
        currentSessionId = sid;
        loadSession(currentSessionId);
        document.querySelectorAll('.hbtn-sb').forEach(x=>x.classList.remove('active'));
        document.querySelectorAll(`.hbtn-sb[data-session-id="${currentSessionId}"]`).forEach(x=>x.classList.add('active'));
      }
    } else {
      if(currentSessionId !== null) {
        startNewChat();
      }
    }
  }

  window.addEventListener('popstate', handleUrlSession);
  
  // Trigger on initial page load
  setTimeout(handleUrlSession, 50);
})();
</script>
</x-app-layout>