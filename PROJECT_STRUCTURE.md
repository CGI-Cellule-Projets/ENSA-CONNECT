# 📂 Project Structure - ENSA Connect (Updated)

This document provides a comprehensive map of the project files after the restructuring.

## 🌳 Directory Tree

```text
ENSA-CONNECT/
├── 📁 backend/                 # Server-side logic and configuration
│   ├── 📁 global/              # Shared logic and connections
│   │   ├── 📁 config/          # Database configuration
│   │   ├── db.php              # Centralized database connection
│   │   └── start.php           # Bootstrapping script
│   ├── 📁 middleware/          # Security and request handlers
│   │   ├── RateLimiter.php     # Request frequency control
│   │   └── XSSProtection.php   # Input sanitization
│   └── 📁 pages/               # Specific backend logic per page
│       ├── 📁 auth/            # Auth logic (login, register, etc.)
│       ├── 📁 chat/            # Chat logic (send, bin, src)
│       └── 📁 posts/           # Social feed logic (get_posts, etc.)
├── 📁 frontend/                # User interface and assets
│   ├── 📁 assets/              # Global shared assets
│   │   ├── 📁 css/             # Common styles
│   │   ├── 📁 js/              # Common scripts
│   │   └── 📁 images/          # Global images
│   └── 📁 pages/               # Individual feature pages
│       ├── 📁 auth/            # Authentication UI (login.php)
│       ├── 📁 chat/            # Chat UI (index.php)
│       └── 📁 newsfeed/        # Newsfeed UI (index.php)
├── Dockerfile                  # Containerization instructions
├── composer.json               # Main PHP dependencies
├── database.sql                # Full database schema export
├── ensa_connect_backup.sql     # Database backup
├── index.php                   # Application entry point
├── start.sh                    # Environment setup script
└── README.md                   # Project overview
```

## 🛠️ Key Components Breakdown

### 1. **Application Core**
- **`index.php`**: Entry point, ideally handles redirects to `frontend/pages/auth/login.php`.
- **`backend/global/db.php`**: Single source of truth for database connections.
- **`backend/middleware/`**: Security layers to protect against XSS and Rate Limiting.

### 2. **Frontend Pages**
- Each page folder is self-contained with its own `scripts/`, `styles/`, and `images/` directory, while using global assets from `frontend/assets/` where applicable.

### 3. **Backend Logic**
- Logic files are now grouped by feature under `backend/pages/`, making it easier to manage API endpoints and their corresponding frontend files.

---
*Updated by Antigravity*
