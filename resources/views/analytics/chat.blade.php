<x-app-layout>
    @php
        $user = auth()->user();
        $isSuperAdmin = $user && method_exists($user, 'hasRole') ? $user->hasRole('super-admin') : false;
        $orgs = \App\Models\Organization::query()->orderBy('name')->get();
        $organizationMissing = $user && !$isSuperAdmin && empty($user->organization_id);
        $userOrganization = $user && !$isSuperAdmin ? $user->organization : null;
        $sessionUrl = route('dashboard.analytics.session', ['session' => '__ID__']);
        $deleteSessionUrl = route('dashboard.analytics.session.destroy', ['session' => '__ID__']);
        $exportUrl = route('dashboard.analytics.export');
    @endphp

    <style>
        @keyframes pulse-dots {
            0%, 100% { opacity: 0.3; transform: scale(0.8); }
            50%       { opacity: 1;   transform: scale(1.2); }
        }
        .typing-dot { animation: pulse-dots 1.4s infinite both; }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }

        /* Scrollbar for chat area */
        #chat-scroll::-webkit-scrollbar { width: 4px; }
        #chat-scroll::-webkit-scrollbar-track { background: transparent; }
        #chat-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 2px; }
        .dark #chat-scroll::-webkit-scrollbar-thumb { background: #374151; }

        /* Data table inside bubble */
        .ai-data-table { border-collapse: collapse; width: max-content; min-width: 100%; font-size: 0.72rem; }
        .ai-data-table th { background: rgba(0,0,0,0.08); padding: 6px 10px; text-align:left; white-space:nowrap; font-weight:600; }
        .ai-data-table td { padding: 5px 10px; border-top: 1px solid rgba(0,0,0,0.06); white-space:nowrap; }
        .dark .ai-data-table th { background: rgba(255,255,255,0.08); }
        .dark .ai-data-table td { border-top-color: rgba(255,255,255,0.06); }
        .ai-table-wrap { overflow-x:auto; max-width:100%; border-radius: 6px; margin-top: 6px; border: 1px solid rgba(0,0,0,0.06); }
        .dark .ai-table-wrap { border-color: rgba(255,255,255,0.08); }
        .ai-table-meta { margin-top: 8px; font-size: 0.7rem; color: #6b7280; }
    </style>

    <div class="h-[calc(100vh-4rem)] bg-gray-50 dark:bg-gray-950">
        <div class="h-full mx-auto flex">

            {{-- ============================================================ --}}
            {{-- Sidebar --}}
            {{-- ============================================================ --}}
            <aside class="hidden md:flex md:flex-col w-72 border-r border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-950/80 backdrop-blur">
                <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">History</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Your recent chats</p>
                    </div>
                    <button type="button" id="new-chat-btn"
                            class="inline-flex items-center px-2.5 py-1.5 rounded-md bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-xs hover:opacity-90 transition">
                        + New chat
                    </button>
                </div>

                <div id="desktop-history-container" class="flex-1 overflow-y-auto p-2 space-y-0.5 text-sm">
                    @forelse($sessions as $session)
                        <div class="history-session-item group flex items-start gap-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-900"
                             data-session-id="{{ $session->id }}">
                            <button type="button"
                                    class="history-session-btn min-w-0 flex-1 text-left px-3 py-2.5 text-gray-800 dark:text-gray-100"
                                    data-session-id="{{ $session->id }}">
                                <div class="truncate font-medium text-xs">{{ \Illuminate\Support\Str::limit($session->title, 55) }}</div>
                                <div class="mt-0.5 text-[10px] text-gray-500 dark:text-gray-400 flex items-center justify-between">
                                    <span>{{ optional($session->created_at)->format('d M, H:i') }}</span>
                                    @if($session->organization)
                                        <span>{{ \Illuminate\Support\Str::limit($session->organization->name, 14) }}</span>
                                    @endif
                                </div>
                            </button>
                            <button type="button"
                                    class="delete-session-btn mt-2 mr-1 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-md text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-950/40"
                                    data-session-id="{{ $session->id }}"
                                    title="Delete chat">
                                &times;
                            </button>
                        </div>
                    @empty
                        <p id="no-history-msg" class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400">
                            No history yet. Ask your first question.
                        </p>
                    @endforelse
                </div>
            </aside>

            {{-- ============================================================ --}}
            {{-- Main chat area --}}
            {{-- ============================================================ --}}
            <main class="flex-1 flex flex-col min-w-0">
                <header class="px-4 sm:px-6 py-3 border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-950/80 backdrop-blur shrink-0">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h1 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100">
                                AI Multi-DB Analytics
                            </h1>
                            <p class="mt-0.5 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                Ask in English, Urdu, Roman Urdu, or Hindi. Results appear directly in chat.
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" id="mobile-history-btn"
                                    class="md:hidden inline-flex items-center px-3 py-1.5 rounded-md bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-xs hover:opacity-90 transition">
                                History
                            </button>
                            @if(!$isSuperAdmin && $userOrganization)
                                <div class="hidden sm:inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-900 px-3 py-1 text-xs text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>
                                    {{ $userOrganization->name }}
                                </div>
                            @endif
                        </div>
                    </div>
                </header>

                {{-- Messages area --}}
                <div id="chat-scroll" class="flex-1 overflow-y-auto px-4 sm:px-6 py-4 bg-gradient-to-b from-gray-50/80 to-white dark:from-gray-950 dark:to-gray-950">
                    <div id="system-message" class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Start by asking a question like:
                        <span class="italic">"aaj ki total executed sale kitni thi?"</span>
                    </div>
                    <div id="chat-messages" class="space-y-3 text-sm"></div>
                </div>

                {{-- Advanced / export panel --}}
                <div class="border-t border-gray-200 dark:border-gray-800 bg-white/90 dark:bg-gray-950/95 shrink-0">
                    <div class="px-4 sm:px-6 py-2">
                        <details class="group" id="advanced-panel">
                            <summary class="flex items-center justify-between cursor-pointer text-xs font-medium text-gray-700 dark:text-gray-200 py-1">
                                <span>Advanced (SQL + Export)</span>
                                <span class="ml-2 text-[10px] text-gray-400 group-open:hidden">show</span>
                                <span class="ml-2 text-[10px] text-gray-400 hidden group-open:inline">hide</span>
                            </summary>
                            <div class="mt-2 space-y-2 pb-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Generated SQL</label>
                                    <textarea id="sql-output"
                                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-mono text-xs"
                                              rows="3" readonly></textarea>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <button type="button" id="copy-sql-btn" disabled
                                            class="inline-flex items-center justify-center px-3 py-1.5 rounded-md bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-100 text-xs disabled:opacity-50">
                                        Copy SQL
                                    </button>
                                    <form id="export-form" method="POST" action="{{ route('dashboard.analytics.export') }}">
                                        @csrf
                                        <input type="hidden" name="organization_id" id="export_org_id" value="">
                                        <input type="hidden" name="sql" id="export_sql" value="">
                                        <button type="submit" id="export-btn" disabled
                                                class="inline-flex items-center justify-center px-3 py-1.5 rounded-md bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-xs disabled:opacity-50 hover:opacity-90 transition">
                                            Export to Excel
                                        </button>
                                    </form>
                                    <div id="ask-status" class="text-xs text-gray-500 dark:text-gray-400 ml-auto min-h-[1rem]"></div>
                                </div>
                            </div>
                        </details>
                    </div>

                    {{-- Input bar --}}
                    <div class="border-t border-gray-200 dark:border-gray-800 px-4 sm:px-6 py-3">
                        <form id="ask-form" class="space-y-2">
                            <div class="flex items-end gap-2">
                                @if($isSuperAdmin)
                                    <select id="organization_id" name="organization_id"
                                            class="hidden sm:inline-flex rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-xs self-end mb-0.5">
                                        @foreach($orgs as $org)
                                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="hidden" id="organization_id" value="{{ $user?->organization_id }}">
                                @endif

                                <div class="flex-1 relative">
                                    <textarea id="question" rows="1"
                                              class="block w-full resize-none rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm py-2.5 pl-4 pr-12 focus:ring-2 focus:ring-gray-900 dark:focus:ring-white transition"
                                              placeholder="Ask anything about your sales, TSOs, or KPIs..."></textarea>
                                    <button type="submit" id="ask-btn"
                                            @if(!$isSuperAdmin && $organizationMissing) disabled @endif
                                            class="absolute right-2 bottom-2 inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-xs disabled:opacity-50 hover:opacity-80 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                            <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            @if(!$isSuperAdmin && $organizationMissing)
                                <div class="rounded-md bg-yellow-50 border border-yellow-200 px-3 py-2 text-xs text-yellow-900 dark:bg-yellow-900/30 dark:border-yellow-900/60 dark:text-yellow-200" role="alert">
                                    Your account isn't assigned to any organization. Ask a Super Admin to set your <code class="font-mono">organization_id</code>.
                                </div>
                            @endif

                            <div id="message-area" class="text-xs text-gray-600 dark:text-gray-400 min-h-[1rem]"></div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- Mobile history drawer --}}
    <div id="mobile-history-overlay"
         class="hidden md:hidden fixed inset-0 bg-black/40 backdrop-blur-[1px] z-40"></div>

    <div id="mobile-history-drawer"
         class="md:hidden fixed inset-y-0 left-0 w-80 max-w-[85vw] bg-white dark:bg-gray-950 border-r border-gray-200 dark:border-gray-800 z-50 transform -translate-x-full transition-transform duration-200 ease-out flex flex-col">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between shrink-0">
            <div>
                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">History</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Your recent chats</div>
            </div>
            <button type="button" id="mobile-history-close"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100 dark:hover:bg-gray-900 text-gray-700 dark:text-gray-200">✕</button>
        </div>
        <div class="p-3 shrink-0">
            <button type="button" id="mobile-new-chat-btn"
                    class="w-full inline-flex items-center justify-center px-3 py-2 rounded-md bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm hover:opacity-90 transition">
                + New chat
            </button>
        </div>
        <div id="mobile-history-container" class="flex-1 overflow-y-auto p-2 space-y-0.5 text-sm">
            @forelse($sessions as $session)
                <div class="history-session-item group flex items-start gap-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-900"
                     data-session-id="{{ $session->id }}">
                    <button type="button"
                            class="history-session-btn min-w-0 flex-1 text-left px-3 py-2.5 text-gray-800 dark:text-gray-100"
                            data-session-id="{{ $session->id }}">
                        <div class="truncate font-medium text-xs">{{ \Illuminate\Support\Str::limit($session->title, 55) }}</div>
                        <div class="mt-0.5 text-[10px] text-gray-500 dark:text-gray-400 flex items-center justify-between">
                            <span>{{ optional($session->created_at)->format('d M, H:i') }}</span>
                            @if($session->organization)
                                <span>{{ \Illuminate\Support\Str::limit($session->organization->name, 14) }}</span>
                            @endif
                        </div>
                    </button>
                    <button type="button"
                            class="delete-session-btn mt-2 mr-1 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-md text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-950/40"
                            data-session-id="{{ $session->id }}"
                            title="Delete chat">
                        &times;
                    </button>
                </div>
            @empty
                <p id="no-mobile-history-msg" class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400">
                    No history yet. Ask your first question.
                </p>
            @endforelse
        </div>
    </div>

    <script>
    (function () {
        const askForm       = document.getElementById('ask-form');
        const questionEl    = document.getElementById('question');
        const organizationEl= document.getElementById('organization_id');
        const askBtn        = document.getElementById('ask-btn');
        const askStatus     = document.getElementById('ask-status');
        const messageArea   = document.getElementById('message-area');
        const chatMessages  = document.getElementById('chat-messages');
        const chatScroll    = document.getElementById('chat-scroll');
        const systemMessage = document.getElementById('system-message');
        const newChatBtn    = document.getElementById('new-chat-btn');
        const sqlOutput     = document.getElementById('sql-output');
        const copySqlBtn    = document.getElementById('copy-sql-btn');
        const exportBtn     = document.getElementById('export-btn');
        const exportOrgId   = document.getElementById('export_org_id');
        const exportSql     = document.getElementById('export_sql');
        const resultsThead  = null; // kept for compat
        const mobileHistoryBtn     = document.getElementById('mobile-history-btn');
        const mobileHistoryOverlay = document.getElementById('mobile-history-overlay');
        const mobileHistoryDrawer  = document.getElementById('mobile-history-drawer');
        const mobileHistoryClose   = document.getElementById('mobile-history-close');
        const mobileNewChatBtn     = document.getElementById('mobile-new-chat-btn');
        const desktopContainer     = document.getElementById('desktop-history-container');
        const mobileContainer      = document.getElementById('mobile-history-container');

        const csrf   = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const askUrl = @json(route('dashboard.analytics.ask'));
        const sessionUrlTemplate = @json($sessionUrl);
        const deleteSessionUrlTemplate = @json($deleteSessionUrl);
        const exportUrl = @json($exportUrl);

        let currentSessionId = null;
        let cooldownTimerId  = null;
        let typingEl         = null;
        let isLoading        = false;

        // ── helpers ──────────────────────────────────────────────────────────
        function escapeHtml(v) {
            return String(v ?? '').replace(/[&<>"']/g, m =>
                ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[m]);
        }

        function setMessage(html, isError) {
            messageArea.innerHTML = html;
            messageArea.className = isError
                ? 'mt-1 text-xs text-red-600 dark:text-red-400'
                : 'mt-1 text-xs text-gray-600 dark:text-gray-300';
        }

        function setAskButtonEnabled(enabled) {
            if (askBtn) askBtn.disabled = !enabled;
            isLoading = !enabled;
        }

        function scrollToBottom() {
            if (chatScroll) chatScroll.scrollTop = chatScroll.scrollHeight;
        }

        function hideSystemMessage() {
            if (systemMessage) systemMessage.style.display = 'none';
        }

        // ── typing indicator ──────────────────────────────────────────────────
        function showTypingIndicator() {
            removeTypingIndicator();
            const w = document.createElement('div');
            w.className = 'flex justify-start';
            w.id = 'ai-typing-indicator';
            const b = document.createElement('div');
            b.className = 'rounded-2xl px-4 py-3 bg-gray-100 dark:bg-gray-800 flex items-center space-x-1';
            b.innerHTML = '<span class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></span>' +
                          '<span class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></span>' +
                          '<span class="w-2 h-2 bg-gray-400 rounded-full typing-dot"></span>';
            w.appendChild(b);
            chatMessages.appendChild(w);
            typingEl = w;
            scrollToBottom();
        }

        function removeTypingIndicator() {
            if (typingEl?.parentNode) typingEl.parentNode.removeChild(typingEl);
            typingEl = null;
        }

        // ── message bubbles ───────────────────────────────────────────────────
        // opts = { columns, rows, ai_response, historyMode, rowCount, sql, orgId }
        function appendMessage(role, text, opts) {
            hideSystemMessage();
            const w = document.createElement('div');
            w.className = 'flex ' + (role === 'user' ? 'justify-end' : 'justify-start');

            const b = document.createElement('div');
            if (role === 'user') {
                b.className = 'max-w-[75%] rounded-2xl rounded-br-sm px-4 py-2.5 text-sm bg-gray-900 text-white dark:bg-white dark:text-gray-900 whitespace-pre-wrap break-words';
                b.textContent = text;
            } else {
                b.className = 'max-w-full sm:max-w-[96%] rounded-2xl rounded-bl-sm px-4 py-2.5 text-sm bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-100';

                const rows    = opts?.rows    || [];
                const columns = opts?.columns || [];
                const aiText  = opts?.ai_response || text;
                const historyMode = opts?.historyMode || false;
                const historyCount = opts?.rowCount || 0;
                const sql = opts?.sql || '';
                const orgId = opts?.orgId || organizationEl?.value || '';

                if (historyMode) {
                    // Session loaded from history — no rows available, just show saved response text
                    const p = document.createElement('p');
                    p.textContent = aiText || `Found ${historyCount} result(s). Open SQL panel to re-export.`;
                    b.appendChild(p);
                    if (sql && historyCount > 0) {
                        b.appendChild(buildExcelButton(sql, orgId));
                    }
                } else if (rows.length > 0) {
                    // Live result with rows — show ai_response text above table
                    if (aiText) {
                        const p = document.createElement('p');
                        p.className = 'mb-2 font-medium';
                        p.textContent = aiText;
                        b.appendChild(p);
                    }

                    const wrap  = document.createElement('div');
                    wrap.className = 'ai-table-wrap';
                    const table = document.createElement('table');
                    table.className = 'ai-data-table';
                    const displayColumns = columns.includes('S.No') || columns.includes('serial_number')
                        ? columns
                        : ['S.No', ...columns];

                    // Header
                    const thead = document.createElement('thead');
                    const hrow  = document.createElement('tr');
                    displayColumns.forEach(col => {
                        const th = document.createElement('th');
                        th.textContent = col;
                        hrow.appendChild(th);
                    });
                    thead.appendChild(hrow);
                    table.appendChild(thead);

                    // Body
                    const tbody = document.createElement('tbody');
                    rows.forEach((row, index) => {
                        const tr = document.createElement('tr');
                        displayColumns.forEach(col => {
                            const td = document.createElement('td');
                            td.textContent = col === 'S.No' ? index + 1 : (row[col] ?? '');
                            tr.appendChild(td);
                        });
                        tbody.appendChild(tr);
                    });
                    table.appendChild(tbody);
                    wrap.appendChild(table);
                    b.appendChild(wrap);
                    const meta = document.createElement('div');
                    meta.className = 'ai-table-meta';
                    meta.textContent = `Showing ${rows.length} row(s) and ${columns.length} data column(s). Scroll sideways if more columns are off-screen.`;
                    b.appendChild(meta);
                    if (sql) {
                        b.appendChild(buildExcelButton(sql, orgId));
                    }
                } else {
                    // Conversational or no-results
                    b.textContent = aiText || 'No results found.';
                }
            }

            w.appendChild(b);
            chatMessages.appendChild(w);
            scrollToBottom();
        }

        function buildExcelButton(sql, orgId) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'mt-3 inline-flex items-center justify-center rounded-md bg-gray-900 px-3 py-1.5 text-xs font-medium text-white transition hover:opacity-90 dark:bg-white dark:text-gray-900';
            btn.textContent = 'Export Excel';
            btn.addEventListener('click', () => submitExcel(sql, orgId));
            return btn;
        }

        function submitExcel(sql, orgId) {
            if (!sql || !orgId) {
                setMessage('Excel export is not available for this result.', true);
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = exportUrl;
            form.style.display = 'none';

            [
                ['_token', csrf],
                ['organization_id', orgId],
                ['sql', sql],
            ].forEach(([name, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            form.remove();
        }

        // ── session history items ─────────────────────────────────────────────
        function buildSessionBtn(sessionId, title, createdAt, orgName) {
            const item = document.createElement('div');
            item.className = 'history-session-item group flex items-start gap-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-900';
            item.setAttribute('data-session-id', sessionId);

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'history-session-btn min-w-0 flex-1 text-left px-3 py-2.5 text-gray-800 dark:text-gray-100';
            btn.setAttribute('data-session-id', sessionId);
            const shortTitle = title.length > 55 ? title.substring(0, 55) + '...' : title;
            const shortOrg = orgName ? (orgName.length > 14 ? orgName.substring(0, 14) + '...' : orgName) : '';
            btn.innerHTML =
                `<div class="truncate font-medium text-xs">${escapeHtml(shortTitle)}</div>` +
                `<div class="mt-0.5 text-[10px] text-gray-500 dark:text-gray-400 flex items-center justify-between">` +
                    `<span>${escapeHtml(createdAt)}</span>` +
                    (shortOrg ? `<span>${escapeHtml(shortOrg)}</span>` : '') +
                `</div>`;
            bindSessionBtn(btn);

            const deleteBtn = document.createElement('button');
            deleteBtn.type = 'button';
            deleteBtn.className = 'delete-session-btn mt-2 mr-1 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-md text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-950/40';
            deleteBtn.setAttribute('data-session-id', sessionId);
            deleteBtn.setAttribute('title', 'Delete chat');
            deleteBtn.innerHTML = '&times;';
            bindDeleteSessionBtn(deleteBtn);

            item.appendChild(btn);
            item.appendChild(deleteBtn);
            return item;
        }

        function prependSession(sessionId, title, createdAt, orgName) {
            [desktopContainer, mobileContainer].forEach(container => {
                if (!container) return;
                // Remove "no history" placeholder
                container.querySelector('p')?.remove();
                // Remove existing btn with same session id (avoid duplicates)
                container.querySelector(`.history-session-item[data-session-id="${sessionId}"]`)?.remove();
                container.insertAdjacentElement('afterbegin', buildSessionBtn(sessionId, title, createdAt, orgName));
            });
        }

        function refreshHistoryEmptyStates() {
            [
                [desktopContainer, 'no-history-msg'],
                [mobileContainer, 'no-mobile-history-msg'],
            ].forEach(([container, id]) => {
                if (!container || container.querySelector('.history-session-item')) return;
                if (container.querySelector(`#${id}`)) return;

                const p = document.createElement('p');
                p.id = id;
                p.className = 'px-3 py-2 text-xs text-gray-500 dark:text-gray-400';
                p.textContent = 'No history yet. Ask your first question.';
                container.appendChild(p);
            });
        }

        // ── load session into chat ─────────────────────────────────────────────
        function loadSession(sessionId) {
            if (isLoading) return;
            clearChat();
            currentSessionId = sessionId;
            showTypingIndicator();
            setAskButtonEnabled(false);

            const url = sessionUrlTemplate.replace('__ID__', sessionId);
            fetch(url, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
            })
            .then(r => r.json())
            .then(data => {
                removeTypingIndicator();
                if (organizationEl && data.organization_id) {
                    organizationEl.value = data.organization_id;
                }
                // Track the last SQL for the export panel
                let lastSql = '';
                (data.messages || []).forEach(msg => {
                    if (msg.role === 'user') {
                        appendMessage('user', msg.text);
                    } else {
                        if (msg.sql) {
                            lastSql = msg.sql;
                        }
                        if (msg.is_conversation) {
                            // Conversational reply — show saved ai_response text
                            appendMessage('assistant', msg.text || msg.ai_response);
                        } else if (msg.row_count > 0) {
                            // SQL result — show saved ai_response text, no live table
                            appendMessage('assistant', null, {
                                historyMode: true,
                                rowCount: msg.row_count,
                                ai_response: msg.ai_response || msg.text,
                                sql: msg.sql || '',
                                orgId: data.organization_id || '',
                            });
                        } else {
                            appendMessage('assistant', msg.ai_response || msg.text || 'No results found.');
                        }
                    }
                });

                // Restore last SQL to export panel
                if (lastSql) {
                    sqlOutput.value   = lastSql;
                    exportSql.value   = lastSql;
                    exportOrgId.value = data.organization_id || '';
                    copySqlBtn.disabled = false;
                    exportBtn.disabled  = false;
                }

                setAskButtonEnabled(true);
            })
            .catch(() => {
                removeTypingIndicator();
                appendMessage('assistant', 'Failed to load session. Please try again.');
                setAskButtonEnabled(true);
            });
        }

        function clearChat() {
            chatMessages.innerHTML = '';
            sqlOutput.value = '';
            exportSql.value = '';
            exportOrgId.value = '';
            copySqlBtn.disabled = true;
            exportBtn.disabled  = true;
            if (systemMessage) systemMessage.style.display = '';
            setMessage('', false);
        }

        // ── bind session button click ─────────────────────────────────────────
        function bindSessionBtn(btn) {
            btn.addEventListener('click', function () {
                const sid = this.getAttribute('data-session-id');
                if (sid) {
                    currentSessionId = parseInt(sid, 10);
                    loadSession(currentSessionId);
                    closeMobileHistory();
                }
            });
        }

        function bindDeleteSessionBtn(btn) {
            btn.addEventListener('click', async function (event) {
                event.preventDefault();
                event.stopPropagation();

                const sid = this.getAttribute('data-session-id');
                if (!sid || !confirm('Delete this chat history?')) return;

                try {
                    const url = deleteSessionUrlTemplate.replace('__ID__', sid);
                    const resp = await fetch(url, {
                        method: 'DELETE',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                    });

                    if (!resp.ok) {
                        setMessage('Could not delete this chat. Please try again.', true);
                        return;
                    }

                    document.querySelectorAll(`.history-session-item[data-session-id="${sid}"]`).forEach(item => item.remove());
                    refreshHistoryEmptyStates();
                    if (String(currentSessionId) === String(sid)) {
                        currentSessionId = null;
                        clearChat();
                    }
                    setMessage('Chat history deleted.', false);
                } catch (_) {
                    setMessage('Could not delete this chat. Please try again.', true);
                }
            });
        }

        document.querySelectorAll('.history-session-btn').forEach(bindSessionBtn);
        document.querySelectorAll('.delete-session-btn').forEach(bindDeleteSessionBtn);

        // ── new chat ──────────────────────────────────────────────────────────
        function startNewChat() {
            currentSessionId = null;
            clearChat();
            if (questionEl) { questionEl.value = ''; questionEl.focus(); }
        }

        if (newChatBtn) newChatBtn.addEventListener('click', startNewChat);

        // ── mobile drawer ─────────────────────────────────────────────────────
        function openMobileHistory() {
            mobileHistoryOverlay?.classList.remove('hidden');
            mobileHistoryDrawer?.classList.remove('-translate-x-full');
        }
        function closeMobileHistory() {
            mobileHistoryDrawer?.classList.add('-translate-x-full');
            mobileHistoryOverlay?.classList.add('hidden');
        }

        mobileHistoryBtn?.addEventListener('click', openMobileHistory);
        mobileHistoryClose?.addEventListener('click', closeMobileHistory);
        mobileHistoryOverlay?.addEventListener('click', closeMobileHistory);
        mobileNewChatBtn?.addEventListener('click', () => { closeMobileHistory(); startNewChat(); });

        // ── cooldown ──────────────────────────────────────────────────────────
        function startRetryCooldown(seconds) {
            let rem = Math.max(1, Number(seconds || 0));
            if (cooldownTimerId) { clearInterval(cooldownTimerId); }
            setAskButtonEnabled(false);
            askStatus.textContent = `Please wait ${rem}s before retrying…`;
            cooldownTimerId = setInterval(() => {
                rem -= 1;
                if (rem <= 0) {
                    clearInterval(cooldownTimerId);
                    cooldownTimerId = null;
                    askStatus.textContent = '';
                    setAskButtonEnabled(true);
                } else {
                    askStatus.textContent = `Please wait ${rem}s before retrying…`;
                }
            }, 1000);
        }

        // ── submit form ───────────────────────────────────────────────────────
        askForm.addEventListener('submit', handleSubmit);

        // Send on Enter (Shift+Enter = newline)
        questionEl.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (!isLoading) askForm.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        });

        async function handleSubmit(e) {
            e.preventDefault();

            const question = questionEl.value?.trim() || '';
            const orgId    = organizationEl?.value || '';

            if (!question) { setMessage('Please enter a question.', true); return; }
            if (isLoading)  return;

            setMessage('', false);
            setAskButtonEnabled(false);
            questionEl.value = ''; // Clear input immediately
            appendMessage('user', question);
            showTypingIndicator();

            try {
                const resp = await fetch(askUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept':       'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({
                        question:        question,
                        organization_id: orgId || null,
                        session_id:      currentSessionId || null,
                    }),
                });

                let data;
                try { data = await resp.json(); } catch (_) {
                    data = { message: resp.status === 403 ? 'Unauthorized.' : 'Request failed.' };
                }

                removeTypingIndicator();

                if (!resp.ok) {
                    setMessage(data.message || 'Request failed.', true);
                    const retryS = Number(data.retry_after_seconds || 0);
                    if (resp.status === 429 && retryS > 0) startRetryCooldown(retryS);
                    else { askStatus.textContent = ''; setAskButtonEnabled(true); }
                    return;
                }

                askStatus.textContent = '';
                setMessage('', false);

                // Keep session id
                if (data.session_id) currentSessionId = data.session_id;

                // If new session, add to sidebar
                if (data.is_new_session && data.session_id) {
                    prependSession(data.session_id, data.session_title, data.session_created_at, data.organization_name);
                }

                if (data.is_conversation) {
                    appendMessage('assistant', data.message);
                    sqlOutput.value     = '';
                    exportOrgId.value   = '';
                    exportSql.value     = '';
                    copySqlBtn.disabled = true;
                    exportBtn.disabled  = true;
                } else {
                    const columns    = data.columns    || [];
                    const rows       = data.rows       || [];
                    const rawSql     = data.raw_sql    || '';
                    const aiResponse = data.ai_response || '';

                    sqlOutput.value     = rawSql;
                    exportOrgId.value   = orgId || '';
                    exportSql.value     = rawSql;
                    copySqlBtn.disabled = false;
                    exportBtn.disabled  = false;

                    appendMessage('assistant', null, {
                        columns,
                        rows,
                        ai_response: aiResponse,
                        sql: rawSql,
                        orgId: orgId || data.organization_id || '',
                    });
                }

                setAskButtonEnabled(true);
                questionEl.focus();

            } catch (err) {
                console.error(err);
                removeTypingIndicator();
                askStatus.textContent = '';
                setMessage('Failed to call the server.', true);
                setAskButtonEnabled(true);
            }
        }

        copySqlBtn.addEventListener('click', async function () {
            if (!sqlOutput.value) return;
            try {
                await navigator.clipboard.writeText(sqlOutput.value);
                setMessage('SQL copied to clipboard.', false);
            } catch {
                setMessage('Could not copy SQL. Please copy manually.', true);
            }
        });

        // Auto-resize textarea
        questionEl.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 160) + 'px';
        });

    })();
    </script>
</x-app-layout>
