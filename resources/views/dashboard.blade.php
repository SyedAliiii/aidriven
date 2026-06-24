<x-app-layout>
<style>
:root{
  --bg:#f7f8fb;
  --surface:#ffffff;
  --surface-2:#fbfbfd;
  --border:#e4e7ee;
  --border-soft:#edeff4;
  --text:#0f1320;
  --text-2:#5a6480;
  --text-3:#9aa2b8;
  --accent:#4f46e5;
  --accent-2:#7c3aed;
  --accent-light:#eef2ff;
  --accent-border:#c7d2fe;
  --green:#0d9568;
  --green-light:#ecfdf5;
  --green-border:#a7f3d4;
  --cyan:#0891b2;
  --cyan-light:#ecfeff;
  --cyan-border:#a5f3fc;
  --amber:#b45309;
  --amber-light:#fffbeb;
  --amber-border:#fde68a;
  --dot:#0d9568;
  --chart-line:#4f46e5;
  --chart-fill-a:rgba(79,70,229,.16);
  --chart-fill-b:rgba(79,70,229,.01);
  --chart-text:#9aa2b8;
  --chart-grid:#edeff4;
  --mono:'SFMono-Regular',Menlo,Consolas,monospace;
}
.dark #dq-root{
  --bg:#0a0d16;
  --surface:#10131e;
  --surface-2:#0d1019;
  --border:#1d2336;
  --border-soft:#171b29;
  --text:#e7eaf3;
  --text-2:#8893b3;
  --text-3:#6b7699;
  --accent:#818cf8;
  --accent-2:#a78bfa;
  --accent-light:#191c3a;
  --accent-border:#312e6e;
  --green:#34d399;
  --green-light:#06231a;
  --green-border:#0d4a35;
  --cyan:#22d3ee;
  --cyan-light:#062229;
  --cyan-border:#0c4a58;
  --amber:#fbbf24;
  --amber-light:#241a04;
  --amber-border:#4a3308;
  --dot:#34d399;
  --chart-line:#818cf8;
  --chart-fill-a:rgba(129,140,248,.18);
  --chart-fill-b:rgba(129,140,248,.01);
  --chart-text:#5d6690;
  --chart-grid:#1a2035;
}

*{box-sizing:border-box;margin:0;padding:0;}
#dq-root{
  background:var(--bg);
  color:var(--text);
  font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',sans-serif;
  min-height:calc(100vh - 4rem);
  padding:20px 0 40px;
  transition:background .2s,color .2s;
}
.dq-w{max-width:1180px;margin:0 auto;padding:0 16px;}
@media(min-width:600px){
  .dq-w{padding:0 28px;}
  #dq-root{padding:30px 0 56px;}
}

@keyframes dq-rise{from{opacity:0;transform:translateY(6px);}to{opacity:1;transform:translateY(0);}}
@keyframes dq-bline{from{transform:scaleX(0);}to{transform:scaleX(1);}}
@keyframes dq-blink{0%,100%{opacity:1;}50%{opacity:.3;}}
@keyframes dq-count-pop{0%{opacity:0;transform:translateY(3px);}100%{opacity:1;transform:translateY(0);}}

/* ── Header ── */
.dq-hdr{
  display:flex;align-items:flex-start;justify-content:space-between;
  flex-wrap:wrap;gap:12px;margin-bottom:18px;
  animation:dq-rise .35s ease both;
}
.dq-hdr-left{display:flex;align-items:center;gap:10px;flex-wrap:wrap;min-width:0;flex:1;}
.dq-hdr-left h1{font-size:18px;font-weight:700;color:var(--text);letter-spacing:-.03em;}
.dq-hdr-sep{width:1px;height:18px;background:var(--border);flex-shrink:0;}
.dq-hdr-left p{font-size:12px;color:var(--text-2);}
.dq-analytics-btn{
  display:inline-flex;align-items:center;gap:7px;
  padding:9px 16px;border-radius:9px;
  background:linear-gradient(135deg,var(--accent),var(--accent-2));
  color:#fff;font-size:13px;font-weight:600;
  text-decoration:none;white-space:nowrap;
  transition:transform .14s,box-shadow .14s;
  flex-shrink:0;letter-spacing:-.01em;
  box-shadow:0 4px 14px rgba(79,70,229,.28);
}
.dq-analytics-btn:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(79,70,229,.38);}
.dq-analytics-btn svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.2;}

