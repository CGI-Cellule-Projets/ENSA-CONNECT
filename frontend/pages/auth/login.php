<?php
require_once __DIR__ . '/../../../backend/middleware/auth_middleware.php';
redirectIfLoggedIn();
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>ENSA Connect | Login</title>
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
                        "primary": "#004359",
                        "primary-container": "#005c78",
                        "on-surface": "#191c1e",
                        "on-surface-variant": "#40484d",
                        "surface": "#f8f9fc",
                        "background": "#ffffff"
                    },
                    borderRadius: { DEFAULT: "0.5rem", lg: "0.5rem", xl: "1.5rem", full: "9999px" },
                    fontFamily: { headline: ["Manrope"], body: ["Inter"] }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2 {
            font-family: 'Manrope', sans-serif;
        }
    </style>
</head>

<body class="bg-surface min-h-screen flex items-center justify-center p-4">

    <main
        class="w-full max-w-5xl bg-white rounded-3xl border border-slate-200/50 overflow-hidden flex flex-col md:flex-row h-[600px]">

        <!-- Illustration Side -->
        <section class="hidden md:block w-1/2 bg-primary relative overflow-hidden">
            <img src="images/lg-login_large.jpg" alt="Login Illustration"
                class="w-full h-full object-cover mix-blend-overlay opacity-80">
            <div
                class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent flex flex-col justify-end p-12">
                <h1 class="text-white text-4xl font-headline font-bold leading-tight">Welcome to <br>ENSA Connect</h1>
                <p class="text-white/60 mt-4 text-sm font-medium tracking-wide">The unified platform for ENSA students
                    and alumni.</p>
            </div>
        </section>

        <!-- Login Side -->
        <section class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center">
            <div class="max-w-md mx-auto w-full">
                <div class="mb-12">
                    <h2 class="text-3xl font-headline font-extrabold text-primary mb-2">Access Platform</h2>
                    <p class="text-sm text-on-surface-variant leading-relaxed">Only UCA students are permitted to access
                        the platform using their official institucional email address.</p>
                </div>

                <div class="flex flex-col gap-6">
                    <button onclick="window.location.href='../../../backend/pages/auth/google_auth.php'"
                        class="w-full flex items-center justify-center gap-3 bg-white border border-slate-200 py-4 rounded-2xl font-bold text-on-surface-variant hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-[0.98]">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="w-5 h-5">
                        Continue with Google
                    </button>

                    <div class="bg-primary/5 border border-primary/10 rounded-2xl p-4 flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-xl mt-0.5">info</span>
                        <p class="text-[12px] font-medium text-primary leading-tight">
                            Make sure to select your <span class="font-bold">@uca.ac.ma</span> account when prompted by
                            Google.
                        </p>
                    </div>
                </div>

                <footer class="mt-auto pt-12">
                    <div
                        class="border-t border-slate-100 pt-6 flex flex-wrap gap-x-6 gap-y-2 text-xs font-bold text-slate-400">
                        <p class="ml-auto text-[10px]">&copy; 2026 ENSA Connect</p>
                    </div>
                </footer>
            </div>
        </section>

    </main>

</body>

</html>