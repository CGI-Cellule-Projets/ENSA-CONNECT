<?php
require_once __DIR__ . '/../../../backend/middleware/auth_middleware.php';
checkAuth();
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>ENSA Connect | Chat</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-tertiary-container": "#78dbc8",
                        "on-surface": "#191c1e",
                        "primary": "#004359",
                        "primary-container": "#005c78",
                        "surface-container-highest": "#e1e3e5",
                        "tertiary-container": "#006054",
                        "outline-variant": "#bfc8cd",
                        "on-surface-variant": "#40484d",
                        "surface-variant": "#e1e3e5",
                        "outline": "#70787d",
                        "on-background": "#191c1e",
                        "surface-bright": "#f8f9fc",
                        "surface-container-high": "#e6e8eb",
                        "on-secondary-container": "#576670",
                        "surface-tint": "#176682",
                        "on-primary": "#ffffff",
                        "background": "#f8f9fc",
                        "surface-container-low": "#f2f4f6",
                        "secondary-container": "#d5e5f1",
                        "surface-container-lowest": "#ffffff",
                        "secondary": "#51606a",
                        "surface": "#f8f9fc",
                        "surface-dim": "#d8dadd",
                        "surface-container": "#eceef0",
                        "on-secondary-fixed-variant": "#3a4952",
                        "error": "#ba1a1a"
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        full: "9999px"
                    },
                    fontFamily: {
                        headline: ["Manrope"],
                        body: ["Inter"],
                        label: ["Inter"]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3 {
            font-family: 'Manrope', sans-serif;
        }

        .chat-container {
            height: calc(100vh - 160px);
        }

        .messages::-webkit-scrollbar {
            width: 6px;
        }

        .messages::-webkit-scrollbar-track {
            background: transparent;
        }

        .messages::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .message-wrapper {
            position: relative;
            max-width: 80%;
            margin-bottom: 1rem;
            display: flex;
            flex-direction: column;
        }

        .sender {
            align-self: flex-end;
            background: #004359;
            color: white;
            border-radius: 1.25rem 1.25rem 0.25rem 1.25rem;
            padding: 0.75rem 1rem;
        }

        .receiver {
            align-self: flex-start;
            background: #f1f5f9;
            color: #1e293b;
            border-radius: 1.25rem 1.25rem 1.25rem 0.25rem;
            padding: 0.75rem 1rem;
        }

        .date {
            display: block;
            font-size: 0.65rem;
            margin-top: 0.25rem;
            opacity: 0.6;
        }

        .sender .date {
            color: white;
            text-align: right;
        }

        .receiver .date {
            color: #64748b;
        }

        /* Responsive handling */
        @media (max-width: 768px) {
            .chat-aside {
                width: 100%;
                display: block;
            }

            .chat-main {
                display: none;
            }

            .show-chat .chat-aside {
                display: none;
            }

            .show-chat .chat-main {
                display: flex;
                width: 100%;
            }
        }

        /* Message Actions Menu */
        .message-actions {
            display: flex;
            gap: 0.25rem;
            padding: 0.25rem 0;
            opacity: 0.4;
            transition: opacity 0.2s;
        }

        .message-wrapper:hover .message-actions {
            opacity: 1;
        }

        .sender .message-actions {
            justify-content: flex-end;
        }

        .receiver .message-actions {
            justify-content: flex-start;
        }

        .action-icon {
            font-size: 1.1rem !important;
            padding: 0.25rem;
            cursor: pointer;
            border-radius: 0.5rem;
        }

        .action-icon:hover {
            background-color: #f1f5f9;
        }

        /* Reactions */
        .reaction-bar {
            display: flex;
            gap: 2px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 2px 5px;
            font-size: 0.7rem;
            position: absolute;
            bottom: -10px;
            z-index: 10;
        }

        .sender .reaction-bar {
            right: 5px;
        }

        .receiver .reaction-bar {
            left: 5px;
        }

        /* Dropdown */
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            min-width: 150px;
            z-index: 50;
        }

        .dropdown-menu.show {
            display: block;
        }

        .reply-preview {
            border-left: 3px solid #004359;
            background: rgba(0, 67, 89, 0.05);
            padding: 5px 10px;
            margin-bottom: 5px;
            border-radius: 4px;
            font-size: 0.75rem;
        }
    </style>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="scripts/main.js" defer></script>
</head>