@media(max-width:480px){
  .dq-hdr{flex-direction:column;gap:10px;}
  .dq-hdr-left h1{font-size:17px;}
  .dq-hdr-sep{display:none;}
  .dq-analytics-btn{width:100%;justify-content:center;padding:10px 14px;}
}

/* ── Org badge ── */
.dq-org{
  display:inline-flex;align-items:center;gap:7px;
  margin-bottom:18px;padding:5px 13px;border-radius:20px;
  background:var(--surface);border:1px solid var(--border);
  font-size:12px;font-weight:500;color:var(--text-2);
  animation:dq-rise .35s .03s ease both;
}
.dq-org-dot{width:6px;height:6px;border-radius:50%;background:var(--dot);animation:dq-blink 2.2s ease-in-out infinite;}

/* ── Stat strip ── */
.dq-stats{
  display:flex;background:var(--surface);border:1px solid var(--border);
  border-radius:14px;margin-bottom:20px;overflow:hidden;
  animation:dq-rise .35s .06s ease both;
}
.dq-stat{
  flex:1;min-width:0;padding:14px 14px 12px;
  position:relative;cursor:default;transition:background .18s;
}
.dq-stat:hover{background:var(--surface-2);}
.dq-stat+.dq-stat{border-left:1px solid var(--border-soft);}
.dq-stat::before{
  content:'';position:absolute;top:0;left:0;right:0;height:2px;
  background:var(--stat-c);transform-origin:left;
  animation:dq-bline .6s cubic-bezier(.4,0,.2,1) both;
  animation-delay:calc(.15s + var(--stat-i, 0) * .08s);
}
.dq-stat-top{display:flex;align-items:center;gap:6px;margin-bottom:8px;}
.dq-stat-icon{width:14px;height:14px;flex-shrink:0;color:var(--stat-c);transition:transform .25s cubic-bezier(.34,1.56,.64,1);}
.dq-stat:hover .dq-stat-icon{transform:scale(1.18) rotate(-4deg);}
.dq-stat-icon svg{width:100%;height:100%;stroke:currentColor;fill:none;stroke-width:2.1;}
.dq-stat-label{font-size:9.5px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:.09em;line-height:1.2;}
.dq-stat-value{font-family:var(--mono);font-size:24px;font-weight:600;color:var(--text);letter-spacing:-.02em;line-height:1;animation:dq-count-pop .3s ease both;animation-delay:calc(.55s + var(--stat-i, 0) * .08s);}

@media(max-width:860px){
  .dq-stats{flex-wrap:wrap;}
  .dq-stat{flex:1 1 50%;}
  .dq-stat:nth-child(2){border-left:1px solid var(--border-soft);}
  .dq-stat:nth-child(3){border-left:none;border-top:1px solid var(--border-soft);}
  .dq-stat:nth-child(4){border-left:1px solid var(--border-soft);border-top:1px solid var(--border-soft);}
}
@media(max-width:480px){
  .dq-stats{border-radius:12px;}
  .dq-stat{flex:1 1 50%;padding:12px 12px 10px;}
  .dq-stat:nth-child(odd){border-left:none;}
  .dq-stat:nth-child(even){border-left:1px solid var(--border-soft);}
  .dq-stat:nth-child(3),.dq-stat:nth-child(4){border-top:1px solid var(--border-soft);}
  .dq-stat-value{font-size:20px;}
  .dq-stat-label{font-size:9px;letter-spacing:.07em;}
  .dq-stat-icon{width:13px;height:13px;}
}

/* ── Charts ── */
.dq-charts{display:grid;grid-template-columns:minmax(0,2fr) minmax(0,1fr);gap:14px;margin-bottom:14px;}
@media(max-width:860px){.dq-charts{grid-template-columns:1fr;}}
.dq-card{
  background:var(--surface);border:1px solid var(--border);border-radius:14px;
  padding:18px;transition:background .2s,border-color .2s;
  animation:dq-rise .35s .1s ease both;
}
@media(max-width:480px){.dq-card{padding:14px;border-radius:12px;}}
.dq-card-hdr{display:flex;align-items:baseline;justify-content:space-between;margin-bottom:14px;}
.dq-card-title{font-size:13px;font-weight:600;color:var(--text);letter-spacing:-.01em;}
.dq-card-sub{font-size:11.5px;color:var(--text-3);font-family:var(--mono);}
.dq-chart-box{position:relative;height:220px;}
@media(max-width:480px){.dq-chart-box{height:180px;}}

