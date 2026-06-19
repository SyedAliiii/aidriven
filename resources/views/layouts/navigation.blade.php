{{--
  NAVBAR — clean version
  • Simple gray border bottom
  • Centered nav menu
  • Dark mode toggle button (same style/behavior as the sidebar's theme toggle)
  • Layout main padding zeroed for analytics page
--}}

@once
<style>
  :root{ --nav-height: 3.5rem; }
  main{ padding: 0 !important; margin: 0 !important; }

  /* theme toggle button — same visual language as the sidebar rail button */
  #aq-nav-theme{
    width:34px;height:34px;border-radius:9px;
    display:flex;align-items:center;justify-content:center;
    background:transparent;border:none;cursor:pointer;
    transition:background .15s,transform .15s;
    flex-shrink:0;
  }
  #aq-nav-theme:hover{background:#f3f4f6;}
  .dark #aq-nav-theme:hover{background:rgba(255,255,255,.07);}
  #aq-nav-theme svg{
    width:18px;height:18px;stroke:#6b7280;fill:none;stroke-width:1.8;
    transition:stroke .15s;
  }
  .dark #aq-nav-theme svg{stroke:#94a3b8;}
  #aq-nav-theme:hover svg{stroke:#4f46e5;}
  .dark #aq-nav-theme:hover svg{stroke:#a5b4fc;}

  /* user avatar trigger — same visual language as the sidebar's avatar */
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
</style>
@endonce

@php
  $navUser = auth()->user();
  $navUserName = $navUser?->name ?? 'User';
  $navIsSuperAdmin = $navUser && method_exists($navUser,'hasRole') ? $navUser->hasRole('super-admin') : false;
  $navUserOrg = $navUser && !$navIsSuperAdmin ? $navUser->organization : null;
  $navInitials = collect(explode(' ', $navUserName))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->join('');
@endphp

