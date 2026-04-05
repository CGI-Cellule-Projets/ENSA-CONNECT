<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>ENSA Connect | Profile</title>
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
                        "on-tertiary-container": "#78dbc8", "on-surface": "#191c1e", "primary": "#004359",
                        "primary-container": "#005c78", "surface-container-highest": "#e1e3e5",
                        "tertiary-container": "#006054", "outline-variant": "#bfc8cd",
                        "on-surface-variant": "#40484d", "surface-variant": "#e1e3e5", "outline": "#70787d",
                        "on-background": "#191c1e", "surface-bright": "#f8f9fc", "surface-container-high": "#e6e8eb",
                        "on-secondary-container": "#576670", "surface-tint": "#176682", "on-primary": "#ffffff",
                        "background": "#f8f9fc", "surface-container-low": "#f2f4f6", "secondary-container": "#d5e5f1",
                        "surface-container-lowest": "#ffffff", "secondary": "#51606a", "surface": "#f8f9fc",
                        "surface-dim": "#d8dadd", "surface-container": "#eceef0", "on-secondary-fixed-variant": "#3a4952",
                        "error": "#ba1a1a"
                    },
                    borderRadius: { DEFAULT: "0.5rem", lg: "0.5rem", xl: "1.25rem", full: "9999px" },
                    fontFamily: { headline: ["Manrope"], body: ["Inter"], label: ["Inter"] }
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

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            min-width: 220px;
            z-index: 50;
            padding: 0.75rem;
        }

        .dropdown-menu.show {
            display: block;
        }
    </style>
</head>

<body class="bg-surface text-on-surface">

    <!-- TOP NAV -->
    <?php include '../../global/navbar.php'; ?>

    <main class="max-w-[1280px] mx-auto pt-24 pb-20 px-4 md:px-8">

        <!-- Simplified Header Area -->
        <section class="bg-white rounded-3xl border border-slate-200/50 p-8 flex flex-col items-center text-center">
            <div class="w-48 h-48 rounded-3xl overflow-hidden border-4 border-slate-50 bg-slate-100 mb-6">
                <img src="../chat/images/man_6997531.png" id="profile-img" class="w-full h-full object-cover">
            </div>
            <h1 class="text-4xl font-headline font-extrabold text-primary mb-2" id="profile-name">John Doe</h1>
            <p class="text-lg font-medium text-slate-500 mb-8" id="profile-status">Software Engineering student at ENSA
                Tangier</p>

            <div class="flex gap-4">
                <button
                    class="px-8 py-3 bg-primary text-white rounded-2xl font-bold flex items-center gap-2 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <span class="material-symbols-outlined text-lg">person_add</span> Follow
                </button>
                <button onclick="window.location.href='../chat/index.php'"
                    class="px-8 py-3 bg-slate-50 text-slate-600 border border-slate-200 rounded-2xl font-bold flex items-center gap-2 hover:bg-slate-100 active:scale-[0.98] transition-all">
                    <span class="material-symbols-outlined text-lg">mail</span> Send Message
                </button>
            </div>
        </section>

        <!-- Dynamic Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-8">

            <!-- Left Column: MAIN -->
            <div class="lg:col-span-8 flex flex-col gap-8">
                <section class="bg-white rounded-3xl border border-slate-200/50 p-8">
                    <h2 class="text-2xl font-headline font-bold text-primary mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-2xl">fingerprint</span> About
                    </h2>
                    <p class="text-on-surface-variant leading-relaxed" id="profile-about">
                        Hello! I am a passionate student at ENSA. I'm building modern applications and connecting with
                        fellow engineers. Passionate about software architecture and user experience. Welcome to my
                        professional space!
                    </p>

                    <div class="mt-8 border-t border-slate-100 pt-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Interests</h3>
                        <div class="flex flex-wrap gap-2" id="profile-interests">
                            <span
                                class="px-3 py-1 bg-slate-50 border border-slate-200 rounded-full text-xs font-bold text-primary">Web
                                Development</span>
                            <span
                                class="px-3 py-1 bg-slate-50 border border-slate-200 rounded-full text-xs font-bold text-primary">Cloud
                                Computing</span>
                            <span
                                class="px-3 py-1 bg-slate-50 border border-slate-200 rounded-full text-xs font-bold text-primary">AI
                                & ML</span>
                            <span
                                class="px-3 py-1 bg-slate-50 border border-slate-200 rounded-full text-xs font-bold text-primary">Software
                                Architecture</span>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right Column: SIDE PANEL -->
            <aside class="lg:col-span-4 flex flex-col gap-8">

                <!-- Matching Interests Section -->
                <section class="bg-white rounded-3xl border border-slate-200/50 p-6">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">Similar Interests</h3>
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-4 group cursor-pointer">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 overflow-hidden">
                                <img src="../chat/images/avatar-design_14663198.png" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-primary truncate">Jane Doe</p>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Software
                                    Architecture</p>
                            </div>
                            <span
                                class="material-symbols-outlined text-slate-300 group-hover:text-primary transition-colors">chevron_right</span>
                        </div>
                        <div class="flex items-center gap-4 group cursor-pointer mt-2">
                            <div
                                class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 font-bold">
                                AL</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-primary truncate">Ahmad Louk</p>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Cloud Computing
                                </p>
                            </div>
                            <span
                                class="material-symbols-outlined text-slate-300 group-hover:text-primary transition-colors">chevron_right</span>
                        </div>
                    </div>
                </section>

                <!-- Following Section -->
                <section class="bg-white rounded-3xl border border-slate-200/50 p-6">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">Following</h3>
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0">
                                <img src="../chat/images/man_6997531.png" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-primary truncate">ENSA Alumnis</p>
                                <p class="text-[10px] text-slate-400">Professional Network</p>
                            </div>
                            <button
                                class="px-4 py-1 text-[10px] font-bold border border-slate-200 rounded-full hover:bg-slate-50 transition-colors">Unfollow</button>
                        </div>
                    </div>
                </section>

            </aside>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Demo parameter logic
            const urlParams = new URLSearchParams(window.location.search);
            const userId = urlParams.get('id');
            if (userId === 'jane') {
                document.getElementById('profile-name').textContent = 'Jane Doe';
                document.getElementById('profile-img').src = '../chat/images/avatar-design_14663198.png';
                document.getElementById('profile-status').textContent = 'Data Scientist at ENSA Tech';
                document.getElementById('profile-about').textContent = 'Specialized in Data Engineering and Machine Learning. Bridging the gap between theory and practical industrial solutions.';
            }

            // Update local links
            document.querySelectorAll('a[href="../chat/index.php"]').forEach(a => a.href = '../chat/index.php');
        });
    </script>

</body>

</html>