/* ── Section label ── */
.dq-section-label{
  display:flex;align-items:center;gap:9px;
  font-size:11px;font-weight:700;color:var(--text-3);
  text-transform:uppercase;letter-spacing:.1em;
  margin-bottom:11px;margin-top:6px;
}
.dq-section-label::after{content:'';flex:1;height:1px;background:var(--border-soft);}

/* ══════════════════════════════════════════
   TABLE — Desktop
══════════════════════════════════════════ */
.dq-tcard{
  background:var(--surface);border:1px solid var(--border);border-radius:14px;
  overflow:hidden;transition:background .2s,border-color .2s;
  animation:dq-rise .35s .14s ease both;
}
@media(max-width:480px){.dq-tcard{border-radius:12px;}}
.dq-thead-row{
  display:flex;align-items:center;justify-content:space-between;
  padding:13px 16px;border-bottom:1px solid var(--border);
  flex-wrap:wrap;gap:8px;
}
.dq-thead-row h2{font-size:13px;font-weight:600;color:var(--text);letter-spacing:-.01em;}
.dq-pill{
  font-size:11px;padding:3px 10px;border-radius:20px;
  background:var(--bg);color:var(--text-3);
  border:1px solid var(--border);font-weight:500;font-family:var(--mono);
}

/* Desktop table */
.dq-tscroll{overflow-x:auto;-webkit-overflow-scrolling:touch;}
table.dq-t{width:100%;border-collapse:collapse;font-size:13px;}
table.dq-t thead th{
  padding:9px 14px;text-align:left;
  font-size:10px;font-weight:700;color:var(--text-3);
  text-transform:uppercase;letter-spacing:.08em;
  background:var(--surface-2);border-bottom:1px solid var(--border);
  white-space:nowrap;
}
table.dq-t tbody td{
  padding:11px 14px;color:var(--text-2);
  border-bottom:1px solid var(--border-soft);vertical-align:middle;
}
table.dq-t tbody tr:last-child td{border-bottom:none;}
table.dq-t tbody tr{transition:background .12s;}
table.dq-t tbody tr:hover td{background:var(--surface-2);}
.dq-q-text{color:var(--text);font-weight:500;}
.dq-badge{
  display:inline-block;padding:2px 8px;border-radius:5px;
  font-size:11.5px;font-weight:600;font-family:var(--mono);
  background:var(--accent-light);color:var(--accent);border:1px solid var(--accent-border);
}
.dq-t-time{font-size:11.5px;color:var(--text-3);font-family:var(--mono);}
.dq-empty{padding:40px;text-align:center;font-size:13px;color:var(--text-3);}

/* ══════════════════════════════════════════
   MOBILE CARD LIST
══════════════════════════════════════════ */
.dq-mob-list{display:none;flex-direction:column;}
.dq-mob-item{
  padding:18px 16px 16px;
  position:relative;
  background:var(--surface);
  transition:background .12s;
}
/* Separator — thicker, more visible */
.dq-mob-item+.dq-mob-item{
  border-top:2px solid var(--border);
}
/* left accent bar */
.dq-mob-item::before{
  content:'';
  position:absolute;
  left:0;top:18px;bottom:16px;
  width:3px;border-radius:0 2px 2px 0;
  background:linear-gradient(to bottom,var(--accent),var(--accent-2));
  opacity:.5;
}
.dq-mob-item:active{background:var(--surface-2);}

/* Serial number + question */
.dq-mob-top{
  display:flex;align-items:flex-start;gap:10px;
  margin-bottom:12px;padding-left:10px;
}
.dq-mob-num{
  font-size:10px;font-weight:700;color:var(--text-3);
  font-family:var(--mono);
  background:var(--bg);
  border:1px solid var(--border-soft);
  border-radius:5px;
  padding:2px 6px;
  flex-shrink:0;
  margin-top:2px;
}
.dq-mob-q{
  font-size:14px;font-weight:500;color:var(--text);
  line-height:1.5;
  display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;
  overflow:hidden;
}