<nav id="aq-navbar" x-data="{ open: false }"
  class="sticky top-0 z-30 bg-white dark:bg-gray-950 border-b border-gray-200 dark:border-gray-800 backdrop-blur-md">

    <div class=" mx-auto px-4 sm:px-6 lg:px-4">
        <div class="relative flex items-center h-14">

            {{-- Logo --}}
            <a href="{{ route('dashboard') }}" class="flex items-center shrink-0 mr-3">
                <x-application-logo class="block h-8 w-auto fill-current text-gray-800 dark:text-gray-100" />
            </a>

            {{-- Desktop Nav Links — truly centered --}}
            <div class="hidden sm:flex items-center gap-1 absolute left-1/2 -translate-x-1/2">
                <a href="{{ route('dashboard') }}"
                    class="px-3 py-1.5 rounded-md text-sm font-medium transition
                        {{ request()->routeIs('dashboard') && !request()->routeIs('dashboard.analytics.*')
                            ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
                            : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                    Dashboard
                </a>
                <a href="{{ route('dashboard.analytics') }}"
                    class="px-3 py-1.5 rounded-md text-sm font-medium transition
                        {{ request()->routeIs('dashboard.analytics.*')
                            ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
                            : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                    Analytics
                </a>
                @if(Auth::user()?->hasRole('super-admin'))
                    <a href="{{ route('admin.organizations.index') }}"
                        class="px-3 py-1.5 rounded-md text-sm font-medium transition
                            {{ request()->routeIs('admin.organizations.*')
                                ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
                                : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                        Organizations
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="px-3 py-1.5 rounded-md text-sm font-medium transition
                            {{ request()->routeIs('admin.users.*')
                                ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
                                : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                        Users
                    </a>
                    <a href="{{ route('admin.ai-settings.edit') }}"
                        class="px-3 py-1.5 rounded-md text-sm font-medium transition
                            {{ request()->routeIs('admin.ai-settings.*')
                                ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
                                : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                        AI Settings
                    </a>
                    <a href="{{ route('admin.ai-guide.edit') }}"
                        class="px-3 py-1.5 rounded-md text-sm font-medium transition
                            {{ request()->routeIs('admin.ai-guide.*')
                                ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
                                : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                        AI Guide
                    </a>
                @endif
            </div>

            {{-- Dark mode toggle + user avatar — pinned to the right --}}
            <div class="hidden sm:flex items-center gap-2 ml-auto">
                <button type="button" id="aq-nav-theme" title="Toggle theme" aria-label="Toggle theme">
                    <svg id="aq-nav-theme-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                    <svg id="aq-nav-theme-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="display:none;"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                </button>

                {{-- User avatar + dropdown (ported from sidebar) --}}
                <div class="relative" id="aq-nav-user-wrap">
                    <button type="button" id="aq-nav-user-btn" title="{{ $navUserName }}">{{ $navInitials ?: 'U' }}</button>

                    <div id="aq-nav-user-menu" style="
                        display:none;
                        position:absolute;
                        top:calc(100% + 8px);
                        right:0;
                        min-width:190px;
                        background:#1e293b;
                        border:1px solid rgba(255,255,255,.1);
                        border-radius:12px;
                        overflow:hidden;
                        box-shadow:0 8px 32px rgba(0,0,0,.35);
                        z-index:9999;
                    ">
                        <div style="padding:12px 14px 10px;border-bottom:1px solid rgba(255,255,255,.07);">
                            <div style="display:flex;align-items:center;gap:9px;">
                                <div style="
                                    width:30px;height:30px;border-radius:50%;flex-shrink:0;
                                    background:linear-gradient(135deg,#4f46e5,#7c3aed);
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:11px;font-weight:700;color:#fff;
                                    border:2px solid rgba(165,180,252,.3);
                                ">{{ $navInitials ?: 'U' }}</div>
                                <div style="min-width:0;">
                                    <div style="font-size:12.5px;font-weight:600;color:#e2e8f0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:110px;">{{ $navUserName }}</div>
                                    @if($navUserOrg)
                                        <div style="font-size:10.5px;color:rgba(255,255,255,.38);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:110px;">{{ $navUserOrg->name }}</div>
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
            </div>

            {{-- Mobile: theme toggle + Hamburger --}}
            <div class="flex items-center sm:hidden ml-auto gap-1">
                <button type="button" id="aq-nav-theme-mobile" title="Toggle theme" aria-label="Toggle theme"
                    class="h-8 w-8 inline-flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <svg id="aq-nav-theme-sun-m" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                    <svg id="aq-nav-theme-moon-m" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="display:none;"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                </button>

                <button @click="open = !open"
                    class="h-8 w-8 inline-flex items-center justify-center rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Mobile Menu --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-gray-100 dark:border-gray-800">
        <div class="px-3 py-3 space-y-1">
            <a href="{{ route('dashboard') }}"
                class="block px-3 py-2 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('dashboard') && !request()->routeIs('dashboard.analytics.*')
                        ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
                        : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                Dashboard
            </a>
            <a href="{{ route('dashboard.analytics') }}"
                class="block px-3 py-2 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('dashboard.analytics.*')
                        ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
                        : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                Analytics
            </a>
            @if(Auth::user()?->hasRole('super-admin'))
                <a href="{{ route('admin.organizations.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm font-medium transition
                        {{ request()->routeIs('admin.organizations.*')
                            ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
                            : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                    Organizations
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm font-medium transition
                        {{ request()->routeIs('admin.users.*')
                            ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
                            : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                    Users
                </a>
                <a href="{{ route('admin.ai-settings.edit') }}"
                    class="block px-3 py-2 rounded-lg text-sm font-medium transition
                        {{ request()->routeIs('admin.ai-settings.*')
                            ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
                            : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                    AI Settings
                </a>
                <a href="{{ route('admin.ai-guide.edit') }}"
                    class="block px-3 py-2 rounded-lg text-sm font-medium transition
                        {{ request()->routeIs('admin.ai-guide.*')
                            ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-semibold'
                            : 'text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' }}">
                    AI Guide
                </a>
            @endif
        </div>
        {{-- Mobile logout --}}
        <div class="px-3 py-3 border-t border-gray-100 dark:border-gray-800 space-y-1">
            <a href="{{ route('profile.edit') }}"
                class="block px-3 py-2 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" onclick="event.preventDefault();this.closest('form').submit();"
                    class="w-full text-left px-3 py-2 rounded-lg text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</nav>

<script>
(function(){
  // Theme is controlled via the `dark` class on <html>, consistent with the sidebar's toggle.
  function applyIcons(){
    const isDark = document.documentElement.classList.contains('dark');
    const sun = document.getElementById('aq-nav-theme-sun');
    const moon = document.getElementById('aq-nav-theme-moon');
    const sunM = document.getElementById('aq-nav-theme-sun-m');
    const moonM = document.getElementById('aq-nav-theme-moon-m');
    if (sun && moon) { sun.style.display = isDark ? 'none' : 'block'; moon.style.display = isDark ? 'block' : 'none'; }
    if (sunM && moonM) { sunM.style.display = isDark ? 'none' : 'block'; moonM.style.display = isDark ? 'block' : 'none'; }
  }

  function toggleTheme(){
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    applyIcons();
  }

  document.getElementById('aq-nav-theme')?.addEventListener('click', toggleTheme);
  document.getElementById('aq-nav-theme-mobile')?.addEventListener('click', toggleTheme);

  applyIcons();
  // keep in sync if theme is changed elsewhere (e.g. the sidebar rail button on another page)
  new MutationObserver(applyIcons).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

  // user avatar dropdown
  const navUserBtn = document.getElementById('aq-nav-user-btn');
  const navUserMenu = document.getElementById('aq-nav-user-menu');
  const navUserWrap = document.getElementById('aq-nav-user-wrap');
  navUserBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    navUserMenu.style.display = navUserMenu.style.display === 'block' ? 'none' : 'block';
  });
  document.addEventListener('click', (e) => {
    if (navUserMenu && !navUserWrap?.contains(e.target)) {
      navUserMenu.style.display = 'none';
    }
  });
})();
</script>