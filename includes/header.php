<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['user'] ?? null;
?>
<header class="sticky top-0 z-50 w-full bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-[#D8EAF7] dark:border-slate-800">
  <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-2">
    
    <!-- Left: Brand Logo & Title -->
    <div class="flex items-center gap-3 shrink-0">
      <a href="index.php" class="flex items-center gap-2.5 group focus:outline-none">
        <div class="relative flex items-center justify-center w-9 h-9 rounded-xl bg-brand-pastel text-brand-deep shadow-sm group-hover:scale-105 transition-all">
          <span class="material-symbols-outlined text-[20px]">hub</span>
          <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-emerald-500 border border-white"></span>
        </div>
        <div class="flex flex-col">
          <div class="flex items-center gap-1">
            <span class="font-headline font-extrabold text-lg tracking-tight text-slate-900 dark:text-white leading-none">C2C</span>
            <span class="px-1.5 py-0.2 rounded bg-brand-pastel/80 dark:bg-sky-900/50 text-[9px] font-bold text-brand-deep dark:text-sky-300 uppercase">Hub</span>
          </div>
          <span class="text-[9px] font-semibold text-slate-400 dark:text-slate-400 tracking-wider uppercase">Campus2Community</span>
        </div>
      </a>

      <div class="hidden 2xl:flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/90 dark:bg-slate-800/80 border border-[#D0E5F5] dark:border-slate-700 shadow-sm">
        <span class="relative flex h-1.5 w-1.5">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
        </span>
        <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase">Live Mesh</span>
      </div>
    </div>

    <!-- Center: Navigation Links -->
    <nav class="hidden lg:flex items-center p-1 rounded-full bg-[#EBF5FC]/90 dark:bg-slate-900/90 border border-[#CAE7F7] dark:border-slate-800 gap-0.5 shadow-sm shrink-0" id="main-nav">
      <a href="index.php#home" class="nav-pill active whitespace-nowrap px-3 py-1.5 rounded-full text-[12px] font-bold text-slate-700 dark:text-slate-300 hover:text-brand-deep transition-all flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[15px] leading-none">home</span>
        <span>Home</span>
      </a>
      <a href="index.php#challenges" class="nav-pill whitespace-nowrap px-3 py-1.5 rounded-full text-[12px] font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-deep transition-all flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[15px] leading-none">report_problem</span>
        <span>Challenges</span>
      </a>
      <a href="index.php#ai-matching" class="nav-pill whitespace-nowrap px-3 py-1.5 rounded-full text-[12px] font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-deep transition-all flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[15px] leading-none">psychology</span>
        <span>AI Match</span>
      </a>
      <a href="index.php#dashboards" class="nav-pill whitespace-nowrap px-3 py-1.5 rounded-full text-[12px] font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-deep transition-all flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[15px] leading-none">space_dashboard</span>
        <span>Portals</span>
      </a>
      <a href="index.php#impact-analytics" class="nav-pill whitespace-nowrap px-3 py-1.5 rounded-full text-[12px] font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-deep transition-all flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[15px] leading-none">analytics</span>
        <span>Analytics</span>
      </a>
      <a href="about.php" class="nav-pill whitespace-nowrap px-3 py-1.5 rounded-full text-[12px] font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-deep transition-all flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[15px] leading-none">info</span>
        <span>About Us</span>
      </a>
    </nav>

    <!-- Right: Actions & User Session -->
    <div class="flex items-center gap-2 shrink-0">
      
      <!-- Theme Switcher -->
      <button onclick="toggleTheme()" id="theme-toggle-btn" class="w-8 h-8 rounded-xl flex items-center justify-center bg-white dark:bg-slate-800 text-slate-600 dark:text-amber-400 border border-[#D5E6F5] dark:border-slate-700 shadow-sm transition-all" title="Toggle Theme">
        <span class="material-symbols-outlined text-[18px]" id="theme-icon">dark_mode</span>
      </button>

      <!-- Multilingual Dropdown -->
      <div class="relative">
        <button onclick="toggleLangDropdown()" id="lang-toggle-btn" class="h-8 px-2.5 rounded-xl flex items-center gap-1.5 bg-white/90 dark:bg-slate-800/90 text-slate-700 dark:text-slate-200 hover:bg-brand-pastel hover:text-brand-deep border border-[#D5E6F5] dark:border-slate-700 shadow-sm text-xs font-bold transition-all">
          <span class="material-symbols-outlined text-[17px] text-brand-primary">g_translate</span>
          <span id="current-lang-code">EN</span>
          <span class="material-symbols-outlined text-[14px] text-slate-400">expand_more</span>
        </button>
        <div id="lang-dropdown" class="hidden absolute right-0 mt-2 w-44 rounded-2xl glass-card p-2 shadow-xl z-50 border border-[#CAE7F7] dark:border-slate-700">
          <div class="text-[10px] font-bold text-slate-400 px-3 py-1 uppercase">Select Language</div>
          <button onclick="setLanguage('en')" class="w-full text-left px-3 py-2 rounded-xl text-xs font-semibold hover:bg-brand-tint flex items-center justify-between">
            <span>🇬🇧 English</span><span class="text-[10px] font-mono text-slate-400">EN</span>
          </button>
          <button onclick="setLanguage('hi')" class="w-full text-left px-3 py-2 rounded-xl text-xs font-semibold hover:bg-brand-tint flex items-center justify-between">
            <span>🇮🇳 हिन्दी</span><span class="text-[10px] font-mono text-slate-400">HI</span>
          </button>
          <button onclick="setLanguage('mr')" class="w-full text-left px-3 py-2 rounded-xl text-xs font-semibold hover:bg-brand-tint flex items-center justify-between">
            <span>🇮🇳 मराठी</span><span class="text-[10px] font-mono text-slate-400">MR</span>
          </button>
        </div>
      </div>

      <?php if ($user): ?>
        <!-- Logged-in User Profile Dropdown -->
        <div class="relative group">
          <button class="flex items-center gap-2 p-1 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E6F5] dark:border-slate-700 shadow-sm">
            <div class="w-7 h-7 rounded-lg bg-brand-primary text-white font-bold text-xs flex items-center justify-center">
              <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
            </div>
            <div class="hidden md:flex flex-col text-left pr-1">
              <span class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate max-w-[110px]"><?= htmlspecialchars($user['full_name']) ?></span>
              <span class="text-[9px] font-mono text-emerald-600 dark:text-emerald-400 uppercase"><?= htmlspecialchars($user['role']) ?> Verified</span>
            </div>
          </button>
          <div class="hidden group-hover:block absolute right-0 mt-1 w-56 rounded-2xl glass-card p-3 shadow-xl z-50 border border-[#CAE7F7] dark:border-slate-700">
            <div class="pb-2 mb-2 border-b border-slate-200 dark:border-slate-700">
              <p class="text-xs font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($user['full_name']) ?></p>
              <p class="text-[10px] font-mono text-slate-400">Aadhaar: <?= htmlspecialchars($user['aadhaar_number']) ?></p>
              <p class="text-[10px] text-brand-primary font-semibold truncate"><?= htmlspecialchars($user['institution_name']) ?></p>
            </div>
            <a href="login.php?action=logout" class="w-full text-left px-3 py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 flex items-center gap-2">
              <span class="material-symbols-outlined text-[16px]">logout</span>
              <span>Sign Out</span>
            </a>
          </div>
        </div>
      <?php else: ?>
        <!-- Sign In & Register Buttons -->
        <a href="login.php" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E6F5] dark:border-slate-700 text-slate-800 dark:text-slate-200 hover:bg-brand-tint font-headline text-xs font-bold shadow-sm transition-all whitespace-nowrap">
          <span class="material-symbols-outlined text-[16px] text-brand-primary">login</span>
          <span>Sign In</span>
        </a>
        <a href="register.php" class="hidden sm:inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-gradient-to-r from-sky-600 to-brand-primary text-white font-headline text-xs font-bold shadow-sm hover:shadow-md transition-all whitespace-nowrap">
          <span class="material-symbols-outlined text-[16px]">person_add</span>
          <span>Register</span>
        </a>
      <?php endif; ?>

    </div>
  </div>
</header>
