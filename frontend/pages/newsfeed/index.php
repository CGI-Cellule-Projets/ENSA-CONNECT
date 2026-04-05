<?php
require_once __DIR__ . '/../../../backend/middleware/auth_middleware.php';
checkAuth();
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>ENSA Connect | Newsfeed</title>
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

    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(10px)
      }

      to {
        opacity: 1;
        transform: translateY(0)
      }
    }

    .post-card {
      animation: fadeUp 0.3s ease both;
    }

    .post-content.collapsed {
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .filter-chip.active {
      background: #004359 !important;
      color: #ffffff !important;
    }

    .action-btn.liked {
      color: #ba1a1a !important;
    }

    .comment-section {
      animation: fadeUp 0.2s ease both;
    }



    article.post-card:hover {
      box-shadow: 0 12px 40px rgba(23, 102, 130, 0.08);
    }

    .skeleton {
      background: linear-gradient(90deg, #eceef0 25%, #f2f4f6 50%, #eceef0 75%);
      background-size: 200% 100%;
      animation: shimmer 1.4s infinite;
      border-radius: 0.5rem;
    }

    @keyframes shimmer {
      0% {
        background-position: 200% 0
      }

      100% {
        background-position: -200% 0
      }
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

  <main class="max-w-[1440px] mx-auto pt-24 pb-20 px-6 grid grid-cols-1 md:grid-cols-12 gap-8">

    <!-- LEFT SIDEBAR -->
    <aside class="hidden md:block md:col-span-3 lg:col-span-2">
      <div class="sticky top-24 flex flex-col gap-6">
        <div class="bg-surface-container-lowest rounded-lg p-5 flex flex-col items-center text-center">
          <div id="sidebarAvatar"
            class="w-14 h-14 rounded-full bg-primary flex items-center justify-center text-white text-lg font-bold mb-3">
            ?</div>
          <h3 id="sidebarName" class="font-headline font-bold text-base text-primary">...</h3>
          <p id="sidebarRole" class="text-xs text-on-surface-variant font-medium mt-1"></p>
          <p class="text-[10px] text-outline mt-1 uppercase tracking-widest">ENSA</p>
        </div>
        <nav class="flex flex-col gap-1 font-semibold text-sm">
          <a class="flex items-center gap-3 bg-white text-primary rounded-lg p-3 shadow-sm" href="#">
            <span class="material-symbols-outlined text-lg">dynamic_feed</span> Feed
          </a>
        </nav>
        <button onclick="document.getElementById('createPostModal').classList.remove('hidden')"
          class="w-full py-2.5 rounded-lg text-sm font-bold text-white tracking-wide"
          style="background:linear-gradient(135deg,#004359,#005c78)">
          Post Opportunity
        </button>
      </div>
    </aside>

    <!-- MAIN FEED -->
    <section class="md:col-span-6 lg:col-span-7 flex flex-col gap-0">

      <!-- Create Post trigger -->
      <div class="bg-surface-container-lowest rounded-lg p-4 mb-6 flex items-center gap-3 cursor-pointer"
        onclick="document.getElementById('createPostModal').classList.remove('hidden')">
        <div id="createPostAvatar"
          class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
          ?</div>
        <div
          class="flex-1 bg-surface-container-low rounded-full px-4 py-2.5 text-sm text-on-surface-variant hover:bg-surface-container transition-colors">
          Share a project, an internship offer, or an insight...
        </div>
      </div>

      <!-- Search Bar -->
      <div class="relative mb-6">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base">search</span>
        <input type="text" id="searchInput" placeholder="Search posts, authors, or content..." 
          class="w-full bg-surface-container-low border-none rounded-xl pl-10 pr-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-primary/20 transition-all"
          oninput="PostManager.filter()">
      </div>

      <!-- Filter chips — TYPE: status/offer  +  category: internship/pfe/mentorship/experience -->
      <div class="flex gap-2 mb-6 flex-wrap">
        <button
          class="filter-chip active px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-surface-container-high text-on-secondary-container transition-all"
          onclick="PostManager.setFilter('all',this)">All</button>
        <button
          class="filter-chip px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-surface-container-high text-on-secondary-container transition-all"
          onclick="PostManager.setFilter('offer',this)">Offers</button>
        <button
          class="filter-chip px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-surface-container-high text-on-secondary-container transition-all"
          onclick="PostManager.setFilter('general',this)">general</button>
        <button
          class="filter-chip px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-surface-container-high text-on-secondary-container transition-all"
          onclick="PostManager.setFilter('internship',this)">Internship</button>
        <button
          class="filter-chip px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-surface-container-high text-on-secondary-container transition-all"
          onclick="PostManager.setFilter('pfe',this)">PFE</button>
        <button
          class="filter-chip px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-surface-container-high text-on-secondary-container transition-all"
          onclick="PostManager.setFilter('mentorship',this)">Mentorship</button>
      </div>

      <!-- Loading skeleton -->
      <div id="loadingSkeleton" class="space-y-4">
        <div class="bg-surface-container-lowest rounded-lg p-6">
          <div class="flex gap-3 mb-4">
            <div class="skeleton w-11 h-11 rounded-lg flex-shrink-0"></div>
            <div class="flex-1">
              <div class="skeleton h-4 w-32 mb-2"></div>
              <div class="skeleton h-3 w-48"></div>
            </div>
          </div>
          <div class="skeleton h-3 w-full mb-2"></div>
          <div class="skeleton h-3 w-4/5 mb-2"></div>
          <div class="skeleton h-3 w-3/5"></div>
        </div>
        <div class="bg-surface-container-lowest rounded-lg p-6">
          <div class="flex gap-3 mb-4">
            <div class="skeleton w-11 h-11 rounded-lg flex-shrink-0"></div>
            <div class="flex-1">
              <div class="skeleton h-4 w-28 mb-2"></div>
              <div class="skeleton h-3 w-40"></div>
            </div>
          </div>
          <div class="skeleton h-3 w-full mb-2"></div>
          <div class="skeleton h-3 w-3/5"></div>
        </div>
      </div>

      <div id="feed" class="flex flex-col gap-0 hidden"></div>

      <button id="loadMoreBtn" onclick="PostManager.loadMore()"
        class="mt-6 w-full py-3 rounded-lg text-sm font-semibold text-on-surface-variant bg-surface-container-low hover:bg-surface-container transition-colors hidden">
        Load more posts
      </button>
    </section>

    <!-- RIGHT SIDEBAR -->
    <aside class="hidden lg:block lg:col-span-3">
      <div class="sticky top-24 flex flex-col gap-6">
        <div class="bg-surface-container-low rounded-lg p-5">
          <h2 class="font-headline font-extrabold text-primary text-sm mb-4 tracking-tight">Suggested Mentors</h2>
          <div class="flex flex-col gap-4">
            <div class="flex items-center gap-3">
              <div
                class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-xs font-bold text-primary">
                LH</div>
              <div class="flex-1">
                <p class="text-xs font-bold text-on-surface">Leila Hassan</p>
                <p class="text-[10px] text-on-surface-variant">Software Lead at Google</p>
              </div><button class="text-primary hover:bg-white p-1 rounded-full"><span
                  class="material-symbols-outlined text-lg">person_add</span></button>
            </div>
            <div class="flex items-center gap-3">
              <div
                class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-xs font-bold text-primary">
                YA</div>
              <div class="flex-1">
                <p class="text-xs font-bold text-on-surface">Yassine Amrani</p>
                <p class="text-[10px] text-on-surface-variant">Project Manager at MASEN</p>
              </div><button class="text-primary hover:bg-white p-1 rounded-full"><span
                  class="material-symbols-outlined text-lg">person_add</span></button>
            </div>
          </div>
        </div>
        <div class="bg-surface-container-low rounded-lg p-5">
          <h2 class="font-headline font-extrabold text-primary text-sm mb-4 tracking-tight">Discover People</h2>
          <div class="flex flex-col gap-4">
            <div class="flex items-center gap-3">
              <div
                class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-primary">
                JD</div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-on-surface truncate">Jane Doe</p>
                <p class="text-[10px] text-on-surface-variant truncate">AI Engineering Student</p>
              </div>
              <button class="text-primary hover:bg-white p-1 rounded-full"><span
                  class="material-symbols-outlined text-lg">person_add</span></button>
            </div>
            <div class="flex items-center gap-3">
              <div
                class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-primary">
                AK</div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-on-surface truncate">Amine Kasmi</p>
                <p class="text-[10px] text-on-surface-variant truncate">Fullstack Developer</p>
              </div>
              <button class="text-primary hover:bg-white p-1 rounded-full"><span
                  class="material-symbols-outlined text-lg">person_add</span></button>
            </div>
          </div>
        </div>
      </div>
    </aside>
  </main>

  <!-- CREATE POST MODAL -->
  <div id="createPostModal"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4 p-6">
      <div class="flex justify-between items-center mb-4">
        <h3 class="font-headline font-bold text-primary text-lg">New Post</h3>
        <button onclick="document.getElementById('createPostModal').classList.add('hidden')"
          class="text-on-surface-variant hover:text-primary"><span
            class="material-symbols-outlined">close</span></button>
      </div>
      <form id="createPostForm">
        <!-- TYPE matches DB enum('status','offer') -->
        <div class="flex gap-2 mb-4 overflow-x-auto pb-2 scrollbar-hide">
          <button type="button" onclick="UI.setPostType('status', this)"
            class="type-pill px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap bg-primary text-white border border-primary">Update</button>
          <button type="button" onclick="UI.setPostType('internship', this)"
            class="type-pill px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap bg-slate-50 text-slate-500 border border-slate-200">Internship</button>
          <button type="button" onclick="UI.setPostType('mentorship', this)"
            class="type-pill px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap bg-slate-50 text-slate-500 border border-slate-200">Mentorship</button>
          <button type="button" onclick="UI.setPostType('pfe', this)"
            class="type-pill px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap bg-slate-50 text-slate-500 border border-slate-200">PFE</button>
        </div>
        <input type="hidden" id="postType" name="TYPE" value="status">

        <!-- Dynamic Inputs Group -->
        <div id="dynamic-inputs" class="space-y-3 mb-3">
          <!-- Title & Company (For Internship/PFE) -->
          <div class="hidden grid grid-cols-2 gap-3" id="field-title-company">
            <input type="text" name="title" placeholder="Job Title"
              class="w-full bg-slate-50 rounded-lg px-4 py-2 text-sm border-none outline-none focus:ring-2 focus:ring-primary/20">
            <input type="text" name="company" placeholder="Company"
              class="w-full bg-slate-50 rounded-lg px-4 py-2 text-sm border-none outline-none focus:ring-2 focus:ring-primary/20">
          </div>
          <!-- Link Field -->
          <div class="relative group">
            <span
              class="material-symbols-outlined absolute left-3 top-1 text-slate-400 text-lg group-focus-within:text-primary">link</span>
            <input type="url" name="link" placeholder="External Link (optional)"
              class="w-full bg-slate-50 rounded-lg pl-10 pr-4 py-2 text-sm border-none outline-none focus:ring-2 focus:ring-primary/20">
          </div>
        </div>

        <textarea id="postContent" name="content" rows="4"
          placeholder="Share your insight, opportunity, or experience..."
          class="w-full mb-3 bg-surface-container-low rounded-lg px-4 py-3 text-sm border-none outline-none focus:ring-2 focus:ring-primary/20 resize-none"></textarea>

        <!-- Attachments Section -->
        <div class="mb-4">
          <label
            class="flex items-center gap-2 cursor-pointer text-sm font-bold text-primary hover:bg-slate-50 p-2 rounded-lg transition-colors w-fit">
            <span class="material-symbols-outlined">attach_file</span> Add Images/Videos (Max 6)
            <input type="file" id="postAttachments" class="hidden" multiple accept="image/*,video/*"
              onchange="UI.handleFiles(this)">
          </label>
          <div id="attachment-preview" class="flex flex-wrap gap-2 mt-2"></div>
        </div>

        <div id="postError" class="text-xs text-red-600 mb-3 hidden"></div>
        <button type="submit"
          class="w-full py-2.5 rounded-lg text-sm font-bold text-white shadow-lg transition-transform active:scale-[0.98]"
          style="background:linear-gradient(135deg,#004359,#005c78)">Publish</button>
      </form>
    </div>
  </div>

  <!-- MOBILE BOTTOM NAV -->
  <nav
    class="fixed bottom-0 w-full z-50 rounded-t-2xl md:hidden bg-white border-t border-slate-100 shadow-lg h-16 flex justify-around items-center px-4">
    <a class="flex flex-col items-center text-primary bg-teal-50 rounded-xl px-3 py-1" href="#"><span
        class="material-symbols-outlined text-lg">home</span><span class="text-[10px] font-bold">Home</span></a>
    <a class="flex flex-col items-center text-slate-400" href="#"><span
        class="material-symbols-outlined text-lg">search</span><span class="text-[10px] font-bold">Search</span></a>
    <a class="flex flex-col items-center text-slate-400" href="../chat/index.php"><span
        class="material-symbols-outlined text-lg">chat</span><span class="text-[10px] font-bold">Chat</span></a>
    <a class="flex flex-col items-center text-slate-400" href="#"><span
        class="material-symbols-outlined text-lg">person</span><span class="text-[10px] font-bold">Profile</span></a>
  </nav>

  <script>
    // ─────────────────────────────────────────────
    // CONSTANTS — match DB schema exactly
    // ─────────────────────────────────────────────
    // role_id: 1=Etudiant, 2=Lauréat, 3=Mentor, 4=Admin  (from schema.sql)
    const ROLE_MAP = { 1: 'Etudiant', 2: 'Lauréat', 3: 'Mentor', 4: 'Admin' };

    // TYPE enum('status','offer') + category for frontend filter
    const CATEGORY_LABEL = {
      offer: 'Offer', status: 'Update',
      internship: 'Internship', pfe: 'PFE',
      mentorship: 'Mentorship', experience: 'Experience'
    };

    const UI = {
      selectedFiles: [],

      setPostType(type, btn) {
        document.getElementById('postType').value = type;
        document.querySelectorAll('.type-pill').forEach(b => {
          b.classList.remove('bg-primary', 'text-white', 'border-primary');
          b.classList.add('bg-slate-50', 'text-slate-500', 'border-slate-200');
        });
        btn.classList.add('bg-primary', 'text-white', 'border-primary');
        btn.classList.remove('bg-slate-50', 'text-slate-500', 'border-slate-200');

        // Show/Hide dynamic fields
        const titleCompany = document.getElementById('field-title-company');
        if (type === 'internship' || type === 'pfe') {
          titleCompany.classList.remove('hidden');
        } else {
          titleCompany.classList.add('hidden');
        }

        const placeholder = type === 'status' ? "What's an update on your engineering journey?" :
          type === 'mentorship' ? "Describe your mentorship offer or request..." :
            "Detail your internship opportunity...";
        document.getElementById('postContent').placeholder = placeholder;
      },

      handleFiles(input) {
        const files = Array.from(input.files);
        if (this.selectedFiles.length + files.length > 6) {
          alert("Maximum 6 files allowed.");
          return;
        }

        files.forEach(file => {
          this.selectedFiles.push(file);
          const reader = new FileReader();
          reader.onload = (e) => {
            const preview = document.getElementById('attachment-preview');
            const div = document.createElement('div');
            div.className = "relative w-20 h-20 rounded-lg overflow-hidden border border-slate-200 bg-slate-50 group";

            if (file.type.startsWith('image/')) {
              div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            } else {
              div.innerHTML = `<div class="w-full h-full flex flex-col items-center justify-center text-[8px] font-bold text-slate-400">
            <span class="material-symbols-outlined text-lg">videocam</span>
            VIDEO
          </div>`;
            }

            const deleteBtn = document.createElement('button');
            deleteBtn.className = "absolute top-1 right-1 bg-black/50 text-white rounded-full p-0.5 opacity-0 group-hover:opacity-100 transition-opacity";
            deleteBtn.innerHTML = `<span class="material-symbols-outlined" style="font-size:12px">close</span>`;
            deleteBtn.onclick = () => {
              this.selectedFiles = this.selectedFiles.filter(f => f !== file);
              div.remove();
            };
            div.appendChild(deleteBtn);
            preview.appendChild(div);
          };
          reader.readAsDataURL(file);
        });
        input.value = ''; // Reset input
      }
    };

    const AVATAR_COLORS = [
      { bg: '#d5e5f1', color: '#004359' }, { bg: '#eceef0', color: '#004359' },
      { bg: '#ffdcbe', color: '#5d3400' }, { bg: '#c6e7ff', color: '#004359' },
      { bg: '#d5e5f1', color: '#004d65' }
    ];
    const getAvatarColor = id => AVATAR_COLORS[(id || 0) % AVATAR_COLORS.length];

    // XSS protection — always sanitize before innerHTML
    function sanitize(str) {
      const d = document.createElement('div');
      d.textContent = str || '';
      return d.innerHTML;
    }
    function getInitials(name) {
      return (name || '?').split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
    }
    function formatTime(ts) {
      if (!ts) return '';
      const diff = Math.floor((Date.now() - new Date(ts)) / 1000);
      if (diff < 60) return 'just now';
      if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
      if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
      return Math.floor(diff / 86400) + 'd ago';
    }

    // ─────────────────────────────────────────────
    // SESSION — reads from backend/session.php
    // which echoes $_SESSION['user_id'] & ['username']
    // set by login.php
    // ─────────────────────────────────────────────
    let currentUser = { id: null, username: '?', role_id: 1 };

    async function loadSession() {
      try {
        const res = await fetch('../../../backend/pages/auth/session.php');
        const data = await res.json();
        if (data.status === 'ok') {
          currentUser = data.user;
        } else {
          // Not logged in → redirect to login
          window.location.href = '../auth/login.php';
          return;
        }
      } catch {
        // If session check fails but we are on this page, let's try reading from the navbar's perspective or just fallback
        currentUser = { id: 0, username: 'Anonymous', role_id: 1 };
      }
      const initials = getInitials(currentUser.username);
      ['userAvatar', 'sidebarAvatar', 'createPostAvatar'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = initials;
      });
      document.getElementById('sidebarName').textContent = currentUser.username;
      document.getElementById('sidebarRole').textContent = ROLE_MAP[currentUser.role_id] || 'Member';
    }

    // ─────────────────────────────────────────────
    // POST MANAGER
    // ─────────────────────────────────────────────
    const PAGE_SIZE = 5;

    const PostManager = (() => {
      let posts = [], filtered = [], page = 1, currentFilter = 'all';

      const icons = {
        heart: `<span class="material-symbols-outlined" style="font-size:18px">favorite</span>`,
        heartFilled: `<span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24;font-size:18px">favorite</span>`,
        comment: `<span class="material-symbols-outlined" style="font-size:18px">comment</span>`,
        share: `<span class="material-symbols-outlined" style="font-size:18px">share</span>`,
        send: `<span class="material-symbols-outlined" style="font-size:16px">send</span>`
      };

      // Normalize API row → frontend post object
      // DB fields: id, author_id, content, image_url, TYPE, created_at
      // Joined:    username, role_id, avatar_url, likes_count, comments_count, user_liked
      function normalize(raw) {
        const av = getAvatarColor(raw.author_id || raw.id);
        const category = raw.category || raw.TYPE || 'status';
        return {
          id: raw.id,
          author: sanitize(raw.username || 'Unknown'),
          initials: getInitials(raw.username),
          role: ROLE_MAP[raw.role_id] || 'Member',
          avatarBg: av.bg, avatarColor: av.color,
          avatarUrl: raw.avatar_url ? (raw.avatar_url.startsWith('http') ? raw.avatar_url : '/' + raw.avatar_url) : null,
          author_id: raw.author_id,
          time: formatTime(raw.created_at),
          category,
          categoryLabel: CATEGORY_LABEL[category] || category,
          content: sanitize(raw.content),
          media: raw.media || [],
          likes: parseInt(raw.likes_count) || 0,
          liked: raw.user_liked === '1' || raw.user_liked === true,
          commentsCount: parseInt(raw.comments_count) || 0,
          comments: [], showComments: false,
          expanded: false, newComment: '',
          TYPE: raw.TYPE || 'status',
          hasApply: raw.TYPE === 'offer'
        };
      }

      async function fetchPosts() {
        try {
          const sEl = document.getElementById('searchInput');
          const q = sEl ? sEl.value.trim() : '';
          const params = new URLSearchParams({ filter: currentFilter, page, limit: PAGE_SIZE, search: q });
          const res = await fetch(`/backend/pages/posts/get_posts.php?${params}`);
          const data = await res.json();

          if (data.status === 'error') throw new Error(data.message || 'Backend error');

          const rawPosts = data.posts || [];
          const rows = rawPosts.map(normalize);

          if (page === 1) {
            posts = rows;
            filtered = rows;
          } else {
            posts = [...posts, ...rows];
            filtered = [...filtered, ...rows];
          }

          render(rawPosts.length >= PAGE_SIZE);

          if (rows.length === 0 && page === 1) {
            document.getElementById('feed').innerHTML = `<div class="py-16 text-center text-on-surface-variant text-sm">No posts found for "${currentFilter}".</div>`;
          }
        } catch (err) {
          console.error(err);
          document.getElementById('loadingSkeleton').classList.add('hidden');
          document.getElementById('feed').classList.remove('hidden');
          document.getElementById('feed').innerHTML = `<div class="py-16 text-center text-error text-sm font-bold">API Error: ${err.message}</div>`;
        }
      }

      function localFilter(list) {
        const sEl = document.getElementById('searchInput');
        const q = sEl ? sEl.value.toLowerCase() : '';
        return list.filter(p => {
          const mf = currentFilter === 'all' || p.category === currentFilter || p.TYPE === currentFilter;
          const ms = !q || p.author.toLowerCase().includes(q) || p.content.toLowerCase().includes(q);
          return mf && ms;
        });
      }

      function loadDemo() {
        // Demo data — fields match DB schema
        const demo = [
          { id: 1, author_id: 1, username: "Dr. Karim Tazi", role_id: 3, content: "Exciting opportunity for final year students! We are looking for 3 PFE interns to join our research lab on Distributed Cloud Systems. Passionate about Go, Kubernetes, and high-scale architectures? Let us talk.", image_url: "https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=640&q=80", TYPE: "offer", category: "pfe", created_at: new Date(Date.now() - 7200000).toISOString(), likes_count: 48, comments_count: 1, user_liked: false },
          { id: 2, author_id: 2, username: "Sara Berrada", role_id: 2, content: "I still remember my first week at ENSA. The jump from high school to engineering logic was tough! For the juniors struggling: stay consistent. It is not about being the smartest; it is about not giving up.\n\nFeel free to DM if you need advice on balancing clubs and studies.", image_url: null, TYPE: "status", category: "experience", created_at: new Date(Date.now() - 18000000).toISOString(), likes_count: 156, comments_count: 34, user_liked: false },
          { id: 3, author_id: 3, username: "Omar Mansouri", role_id: 2, content: "Opening 5 slots for Summer Internship in AI and Data Engineering. We prioritize candidates with strong analytical skills. Join us to build the next generation of logistics tools.", image_url: null, TYPE: "offer", category: "internship", created_at: new Date(Date.now() - 86400000).toISOString(), likes_count: 89, comments_count: 0, user_liked: false },
          { id: 4, author_id: 4, username: "Hamza Ouali", role_id: 3, content: "Open to mentoring 2nd-year students in backend development. I have been working with Node.js and PostgreSQL professionally. We can do bi-weekly 30min calls. DM me with a short intro.", image_url: null, TYPE: "offer", category: "mentorship", created_at: new Date(Date.now() - 172800000).toISOString(), likes_count: 67, comments_count: 2, user_liked: false },
          { id: 5, author_id: 5, username: "Kawtar Ait Said", role_id: 1, content: "Finally got my offer letter from Capgemini Morocco! Starting in April as a DevOps intern. Huge thanks to everyone who gave me feedback on my CV. This network is really something special.", image_url: null, TYPE: "status", category: "experience", created_at: new Date(Date.now() - 259200000).toISOString(), likes_count: 112, comments_count: 0, user_liked: false }
        ];
        posts = demo.map(normalize);
        filtered = localFilter(posts);
        render(false);
      }

      function renderPost(post, idx) {
        const isLong = post.content.length > 240;
        const colClass = (isLong && !post.expanded) ? ' collapsed' : '';
        const profileLink = `../profile/index.php?id=${post.author_id}`;
        
        const avatarHtml = `
          <a href="${profileLink}" class="block hover:opacity-80 transition-opacity flex-shrink-0">
            ${post.avatarUrl
              ? `<img src="${sanitize(post.avatarUrl)}" class="w-11 h-11 rounded-lg object-cover" alt="">`
              : `<div class="w-11 h-11 rounded-lg flex items-center justify-center font-bold text-sm" style="background:${post.avatarBg};color:${post.avatarColor}">${post.initials}</div>`
            }
          </a>`;

        // Render Multiple Media
        let mediaHtml = '';
        if (post.media && post.media.length > 0) {
          const gridClass = post.media.length === 1 ? 'grid-cols-1' : (post.media.length === 2 ? 'grid-cols-2' : 'grid-cols-2 lg:grid-cols-3');
          mediaHtml = `<div class="grid ${gridClass} gap-2 mb-4 rounded-lg overflow-hidden">`;
          post.media.forEach(m => {
            const url = m.media_path.startsWith('http') ? m.media_path : '/' + m.media_path;
            if (m.media_type === 'video') {
              mediaHtml += `<div class="aspect-video bg-black flex items-center justify-center relative group">
                <video src="${url}" class="w-full h-full object-contain" controls></video>
              </div>`;
            } else {
              mediaHtml += `<div class="aspect-square bg-slate-100 relative group overflow-hidden">
                <img src="${url}" class="w-full h-full object-cover transition-transform group-hover:scale-105" loading="lazy" alt="Post attachment">
              </div>`;
            }
          });
          mediaHtml += '</div>';
        }

        const applyBtn = post.hasApply
          ? `<button class="ml-auto px-5 py-1.5 rounded-lg text-xs font-bold text-white" style="background:linear-gradient(135deg,#004359,#005c78)">Apply Now</button>`
          : '';
        const commentsHtml = post.showComments ? `
      <div class="comment-section mt-4 pt-4 border-t border-surface-container space-y-3">
        ${post.comments.map(c => `
          <div class="flex gap-3 items-start">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold flex-shrink-0" style="background:${c.bg};color:${c.color}">${sanitize(c.initials)}</div>
            <div class="bg-surface-container-low rounded-lg px-3 py-2 flex-1">
              <p class="text-xs font-bold text-on-surface mb-0.5">${sanitize(c.author)}</p>
              <p class="text-xs text-on-surface-variant leading-relaxed">${sanitize(c.text)}</p>
            </div>
          </div>`).join('')}
        <div class="flex gap-2 items-center mt-2">
          <div class="w-7 h-7 rounded-full bg-primary flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">${getInitials(currentUser.username)}</div>
          <input id="cinput-${post.id}" type="text" placeholder="Write a comment…" maxlength="500"
            class="flex-1 bg-surface-container-low rounded-full px-4 py-2 text-xs border-none outline-none focus:ring-2 focus:ring-primary/20 transition-all"
            value="${sanitize(post.newComment)}"
            oninput="PostManager.updateComment(${post.id},this.value)"
            onkeydown="if(event.key==='Enter')PostManager.addComment(${post.id})">
          <button onclick="PostManager.addComment(${post.id})"
            class="w-8 h-8 rounded-full flex items-center justify-center text-white flex-shrink-0 hover:opacity-80 transition-opacity"
            style="background:linear-gradient(135deg,#004359,#005c78)">${icons.send}</button>
        </div>
      </div>` : '';

        return `
      <article class="post-card bg-surface-container-lowest rounded-lg group mb-4" style="animation-delay:${idx * 0.07}s">
        <div class="p-6">
          <div class="flex justify-between items-start mb-4">
            <div class="flex gap-3 items-center">
              ${avatarHtml}
              <div>
                <a href="${profileLink}" class="hover:underline">
                  <h4 class="font-headline font-bold text-on-surface text-sm">${post.author}</h4>
                </a>
                <p class="text-xs text-on-surface-variant">${post.role} • ${post.time}</p>
              </div>
            </div>
            <span class="bg-tertiary-container text-on-tertiary-container px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider flex-shrink-0">${sanitize(post.categoryLabel)}</span>
          </div>
          <div class="post-content text-sm text-on-surface leading-relaxed mb-3${colClass}" style="white-space:pre-line">${post.content}</div>
          ${isLong ? `<button onclick="PostManager.toggleExpand(${post.id})" class="text-xs font-semibold text-primary hover:underline mb-3 block">${post.expanded ? 'Show less' : 'Show more'}</button>` : ''}
          ${mediaHtml}
          <div class="flex items-center gap-1 pt-2">
            <button onclick="PostManager.toggleLike(${post.id})"
              class="action-btn ${post.liked ? 'liked' : ''} flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-surface-container text-on-secondary-container text-xs font-semibold transition-colors">
              ${post.liked ? icons.heartFilled : icons.heart}<span>${post.likes}</span>
            </button>
            <button onclick="PostManager.toggleComments(${post.id})"
              class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-surface-container text-on-secondary-container text-xs font-semibold transition-colors">
              ${icons.comment}<span>${post.commentsCount}</span>
            </button>
            <button onclick="PostManager.sharePost(${post.id})"
              class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-surface-container text-on-secondary-container text-xs font-semibold transition-colors">
              ${icons.share}
            </button>
            ${applyBtn}
          </div>
          ${commentsHtml}
        </div>
      </article>`;
      }

      function render(hasMore) {
        document.getElementById('loadingSkeleton').classList.add('hidden');
        const feed = document.getElementById('feed');
        feed.classList.remove('hidden');
        const visible = filtered.slice(0, page * PAGE_SIZE);
        feed.innerHTML = visible.length
          ? visible.map((p, i) => renderPost(p, i)).join('')
          : `<div class="py-16 text-center text-on-surface-variant text-sm">No posts found.</div>`;
        const btn = document.getElementById('loadMoreBtn');
        btn.classList.remove('hidden');
        const allLoaded = !hasMore && filtered.length <= page * PAGE_SIZE;
        btn.disabled = allLoaded;
        btn.textContent = allLoaded ? 'All posts loaded' : 'Load more posts';
        btn.style.opacity = allLoaded ? '0.4' : '1';
      }

      return {
        async init() { await fetchPosts(); },
        filter() { page = 1; filtered = localFilter(posts); fetchPosts(); },
        setFilter(cat, el) {
          currentFilter = cat;
          document.querySelectorAll('.filter-chip').forEach(b => b.classList.remove('active'));
          el.classList.add('active');
          page = 1; this.filter();
        },
        loadMore() { page++; fetchPosts(); },
        toggleExpand(id) {
          const p = posts.find(x => x.id === id);
          if (p) { p.expanded = !p.expanded; render(false); }
        },
        // sends to backend/react.php → inserts into reactions table
        async toggleLike(id) {
          const p = posts.find(x => x.id === id);
          if (!p) return;
          p.liked = !p.liked; p.likes += p.liked ? 1 : -1; render(false);
          try {
            await fetch('../../../backend/pages/posts/react.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: `post_id=${id}&type=like&action=${p.liked ? 'add' : 'remove'}`
            });
          } catch { }
        },
        // fetches from backend/comments.php?post_id=X
        async toggleComments(id) {
          const p = posts.find(x => x.id === id);
          if (!p) return;
          p.showComments = !p.showComments;
          if (p.showComments && p.comments.length === 0) {
            try {
              const res = await fetch(`../../../backend/pages/posts/comments.php?post_id=${id}`);
              const data = await res.json();
              p.comments = (data.comments || []).map(c => ({
                author: sanitize(c.username),
                initials: getInitials(c.username),
                bg: getAvatarColor(c.user_id).bg,
                color: getAvatarColor(c.user_id).color,
                text: sanitize(c.content)
              }));
              p.commentsCount = p.comments.length;
            } catch { }
          }
          render(false);
          if (p.showComments) setTimeout(() => document.getElementById(`cinput-${id}`)?.focus(), 50);
        },
        updateComment(id, val) { const p = posts.find(x => x.id === id); if (p) p.newComment = val; },
        // POST to backend/comments.php → inserts comment row
        async addComment(id) {
          const p = posts.find(x => x.id === id);
          if (!p) return;
          const text = (p.newComment || '').trim();
          if (!text) return;
          const av = getAvatarColor(currentUser.id || 0);
          p.comments.push({ author: currentUser.username || 'You', initials: getInitials(currentUser.username), bg: av.bg, color: av.color, text: sanitize(text) });
          p.commentsCount++; p.newComment = ''; render(false);
          try {
            await fetch('../../../backend/pages/posts/comments.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: `post_id=${id}&content=${encodeURIComponent(text)}`
            });
          } catch { }
        },
        sharePost(id) {
          const p = posts.find(x => x.id === id);
          if (!p) return;
          navigator.share?.({ title: `${p.author} on ENSA Connect`, text: p.content.slice(0, 100) })
            || navigator.clipboard?.writeText(window.location.href + '#post-' + id);
        }
      };
    })();

    // ─────────────────────────────────────────────
    // CREATE POST — submits to backend/posts.php
    // body: content, image_url, TYPE, category
    // ─────────────────────────────────────────────
    document.getElementById('createPostForm').addEventListener('submit', async e => {
      e.preventDefault();
      const content = document.getElementById('postContent').value.trim();
      const errEl = document.getElementById('postError');
      const submitBtn = e.target.querySelector('button[type="submit"]');

      errEl.classList.add('hidden');
      if (!content) { errEl.textContent = 'Content is required.'; errEl.classList.remove('hidden'); return; }

      const formData = new FormData();
      formData.append('content', content);
      formData.append('TYPE', document.getElementById('postType').value);
      // Determine category (could be more dynamic if we had a category select)
      formData.append('category', document.getElementById('postType').value === 'status' ? 'general' : document.getElementById('postType').value);

      UI.selectedFiles.forEach(file => {
        formData.append('attachments[]', file);
      });

      try {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Publishing...';

        const res = await fetch('../../../backend/pages/posts/create_post.php', {
          method: 'POST',
          body: formData // No Content-Type header! Let the browser set multipart/form-data
        });

        const data = await res.json();
        if (data.error) throw new Error(data.error);

        document.getElementById('createPostModal').classList.add('hidden');
        document.getElementById('createPostForm').reset();
        document.getElementById('attachment-preview').innerHTML = '';
        UI.selectedFiles = [];

        PostManager.setFilter('all', document.querySelector('.filter-chip'));
      } catch (err) {
        errEl.textContent = err.message || 'Failed to post. Try again.';
        errEl.classList.remove('hidden');
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Publish';
      }
    });

    // ─────────────────────────────────────────────
    // INIT
    // ─────────────────────────────────────────────
    (async () => {
      await loadSession();
      await PostManager.init();

      // Local mobile bottom nav update
      document.querySelectorAll('nav.md\\:hidden a').forEach(a => {
        if (a.getAttribute('href') === '../chat/index.php') a.setAttribute('href', '../chat/index.php');
      });
    })();
  </script>
</body>

</html>