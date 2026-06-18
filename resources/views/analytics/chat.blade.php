<x-app-layout>
    <style>
        @keyframes pulse-dots {
            0%, 100% { opacity: 0.3; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1.2); }
        }
        .typing-dot {
            animation: pulse-dots 1.4s infinite both;
        }
        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }
        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }
    </style>
    @php
        $user = auth()->user();
        $isSuperAdmin = $user && method_exists($user, 'hasRole') ? $user->hasRole('super-admin') : false;
        $orgs = \App\Models\Organization::query()
            ->orderBy('name')
            ->get();
        $organizationMissing = $user && !$isSuperAdmin && empty($user->organization_id);
        $userOrganization = $user && !$isSuperAdmin ? $user->organization : null;
    @endphp

    <div class="h-[calc(100vh-4rem)] bg-gray-50 dark:bg-gray-950">
        <div class="h-full max-w-7xl mx-auto flex">
            <!-- Sidebar: history -->
            <aside class="hidden md:flex md:flex-col w-72 border-r border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-950/80 backdrop-blur">
                <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            History
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Recent analytics chats
                        </p>
                    </div>
                    <button type="button"
                            id="new-chat-btn"
                            class="inline-flex items-center px-2.5 py-1.5 rounded-md bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-xs hover:opacity-90 transition">
                        New chat
                    </button>
                </div>

                <div id="desktop-history-container" class="flex-1 overflow-y-auto p-2 space-y-1 text-sm">
                    @forelse($history as $item)
                        <button type="button"
                                class="w-full text-left px-3 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-900 text-gray-800 dark:text-gray-100 truncate"
                                data-question="{{ $item->question }}">
                            <div class="truncate">
                                {{ \Illuminate\Support\Str::limit($item->question, 60) }}
                            </div>
                            <div class="mt-0.5 text-[10px] text-gray-500 dark:text-gray-400 flex items-center justify-between">
                                <span>{{ optional($item->created_at)->format('d M, H:i') }}</span>
                                @if($item->organization)
                                    <span>{{ \Illuminate\Support\Str::limit($item->organization->name, 14) }}</span>
                                @endif
                            </div>
                        </button>
                    @empty
                        <p class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400">
                            No history yet. Ask your first question.
                        </p>
                    @endforelse
                </div>
            </aside>

            <!-- Main chat area -->
            <main class="flex-1 flex flex-col">
                <header class="px-4 sm:px-6 py-3 border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-gray-950/80 backdrop-blur">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h1 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100">
                                AI Multi-DB Analytics
                            </h1>
                            <p class="mt-0.5 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                Ask in English, Urdu, Roman Urdu, or Hindi. The AI inspects your schema and generates safe SQL.
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button"
                                    id="mobile-history-btn"
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

                <div class="flex-1 flex flex-col">
                    <!-- messages area -->
                    <div id="chat-scroll"
                         class="flex-1 overflow-y-auto px-4 sm:px-6 py-4 space-y-4 bg-gradient-to-b from-gray-50/80 to-white dark:from-gray-950 dark:to-gray-950">
                        <div id="system-message"
                             class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                            Start by asking a question like:
                            <span class="italic">
                                “aaj ki total executed sale kitni thi?”
                            </span>
                        </div>
                        <div id="chat-messages" class="space-y-4 text-sm"></div>
                    </div>

                    <!-- advanced / results panel -->
                    <div class="border-t border-gray-200 dark:border-gray-800 bg-white/90 dark:bg-gray-950/95">
                        <div class="px-4 sm:px-6 py-3">
                            <details class="group">
                                <summary class="flex items-center justify-between cursor-pointer text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200">
                                    <span>Advanced (SQL + Export)</span>
                                    <span class="ml-2 text-[10px] text-gray-400 group-open:hidden">show</span>
                                    <span class="ml-2 text-[10px] text-gray-400 hidden group-open:inline">hide</span>
                                </summary>
                                <div class="mt-3 space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                            Generated SQL
                                        </label>
                                        <textarea id="sql-output"
                                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-mono text-xs"
                                                  rows="3"
                                                  readonly></textarea>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button type="button"
                                                id="copy-sql-btn"
                                                disabled
                                                class="inline-flex items-center justify-center px-3 py-1.5 rounded-md bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-100 text-xs disabled:opacity-50">
                                            Copy SQL
                                        </button>

                                        <form id="export-form" method="POST" action="{{ route('dashboard.analytics.export') }}">
                                            @csrf
                                            <input type="hidden" name="organization_id" id="export_org_id" value="">
                                            <input type="hidden" name="sql" id="export_sql" value="">
                                            <button type="submit"
                                                    id="export-btn"
                                                    disabled
                                                    class="inline-flex items-center justify-center px-3 py-1.5 rounded-md bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-xs disabled:opacity-50 hover:opacity-90 transition">
                                                Export to Excel
                                            </button>
                                        </form>

                                        <div id="ask-status"
                                             class="text-xs text-gray-500 dark:text-gray-400 ml-auto min-h-[1rem]">
                                        </div>
                                    </div>

                                    <div class="mt-2 overflow-x-auto border border-gray-200 dark:border-gray-800 rounded-md max-h-64">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800 text-xs">
                                            <thead id="results-thead" class="bg-gray-50 dark:bg-gray-900"></thead>
                                            <tbody id="results-tbody" class="divide-y divide-gray-200 dark:divide-gray-900"></tbody>
                                        </table>
                                        <div id="empty-state" class="hidden px-3 py-2 text-xs text-gray-500 dark:text-gray-400">
                                            No results yet.
                                        </div>
                                    </div>
                                </div>
                            </details>
                        </div>

                        <!-- input bar -->
                        <div class="border-t border-gray-200 dark:border-gray-800 px-4 sm:px-6 py-3">
                            <form id="ask-form" class="space-y-2">
                                <div class="flex items-start gap-2">
                                    @if($isSuperAdmin)
                                        <select id="organization_id" name="organization_id"
                                                class="hidden sm:inline-flex mt-1 rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-xs">
                                            @foreach($orgs as $org)
                                                <option value="{{ $org->id }}">
                                                    {{ $org->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="hidden" id="organization_id" value="{{ $user?->organization_id }}">
                                    @endif

                                    <div class="flex-1 relative">
                                        <textarea id="question"
                                                  rows="1"
                                                  class="block w-full resize-none rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm py-2 pl-3 pr-10"
                                                  placeholder="Ask anything about your sales, TSOs, or KPIs..."></textarea>
                                        <button type="submit"
                                                id="ask-btn"
                                                @if(!$isSuperAdmin && $organizationMissing) disabled @endif
                                                class="absolute right-1.5 top-1.5 inline-flex items-center justify-center w-7 h-7 rounded-md bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-xs disabled:opacity-50">
                                            Send
                                        </button>
                                    </div>
                                </div>

                                @if(!$isSuperAdmin && $organizationMissing)
                                    <div class="rounded-md bg-yellow-50 border border-yellow-200 px-3 py-2 text-xs text-yellow-900 dark:bg-yellow-900/30 dark:border-yellow-900/60 dark:text-yellow-200" role="alert">
                                        Your account isn’t assigned to any organization. Ask a Super Admin to set your <code class="font-mono">organization_id</code>.
                                    </div>
                                @endif

                                <div id="message-area" class="text-xs text-gray-600 dark:text-gray-400 min-h-[1rem]"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile history drawer -->
    <div id="mobile-history-overlay"
         class="hidden md:hidden fixed inset-0 bg-black/40 backdrop-blur-[1px] z-40"></div>

    <div id="mobile-history-drawer"
         class="md:hidden fixed inset-y-0 left-0 w-80 max-w-[85vw] bg-white dark:bg-gray-950 border-r border-gray-200 dark:border-gray-800 z-50 transform -translate-x-full transition-transform duration-200 ease-out">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">History</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Recent analytics chats</div>
            </div>
            <button type="button"
                    id="mobile-history-close"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-gray-100 dark:hover:bg-gray-900 text-gray-700 dark:text-gray-200">
                ✕
            </button>
        </div>

        <div class="p-3">
            <button type="button"
                    id="mobile-new-chat-btn"
                    class="w-full inline-flex items-center justify-center px-3 py-2 rounded-md bg-gray-900 text-white dark:bg-white dark:text-gray-900 text-sm hover:opacity-90 transition">
                New chat
            </button>
        </div>

        <div id="mobile-history-container" class="flex-1 overflow-y-auto p-2 space-y-1 text-sm">
            @forelse($history as $item)
                <button type="button"
                        class="w-full text-left px-3 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-900 text-gray-800 dark:text-gray-100"
                        data-mobile-question="{{ $item->question }}">
                    <div class="truncate">
                        {{ \Illuminate\Support\Str::limit($item->question, 70) }}
                    </div>
                    <div class="mt-0.5 text-[10px] text-gray-500 dark:text-gray-400 flex items-center justify-between">
                        <span>{{ optional($item->created_at)->format('d M, H:i') }}</span>
                        @if($item->organization)
                            <span>{{ \Illuminate\Support\Str::limit($item->organization->name, 14) }}</span>
                        @endif
                    </div>
                </button>
            @empty
                <p class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400">
                    No history yet. Ask your first question.
                </p>
            @endforelse
        </div>
    </div>

    <script>
        (function () {
            const askForm = document.getElementById('ask-form');
            const questionEl = document.getElementById('question');
            const organizationEl = document.getElementById('organization_id');
            const askBtn = document.getElementById('ask-btn');
            const askStatus = document.getElementById('ask-status');
            const messageArea = document.getElementById('message-area');
            const chatMessages = document.getElementById('chat-messages');
            const chatScroll = document.getElementById('chat-scroll');
            const newChatBtn = document.getElementById('new-chat-btn');
            const historyButtons = document.querySelectorAll('aside [data-question]');
            const mobileHistoryBtn = document.getElementById('mobile-history-btn');
            const mobileHistoryOverlay = document.getElementById('mobile-history-overlay');
            const mobileHistoryDrawer = document.getElementById('mobile-history-drawer');
            const mobileHistoryClose = document.getElementById('mobile-history-close');
            const mobileNewChatBtn = document.getElementById('mobile-new-chat-btn');
            const mobileHistoryButtons = document.querySelectorAll('[data-mobile-question]');

            const sqlOutput = document.getElementById('sql-output');
            const copySqlBtn = document.getElementById('copy-sql-btn');
            const exportBtn = document.getElementById('export-btn');
            const exportOrgId = document.getElementById('export_org_id');
            const exportSql = document.getElementById('export_sql');

            const resultsThead = document.getElementById('results-thead');
            const resultsTbody = document.getElementById('results-tbody');
            const emptyState = document.getElementById('empty-state');

            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const askUrl = @json(route('dashboard.analytics.ask'));
            let cooldownTimerId = null;

            function escapeHtml(value) {
                return String(value ?? '').replace(/[&<>"']/g, function (m) {
                    return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[m];
                });
            }

            function setMessage(html, isError) {
                messageArea.innerHTML = html;
                messageArea.className = isError
                    ? 'mt-3 text-sm text-red-600 dark:text-red-400'
                    : 'mt-3 text-sm text-gray-600 dark:text-gray-300';
            }

            function setAskButtonEnabled(enabled) {
                if (!askBtn) return;
                askBtn.disabled = !enabled;
            }

            function startRetryCooldown(seconds) {
                const totalSeconds = Math.max(1, Number(seconds || 0));
                let remaining = totalSeconds;

                if (cooldownTimerId) {
                    clearInterval(cooldownTimerId);
                    cooldownTimerId = null;
                }

                setAskButtonEnabled(false);
                askStatus.textContent = 'Please wait ' + remaining + 's before retrying...';

                cooldownTimerId = setInterval(function () {
                    remaining -= 1;

                    if (remaining <= 0) {
                        clearInterval(cooldownTimerId);
                        cooldownTimerId = null;
                        askStatus.textContent = '';
                        setAskButtonEnabled(true);
                        return;
                    }

                    askStatus.textContent = 'Please wait ' + remaining + 's before retrying...';
                }, 1000);
            }

            function renderTable(columns, rows) {
                resultsThead.innerHTML = '';
                resultsTbody.innerHTML = '';

                if (!rows || rows.length === 0) {
                    emptyState.classList.remove('hidden');
                    return;
                }

                emptyState.classList.add('hidden');

                // Header
                const headerHtml = '<tr>' + columns.map(col => (
                    '<th class="px-3 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">' + escapeHtml(col) + '</th>'
                )).join('') + '</tr>';
                resultsThead.insertAdjacentHTML('beforeend', headerHtml);

                // Body
                rows.forEach(row => {
                    const cells = columns.map(col => (
                        '<td class="px-3 py-2 whitespace-nowrap text-sm text-gray-800 dark:text-gray-100">' + escapeHtml(row[col]) + '</td>'
                    )).join('');
                    resultsTbody.insertAdjacentHTML('beforeend', '<tr>' + cells + '</tr>');
                });
            }

            function appendMessage(role, text) {
                const wrapper = document.createElement('div');
                wrapper.className = 'flex ' + (role === 'user' ? 'justify-end' : 'justify-start');

                const bubble = document.createElement('div');
                bubble.className = 'max-w-[80%] rounded-2xl px-3 py-2 text-xs sm:text-sm ' +
                    (role === 'user'
                        ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900'
                        : 'bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100');
                bubble.textContent = text;

                wrapper.appendChild(bubble);
                chatMessages.appendChild(wrapper);

                if (chatScroll) {
                    chatScroll.scrollTop = chatScroll.scrollHeight;
                }
            }

            let typingIndicatorEl = null;

            function showTypingIndicator() {
                removeTypingIndicator();
                const wrapper = document.createElement('div');
                wrapper.className = 'flex justify-start';
                wrapper.id = 'ai-typing-indicator';
                
                const bubble = document.createElement('div');
                bubble.className = 'max-w-[80%] rounded-2xl px-4 py-3 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100 flex items-center space-x-1';
                bubble.innerHTML = '<span class="w-1.5 h-1.5 bg-gray-500 rounded-full typing-dot"></span>' +
                                   '<span class="w-1.5 h-1.5 bg-gray-500 rounded-full typing-dot"></span>' +
                                   '<span class="w-1.5 h-1.5 bg-gray-500 rounded-full typing-dot"></span>';
                
                wrapper.appendChild(bubble);
                chatMessages.appendChild(wrapper);
                typingIndicatorEl = wrapper;

                if (chatScroll) {
                    chatScroll.scrollTop = chatScroll.scrollHeight;
                }
            }

            function removeTypingIndicator() {
                if (typingIndicatorEl && typingIndicatorEl.parentNode) {
                    typingIndicatorEl.parentNode.removeChild(typingIndicatorEl);
                }
                typingIndicatorEl = null;
            }

            function appendHistoryItem(item) {
                if (!item || !item.question) return;
                
                const questionHtml = escapeHtml(item.question);
                const shortQuestion = questionHtml.length > 60 ? questionHtml.substring(0, 60) + '...' : questionHtml;
                const orgNameHtml = item.organization_name ? escapeHtml(item.organization_name.length > 14 ? item.organization_name.substring(0, 14) + '...' : item.organization_name) : '';
                
                const btnHtml = '<button type="button" class="w-full text-left px-3 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-900 text-gray-800 dark:text-gray-100 truncate" data-question="' + escapeHtml(item.question) + '">' +
                    '<div class="truncate">' + shortQuestion + '</div>' +
                    '<div class="mt-0.5 text-[10px] text-gray-500 dark:text-gray-400 flex items-center justify-between">' +
                        '<span>' + escapeHtml(item.created_at || '') + '</span>' +
                        (orgNameHtml ? '<span>' + orgNameHtml + '</span>' : '') +
                    '</div>' +
                '</button>';

                const desktopContainer = document.getElementById('desktop-history-container');
                const mobileContainer = document.getElementById('mobile-history-container');

                function insertInto(container) {
                    if (!container) return;
                    const p = container.querySelector('p');
                    if (p) p.remove(); // Remove "No history yet" message
                    container.insertAdjacentHTML('afterbegin', btnHtml);
                    
                    // Bind click event to new button
                    const newBtn = container.firstElementChild;
                    newBtn.addEventListener('click', function () {
                        const q = this.getAttribute('data-question') || '';
                        if (q && questionEl) {
                            questionEl.value = q;
                            questionEl.focus();
                        }
                        closeMobileHistory();
                    });
                }

                insertInto(desktopContainer);
                insertInto(mobileContainer);
            }

            if (newChatBtn) {
                newChatBtn.addEventListener('click', function () {
                    chatMessages.innerHTML = '';
                    sqlOutput.value = '';
                    exportSql.value = '';
                    exportOrgId.value = '';
                    copySqlBtn.disabled = true;
                    exportBtn.disabled = true;
                    renderTable([], []);
                    messageArea.textContent = '';
                    if (questionEl) {
                        questionEl.value = '';
                        questionEl.focus();
                    }
                });
            }

            function openMobileHistory() {
                if (!mobileHistoryDrawer || !mobileHistoryOverlay) return;
                mobileHistoryOverlay.classList.remove('hidden');
                mobileHistoryDrawer.classList.remove('-translate-x-full');
            }

            function closeMobileHistory() {
                if (!mobileHistoryDrawer || !mobileHistoryOverlay) return;
                mobileHistoryDrawer.classList.add('-translate-x-full');
                mobileHistoryOverlay.classList.add('hidden');
            }

            if (mobileHistoryBtn) {
                mobileHistoryBtn.addEventListener('click', openMobileHistory);
            }
            if (mobileHistoryClose) {
                mobileHistoryClose.addEventListener('click', closeMobileHistory);
            }
            if (mobileHistoryOverlay) {
                mobileHistoryOverlay.addEventListener('click', closeMobileHistory);
            }

            if (mobileNewChatBtn) {
                mobileNewChatBtn.addEventListener('click', function () {
                    closeMobileHistory();
                    if (newChatBtn) newChatBtn.click();
                });
            }

            historyButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const q = this.getAttribute('data-question') || '';
                    if (q && questionEl) {
                        questionEl.value = q;
                        questionEl.focus();
                    }
                });
            });

            mobileHistoryButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const q = this.getAttribute('data-mobile-question') || '';
                    if (q && questionEl) {
                        questionEl.value = q;
                        questionEl.focus();
                    }
                    closeMobileHistory();
                });
            });

            askForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                const question = questionEl.value?.trim() || '';
                const orgId = organizationEl?.value || '';

                if (!question) {
                    setMessage('Please enter a question.', true);
                    return;
                }

                setMessage('', false);
                setAskButtonEnabled(false);
                askStatus.textContent = '';
                appendMessage('user', question);
                showTypingIndicator();

                try {
                    const resp = await fetch(askUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                        body: JSON.stringify({
                            question: question,
                            organization_id: orgId || null,
                        })
                    });

                    let data;
                    try {
                        data = await resp.json();
                    } catch (_e) {
                        data = { message: resp.status === 403 ? 'Unauthorized.' : 'Request failed.' };
                    }

                    if (!resp.ok) {
                        removeTypingIndicator();
                        setMessage(data.message || 'Request failed.', true);
                        sqlOutput.value = '';
                        copySqlBtn.disabled = true;
                        exportBtn.disabled = true;
                        renderTable([], []);
                        const retrySeconds = Number(data.retry_after_seconds || 0);
                        if (resp.status === 429 && retrySeconds > 0) {
                            startRetryCooldown(retrySeconds);
                        } else {
                            askStatus.textContent = '';
                            setAskButtonEnabled(true);
                        }
                        return;
                    }

                    // Success
                    removeTypingIndicator();
                    askStatus.textContent = '';
                    setMessage('', false);

                    const columns = data.columns || [];
                    const rows = data.rows || [];
                    const rawSql = data.raw_sql || '';

                    if (data.is_conversation) {
                        sqlOutput.value = '';
                        exportOrgId.value = '';
                        exportSql.value = '';
                        copySqlBtn.disabled = true;
                        exportBtn.disabled = true;
                        renderTable([], []);
                        appendMessage('assistant', data.message);
                    } else {
                        sqlOutput.value = rawSql;
                        exportOrgId.value = orgId || '';
                        exportSql.value = rawSql;

                        copySqlBtn.disabled = false;
                        exportBtn.disabled = false;

                        renderTable(columns, rows);

                        const answerSummary = (data.message || 'Success') +
                            (rows.length ? ` — ${rows.length} row(s)` : '');
                        appendMessage('assistant', answerSummary);
                    }

                    if (data.history_item) {
                        appendHistoryItem(data.history_item);
                    }
                    setAskButtonEnabled(true);
                } catch (err) {
                    console.error(err);
                    removeTypingIndicator();
                    askStatus.textContent = '';
                    setMessage('Failed to call the server.', true);
                    setAskButtonEnabled(true);
                }
            });

            copySqlBtn.addEventListener('click', async function () {
                if (!sqlOutput.value) return;
                try {
                    await navigator.clipboard.writeText(sqlOutput.value);
                    setMessage('SQL copied to clipboard.', false);
                } catch (e) {
                    setMessage('Could not copy SQL. Please copy manually.', true);
                }
            });
        })();
    </script>
</x-app-layout>

