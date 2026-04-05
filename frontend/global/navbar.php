<!-- Shared Navbar Component -->
<header class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/50">
    <div class="flex justify-between items-center px-6 h-16 max-w-[1440px] mx-auto">
        <div class="flex items-center gap-8">
            <span class="text-xl font-headline font-bold tracking-tighter text-primary">ENSA Connect</span>
        </div>
        <nav class="hidden md:flex items-center gap-8 text-sm font-semibold tracking-tight">
            <a class="text-slate-500 hover:text-primary transition-colors" href="../newsfeed/index.php">Network</a>
            <a class="text-slate-500 hover:text-primary transition-colors" href="../chat/index.php">Messages</a>
        </nav>
        <div class="flex items-center gap-4">
            <!-- Notifications -->
            <div class="relative">
                <button id="notif-btn"
                    class="text-on-surface-variant hover:text-primary transition-colors flex items-center justify-center p-2 rounded-xl hover:bg-slate-50 relative">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>

                <div id="notif-dropdown" class="dropdown-menu p-2 min-w-[320px] max-h-[400px] overflow-y-auto">
                    <div class="px-3 py-2 mb-2 flex justify-between items-center border-b border-slate-50 pb-3">
                        <h3 class="font-headline font-bold text-primary">Notifications</h3>
                        <button
                            class="text-[10px] font-bold text-slate-400 hover:text-primary uppercase tracking-wider">Mark
                            all as read</button>
                    </div>
                    <div class="flex flex-col gap-1 text-left">
                        <!-- Mock Notification -->
                        <a href="../newsfeed/index.php#post-123"
                            class="flex gap-4 p-3 hover:bg-slate-50 rounded-xl transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0">
                                <img src="../chat/images/avatar-design_14663198.png" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-on-surface leading-tight">
                                    <span class="font-bold text-primary">Jane Doe</span> published a new post in <span
                                        class="font-bold">Software Architecture</span>.
                                </p>
                                <span class="text-[10px] font-bold text-slate-400 mt-1 block">2 mins ago</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Profile Dropdown -->
            <div class="relative">
                <div id="userAvatar"
                    class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white text-xs font-bold cursor-pointer transition-transform active:scale-95">
                    <?php 
                        if (isset($_SESSION['username'])) {
                            echo strtoupper(substr($_SESSION['username'], 0, 2));
                        } else {
                            echo '??';
                        }
                    ?>
                </div>
                <!-- User Dropdown Menu -->
                <div id="user-dropdown" class="dropdown-menu p-3 min-w-[220px]">
                    <div class="px-2 py-2 mb-2 text-left">
                        <p class="font-headline font-bold text-primary truncate" id="dropdown-user-name">
                            <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>
                        </p>
                        <p class="text-xs text-on-surface-variant truncate" id="dropdown-user-email">
                            <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>
                        </p>
                    </div>
                    <div class="border-t border-slate-100 my-1"></div>
                    <a href="../profile/index.php"
                        class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 text-slate-600 flex items-center gap-2 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-lg">person</span> Profile
                    </a>
                    <a href="../settings/index.php"
                        class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 text-slate-600 flex items-center gap-2 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-lg">settings</span> Settings
                    </a>
                    <div class="border-t border-slate-100 my-1"></div>
                    <a href="../../../backend/pages/auth/logout.php"
                        class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 text-error flex items-center gap-2 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-lg">logout</span> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    // Shared Navbar Dropdown Logic
    document.addEventListener('DOMContentLoaded', () => {
        const userAvatar = document.getElementById('userAvatar');
        const userDropdown = document.getElementById('user-dropdown');
        const notifBtn = document.getElementById('notif-btn');
        const notifDropdown = document.getElementById('notif-dropdown');

        if (userAvatar && userDropdown) {
            userAvatar.onclick = (e) => {
                e.stopPropagation();
                if (notifDropdown) notifDropdown.classList.remove('show');
                userDropdown.classList.toggle('show');
            };
        }

        if (notifBtn && notifDropdown) {
            notifBtn.onclick = (e) => {
                e.stopPropagation();
                if (userDropdown) userDropdown.classList.remove('show');
                notifDropdown.classList.toggle('show');
            };
        }

        document.addEventListener('click', () => {
            if (userDropdown) userDropdown.classList.remove('show');
            if (notifDropdown) notifDropdown.classList.remove('show');
        });
    });
</script>