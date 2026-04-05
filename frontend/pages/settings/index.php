<?php
require_once __DIR__ . '/../../../backend/middleware/auth_middleware.php';
checkAuth();
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>ENSA Connect | Settings</title>
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
                    borderRadius: { DEFAULT: "0.5rem", lg: "0.5rem", xl: "0.75rem", full: "9999px" },
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

    <main class="max-w-[800px] mx-auto pt-24 pb-20 px-4 md:px-6">
        <h1 class="text-2xl font-headline font-bold text-primary mb-8 flex items-center gap-3">
            <span class="material-symbols-outlined text-3xl">settings</span>
            Account Settings
        </h1>

        <div class="flex flex-col gap-8">

            <!-- Basic Info Section -->
            <section class="bg-white rounded-xl border border-slate-200/50 overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">person</span>
                        Personal Information
                    </h2>
                </div>
                <div class="p-6">
                    <!-- Avatar Upload -->
                    <div class="flex items-center gap-6 mb-8 p-4 bg-slate-50/50 rounded-xl border border-slate-100">
                        <div class="relative group">
                            <div id="pfp-preview"
                                class="w-24 h-24 rounded-2xl bg-primary flex items-center justify-center text-white text-2xl font-bold overflow-hidden border-4 border-white shadow-sm">
                                ?</div>
                            <label
                                class="absolute inset-0 flex items-center justify-center bg-black/40 text-white opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer rounded-2xl">
                                <span class="material-symbols-outlined">add_a_photo</span>
                                <input type="file" id="pfp-input" class="hidden" accept="image/*"
                                    onchange="UI.handleAvatar(this)">
                            </label>
                        </div>
                        <div>
                            <h3 class="font-bold text-on-surface">Profile Picture</h3>
                            <p class="text-xs text-slate-500 mb-2">JPG, PNG or GIF. Max 2MB.</p>
                            <button onclick="document.getElementById('pfp-input').click()"
                                class="text-xs font-bold text-primary hover:underline">Change Photo</button>
                        </div>
                    </div>

                    <form id="info-form" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">First
                                Name</label>
                            <input type="text" name="first_name" id="first_name"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Last
                                Name</label>
                            <input type="text" name="last_name" id="last_name"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Current
                                Position</label>
                            <input type="text" name="position" id="position"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Interests
                                (comma separated)</label>
                            <input type="text" name="interests" id="interests"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">School Entry
                                Year</label>
                            <input type="number" name="school_entry_year" id="school_entry_year" placeholder="YYYY"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Graduation
                                Year</label>
                            <input type="number" name="graduation_year" id="graduation_year" placeholder="YYYY"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                        </div>
                        <div class="md:col-span-2 flex flex-col gap-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">About
                                Me</label>
                            <textarea name="bio" id="bio" rows="4"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none resize-none"></textarea>
                        </div>
                        <div class="md:col-span-2 mt-2">
                            <button type="submit" id="save-pfp-btn"
                                class="px-6 py-2.5 bg-primary text-white rounded-full text-sm font-bold hover:bg-primary-container transition-all active:scale-95">Update
                                Profile</button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Password Change Section Removed -->

        </div>
    </main>

    <script>
        const UI = {
            handleAvatar(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('pfp-preview').innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        };

        document.addEventListener('DOMContentLoaded', async () => {
            // Update local links
            document.querySelectorAll('a[href="../newsfeed/index.php"]').forEach(a => a.href = '../newsfeed/index.php');
            document.querySelectorAll('a[href="../chat/index.php"]').forEach(a => a.href = '../chat/index.php');

            // Load Profile Data
            try {
                const res = await fetch('../../../backend/pages/settings/get_profile.php');
                const data = await res.json();
                if (data.status === 'ok') {
                    const u = data.user;
                    if (u.first_name) document.getElementById('first_name').value = u.first_name;
                    if (u.last_name) document.getElementById('last_name').value = u.last_name;
                    if (u.position) document.getElementById('position').value = u.position;
                    if (u.interests) document.getElementById('interests').value = u.interests;
                    if (u.bio) document.getElementById('bio').value = u.bio;
                    if (u.school_entry_year) document.getElementById('school_entry_year').value = u.school_entry_year;
                    if (u.graduation_year) document.getElementById('graduation_year').value = u.graduation_year;

                    if (u.avatar_url) {
                        document.getElementById('pfp-preview').innerHTML = `<img src="${u.avatar_url}" class="w-full h-full object-cover">`;
                    } else {
                        const initials = (u.username || '?').split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
                        document.getElementById('pfp-preview').textContent = initials;
                    }
                }
            } catch (err) { console.error("Failed to load profile:", err); }

            // Profile info update
            document.getElementById('info-form').addEventListener('submit', async (e) => {
                e.preventDefault();
                const btn = document.getElementById('save-pfp-btn');
                btn.disabled = true;
                btn.textContent = 'Saving...';

                const formData = new FormData(e.target);
                const pfp = document.getElementById('pfp-input').files[0];
                if (pfp) formData.append('avatar', pfp);

                try {
                    const res = await fetch('../../../backend/pages/settings/update_profile.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();
                    alert(data.message || 'Profile updated!');
                    if (data.status === 'ok') location.reload();
                } catch (err) {
                    console.error(err);
                    alert("Error saving profile. Check console.");
                } finally {
                    btn.disabled = false;
                    btn.textContent = 'Update Profile';
                }
            });
        });
    </script>

</body>

</html>