/* Meta chips row */
.dq-mob-meta{
  display:flex;align-items:center;flex-wrap:wrap;gap:6px;
  padding-left:10px;
}
.dq-mob-tag{
  display:inline-flex;align-items:center;gap:4px;
  padding:4px 10px;border-radius:7px;
  font-size:11.5px;font-weight:500;
  background:var(--surface-2);
  border:1px solid var(--border-soft);
  color:var(--text-2);
  white-space:nowrap;
}
.dq-mob-tag svg{width:11px;height:11px;stroke:currentColor;fill:none;stroke-width:2;flex-shrink:0;}
.dq-mob-tag.rows{
  background:var(--accent-light);
  border-color:var(--accent-border);
  color:var(--accent);
  font-family:var(--mono);
  font-weight:600;
}
.dq-mob-tag.time{
  color:var(--text-3);
  margin-left:auto;
  font-size:11px;
}

/* No data */
.dq-mob-empty{
  padding:36px 16px;
  text-align:center;
  font-size:13px;
  color:var(--text-3);
}

@media(max-width:600px){
  .dq-tscroll{display:none;}
  .dq-mob-list{display:flex;}
}

@media(prefers-reduced-motion:reduce){
  .dq-hdr,.dq-org,.dq-stats,.dq-card,.dq-tcard,.dq-stat::before,.dq-stat-value{animation:none!important;}
}
</style>

@php
  $isSuperAdmin = isset($isSuperAdmin) ? $isSuperAdmin : false;
  $organization = isset($organization) ? $organization : null;
@endphp