<body class="bg-surface text-on-surface overflow-hidden">

    <!-- TOP NAV -->
    <?php include '../../global/navbar.php'; ?>

    <main class="max-w-[1440px] mx-auto pt-24 pb-20 px-6">
        <div class="bg-white rounded-2xl border border-slate-200/50 overflow-hidden flex chat-container" id="chat-wrapper">

            <!-- ASIDE: Discussions List -->
            <aside class="chat-aside w-full md:w-80 lg:w-96 border-r border-slate-200/50 flex flex-col bg-slate-50/50">
                <div class="p-4 border-b border-slate-200/50 bg-white">
                    <h2 class="font-headline font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined">forum</span>
                        Discussions
                    </h2>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <ul class="divide-y divide-slate-100" id="discussion-list">
                        <li class="discussion-item p-4 flex gap-3 hover:bg-white cursor-pointer transition-colors active bg-white border-l-4 border-primary">
                            <div class="w-12 h-12 rounded-xl bg-secondary-container flex items-center justify-center text-primary font-bold overflow-hidden">
                                <img src="images/man_6997531.png" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-1">
                                    <span class="font-bold text-sm text-on-surface truncate">John Doe</span>
                                    <span class="text-[10px] text-slate-400">14:30</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-xs text-on-surface-variant truncate mr-2">bonjour, je peux utili...</p>
                                    <span class="material-symbols-outlined text-blue-500" style="font-size:16px">done_all</span>
                                </div>
                            </div>
                        </li>
                        <li class="discussion-item p-4 flex gap-3 hover:bg-white cursor-pointer transition-colors">
                            <div class="w-12 h-12 rounded-xl bg-secondary-container flex items-center justify-center text-primary font-bold overflow-hidden">
                                <img src="images/avatar-design_14663198.png" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-1">
                                    <span class="font-bold text-sm text-on-surface truncate">Jane Doe</span>
                                    <span class="text-[10px] text-slate-400">Yesterday</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-xs text-on-surface-variant truncate mr-2">merci de vérifie...</p>
                                    <span class="material-symbols-outlined text-slate-300" style="font-size:16px">done</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </aside>

            <!-- SECTION: Chat View -->
            <section class="chat-main flex-1 flex flex-col bg-white overflow-hidden relative" id="test">

                <!-- User Profile Header -->
                <section class="user-profile p-4 border-b border-slate-200/50 flex items-center gap-4 bg-white/80 backdrop-blur-md sticky top-0 z-10">
                    <button class="md:hidden p-2 -ml-2 text-primary" id="back-to-list">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </button>
                    <div class="w-10 h-10 rounded-xl bg-secondary-container flex items-center justify-center text-primary font-bold overflow-hidden">
                        <img src="images/man_6997531.png" id="header-avatar" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <h2 class="font-headline font-bold text-sm text-primary" id="header-name">John Doe</h2>
                        <p class="info text-[10px] text-on-surface-variant flex items-center gap-1">
                            last seen today
                        </p>
                    </div>
                    <div class="flex items-center gap-2 relative">
                        <button class="p-2 hover:bg-slate-100 rounded-lg text-slate-400 transition-colors" id="more-options-btn">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>
                        <div class="dropdown-menu p-2" id="header-dropdown">
                            <button class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 text-error flex items-center gap-2 rounded-lg">
                                <span class="material-symbols-outlined text-lg">block</span> Block User
                            </button>
                            <button class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 text-slate-600 flex items-center gap-2 rounded-lg">
                                <span class="material-symbols-outlined text-lg">delete_sweep</span> Clear History
                            </button>
                        </div>
                    </div>
                </section>

                <!-- Messages Area -->
                <div class="messages flex-1 overflow-y-auto p-6 flex flex-col bg-slate-50/30" id="chat-messages">
                    <!-- Sample Sender Message -->
                    <div class="message-wrapper self-end items-end group" data-id="1" data-time="2026-04-30T14:30:00">
                        <div class="sender">
                            <div class="content-msg">Lorem ipsum dolor sit amet consectetur adipisicing elit.</div>
                            <span class="date">30/04/26 14:30</span>
                        </div>
                        <div class="message-actions text-slate-500">
                            <span class="material-symbols-outlined action-icon" title="Reply" onclick="ChatUI.setReply(1)">reply</span>
                            <span class="material-symbols-outlined action-icon" title="React" onclick="ChatUI.toggleReactionMenu(1)">add_reaction</span>
                            <span class="material-symbols-outlined action-icon" title="Edit" onclick="ChatUI.editMessage(1)">edit</span>
                            <span class="material-symbols-outlined action-icon text-error/70" title="Delete" onclick="ChatUI.deleteMessage(1)">delete</span>
                        </div>
                    </div>

                    <!-- Sample Receiver Message -->
                    <div class="message-wrapper self-start items-start group" data-id="2">
                        <div class="receiver">
                            <div class="content-msg">Hello! How can I help you?</div>
                            <span class="date">30/04/26 14:34</span>
                        </div>
                        <div class="message-actions text-slate-500">
                            <span class="material-symbols-outlined action-icon" title="Reply" onclick="ChatUI.setReply(2)">reply</span>
                            <span class="material-symbols-outlined action-icon" title="React" onclick="ChatUI.toggleReactionMenu(2)">add_reaction</span>
                        </div>
                        <div class="reaction-bar">🔥 ❤️ 👍</div>
                    </div>
                </div>

                <!-- Input Area -->
                <section class="entree p-4 bg-white border-t border-slate-200/50">
                    <div id="reply-box" class="hidden flex items-center justify-between bg-slate-50 p-2 px-4 mb-2 border-l-4 border-primary rounded-r-lg">
                        <div class="text-xs">
                            <p class="font-bold text-primary">Replying to...</p>
                            <p id="reply-text" class="text-slate-500 truncate max-w-sm"></p>
                        </div>
                        <button onclick="ChatUI.cancelReply()" class="text-slate-400 hover:text-error"><span class="material-symbols-outlined" style="font-size:18px">close</span></button>
                    </div>

                    <form class="flex items-end gap-3 max-w-4xl mx-auto" id="message-form">
                        <div class="flex-1 bg-slate-50 rounded-2xl border border-slate-200 px-4 py-2 flex items-end gap-2 focus-within:ring-2 focus-within:ring-primary/10 transition-all relative">
                            <textarea id="message-content" placeholder="Type a message..." rows="1"
                                class="flex-1 bg-transparent border-none focus:ring-0 text-sm p-1.5 resize-none max-h-32 text-on-surface"></textarea>
                        </div>
                        <button type="submit"
                            class="w-10 h-10 rounded-full flex items-center justify-center text-white hover:scale-105 active:scale-95 transition-all"
                            style="background:linear-gradient(135deg,#004359,#005c78)">
                            <span class="material-symbols-outlined" style="font-size:20px">send</span>
                        </button>
                    </form>
                </section>

                <!-- Reaction Menu Float (Hidden) -->
                <div id="reaction-menu" class="hidden absolute bg-white border border-slate-200 rounded-full p-1 z-50 flex gap-1 animate-in fade-in zoom-in duration-200">
                    <button class="hover:bg-slate-100 p-1 rounded-full px-2" onclick="ChatUI.addReaction('🔥')">🔥</button>
                    <button class="hover:bg-slate-100 p-1 rounded-full px-2" onclick="ChatUI.addReaction('❤️')">❤️</button>
                    <button class="hover:bg-slate-100 p-1 rounded-full px-2" onclick="ChatUI.addReaction('😨')">😨</button>
                    <button class="hover:bg-slate-100 p-1 rounded-full px-2" onclick="ChatUI.addReaction('😂')">😂</button>
                    <button class="hover:bg-slate-100 p-1 rounded-full px-2" onclick="ChatUI.addReaction('👍')">👍</button>
                    <button class="hover:bg-slate-100 p-1 rounded-full px-2" onclick="ChatUI.addReaction('😲')">😲</button>
                </div>

            </section>
        </div>
    </main>

    <!-- MOBILE BOTTOM NAV -->
    <nav class="fixed bottom-0 w-full z-50 rounded-t-2xl md:hidden bg-white border-t border-slate-100 h-16 flex justify-around items-center px-4">
        <a class="flex flex-col items-center text-slate-400" href="../newsfeed/index.php"><span
                class="material-symbols-outlined text-lg">home</span><span class="text-[10px] font-bold">Home</span></a>
        <a class="flex flex-col items-center text-slate-400" href="#"><span
                class="material-symbols-outlined text-lg">search</span><span
                class="text-[10px] font-bold">Search</span></a>
        <a class="flex flex-col items-center text-primary bg-teal-50 rounded-xl px-3 py-1" href="#"><span
                class="material-symbols-outlined text-lg">chat</span><span class="text-[10px] font-bold">Chat</span></a>
        <a class="flex flex-col items-center text-slate-400" href="../profile/index.php"><span
                class="material-symbols-outlined text-lg">person</span><span
                class="text-[10px] font-bold">Profile</span></a>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Discussion items toggle for mobile
            const discItems = document.querySelectorAll('.discussion-item');
            const chatWrapper = document.getElementById('chat-wrapper');
            const backBtn = document.getElementById('back-to-list');

            discItems.forEach(item => {
                item.onclick = () => {
                    discItems.forEach(i => i.classList.remove('active', 'border-l-4', 'border-primary', 'bg-white'));
                    item.classList.add('active', 'border-l-4', 'border-primary', 'bg-white');
                    chatWrapper.classList.add('show-chat');
                };
            });

            if (backBtn) backBtn.onclick = () => chatWrapper.classList.remove('show-chat');

            // Dropdown toggle
            const moreBtn = document.getElementById('more-options-btn');
            const dropdown = document.getElementById('header-dropdown');
            if (moreBtn && dropdown) {
                moreBtn.onclick = (e) => {
                    e.stopPropagation();
                    dropdown.classList.toggle('show');
                };
            }
            document.addEventListener('click', () => {
                if (dropdown) dropdown.classList.remove('show');
            });
        });

        // UI Logic for messages
        const ChatUI = {
            activeMsgId: null,
            replyingTo: null,
            activeConversationId: null,

            async loadConversations() {
                try {
                    const res = await fetch('../../../backend/pages/chat/get_conversations.php');
                    const data = await res.json();
                    if (data.status === 'ok') {
                        const list = document.getElementById('discussion-list');
                        list.innerHTML = data.conversations.map(c => `
                            <li class="discussion-item p-4 flex gap-3 hover:bg-white cursor-pointer transition-colors" 
                                onclick="ChatUI.loadMessages(${c.conversation_id}, '${c.other_user_name}', '${c.other_user_avatar}')">
                                <div class="w-12 h-12 rounded-xl bg-secondary-container flex items-center justify-center text-primary font-bold overflow-hidden">
                                    <img src="${c.other_user_avatar || 'images/avatar-design_14663198.png'}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-baseline mb-1">
                                        <span class="font-bold text-sm text-on-surface truncate">${c.other_user_name}</span>
                                        <span class="text-[10px] text-slate-400">${c.last_message_at ? new Date(c.last_message_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : ''}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <p class="text-xs text-on-surface-variant truncate mr-2">${c.last_message || 'No messages yet'}</p>
                                    </div>
                                </div>
                            </li>
                        `).join('');
                    }
                } catch (e) {
                    console.error(e);
                }
            },

            async loadMessages(convId, name, avatar) {
                this.activeConversationId = convId;
                document.getElementById('header-name').textContent = name;
                document.getElementById('header-avatar').src = avatar || 'images/man_6997531.png';
                document.getElementById('chat-wrapper').classList.add('show-chat');

                try {
                    const res = await fetch(`../../../backend/pages/chat/get_messages.php?conversation_id=${convId}`);
                    const data = await res.json();
                    if (data.status === 'ok') {
                        const area = document.getElementById('chat-messages');
                        area.innerHTML = data.messages.map(m => `
                            <div class="message-wrapper ${m.sender_id == <?php echo $_SESSION['user_id']; ?> ? 'self-end items-end' : 'self-start items-start'} group">
                                <div class="${m.sender_id == <?php echo $_SESSION['user_id']; ?> ? 'sender' : 'receiver'}">
                                    <div class="content-msg">${m.content}</div>
                                    <span class="date">${new Date(m.created_at).toLocaleString()}</span>
                                </div>
                            </div>
                        `).join('');
                        area.scrollTop = area.scrollHeight;
                    }
                } catch (e) {
                    console.error(e);
                }
            },

            async sendMessage(e) {
                e.preventDefault();
                const content = document.getElementById('message-content').value.trim();
                if (!content || !this.activeConversationId) return;

                try {
                    const res = await fetch('../../../backend/pages/chat/send.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            conversation_id: this.activeConversationId, // Simplified for now
                            content: content
                        })
                    });
                    const data = await res.json();
                    if (data.status === 'ok') {
                        document.getElementById('message-content').value = '';
                        this.loadMessages(this.activeConversationId, document.getElementById('header-name').textContent, document.getElementById('header-avatar').src);
                    }
                } catch (e) {
                    console.error(e);
                }
            }
        };

        // Initialize Chat
        document.addEventListener('DOMContentLoaded', () => {
            ChatUI.loadConversations();
            document.getElementById('message-form').onsubmit = (e) => ChatUI.sendMessage(e);
        });
    </script>

</body>

</html>