<div id="dq-root">
<div class="dq-w">

  {{-- Header --}}
  <div class="dq-hdr">
    <div class="dq-hdr-left">
      <h1>{{ $isSuperAdmin ? 'Super Admin Dashboard' : 'Dashboard' }}</h1>
      <div class="dq-hdr-sep"></div>
      <p>{{ $isSuperAdmin ? 'Platform-wide usage, users, organizations, and query activity.' : 'Your organization analytics usage and recent query activity.' }}</p>
    </div>
    <a href="{{ route('dashboard.analytics') }}" class="dq-analytics-btn">
      <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
      Open Analytics
    </a>
  </div>

  {{-- Org badge --}}
  @if(!$isSuperAdmin && $organization)
    <div class="dq-org">
      <span class="dq-org-dot"></span>
      {{ $organization->name }}
    </div>
  @else
    <div style="margin-bottom:18px;"></div>
  @endif

  {{-- Stat strip --}}
  <div class="dq-stats">
    @if($isSuperAdmin)
      <div class="dq-stat" style="--stat-c:var(--accent);--stat-i:0;">
        <div class="dq-stat-top">
          <span class="dq-stat-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></span>
          <span class="dq-stat-label">Total Users</span>
        </div>
        <div class="dq-stat-value" data-count="{{ $kpis['total_users'] }}">0</div>
      </div>
      <div class="dq-stat" style="--stat-c:var(--green);--stat-i:1;">
        <div class="dq-stat-top">
          <span class="dq-stat-icon"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>
          <span class="dq-stat-label">Organizations</span>
        </div>
        <div class="dq-stat-value" data-count="{{ $kpis['total_organizations'] }}">0</div>
      </div>
      <div class="dq-stat" style="--stat-c:var(--accent-2);--stat-i:2;">
        <div class="dq-stat-top">
          <span class="dq-stat-icon"><svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></span>
          <span class="dq-stat-label">Active Orgs</span>
        </div>
        <div class="dq-stat-value" data-count="{{ $kpis['active_organizations'] }}">0</div>
      </div>
      <div class="dq-stat" style="--stat-c:var(--amber);--stat-i:3;">
        <div class="dq-stat-top">
          <span class="dq-stat-icon"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg></span>
          <span class="dq-stat-label">Total Queries</span>
        </div>
        <div class="dq-stat-value" data-count="{{ $kpis['total_queries'] }}">0</div>
      </div>
    @else
      <div class="dq-stat" style="--stat-c:var(--accent);--stat-i:0;">
        <div class="dq-stat-top">
          <span class="dq-stat-icon"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg></span>
          <span class="dq-stat-label">My Queries</span>
        </div>
        <div class="dq-stat-value" data-count="{{ $kpis['my_queries'] }}">0</div>
      </div>
      <div class="dq-stat" style="--stat-c:var(--green);--stat-i:1;">
        <div class="dq-stat-top">
          <span class="dq-stat-icon"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>
          <span class="dq-stat-label">Org Queries</span>
        </div>
        <div class="dq-stat-value" data-count="{{ $kpis['org_queries'] }}">0</div>
      </div>
      <div class="dq-stat" style="--stat-c:var(--accent-2);--stat-i:2;">
        <div class="dq-stat-top">
          <span class="dq-stat-icon"><svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></span>
          <span class="dq-stat-label">Avg Result Rows</span>
        </div>
        <div class="dq-stat-value" data-count="{{ $kpis['avg_rows'] }}">0</div>
      </div>
      <div class="dq-stat" style="--stat-c:var(--amber);--stat-i:3;">
        <div class="dq-stat-top">
          <span class="dq-stat-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></span>
          <span class="dq-stat-label">Org Users</span>
        </div>
        <div class="dq-stat-value" data-count="{{ $kpis['org_users'] }}">0</div>
      </div>
    @endif
  </div>

  {{-- Charts --}}
  <div class="dq-section-label">Analytics</div>
  <div class="dq-charts">
    <div class="dq-card">
      <div class="dq-card-hdr">
        <span class="dq-card-title">Query trend</span>
        <span class="dq-card-sub">14d</span>
      </div>
      <div class="dq-chart-box"><canvas id="trendChart"></canvas></div>
    </div>
    <div class="dq-card">
      <div class="dq-card-hdr">
        <span class="dq-card-title">{{ $isSuperAdmin ? 'By organization' : 'By user' }}</span>
        <span class="dq-card-sub">share</span>
      </div>
      <div class="dq-chart-box"><canvas id="breakdownChart"></canvas></div>
    </div>
  </div>

  {{-- Recent Activity --}}
  <div class="dq-section-label">Recent activity</div>
  <div class="dq-tcard">
    <div class="dq-thead-row">
      <h2>Query log</h2>
      <span class="dq-pill">{{ count($recentQueries) }} entries</span>
    </div>

    {{-- Desktop Table --}}
    <div class="dq-tscroll">
      <table class="dq-t">
        <thead>
          <tr>
            <th style="width:40%">Question</th>
            <th>User</th>
            <th>Organization</th>
            <th>Rows</th>
            <th>When</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentQueries as $item)
            <tr>
              <td><span class="dq-q-text">{{ \Illuminate\Support\Str::limit($item->question, 80) }}</span></td>
              <td>{{ $item->user?->name ?? '—' }}</td>
              <td>{{ $item->organization?->name ?? '—' }}</td>
              <td><span class="dq-badge">{{ $item->row_count }}</span></td>
              <td><span class="dq-t-time">{{ optional($item->created_at)->diffForHumans() }}</span></td>
            </tr>
          @empty
            <tr><td colspan="5" class="dq-empty">No activity yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Mobile Card List --}}
    <div class="dq-mob-list">
      @forelse($recentQueries as $item)
        <div class="dq-mob-item">
          <div class="dq-mob-top">
            <span class="dq-mob-num">#{{ $loop->iteration }}</span>
            <div class="dq-mob-q">{{ \Illuminate\Support\Str::limit($item->question, 120) }}</div>
          </div>
          <div class="dq-mob-meta">
            {{-- User --}}
            <span class="dq-mob-tag">
              <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              {{ $item->user?->name ?? '—' }}
            </span>
            {{-- Org --}}
            @if($item->organization)
            <span class="dq-mob-tag">
              <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
              {{ \Illuminate\Support\Str::limit($item->organization->name, 14) }}
            </span>
            @endif
            {{-- Rows --}}
            <span class="dq-mob-tag rows">{{ $item->row_count }} rows</span>
            {{-- Time --}}
            <span class="dq-mob-tag time">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              {{ optional($item->created_at)->diffForHumans() }}
            </span>
          </div>
        </div>
      @empty
        <div class="dq-mob-empty">No activity yet.</div>
      @endforelse
    </div>

  </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function(){
  const root = document.getElementById('dq-root');
  const trend = @json($trendChart);
  const breakdown = @json($breakdownChart);
  const slices = ['#4f46e5','#7c3aed','#0d9568','#b45309','#0891b2','#db2777','#818cf8','#9aa2b8'];
  let tC, bC;

  function gc(){
    const s = getComputedStyle(root);
    return {
      line:    s.getPropertyValue('--chart-line').trim(),
      fillA:   s.getPropertyValue('--chart-fill-a').trim(),
      fillB:   s.getPropertyValue('--chart-fill-b').trim(),
      text:    s.getPropertyValue('--chart-text').trim(),
      grid:    s.getPropertyValue('--chart-grid').trim(),
      surface: s.getPropertyValue('--surface').trim(),
    };
  }

  function build(){
    const c = gc();
    if(tC) tC.destroy();
    if(bC) bC.destroy();

    const tEl = document.getElementById('trendChart');
    if(tEl){
      const ctx = tEl.getContext('2d');
      const grad = ctx.createLinearGradient(0,0,0,220);
      grad.addColorStop(0, c.fillA);
      grad.addColorStop(1, c.fillB);
      tC = new Chart(tEl,{
        type:'line',
        data:{
          labels: trend.labels||[],
          datasets:[{
            label:'Queries',
            data: trend.values||[],
            borderColor:c.line,backgroundColor:grad,
            borderWidth:2.25,fill:true,tension:.35,
            pointRadius:0,pointHoverRadius:5,
            pointBackgroundColor:c.line,
            pointBorderColor:c.surface,pointBorderWidth:2,pointHitRadius:14,
          }]
        },
        options:{
          maintainAspectRatio:false,
          interaction:{mode:'index',intersect:false},
          animation:{duration:700,easing:'easeOutCubic'},
          plugins:{
            legend:{display:false},
            tooltip:{
              backgroundColor:c.surface,titleColor:c.text,bodyColor:c.text,
              borderColor:c.grid,borderWidth:1,padding:10,displayColors:false,
              callbacks:{label:ctx=>' '+ctx.parsed.y+' queries'}
            }
          },
          scales:{
            x:{ticks:{color:c.text,font:{size:10.5}},grid:{display:false}},
            y:{ticks:{color:c.text,font:{size:10.5}},grid:{color:c.grid},beginAtZero:true}
          }
        }
      });
    }

    const bEl = document.getElementById('breakdownChart');
    if(bEl) bC = new Chart(bEl,{
      type:'doughnut',
      data:{
        labels:breakdown.labels||[],
        datasets:[{
          data:breakdown.values||[],
          backgroundColor:slices,borderWidth:2,
          borderColor:c.surface,hoverOffset:6
        }]
      },
      options:{
        maintainAspectRatio:false,cutout:'68%',
        animation:{duration:700,easing:'easeOutCubic'},
        plugins:{legend:{labels:{color:c.text,font:{size:11},boxWidth:9,padding:9},position:'bottom'}}
      }
    });
  }

  build();

  /* Count-up animation */
  function animateCounts(){
    document.querySelectorAll('.dq-stat-value[data-count]').forEach(el=>{
      const target = parseFloat(el.dataset.count)||0;
      const isInt  = Number.isInteger(target);
      const dur=700, start=performance.now();
      function step(now){
        const p=Math.min(1,(now-start)/dur);
        const eased=1-Math.pow(1-p,3);
        const val=target*eased;
        el.textContent=isInt?Math.round(val).toLocaleString():val.toFixed(1);
        if(p<1)requestAnimationFrame(step);
        else el.textContent=isInt?target.toLocaleString():target.toFixed(1);
      }
      requestAnimationFrame(step);
    });
  }
  animateCounts();

  /* Dark mode sync */
  new MutationObserver(()=>{
    const dark=document.documentElement.classList.contains('dark');
    root.setAttribute('data-theme',dark?'dark':'');
    build();
  }).observe(document.documentElement,{attributes:true,attributeFilter:['class']});

  if(document.documentElement.classList.contains('dark'))
    root.setAttribute('data-theme','dark');
})();
</script>
</x-app-layout>