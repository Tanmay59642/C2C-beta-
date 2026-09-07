<?php
require_once __DIR__ . '/db.php';
$metrics = getImpactMetrics();
$tickets = getCivicTickets();
$telemetry = getSensorTelemetry();
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>CAMPUS2COMMUNITY (C2C) | Civic Innovation Platform</title>
  
  <!-- Modern Typography & Material Symbols Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            // Theme centered on the user's provided soft sky-blue photo (#CAE7F7)
            brand: {
              pastel: "#CAE7F7",       // Exact photo circle hex
              tint: "#EBF5FC",         // Soft sky aura tint
              ice: "#F0F7FD",          // Very light sky canvas
              accent: "#38BDF8",       // Bright sky
              primary: "#0284C7",      // Ocean sky 600
              deep: "#0369A1",         // Deep sky 700
              navy: "#0C4A6E"          // Deep contrast text
            },
            surface: {
              DEFAULT: "#F4F9FD",
              lowest: "#FFFFFF",
              low: "#EDF5FB",
              container: "#FFFFFF",
              high: "#E4F0FA",
              highest: "#D4E8F7"
            },
            outline: {
              DEFAULT: "#94A3B8",
              variant: "#CBD5E1",
              border: "#CAE7F7"
            }
          },
          fontFamily: {
            headline: ["Plus Jakarta Sans", "sans-serif"],
            body: ["Inter", "sans-serif"],
            mono: ["JetBrains Mono", "monospace"]
          },
          boxShadow: {
            'sky-sm': '0 2px 8px -2px rgba(2, 132, 199, 0.08), 0 1px 4px -1px rgba(202, 231, 247, 0.4)',
            'sky-md': '0 8px 24px -4px rgba(2, 132, 199, 0.10), 0 4px 12px -2px rgba(202, 231, 247, 0.6)',
            'sky-lg': '0 16px 36px -6px rgba(2, 132, 199, 0.14), 0 8px 20px -4px rgba(202, 231, 247, 0.7)',
            'sky-glow': '0 0 24px 2px rgba(202, 231, 247, 0.85)'
          }
        }
      }
    };
  </script>

  <style>
    html {
      scroll-behavior: smooth;
    }
    body {
      font-family: 'Inter', sans-serif;
      overflow-x: hidden;
    }
    h1, h2, h3, h4, h5, h6 {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    /* Dedicated section scroll offset for fixed navbar */
    section {
      scroll-margin-top: 4.5rem;
    }

    /* Ultra-Glossy High-Reflectivity Liquid Glass System */
    .glass-sky {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.72) 0%, rgba(240, 247, 253, 0.55) 100%) !important;
      backdrop-filter: blur(28px) saturate(210%) contrast(105%);
      -webkit-backdrop-filter: blur(28px) saturate(210%) contrast(105%);
      border: 1px solid rgba(255, 255, 255, 0.95) !important;
      border-top: 1.5px solid rgba(255, 255, 255, 1) !important;
      box-shadow: 
        0 10px 40px 0 rgba(2, 132, 199, 0.12), 
        inset 0 2px 1px 0 rgba(255, 255, 255, 1), 
        inset 0 -1.5px 1px 0 rgba(202, 231, 247, 0.6);
    }
    .dark .glass-sky {
      background: linear-gradient(135deg, rgba(15, 28, 48, 0.75) 0%, rgba(7, 17, 31, 0.6) 100%) !important;
      backdrop-filter: blur(28px) saturate(210%) contrast(105%);
      -webkit-backdrop-filter: blur(28px) saturate(210%) contrast(105%);
      border: 1px solid rgba(56, 189, 248, 0.38) !important;
      border-top: 1.5px solid rgba(56, 189, 248, 0.65) !important;
      box-shadow: 
        0 10px 40px 0 rgba(0, 0, 0, 0.6), 
        inset 0 2px 1px 0 rgba(56, 189, 248, 0.45), 
        inset 0 -1.5px 1px 0 rgba(255, 255, 255, 0.1);
    }

    .glass-card, .liquid-glass {
      background: linear-gradient(140deg, rgba(255, 255, 255, 0.58) 0%, rgba(235, 245, 252, 0.38) 100%) !important;
      backdrop-filter: blur(26px) saturate(220%) contrast(104%);
      -webkit-backdrop-filter: blur(26px) saturate(220%) contrast(104%);
      border: 1px solid rgba(255, 255, 255, 0.88) !important;
      border-top: 1.8px solid rgba(255, 255, 255, 0.98) !important;
      box-shadow: 
        0 16px 44px -6px rgba(2, 132, 199, 0.14),
        0 4px 16px -2px rgba(202, 231, 247, 0.5),
        inset 0 2px 1px 0 rgba(255, 255, 255, 1),
        inset 0 -1.5px 1px 0 rgba(202, 231, 247, 0.6);
      position: relative;
    }
    .dark .glass-card, .dark .liquid-glass {
      background: linear-gradient(140deg, rgba(15, 30, 52, 0.62) 0%, rgba(8, 18, 34, 0.45) 100%) !important;
      backdrop-filter: blur(26px) saturate(220%) contrast(104%);
      -webkit-backdrop-filter: blur(26px) saturate(220%) contrast(104%);
      border: 1px solid rgba(56, 189, 248, 0.35) !important;
      border-top: 1.8px solid rgba(56, 189, 248, 0.7) !important;
      box-shadow: 
        0 16px 44px -6px rgba(0, 0, 0, 0.55),
        0 4px 16px -2px rgba(56, 189, 248, 0.15),
        inset 0 2px 1px 0 rgba(56, 189, 248, 0.45),
        inset 0 -1.5px 1px 0 rgba(255, 255, 255, 0.08);
      position: relative;
    }

    /* Glossy Specular Curved Sheen */
    .glass-card::after, .liquid-glass::after {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 48%;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.65) 0%, rgba(255, 255, 255, 0.22) 35%, transparent 70%);
      pointer-events: none;
      border-top-left-radius: inherit;
      border-top-right-radius: inherit;
    }
    .dark .glass-card::after, .dark .liquid-glass::after {
      background: linear-gradient(135deg, rgba(56, 189, 248, 0.35) 0%, rgba(255, 255, 255, 0.06) 35%, transparent 70%);
    }

    /* Active & Hover Nav link styling */
    .nav-pill {
      transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .nav-pill.active {
      background-color: #CAE7F7;
      color: #0369A1;
      font-weight: 700;
      box-shadow: 0 1px 4px rgba(2, 132, 199, 0.15);
    }
    .dark .nav-pill.active {
      background-color: #0284C7;
      color: #FFFFFF;
    }
    .nav-pill:not(.active):hover {
      background-color: rgba(255, 255, 255, 0.9);
      color: #0284C7;
    }
    .dark .nav-pill:not(.active):hover {
      background-color: rgba(30, 41, 59, 0.8);
      color: #38BDF8;
    }

    /* Hide scrollbars cleanly */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    ::-webkit-scrollbar-track {
      background: #F0F7FD;
    }
    ::-webkit-scrollbar-thumb {
      background: #CAE7F7;
      border-radius: 9999px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #93C5FD;
    }

    /* Ambient Animated Aurora Glows (Smooth breathing and drifting in dark mode) */
    @keyframes orb-float-1 {
      0%, 100% {
        transform: translate(0px, 0px) scale(1) rotate(0deg);
      }
      33% {
        transform: translate(50px, -40px) scale(1.18) rotate(20deg);
      }
      66% {
        transform: translate(-40px, 30px) scale(0.90) rotate(-15deg);
      }
    }

    @keyframes orb-float-2 {
      0%, 100% {
        transform: translate(0px, 0px) scale(1) rotate(0deg);
      }
      33% {
        transform: translate(-45px, 45px) scale(1.22) rotate(-25deg);
      }
      66% {
        transform: translate(40px, -30px) scale(0.92) rotate(20deg);
      }
    }

    @keyframes orb-float-3 {
      0%, 100% {
        transform: translate(-50%, 0px) scale(1);
        opacity: 0.7;
      }
      50% {
        transform: translate(-50%, 35px) scale(1.12);
        opacity: 1;
      }
    }

    @keyframes aurora-pulse {
      0%, 100% {
        opacity: 0.55;
        filter: blur(100px);
      }
      50% {
        opacity: 0.85;
        filter: blur(120px);
      }
    }

    .animate-orb-1 {
      animation: orb-float-1 16s ease-in-out infinite, aurora-pulse 9s ease-in-out infinite;
      will-change: transform, opacity, filter;
    }

    .animate-orb-2 {
      animation: orb-float-2 20s ease-in-out infinite, aurora-pulse 11s ease-in-out infinite;
      will-change: transform, opacity, filter;
    }

    .animate-orb-3 {
      animation: orb-float-3 14s ease-in-out infinite;
      will-change: transform, opacity;
    }

    /* Shimmering animated text gradient */
    @keyframes gradient-shimmer {
      0% {
        background-position: 0% 50%;
      }
      50% {
        background-position: 100% 50%;
      }
      100% {
        background-position: 0% 50%;
      }
    }
    .animate-gradient-text {
      background-size: 200% auto;
      animation: gradient-shimmer 6s linear infinite;
    }

    /* ========================================================================== */
    /* INTERACTIVE ANIMATION & MICRO-INTERACTION ENGINE STYLES                    */
    /* ========================================================================== */
    .reveal-init {
      opacity: 0;
      transform: translateY(32px);
      transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
      will-change: opacity, transform;
    }
    .reveal-scale {
      opacity: 0;
      transform: scale(0.92);
      transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
      will-change: opacity, transform;
    }
    .reveal-left {
      opacity: 0;
      transform: translateX(-36px);
      transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
      will-change: opacity, transform;
    }
    .reveal-right {
      opacity: 0;
      transform: translateX(36px);
      transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
      will-change: opacity, transform;
    }
    .reveal-visible {
      opacity: 1 !important;
      transform: translate(0, 0) scale(1) !important;
    }

    .stagger-1 { transition-delay: 80ms; }
    .stagger-2 { transition-delay: 160ms; }
    .stagger-3 { transition-delay: 240ms; }
    .stagger-4 { transition-delay: 320ms; }
    .stagger-5 { transition-delay: 400ms; }
    .stagger-6 { transition-delay: 480ms; }

    .tilt-card {
      transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease;
      transform-style: preserve-3d;
      will-change: transform;
    }

    .radial-spotlight {
      position: relative;
      overflow: hidden;
    }
    .radial-spotlight::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: radial-gradient(500px circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(56, 189, 248, 0.12), transparent 45%);
      pointer-events: none;
      opacity: 0;
      transition: opacity 0.35s ease;
      z-index: 2;
    }
    .radial-spotlight:hover::before {
      opacity: 1;
    }

    .magnetic-btn {
      transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease;
      will-change: transform;
    }

    .ripple-target {
      position: relative;
      overflow: hidden;
    }
    .ripple-circle {
      position: absolute;
      border-radius: 50%;
      background: rgba(56, 189, 248, 0.35);
      transform: scale(0);
      animation: ripple-anim 0.65s ease-out;
      pointer-events: none;
    }
    @keyframes ripple-anim {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }

    @keyframes float-gentle {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-6px); }
    }
    .animate-float-gentle {
      animation: float-gentle 4s ease-in-out infinite;
    }

    @keyframes pulse-glow {
      0%, 100% { box-shadow: 0 0 15px rgba(56, 189, 248, 0.2); }
      50% { box-shadow: 0 0 30px rgba(56, 189, 248, 0.5); }
    }
    .animate-pulse-glow {
      animation: pulse-glow 3s ease-in-out infinite;
    }

    @media (prefers-reduced-motion: reduce) {
      .reveal-init, .reveal-scale, .reveal-left, .reveal-right {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
      }
      .tilt-card, .magnetic-btn {
        transform: none !important;
      }
    }
  </style>
</head>

<body class="bg-[#F4F9FD] dark:bg-[#07111F] text-slate-800 dark:text-slate-100 min-h-screen selection:bg-brand-pastel selection:text-brand-deep transition-colors duration-300">

  <!-- ========================================================================= -->
  <!-- NAVIGATION BAR: NEAT, CLEAN, PROPER SPACING & DEDICATED SECTIONS          -->
  <!-- ========================================================================= -->
  <header class="fixed top-0 left-0 right-0 z-50 glass-sky border-b border-[#D8E8F5] dark:border-slate-800 transition-all duration-300 shadow-sm">
    <div class="h-16 w-full max-w-[1520px] mx-auto px-3 sm:px-6 lg:px-8 flex items-center justify-between gap-2 lg:gap-3">
      
      <!-- Left: Logo & Live Status Badge -->
      <div class="flex items-center gap-2.5 shrink-0">
        <a href="#home" class="flex items-center gap-2 group focus:outline-none">
          <div class="relative flex items-center justify-center w-9 h-9 rounded-xl bg-brand-pastel text-brand-deep shadow-sm group-hover:scale-105 group-hover:bg-[#B5DCF5] transition-all">
            <span class="material-symbols-outlined text-[20px]">hub</span>
            <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-emerald-500 border border-white"></span>
          </div>
          <div class="flex flex-col">
            <div class="flex items-center gap-1">
              <span class="font-headline font-extrabold text-lg tracking-tight text-slate-900 dark:text-white leading-none">C2C</span>
              <span class="px-1.5 py-0.2 rounded bg-brand-pastel/80 dark:bg-sky-900/50 text-[9px] font-bold text-brand-deep dark:text-sky-300 uppercase tracking-wide">Hub</span>
            </div>
            <span class="text-[9px] font-semibold text-slate-400 dark:text-slate-400 tracking-wider uppercase">Campus2Community</span>
          </div>
        </a>

        <!-- Compact Live Indicator for extra wide screens -->
        <div class="hidden 2xl:flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/90 dark:bg-slate-800/80 border border-[#D0E5F5] dark:border-slate-700 shadow-sm">
          <span class="relative flex h-1.5 w-1.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
          </span>
          <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Live Mesh</span>
        </div>
      </div>

      <!-- Center: Clean, Neatly Spaced Navigation Bar with Separate Section Pills -->
      <nav class="hidden lg:flex items-center p-1 rounded-full bg-[#EBF5FC]/90 dark:bg-slate-900/90 border border-[#CAE7F7] dark:border-slate-800 gap-0.5 shadow-sm shrink-0" id="main-nav">
        <a href="#home" class="nav-pill active whitespace-nowrap px-3 py-1.5 rounded-full text-[12px] font-bold text-slate-700 dark:text-slate-300 hover:text-brand-deep transition-all flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[15px] leading-none">home</span>
          <span data-i18n="nav_home">Home</span>
        </a>
        <a href="#challenges" class="nav-pill whitespace-nowrap px-3 py-1.5 rounded-full text-[12px] font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-deep transition-all flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[15px] leading-none">report_problem</span>
          <span data-i18n="nav_challenges">Challenges</span>
        </a>
        <a href="#ai-matching" class="nav-pill whitespace-nowrap px-3 py-1.5 rounded-full text-[12px] font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-deep transition-all flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[15px] leading-none">psychology</span>
          <span data-i18n="nav_ai">AI Match</span>
        </a>
        <a href="#ecosystem-flow" class="nav-pill whitespace-nowrap px-3 py-1.5 rounded-full text-[12px] font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-deep transition-all flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[15px] leading-none">account_tree</span>
          <span data-i18n="nav_ecosystem">Ecosystem</span>
        </a>
        <a href="#core-modules" class="nav-pill whitespace-nowrap px-3 py-1.5 rounded-full text-[12px] font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-deep transition-all flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[15px] leading-none">dashboard_customize</span>
          <span data-i18n="nav_modules">Modules</span>
        </a>
        <a href="#dashboards" class="nav-pill whitespace-nowrap px-3 py-1.5 rounded-full text-[12px] font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-deep transition-all flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[15px] leading-none">space_dashboard</span>
          <span data-i18n="nav_portals">Portals</span>
        </a>
        <a href="#tracking" class="nav-pill whitespace-nowrap px-3 py-1.5 rounded-full text-[12px] font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-deep transition-all flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[15px] leading-none">timeline</span>
          <span data-i18n="nav_tracking">Tracking</span>
        </a>
        <a href="#impact-analytics" class="nav-pill whitespace-nowrap px-3 py-1.5 rounded-full text-[12px] font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-deep transition-all flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[15px] leading-none">analytics</span>
          <span data-i18n="nav_analytics">Analytics</span>
        </a>
        <a href="about.html" class="nav-pill whitespace-nowrap px-3 py-1.5 rounded-full text-[12px] font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-deep transition-all flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[15px] leading-none">info</span>
          <span data-i18n="nav_about">About Us</span>
        </a>
      </nav>

      <!-- Right: Action Buttons (Search, Notifications, Theme Toggle, Primary CTA, Profile) -->
      <div class="flex items-center gap-2 shrink-0">
        
        <!-- Search Trigger Button -->
        <button onclick="toggleSearchModal()" aria-label="Search Ecosystem" class="w-8 h-8 rounded-xl flex items-center justify-center bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-brand-pastel hover:text-brand-deep border border-[#D5E6F5] dark:border-slate-700 shadow-sm transition-all" title="Search System (Ctrl+K)">
          <span class="material-symbols-outlined text-[18px]">search</span>
        </button>

        <!-- Notification Bell with Dropdown Toggle -->
        <div class="relative">
          <button onclick="toggleNotificationDropdown()" aria-label="Notifications" class="relative w-8 h-8 rounded-xl flex items-center justify-center bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-brand-pastel hover:text-brand-deep border border-[#D5E6F5] dark:border-slate-700 shadow-sm transition-all">
            <span class="material-symbols-outlined text-[18px]">notifications</span>
            <span class="absolute -top-1 -right-1 min-w-[15px] h-[15px] px-0.5 rounded-full bg-brand-primary text-white font-bold text-[8px] flex items-center justify-center border border-white dark:border-slate-800">4</span>
          </button>
          <!-- Notification Dropdown Panel -->
          <div id="notification-dropdown" class="hidden absolute right-0 mt-3 w-80 sm:w-96 rounded-2xl glass-card p-4 shadow-xl z-50">
            <div class="flex items-center justify-between pb-3 border-b border-[#E2EFF8] dark:border-slate-700 mb-3">
              <div class="flex items-center gap-2">
                <span class="font-headline font-bold text-sm text-slate-900 dark:text-white">Live Dispatches</span>
                <span class="px-2 py-0.5 rounded-full bg-brand-pastel text-brand-deep text-[10px] font-bold">4 New</span>
              </div>
              <button onclick="toggleNotificationDropdown()" class="text-slate-400 hover:text-slate-600 text-xs">Close</button>
            </div>
            <div class="space-y-2.5 max-h-72 overflow-y-auto pr-1">
              <div class="p-2.5 rounded-xl bg-brand-tint/60 dark:bg-slate-800/60 border border-[#D8EAF7] dark:border-slate-700 flex items-start gap-2.5">
                <span class="material-symbols-outlined text-brand-primary text-[18px] mt-0.5">verified_user</span>
                <div>
                  <p class="text-[12px] font-medium text-slate-800 dark:text-slate-200"><span class="font-bold text-brand-deep">Maharashtra BMC</span> approved pilot testing for arsenic filtration.</p>
                  <span class="text-[10px] text-slate-400">3 mins ago • SPRINT-C2C-881</span>
                </div>
              </div>
              <div class="p-2.5 rounded-xl bg-white dark:bg-slate-800/40 border border-[#E5F0FA] dark:border-slate-700 flex items-start gap-2.5">
                <span class="material-symbols-outlined text-emerald-500 text-[18px] mt-0.5">payments</span>
                <div>
                  <p class="text-[12px] font-medium text-slate-800 dark:text-slate-200">Tata CSR released <span class="font-bold text-emerald-600">₹2.5L Escrow Milestone</span> to student teams.</p>
                  <span class="text-[10px] text-slate-400">18 mins ago</span>
                </div>
              </div>
              <div class="p-2.5 rounded-xl bg-white dark:bg-slate-800/40 border border-[#E5F0FA] dark:border-slate-700 flex items-start gap-2.5">
                <span class="material-symbols-outlined text-indigo-500 text-[18px] mt-0.5">neurology</span>
                <div>
                  <p class="text-[12px] font-medium text-slate-800 dark:text-slate-200">New challenge C2C-2026-904 matched with IIT Bombay labs.</p>
                  <span class="text-[10px] text-slate-400">42 mins ago</span>
                </div>
              </div>
            </div>
            <a href="#impact-analytics" onclick="toggleNotificationDropdown()" class="mt-3 block text-center py-2 rounded-xl bg-brand-tint dark:bg-slate-800 text-[11px] font-bold text-brand-deep dark:text-sky-300 hover:bg-brand-pastel transition-colors">
              View All Stream Telemetry →
            </a>
          </div>
        </div>

        <!-- Light / Dark Theme Switcher (Defaults to Sky Light Mode matching the photo) -->
        <button onclick="toggleTheme()" id="theme-toggle-btn" aria-label="Toggle Theme" class="w-8 h-8 rounded-xl flex items-center justify-center bg-white dark:bg-slate-800 text-slate-600 dark:text-amber-400 hover:bg-brand-pastel hover:text-brand-deep border border-[#D5E6F5] dark:border-slate-700 shadow-sm transition-all" title="Toggle Theme">
          <span class="material-symbols-outlined text-[18px]" id="theme-icon">dark_mode</span>
        </button>

        <!-- Multilingual Language Selector Dropdown (EN, HI, MR) -->
        <div class="relative">
          <button onclick="toggleLangDropdown()" id="lang-toggle-btn" aria-label="Select Language" class="h-8 px-2.5 rounded-xl flex items-center gap-1.5 bg-white/90 dark:bg-slate-800/90 text-slate-700 dark:text-slate-200 hover:bg-brand-pastel hover:text-brand-deep border border-[#D5E6F5] dark:border-slate-700 shadow-sm transition-all text-xs font-bold" title="Translate Language (English, Hindi, Marathi)">
            <span class="material-symbols-outlined text-[17px] text-brand-primary dark:text-sky-400">g_translate</span>
            <span id="current-lang-code" class="uppercase">EN</span>
            <span class="material-symbols-outlined text-[14px] text-slate-400">expand_more</span>
          </button>
          <!-- Language Selection Menu -->
          <div id="lang-dropdown" class="hidden absolute right-0 mt-2 w-44 rounded-2xl glass-card p-2 shadow-xl z-50 border border-[#CAE7F7] dark:border-slate-700">
            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-400 px-3 py-1 uppercase tracking-wider">Select Language</div>
            <button onclick="setLanguage('en')" class="w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-slate-800 dark:text-slate-200 hover:bg-brand-tint dark:hover:bg-slate-800 flex items-center justify-between transition-colors">
              <span class="flex items-center gap-2"><span>🇬🇧</span> <span>English</span></span>
              <span class="text-[10px] font-mono text-slate-400">EN</span>
            </button>
            <button onclick="setLanguage('hi')" class="w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-slate-800 dark:text-slate-200 hover:bg-brand-tint dark:hover:bg-slate-800 flex items-center justify-between transition-colors">
              <span class="flex items-center gap-2"><span>🇮🇳</span> <span>हिन्दी</span></span>
              <span class="text-[10px] font-mono text-slate-400">HI</span>
            </button>
            <button onclick="setLanguage('mr')" class="w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-slate-800 dark:text-slate-200 hover:bg-brand-tint dark:hover:bg-slate-800 flex items-center justify-between transition-colors">
              <span class="flex items-center gap-2"><span>🇮🇳</span> <span>मराठी</span></span>
              <span class="text-[10px] font-mono text-slate-400">MR</span>
            </button>
          </div>
        </div>

        <!-- Primary Call to Action Button: "Report Problem" -->
        <a href="#challenges" class="relative group hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-sky-600 to-brand-primary text-white font-headline text-xs font-bold shadow-sm hover:shadow-md hover:scale-[1.02] active:scale-95 transition-all whitespace-nowrap">
          <span class="material-symbols-outlined text-[16px]">add_circle</span>
          <span data-i18n="btn_report_problem">Report Problem</span>
        </a>

        <!-- Sign In Button -->
        <a href="login.html" class="hidden sm:inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E6F5] dark:border-slate-700 text-slate-800 dark:text-slate-200 hover:bg-brand-tint font-headline text-xs font-bold shadow-sm transition-all whitespace-nowrap">
          <span class="material-symbols-outlined text-[16px] text-brand-primary">login</span>
          <span>Sign In</span>
        </a>

        <!-- User Profile Avatar with Role Indicator -->
        <div class="relative cursor-pointer shrink-0" onclick="alert('Logged in as Student Innovator Lead: Parnavi Janbhor (VSIT)')">
          <img alt="Parnavi Janbhor Profile" class="w-8 h-8 rounded-xl object-cover ring-2 ring-brand-pastel border border-white shadow-sm" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=120&auto=format&fit=crop&q=80"/>
          <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500 border border-white"></span>
        </div>

        <!-- Mobile Menu Hamburger Button -->
        <button onclick="toggleMobileMenu()" class="lg:hidden w-8 h-8 rounded-xl flex items-center justify-center bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-[#D5E6F5] dark:border-slate-700">
          <span class="material-symbols-outlined text-[20px]" id="mobile-menu-icon">menu</span>
        </button>

      </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobile-menu" class="hidden lg:hidden border-t border-[#D8E8F5] dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl px-4 py-4 space-y-2">
      <a href="#home" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-200 hover:bg-brand-pastel/60">
        <span class="material-symbols-outlined text-brand-primary text-[20px]">home</span>
        <span>Home Overview</span>
      </a>
      <a href="#challenges" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-200 hover:bg-brand-pastel/60">
        <span class="material-symbols-outlined text-brand-primary text-[20px]">report_problem</span>
        <span>Challenges & Problem Reporting</span>
      </a>
      <a href="#ai-matching" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-200 hover:bg-brand-pastel/60">
        <span class="material-symbols-outlined text-brand-primary text-[20px]">psychology</span>
        <span>AI Matching Hub</span>
      </a>
      <a href="#ecosystem-flow" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-200 hover:bg-brand-pastel/60">
        <span class="material-symbols-outlined text-brand-primary text-[20px]">account_tree</span>
        <span>9-Stage Ecosystem Flow</span>
      </a>
      <a href="#core-modules" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-200 hover:bg-brand-pastel/60">
        <span class="material-symbols-outlined text-brand-primary text-[20px]">dashboard_customize</span>
        <span>Platform Modules</span>
      </a>
      <a href="#dashboards" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-200 hover:bg-brand-pastel/60">
        <span class="material-symbols-outlined text-brand-primary text-[20px]">space_dashboard</span>
        <span>Stakeholder Portals</span>
      </a>
      <a href="#tracking" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-200 hover:bg-brand-pastel/60">
        <span class="material-symbols-outlined text-brand-primary text-[20px]">timeline</span>
        <span>Live Tracking</span>
      </a>
      <a href="#impact-analytics" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-200 hover:bg-brand-pastel/60">
        <span class="material-symbols-outlined text-brand-primary text-[20px]">analytics</span>
        <span>Impact Analytics</span>
      </a>
      <a href="about.html" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-200 hover:bg-brand-pastel/60">
        <span class="material-symbols-outlined text-brand-primary text-[20px]">info</span>
        <span>About C2C Platform</span>
      </a>
      <div class="pt-2 border-t border-slate-200 dark:border-slate-800">
        <a href="#challenges" onclick="toggleMobileMenu()" class="w-full py-2.5 rounded-xl bg-brand-primary text-white font-bold flex items-center justify-center gap-2 text-sm shadow-md">
          <span class="material-symbols-outlined text-[18px]">add_circle</span>
          <span>Report a Ground Problem</span>
        </a>
      </div>
    </div>
  </header>

  <!-- ========================================================================= -->
  <!-- MAIN CONTENT CONTAINER                                                    -->
  <!-- ========================================================================= -->
  <main class="pt-20 pb-20 overflow-x-hidden">

    <!-- ----------------------------------------------------------------------- -->
    <!-- SECTION 1: #home (HERO & 6-STAKEHOLDER ECOSYSTEM VISUALIZER)            -->
    <!-- ----------------------------------------------------------------------- -->
    <section id="home" class="relative w-full overflow-hidden pt-8 pb-16 lg:pb-24">
      <!-- Soft ambient pastel lighting orbs matching the photo (#CAE7F7) & animated in dark mode -->
      <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[520px] bg-gradient-to-b from-[#CAE7F7]/60 via-[#DCEEFB]/40 to-transparent dark:from-sky-500/30 dark:via-cyan-400/20 dark:to-transparent blur-[110px] pointer-events-none -z-10 animate-orb-3"></div>
      <div class="absolute top-64 left-6 sm:left-12 w-80 h-80 bg-[#BAE3F8]/35 dark:bg-cyan-500/25 rounded-full blur-[95px] pointer-events-none -z-10 animate-orb-1"></div>
      <div class="absolute top-56 right-6 sm:right-12 w-96 h-96 bg-[#E0F2FE]/70 dark:bg-indigo-600/30 rounded-full blur-[105px] pointer-events-none -z-10 animate-orb-2"></div>
      <!-- Additional floating aurora orb in dark mode -->
      <div class="hidden dark:block absolute top-[440px] left-1/3 w-80 h-80 bg-sky-400/20 rounded-full blur-[110px] pointer-events-none -z-10 animate-orb-1" style="animation-delay: -6s;"></div>

      <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
        
        <!-- Live Pill Telemetry Tag -->
        <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-white/90 dark:bg-slate-800/90 border border-[#CAE7F7] dark:border-slate-700 shadow-sky-sm mb-6">
          <span class="flex h-2.5 w-2.5 relative">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-accent opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-brand-primary"></span>
          </span>
          <span class="font-headline text-[12px] font-bold text-brand-deep dark:text-sky-300 tracking-wider uppercase" data-i18n="mesh_tag">SIH 2026 Innovation Mesh</span>
          <span class="text-slate-300 dark:text-slate-600">•</span>
          <span class="text-[12px] font-semibold text-slate-600 dark:text-slate-300" data-i18n="active_challenges_count">1,284 Verified Challenges Active</span>
        </div>

        <!-- Headline & Impact Narrative -->
        <h1 class="font-headline font-extrabold text-4xl sm:text-5xl lg:text-6xl text-center max-w-5xl tracking-tight text-slate-900 dark:text-white mb-5 leading-[1.15]" data-i18n="hero_headline">
          Turn Campus Ideas Into <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-600 via-brand-primary to-indigo-600 dark:from-sky-300 dark:via-cyan-300 dark:to-indigo-300 animate-gradient-text">Community Solutions.</span>
        </h1>
        
        <p class="font-body text-base sm:text-lg text-slate-600 dark:text-slate-300 text-center max-w-3xl mb-10 leading-relaxed font-normal" data-i18n="hero_subhead">
          Sovereign digital bridge unifying Smart India Hackathon innovators, public administration, university research, and civic ground telemetry for verified societal impact.
        </p>

        <!-- Hero Primary CTA Cluster -->
        <div class="flex flex-wrap items-center justify-center gap-3.5 mb-14 z-10">
          <a href="#challenges" class="flex items-center gap-2 px-6 py-3.5 rounded-xl bg-gradient-to-r from-sky-600 to-brand-primary text-white font-headline text-sm font-bold shadow-sky-md hover:shadow-sky-lg hover:scale-[1.02] active:scale-95 transition-all">
            <span class="material-symbols-outlined text-[20px]">add_circle</span>
            <span data-i18n="hero_cta_report">Report a Ground Problem</span>
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
          </a>
          <a href="#ai-matching" class="flex items-center gap-2 px-6 py-3.5 rounded-xl bg-white dark:bg-slate-800 text-slate-800 dark:text-white border border-[#CAE7F7] dark:border-slate-700 font-headline text-sm font-bold shadow-sky-sm hover:bg-brand-tint dark:hover:bg-slate-700 transition-all">
            <span class="material-symbols-outlined text-brand-primary text-[20px]">psychology</span>
            <span data-i18n="hero_cta_ai">Explore AI Matching</span>
          </a>
          <a href="#ecosystem-flow" class="flex items-center gap-2 px-5 py-3.5 rounded-xl bg-transparent text-slate-600 dark:text-slate-400 font-headline text-sm font-semibold hover:text-brand-deep hover:bg-brand-pastel/30 transition-colors">
            <span class="material-symbols-outlined text-[20px]">account_tree</span>
            <span data-i18n="hero_cta_how">How C2C Works</span>
          </a>
        </div>

        <!-- Bento Stage: 6-Stakeholder Interactive Orbital Network -->
        <div class="w-full relative rounded-2xl glass-card p-6 sm:p-8 lg:p-10 shadow-sky-md overflow-hidden border border-[#D5E8F7] dark:border-slate-800">
          
          <!-- Subtle Blueprint Coordinate Background -->
          <div class="absolute inset-0 bg-[radial-gradient(#CAE7F7_1px,transparent_1px)] dark:bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] opacity-70 pointer-events-none"></div>

          <!-- Top Status Bar -->
          <div class="relative z-10 flex flex-wrap items-center justify-between gap-4 pb-6 mb-6 border-b border-[#E0EEF8] dark:border-slate-800">
            <div class="flex items-center gap-3">
              <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse shadow-sm"></span>
              <span class="font-headline font-bold text-sm text-slate-900 dark:text-white uppercase tracking-wider">C2C Neural Graph Network</span>
              <span class="px-2.5 py-0.5 rounded-md bg-brand-pastel text-[11px] font-bold text-brand-deep">Mesh v4.2 Active</span>
            </div>
            <div class="flex flex-wrap items-center gap-5 text-xs font-medium text-slate-600 dark:text-slate-300">
              <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-sky-500"></span>412 Universities Online</span>
              <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>18 District Municipalities</span>
              <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-indigo-500"></span>Real-time Telemetry: Healthy</span>
            </div>
          </div>

          <!-- Central Interactive Orbital Canvas -->
          <div class="relative w-full min-h-[460px] flex items-center justify-center py-6">
            <!-- Concentric Orbits with soft sky tones -->
            <div class="absolute w-72 h-72 rounded-full border border-sky-200/80 dark:border-slate-700 pointer-events-none"></div>
            <div class="absolute w-[440px] h-[440px] rounded-full border border-sky-200/50 dark:border-slate-800 pointer-events-none"></div>
            <div class="absolute w-[620px] h-[620px] rounded-full border border-dashed border-sky-100 dark:border-slate-800/50 pointer-events-none hidden md:block"></div>

            <!-- Central Hub Node: C2C Core AI Orchestrator -->
            <div class="relative z-20 flex flex-col items-center justify-center text-center p-6 w-48 h-48 rounded-full bg-gradient-to-br from-white via-brand-tint to-brand-pastel dark:from-slate-800 dark:to-slate-900 border-2 border-brand-pastel dark:border-sky-500/40 shadow-sky-lg group cursor-pointer hover:scale-105 transition-all">
              <div class="w-12 h-12 rounded-2xl bg-brand-primary text-white flex items-center justify-center mb-2 shadow-sm group-hover:rotate-12 transition-transform">
                <span class="material-symbols-outlined text-[28px]">hub</span>
              </div>
              <span class="font-headline font-extrabold text-lg text-slate-900 dark:text-white leading-tight">C2C Core</span>
              <span class="text-[10px] font-bold text-brand-deep dark:text-sky-300 uppercase tracking-wider mt-0.5">AI Orchestrator</span>
              <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-1">4.2M Queries/day</span>
            </div>

            <!-- 6 Satellite Nodes (Positioned around the orbits) -->
            <!-- 1. Students -->
            <div class="absolute top-2 left-2 sm:left-12 flex items-center gap-3 p-3.5 rounded-2xl bg-white/95 dark:bg-slate-800/95 border border-[#CAE7F7] dark:border-slate-700 shadow-sky-sm hover:-translate-y-1 transition-transform cursor-pointer" onclick="setActorView('student'); document.getElementById('ecosystem-flow').scrollIntoView({behavior: 'smooth'})">
              <div class="w-10 h-10 rounded-xl bg-brand-pastel text-brand-deep flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">school</span>
              </div>
              <div class="flex flex-col">
                <span class="font-headline font-bold text-xs text-slate-900 dark:text-white">Students & Innovators</span>
                <span class="text-[11px] font-bold text-brand-deep dark:text-sky-300">32,480 Active Solvers</span>
                <span class="text-[10px] text-slate-500">AICTE Credit Rails</span>
              </div>
            </div>

            <!-- 2. Universities & Labs -->
            <div class="absolute top-2 right-2 sm:right-12 flex items-center gap-3 p-3.5 rounded-2xl bg-white/95 dark:bg-slate-800/95 border border-[#CAE7F7] dark:border-slate-700 shadow-sky-sm hover:-translate-y-1 transition-transform cursor-pointer" onclick="setActorView('faculty'); document.getElementById('ecosystem-flow').scrollIntoView({behavior: 'smooth'})">
              <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-300 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">account_balance</span>
              </div>
              <div class="flex flex-col">
                <span class="font-headline font-bold text-xs text-slate-900 dark:text-white">Universities & Labs</span>
                <span class="text-[11px] font-bold text-indigo-600 dark:text-indigo-300">412 Campuses</span>
                <span class="text-[10px] text-slate-500">Faculty Mentorship</span>
              </div>
            </div>

            <!-- 3. Citizen Communities -->
            <div class="absolute bottom-2 left-2 sm:left-10 flex items-center gap-3 p-3.5 rounded-2xl bg-white/95 dark:bg-slate-800/95 border border-[#CAE7F7] dark:border-slate-700 shadow-sky-sm hover:-translate-y-1 transition-transform cursor-pointer" onclick="setActorView('community'); document.getElementById('ecosystem-flow').scrollIntoView({behavior: 'smooth'})">
              <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-300 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">location_city</span>
              </div>
              <div class="flex flex-col">
                <span class="font-headline font-bold text-xs text-slate-900 dark:text-white">Citizen Communities</span>
                <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-300">126 Connected Blocks</span>
                <span class="text-[10px] text-slate-500">Ground Problem Sourcing</span>
              </div>
            </div>

            <!-- 4. Municipal Governments -->
            <div class="absolute bottom-2 right-2 sm:right-10 flex items-center gap-3 p-3.5 rounded-2xl bg-white/95 dark:bg-slate-800/95 border border-[#CAE7F7] dark:border-slate-700 shadow-sky-sm hover:-translate-y-1 transition-transform cursor-pointer" onclick="setActorView('admin'); document.getElementById('ecosystem-flow').scrollIntoView({behavior: 'smooth'})">
              <div class="w-10 h-10 rounded-xl bg-sky-100 dark:bg-sky-950 text-sky-700 dark:text-sky-300 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">policy</span>
              </div>
              <div class="flex flex-col">
                <span class="font-headline font-bold text-xs text-slate-900 dark:text-white">Municipalities & Govt</span>
                <span class="text-[11px] font-bold text-sky-700 dark:text-sky-300">18 District Admins</span>
                <span class="text-[10px] text-slate-500">Fast-Track Test Clearances</span>
              </div>
            </div>

            <!-- 5. Industry & CSR -->
            <div class="absolute top-1/2 -translate-y-1/2 right-1 sm:right-4 hidden md:flex items-center gap-3 p-3.5 rounded-2xl bg-white/95 dark:bg-slate-800/95 border border-[#CAE7F7] dark:border-slate-700 shadow-sky-sm hover:-translate-y-1 transition-transform cursor-pointer" onclick="setActorView('industry'); document.getElementById('ecosystem-flow').scrollIntoView({behavior: 'smooth'})">
              <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950 text-amber-600 dark:text-amber-300 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">factory</span>
              </div>
              <div class="flex flex-col">
                <span class="font-headline font-bold text-xs text-slate-900 dark:text-white">Industry CSR Escrow</span>
                <span class="text-[11px] font-bold text-amber-600 dark:text-amber-300">₹4.8 Cr Committed</span>
                <span class="text-[10px] text-slate-500">Sec 135 Compliance</span>
              </div>
            </div>

            <!-- 6. Field NGOs -->
            <div class="absolute top-1/2 -translate-y-1/2 left-1 sm:left-4 hidden md:flex items-center gap-3 p-3.5 rounded-2xl bg-white/95 dark:bg-slate-800/95 border border-[#CAE7F7] dark:border-slate-700 shadow-sky-sm hover:-translate-y-1 transition-transform cursor-pointer">
              <div class="w-10 h-10 rounded-xl bg-teal-100 dark:bg-teal-950 text-teal-600 dark:text-teal-300 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">volunteer_activism</span>
              </div>
              <div class="flex flex-col">
                <span class="font-headline font-bold text-xs text-slate-900 dark:text-white">Field NGO Coalition</span>
                <span class="text-[11px] font-bold text-teal-600 dark:text-teal-300">85 Verifiers</span>
                <span class="text-[10px] text-slate-500">Social Auditing</span>
              </div>
            </div>

          </div>

          <!-- Real-Time Metrics Strip: 5 Core High-Performance Counters -->
          <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5 pt-6 mt-4 border-t border-[#E0EEF8] dark:border-slate-800">
            <div class="p-3.5 rounded-xl bg-white/80 dark:bg-slate-800/60 border border-[#D8EAF7] dark:border-slate-700 flex flex-col">
              <span class="font-headline font-extrabold text-2xl text-brand-deep dark:text-sky-300">1,284</span>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Problems Reported</span>
              <span class="text-[11px] font-semibold text-emerald-600 mt-0.5 flex items-center gap-0.5">
                <span class="material-symbols-outlined text-[13px]">arrow_upward</span> +14% this month
              </span>
            </div>
            <div class="p-3.5 rounded-xl bg-white/80 dark:bg-slate-800/60 border border-[#D8EAF7] dark:border-slate-700 flex flex-col">
              <span class="font-headline font-extrabold text-2xl text-sky-600 dark:text-sky-400">742</span>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Solutions Formulated</span>
              <span class="text-[11px] text-slate-500 mt-0.5">Across 9 civic tracks</span>
            </div>
            <div class="p-3.5 rounded-xl bg-white/80 dark:bg-slate-800/60 border border-[#D8EAF7] dark:border-slate-700 flex flex-col">
              <span class="font-headline font-extrabold text-2xl text-indigo-600 dark:text-indigo-400">126</span>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Communities Synced</span>
              <span class="text-[11px] font-semibold text-emerald-600 mt-0.5">100% Geo-Validated</span>
            </div>
            <div class="p-3.5 rounded-xl bg-white/80 dark:bg-slate-800/60 border border-[#D8EAF7] dark:border-slate-700 flex flex-col">
              <span class="font-headline font-extrabold text-2xl text-emerald-600 dark:text-emerald-400">89</span>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Field Prototypes Live</span>
              <span class="text-[11px] text-slate-500 mt-0.5">Avg. Cycle: 38 Days</span>
            </div>
            <div class="col-span-2 md:col-span-1 p-3.5 rounded-xl bg-white/80 dark:bg-slate-800/60 border border-[#D8EAF7] dark:border-slate-700 flex flex-col">
              <span class="font-headline font-extrabold text-2xl text-amber-600 dark:text-amber-400">₹4.8 Cr</span>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">CSR Grants Disbursed</span>
              <span class="text-[11px] text-slate-500 mt-0.5">Direct student allocation</span>
            </div>
          </div>

        </div>

      </div>
    </section>

    <!-- ----------------------------------------------------------------------- -->
    <!-- SECTION 2: #challenges (GROUND CHALLENGES & REPORTING DESK)              -->
    <!-- ----------------------------------------------------------------------- -->
    <section id="challenges" class="w-full py-16 bg-[#EDF6FC]/60 dark:bg-slate-900/40 border-y border-[#DCEBF7] dark:border-slate-800">
      <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
          <div>
            <div class="flex items-center gap-2 text-brand-deep dark:text-sky-300 mb-1.5">
              <span class="material-symbols-outlined text-[20px]">report_problem</span>
              <span class="font-headline font-bold text-xs uppercase tracking-widest">Sovereign Citizen Desk</span>
            </div>
            <h2 class="font-headline font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white">Civic Problem Intake & Manifests</h2>
          </div>
          <p class="font-body text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-lg">
            Empowering ward counselors, village sarpanches, and citizens to register distress vectors with automated GPS tagging and NLP deduplication.
          </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
          
          <!-- Left: Live Interactive Problem Submission Form -->
          <div class="lg:col-span-7 p-6 sm:p-8 rounded-2xl glass-card border border-[#CAE7F7] dark:border-slate-800 shadow-sky-sm flex flex-col gap-5">
            <div class="flex items-center justify-between pb-3 border-b border-[#E2EFF8] dark:border-slate-800">
              <div class="flex items-center gap-2.5">
                <span class="w-3 h-3 rounded-full bg-brand-primary"></span>
                <h3 class="font-headline font-bold text-lg text-slate-900 dark:text-white">Submit Field Distress Report</h3>
              </div>
              <span class="px-2.5 py-1 rounded-full bg-brand-pastel text-brand-deep font-bold text-xs">Direct Civic Portal</span>
            </div>

            <!-- Form Fields -->
            <div>
              <label class="block font-headline text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Problem Summary / Title</label>
              <input type="text" id="report-title-input" oninput="updateReportPreview()" value="Contaminated Groundwater In Sub-Surface Wells" class="w-full bg-white dark:bg-slate-800 px-4 py-2.5 rounded-xl border border-[#CAE7F7] dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary" placeholder="State the core issue clearly..."/>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-headline text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Domain Category</label>
                <select id="report-category-input" onchange="updateReportPreview()" class="w-full bg-white dark:bg-slate-800 px-4 py-2.5 rounded-xl border border-[#CAE7F7] dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary">
                  <option selected>Water & Sanitation (SDG 6)</option>
                  <option>Healthcare & Diagnostics (SDG 3)</option>
                  <option>Agritech & Soil Health (SDG 2)</option>
                  <option>Clean Energy & Microgrids (SDG 7)</option>
                  <option>Rural Education & Digital Literacy</option>
                  <option>Urban Waste & Circularity</option>
                </select>
              </div>
              <div>
                <label class="block font-headline text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Severity Level</label>
                <select id="report-severity-input" onchange="updateReportPreview()" class="w-full bg-white dark:bg-slate-800 px-4 py-2.5 rounded-xl border border-[#CAE7F7] dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary">
                  <option selected>Critical (Immediate Hazard)</option>
                  <option>High (Impacting 500+ Daily)</option>
                  <option>Moderate (Infrastructure Lag)</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-headline text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Location / Gram Panchayat</label>
                <div class="relative">
                  <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-[18px]">location_on</span>
                  <input type="text" id="report-location-input" oninput="updateReportPreview()" value="Bhiwandi Block 4, Maharashtra" class="w-full bg-white dark:bg-slate-800 pl-9 pr-4 py-2.5 rounded-xl border border-[#CAE7F7] dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary"/>
                </div>
              </div>
              <div>
                <label class="block font-headline text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Citizens Affected</label>
                <input type="number" id="report-affected-input" oninput="updateReportPreview()" value="3400" class="w-full bg-white dark:bg-slate-800 px-4 py-2.5 rounded-xl border border-[#CAE7F7] dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary"/>
              </div>
            </div>

            <div>
              <label class="block font-headline text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Observed Distress Details</label>
              <textarea id="report-desc-input" rows="3" class="w-full bg-white dark:bg-slate-800 px-4 py-2.5 rounded-xl border border-[#CAE7F7] dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary resize-none">High concentration of industrial arsenic and turbidity observed after recent drainage overflow. Local PHC reports 24 gastroenteritis cases in 72h.</textarea>
            </div>

            <!-- Upload Box Mock -->
            <div class="p-4 rounded-xl bg-brand-tint/60 dark:bg-slate-800/60 border border-dashed border-[#CAE7F7] dark:border-slate-700 text-center flex flex-col items-center justify-center cursor-pointer hover:bg-brand-pastel/30 transition-colors">
              <span class="material-symbols-outlined text-brand-primary text-[28px] mb-1">cloud_upload</span>
              <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Attach Lab Water Sample / Geo-Tagged Photo / Vernacular Audio Memo</span>
              <span class="text-[11px] text-slate-500 mt-0.5">Instant EXIF GPS extraction & NIC server encryption</span>
            </div>

            <button onclick="triggerSubmitConfirmation()" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-sky-600 to-brand-primary text-white font-headline text-sm font-bold shadow-sky-sm hover:shadow-sky-md hover:scale-[1.01] active:scale-95 transition-all flex items-center justify-center gap-2">
              <span class="material-symbols-outlined text-[19px]">send</span>
              <span>Submit for Sovereign AI Intake Verification</span>
            </button>
          </div>

          <!-- Right: Live Diagnostic Ticket Preview & Verified Feed -->
          <div class="lg:col-span-5 flex flex-col gap-6">
            
            <!-- Real-Time Ticket Preview Card -->
            <div class="p-6 rounded-2xl glass-card border border-[#CAE7F7] dark:border-slate-800 shadow-sky-sm flex flex-col justify-between">
              <div>
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-[#E2EFF8] dark:border-slate-800">
                  <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-brand-primary text-[20px]">badge</span>
                    <span class="font-headline font-bold text-xs text-slate-900 dark:text-white uppercase tracking-wider">Live Intake Telemetry</span>
                  </div>
                  <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-300 font-bold text-[11px]">Valid Manifest</span>
                </div>

                <div class="p-3.5 rounded-xl bg-[#EBF5FC] dark:bg-slate-800/80 mb-4">
                  <span class="text-[10px] font-bold text-brand-deep dark:text-sky-300 uppercase tracking-widest block">Generated Sovereign Ticket ID</span>
                  <span class="font-mono text-xl font-bold text-slate-900 dark:text-white" id="preview-ticket-id">C2C-2026-W482</span>
                  <div class="flex items-center gap-2 mt-1">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <span class="text-xs font-semibold text-amber-700 dark:text-amber-400">NLP Urgency Index: 98.4 (Tier 1 Priority)</span>
                  </div>
                </div>

                <div class="space-y-2.5 text-xs">
                  <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
                    <span class="text-slate-500">Domain Classification</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200" id="preview-category">Water & Sanitation (SDG 6)</span>
                  </div>
                  <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
                    <span class="text-slate-500">Geo Coordinates</span>
                    <span class="font-mono text-brand-deep dark:text-sky-300" id="preview-location">19.2968° N, 73.0631° E</span>
                  </div>
                  <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
                    <span class="text-slate-500">Impacted Population</span>
                    <span class="font-bold text-emerald-600" id="preview-affected">3,400 Verified Citizens</span>
                  </div>
                  <div class="flex justify-between py-1">
                    <span class="text-slate-500">Recommended Lab Unit</span>
                    <span class="font-semibold text-indigo-600 dark:text-indigo-300">Environmental Engg + IoT Nodes</span>
                  </div>
                </div>
              </div>

              <div class="mt-5 p-3.5 rounded-xl bg-brand-tint/70 dark:bg-slate-800/50 border border-[#D5E8F7] dark:border-slate-700">
                <div class="flex items-center gap-1.5 text-brand-deep dark:text-sky-300 mb-1">
                  <span class="material-symbols-outlined text-[16px]">bolt</span>
                  <span class="text-[11px] font-bold uppercase tracking-wider">Fast-Track AI Routing</span>
                </div>
                <p class="text-[11px] text-slate-600 dark:text-slate-300 leading-relaxed">
                  Upon dispatch, 4 shortlisted universities within a 50km radius and 2 CSR partners receive automated RFPs.
                </p>
              </div>
            </div>

            <!-- Deduplication Feature Mini-Card -->
            <div class="p-4 rounded-2xl bg-white dark:bg-slate-800/70 border border-[#D8EAF7] dark:border-slate-700 shadow-sm flex items-center gap-3.5">
              <div class="w-11 h-11 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-300 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[24px]">verified</span>
              </div>
              <div class="flex flex-col">
                <span class="font-headline font-bold text-xs text-slate-900 dark:text-white">Automatic Ground Deduplication</span>
                <span class="text-[11px] text-slate-500 dark:text-slate-400">Cross-checked with Jal Jeevan Mission national database in 0.4s.</span>
              </div>
            </div>

          </div>

        </div>

      </div>
    </section>

    </section>

    <!-- ----------------------------------------------------------------------- -->
    <!-- SECTION 3: #ai-matching (AI MATCHING HUB & SQUAD BUILDER)               -->
    <!-- ----------------------------------------------------------------------- -->
    <section id="ai-matching" class="w-full py-16">
      <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Title -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
          <div>
            <div class="flex items-center gap-2 text-brand-deep dark:text-sky-300 mb-1.5">
              <span class="material-symbols-outlined text-[20px]">psychology</span>
              <span class="font-headline font-bold text-xs uppercase tracking-widest">Semantic Vector Engine</span>
            </div>
            <h2 class="font-headline font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white">AI Matching Workspace</h2>
          </div>
          <div class="flex items-center gap-3">
            <span class="text-xs text-slate-500 font-semibold">Active Solver Profile:</span>
            <div class="px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-[#CAE7F7] dark:border-slate-700 text-xs font-bold text-brand-deep dark:text-sky-300 shadow-sm">
              Parnavi Janbhor (VSIT)
            </div>
          </div>
        </div>

        <!-- Featured High-Confidence Match Card -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
          
          <!-- Primary 95% Match Card -->
          <div class="lg:col-span-2 p-6 sm:p-8 rounded-2xl glass-card border-2 border-brand-pastel dark:border-sky-500/40 shadow-sky-md flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-brand-pastel/40 dark:bg-cyan-500/20 rounded-full blur-3xl pointer-events-none -z-0 animate-orb-2"></div>

            <div class="relative z-10">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                  <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 font-bold text-xs flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    95% Vector Match Score
                  </span>
                  <span class="text-xs font-mono text-slate-400">ID: C2C-2026-W482</span>
                </div>
                <span class="px-3 py-1 rounded-full bg-brand-pastel text-brand-deep font-bold text-xs">High Civic Urgency</span>
              </div>

              <h3 class="font-headline font-bold text-xl sm:text-2xl text-slate-900 dark:text-white mb-2.5">
                Smart IoT Groundwater Filtration & Turbidity Telemetry Grid
              </h3>
              <p class="font-body text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                Severe industrial arsenic runoff affecting Bhiwandi rural blocks. Recommended project architecture: solar-powered UV/Arsenic electro-flocculation unit paired with real-time sub-surface telemetry nodes reporting to district dashboard.
              </p>

              <!-- Matched Competencies -->
              <div class="mb-6">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Matched Competencies for Parnavi Janbhor</span>
                <div class="flex flex-wrap gap-2">
                  <span class="px-3 py-1 rounded-lg bg-brand-tint dark:bg-slate-800 text-brand-deep dark:text-sky-300 font-bold text-xs">Embedded C / ESP32</span>
                  <span class="px-3 py-1 rounded-lg bg-brand-tint dark:bg-slate-800 text-brand-deep dark:text-sky-300 font-bold text-xs">Python MQTT Telemetry</span>
                  <span class="px-3 py-1 rounded-lg bg-brand-tint dark:bg-slate-800 text-brand-deep dark:text-sky-300 font-bold text-xs">Hydrology Flow Data</span>
                  <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs">Solar MPPT Systems</span>
                </div>
              </div>

              <!-- Squad Composition Visualizer -->
              <div class="p-4 rounded-xl bg-[#F0F7FD] dark:bg-slate-800/80 border border-[#D5E8F7] dark:border-slate-700 mb-6">
                <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-300 uppercase tracking-widest block mb-2.5">AI-Recommended Solution Squad Matrix</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                  <div class="flex items-center gap-2.5 p-2 rounded-lg bg-white dark:bg-slate-800 shadow-sm">
                    <div class="w-8 h-8 rounded-full bg-brand-pastel text-brand-deep font-bold text-xs flex items-center justify-center">4</div>
                    <div>
                      <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Student Engineers</span>
                      <span class="text-[10px] text-brand-deep">3 Positions Vacant</span>
                    </div>
                  </div>
                  <div class="flex items-center gap-2.5 p-2 rounded-lg bg-white dark:bg-slate-800 shadow-sm">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 font-bold text-xs flex items-center justify-center">1</div>
                    <div>
                      <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Faculty Advisor</span>
                      <span class="text-[10px] text-emerald-600">Dr. S. Kulkarni (Assigned)</span>
                    </div>
                  </div>
                  <div class="flex items-center gap-2.5 p-2 rounded-lg bg-white dark:bg-slate-800 shadow-sm">
                    <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 font-bold text-xs flex items-center justify-center">1</div>
                    <div>
                      <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Industry Mentor</span>
                      <span class="text-[10px] text-amber-600">Tata R&D CleanTech</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Grant & Action Button -->
            <div class="relative z-10 flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-[#DDEEF9] dark:border-slate-700">
              <div class="flex items-center gap-3">
                <span class="font-headline font-extrabold text-2xl text-emerald-600">₹2,50,000</span>
                <span class="text-xs text-slate-500 font-medium">Approved Hardware Prototyping Grant</span>
              </div>
              <button onclick="alert('Solution team application registered for C2C-2026-W482! Faculty mentor approval request dispatched.')" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-brand-primary text-white font-headline text-xs font-bold shadow-sky-sm hover:scale-105 transition-all">
                Join Solution Squad
              </button>
            </div>
          </div>

          <!-- Secondary Matched Challenges Column -->
          <div class="flex flex-col gap-4">
            
            <!-- Match 2: Cold-Chain Monitor -->
            <div class="p-5 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 shadow-sm flex flex-col justify-between">
              <div>
                <div class="flex items-center justify-between mb-2">
                  <span class="px-2.5 py-0.5 rounded-full bg-brand-pastel text-brand-deep font-bold text-[11px]">88% Match</span>
                  <span class="text-[11px] text-slate-400">Healthcare Track</span>
                </div>
                <h4 class="font-headline font-bold text-sm text-slate-900 dark:text-white mb-1">Cold-Chain Vaccine Telemetry Monitor</h4>
                <p class="text-xs text-slate-600 dark:text-slate-300 mb-3 leading-relaxed">
                  Continuous cellular temperature telemetry for primary healthcare storage in Gadchiroli tribal belts.
                </p>
                <div class="flex flex-wrap gap-1.5 mb-3">
                  <span class="px-2 py-0.5 rounded bg-brand-tint text-brand-deep text-[10px] font-semibold">BLE Sensors</span>
                  <span class="px-2 py-0.5 rounded bg-brand-tint text-brand-deep text-[10px] font-semibold">Flutter Mobile App</span>
                </div>
              </div>
              <button onclick="alert('Viewing Squad for Vaccine Telemetry Monitor.')" class="w-full py-2 rounded-xl bg-white dark:bg-slate-800 border border-[#CAE7F7] text-brand-deep font-bold text-xs hover:bg-brand-pastel transition-colors">
                Review Squad & Apply
              </button>
            </div>

            <!-- Match 3: LiDAR Pest Early Warning -->
            <div class="p-5 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 shadow-sm flex flex-col justify-between">
              <div>
                <div class="flex items-center justify-between mb-2">
                  <span class="px-2.5 py-0.5 rounded-full bg-brand-pastel text-brand-deep font-bold text-[11px]">82% Match</span>
                  <span class="text-[11px] text-slate-400">Agritech Track</span>
                </div>
                <h4 class="font-headline font-bold text-sm text-slate-900 dark:text-white mb-1">Pest Infestation Early Warning LiDAR</h4>
                <p class="text-xs text-slate-600 dark:text-slate-300 mb-3 leading-relaxed">
                  Autonomous edge-AI drone scans for pink bollworm detection across smallholder cotton belts in Vidarbha.
                </p>
                <div class="flex flex-wrap gap-1.5 mb-3">
                  <span class="px-2 py-0.5 rounded bg-brand-tint text-brand-deep text-[10px] font-semibold">YOLOv8 Edge AI</span>
                  <span class="px-2 py-0.5 rounded bg-brand-tint text-brand-deep text-[10px] font-semibold">ROS2 Drones</span>
                </div>
              </div>
              <button onclick="alert('Viewing Squad for LiDAR Pest Early Warning.')" class="w-full py-2 rounded-xl bg-white dark:bg-slate-800 border border-[#CAE7F7] text-brand-deep font-bold text-xs hover:bg-brand-pastel transition-colors">
                Review Squad & Apply
              </button>
            </div>

          </div>

        </div>

      </div>
    </section>

    <!-- ----------------------------------------------------------------------- -->
    <!-- SECTION 4: #ecosystem-flow (9-STAGE IMPACT PIPELINE & ACTOR SANDBOX)    -->
    <!-- ----------------------------------------------------------------------- -->
    <section id="ecosystem-flow" class="w-full py-16 bg-[#EDF6FC]/60 dark:bg-slate-900/40 border-y border-[#DCEBF7] dark:border-slate-800">
      <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
          <div>
            <div class="flex items-center gap-2 text-brand-deep dark:text-sky-300 mb-1.5">
              <span class="material-symbols-outlined text-[20px]">account_tree</span>
              <span class="font-headline font-bold text-xs uppercase tracking-widest">SIH 9-Stage Architecture</span>
            </div>
            <h2 class="font-headline font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white">The 9-Stage Impact Pipeline</h2>
          </div>
          <p class="font-body text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-lg">
            From ground-level citizen distress calls to verified societal deployment, every milestone is auditable and automated.
          </p>
        </div>

        <!-- 9-Stage Stepper Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-12">
          
          <!-- Stage 1 -->
          <div class="p-5 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-sm hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-3">
              <span class="px-2.5 py-0.5 rounded-full bg-brand-pastel text-brand-deep font-bold text-[11px]">PHASE 01</span>
              <span class="material-symbols-outlined text-brand-primary text-[20px]">pin_drop</span>
            </div>
            <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-1.5">Community Reports Problem</h3>
            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3 leading-relaxed">Geo-tagged submissions via web portal, vernacular voice notes, or WhatsApp bots by panchayats and local residents.</p>
            <div class="flex items-center gap-1.5 text-[11px] text-brand-deep font-semibold">
              <span class="w-1.5 h-1.5 rounded-full bg-brand-primary"></span>GPS Geo-Spatial Tagging
            </div>
          </div>

          <!-- Stage 2 -->
          <div class="p-5 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-sm hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-3">
              <span class="px-2.5 py-0.5 rounded-full bg-sky-100 text-sky-800 font-bold text-[11px]">PHASE 02</span>
              <span class="material-symbols-outlined text-sky-600 text-[20px]">psychology</span>
            </div>
            <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-1.5">AI Engine Verifies & Catalogs</h3>
            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3 leading-relaxed">NLP models perform cross-regional deduplication, priority grading, and cross-reference with municipal datasets.</p>
            <div class="flex items-center gap-1.5 text-[11px] text-sky-700 font-semibold">
              <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>Deduplication & NLP Scoring
            </div>
          </div>

          <!-- Stage 3 -->
          <div class="p-5 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-sm hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-3">
              <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800 font-bold text-[11px]">PHASE 03</span>
              <span class="material-symbols-outlined text-indigo-600 text-[20px]">device_hub</span>
            </div>
            <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-1.5">AI Smart Matching Hub</h3>
            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3 leading-relaxed">Algorithms pair the verified challenge with student engineering cohorts, department labs, and faculty guides.</p>
            <div class="flex items-center gap-1.5 text-[11px] text-indigo-700 font-semibold">
              <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>Semantic Cosine Matching
            </div>
          </div>

          <!-- Stage 4 -->
          <div class="p-5 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-sm hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-3">
              <span class="px-2.5 py-0.5 rounded-full bg-brand-pastel text-brand-deep font-bold text-[11px]">PHASE 04</span>
              <span class="material-symbols-outlined text-brand-primary text-[20px]">group_work</span>
            </div>
            <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-1.5">Student Team Ideation</h3>
            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3 leading-relaxed">Interdisciplinary squads assemble (e.g., 3 engineers + 1 data scientist) under an accredited faculty mentor.</p>
            <div class="flex items-center gap-1.5 text-[11px] text-brand-deep font-semibold">
              <span class="w-1.5 h-1.5 rounded-full bg-brand-primary"></span>AICTE Activity Points Earned
            </div>
          </div>

          <!-- Stage 5 -->
          <div class="p-5 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-sm hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-3">
              <span class="px-2.5 py-0.5 rounded-full bg-sky-100 text-sky-800 font-bold text-[11px]">PHASE 05</span>
              <span class="material-symbols-outlined text-sky-600 text-[20px]">biotech</span>
            </div>
            <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-1.5">Lab Validation & Mentorship</h3>
            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3 leading-relaxed">University lab facilities and professors certify hardware safety, sensor calibration, and code stability.</p>
            <div class="flex items-center gap-1.5 text-[11px] text-sky-700 font-semibold">
              <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>Academic Signoff Issued
            </div>
          </div>

          <!-- Stage 6 -->
          <div class="p-5 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-sm hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-3">
              <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-[11px]">PHASE 06</span>
              <span class="material-symbols-outlined text-amber-600 text-[20px]">payments</span>
            </div>
            <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-1.5">Industry CSR Sponsorship</h3>
            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3 leading-relaxed">Corporate CSR capital, cloud compute credits, and hardware toolkits route directly to project escrow.</p>
            <div class="flex items-center gap-1.5 text-[11px] text-amber-700 font-semibold">
              <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Section 135 Escrow Backed
            </div>
          </div>

          <!-- Stage 7 -->
          <div class="p-5 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-sm hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-3">
              <span class="px-2.5 py-0.5 rounded-full bg-sky-100 text-sky-800 font-bold text-[11px]">PHASE 07</span>
              <span class="material-symbols-outlined text-sky-600 text-[20px]">verified_user</span>
            </div>
            <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-1.5">Govt Pilot Greenlight</h3>
            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3 leading-relaxed">District Collectors authorize testing grounds and road-cutting NOCs with zero bureaucratic delay.</p>
            <div class="flex items-center gap-1.5 text-[11px] text-sky-700 font-semibold">
              <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>Single-Window Clearances
            </div>
          </div>

          <!-- Stage 8 -->
          <div class="p-5 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-sm hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-3">
              <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[11px]">PHASE 08</span>
              <span class="material-symbols-outlined text-emerald-600 text-[20px]">sensors</span>
            </div>
            <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-1.5">Field Deployment & Feedback</h3>
            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3 leading-relaxed">Prototypes live in community. Real-time IoT feedback and citizen satisfaction ratings stream to dashboards.</p>
            <div class="flex items-center gap-1.5 text-[11px] text-emerald-700 font-semibold">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Closed-Loop Validation
            </div>
          </div>

          <!-- Stage 9 -->
          <div class="p-5 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-sm hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-3">
              <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800 font-bold text-[11px]">PHASE 09</span>
              <span class="material-symbols-outlined text-indigo-600 text-[20px]">query_stats</span>
            </div>
            <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-1.5">Telemetry Measures SROI</h3>
            <p class="text-xs text-slate-600 dark:text-slate-300 mb-3 leading-relaxed">Immutable impact quantified: liters purified, kWh saved, citizen hours recovered, and Social ROI calculated.</p>
            <div class="flex items-center gap-1.5 text-[11px] text-indigo-700 font-semibold">
              <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>Audited ESG Returns
            </div>
          </div>

        </div>

        <!-- Stakeholder Diagnostic Sandbox Deck -->
        <div class="p-6 sm:p-8 rounded-2xl glass-card border border-[#CAE7F7] dark:border-slate-800 shadow-sky-sm">
          <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pb-5 mb-5 border-b border-[#E2EFF8] dark:border-slate-800">
            <div>
              <span class="text-xs font-bold text-brand-deep dark:text-sky-300 uppercase tracking-wider">Multi-Actor Role Switcher</span>
              <h4 class="font-headline font-bold text-xl text-slate-900 dark:text-white">Experience C2C in Stakeholder Mode</h4>
            </div>
            <!-- Actor Pills -->
            <div class="flex flex-wrap gap-2" id="actor-pill-container">
              <button class="actor-btn active px-3.5 py-1.5 rounded-xl bg-brand-pastel text-brand-deep font-bold text-xs shadow-sm" data-actor="student" onclick="setActorView('student')">Student Innovator</button>
              <button class="actor-btn px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E8F7] text-slate-700 dark:text-slate-300 font-semibold text-xs hover:bg-brand-tint" data-actor="community" onclick="setActorView('community')">Community Citizen</button>
              <button class="actor-btn px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E8F7] text-slate-700 dark:text-slate-300 font-semibold text-xs hover:bg-brand-tint" data-actor="faculty" onclick="setActorView('faculty')">University Faculty</button>
              <button class="actor-btn px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E8F7] text-slate-700 dark:text-slate-300 font-semibold text-xs hover:bg-brand-tint" data-actor="industry" onclick="setActorView('industry')">Industry / CSR</button>
              <button class="actor-btn px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E8F7] text-slate-700 dark:text-slate-300 font-semibold text-xs hover:bg-brand-tint" data-actor="admin" onclick="setActorView('admin')">District Admin</button>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pt-2">
            <div class="lg:col-span-2 flex flex-col justify-center">
              <div class="flex items-center gap-2 text-brand-deep dark:text-sky-300 mb-1">
                <span class="material-symbols-outlined text-[18px]">verified</span>
                <span class="text-xs font-bold uppercase tracking-wider">Active Persona Perspective</span>
              </div>
              <h5 class="font-headline font-bold text-xl text-slate-900 dark:text-white mb-2" id="actor-title">Student Innovator Portal</h5>
              <p class="font-body text-sm text-slate-600 dark:text-slate-300 mb-4 leading-relaxed" id="actor-desc">
                Direct access to AI-matched community challenges that align with university curriculum credits. Form interdisciplinary teams, unlock hardware stipends, and submit verified engineering solutions.
              </p>
              <div class="flex flex-wrap gap-2.5 text-xs font-bold" id="actor-badges">
                <span class="px-3 py-1 rounded-lg bg-brand-pastel text-brand-deep">Earns AICTE Activity Points</span>
                <span class="px-3 py-1 rounded-lg bg-white dark:bg-slate-800 border border-[#D5E8F7] text-slate-700">Lab Sandbox Access</span>
                <span class="px-3 py-1 rounded-lg bg-indigo-100 text-indigo-700">Direct CSR Micro-grants</span>
              </div>
            </div>
            
            <div class="p-5 rounded-2xl bg-[#F0F7FD] dark:bg-slate-800/80 border border-[#D5E8F7] dark:border-slate-700 flex flex-col justify-between">
              <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Live Persona Telemetry</span>
                <div class="font-headline font-bold text-xl text-slate-900 dark:text-white mt-1" id="actor-stat-val">32,480 Active Students</div>
                <p class="text-xs text-slate-500 mt-1" id="actor-stat-sub">Spanning 89 engineering disciplines & 412 accredited universities.</p>
              </div>
              <a href="#dashboards" class="mt-4 w-full py-2 rounded-xl bg-brand-primary text-white font-bold text-xs flex items-center justify-center gap-1 shadow-sm hover:scale-102 transition-all">
                <span>Open Stakeholder Portal</span>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
              </a>
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- ----------------------------------------------------------------------- -->
    <!-- SECTION 5: #core-modules (8 INTERCONNECTED FRAMEWORK MODULES)           -->
    <!-- ----------------------------------------------------------------------- -->
    <section id="core-modules" class="w-full py-16">
      <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-12">
          <div class="inline-flex items-center gap-2 text-brand-deep dark:text-sky-300 mb-2">
            <span class="material-symbols-outlined text-[18px]">dashboard_customize</span>
            <span class="font-headline font-bold text-xs uppercase tracking-widest">Enterprise Architecture</span>
          </div>
          <h2 class="font-headline font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white mb-3">8 Core Interconnected Impact Modules</h2>
          <p class="font-body text-sm sm:text-base text-slate-600 dark:text-slate-300">
            Engineered for high-volume civic scale, regulatory Section 135 compliance, and frictionless cross-sector execution.
          </p>
        </div>

        <!-- 8 Glass Feature Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
          
          <!-- Module 1 -->
          <div class="p-6 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-md hover:-translate-y-1 transition-all flex flex-col justify-between">
            <div>
              <div class="w-12 h-12 rounded-2xl bg-brand-pastel text-brand-deep flex items-center justify-center mb-4 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">map</span>
              </div>
              <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-2">Community Problems</h3>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">Discover and report ground-level issues with spatial GIS mapping, population density indexing, and urgency scoring.</p>
            </div>
            <div class="mt-5 pt-3.5 border-t border-[#E5F0FA] dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-brand-deep">
              <span>GIS Coordinates Synced</span>
              <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </div>
          </div>

          <!-- Module 2 -->
          <div class="p-6 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-md hover:-translate-y-1 transition-all flex flex-col justify-between">
            <div>
              <div class="w-12 h-12 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center mb-4 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">lightbulb</span>
              </div>
              <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-2">Student Innovation</h3>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">Pitch multidisciplinary solutions, earn verified AICTE academic credit points, and register provisional patents.</p>
            </div>
            <div class="mt-5 pt-3.5 border-t border-[#E5F0FA] dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-sky-700">
              <span>AICTE Credit Rails</span>
              <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </div>
          </div>

          <!-- Module 3 -->
          <div class="p-6 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-md hover:-translate-y-1 transition-all flex flex-col justify-between">
            <div>
              <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center mb-4 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">domain_add</span>
              </div>
              <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-2">University Collab</h3>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">Connect department research labs with municipal challenges. Turn final-year capstone theses into field pilots.</p>
            </div>
            <div class="mt-5 pt-3.5 border-t border-[#E5F0FA] dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-indigo-700">
              <span>Lab Shared Resources</span>
              <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </div>
          </div>

          <!-- Module 4 -->
          <div class="p-6 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-md hover:-translate-y-1 transition-all flex flex-col justify-between">
            <div>
              <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center mb-4 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">handshake</span>
              </div>
              <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-2">Industry Partnerships</h3>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">Direct CSR funding, tech hardware grants, and executive engineering mentorship under formalized Section 135 compliance.</p>
            </div>
            <div class="mt-5 pt-3.5 border-t border-[#E5F0FA] dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-amber-700">
              <span>CSR Escrow Audited</span>
              <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </div>
          </div>

          <!-- Module 5 -->
          <div class="p-6 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-md hover:-translate-y-1 transition-all flex flex-col justify-between">
            <div>
              <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center mb-4 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">apartment</span>
              </div>
              <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-2">Govt & NGO Connect</h3>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">Direct portal for District Collectors and Municipal Commissioners to validate pilot proposals within 48 hours.</p>
            </div>
            <div class="mt-5 pt-3.5 border-t border-[#E5F0FA] dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-teal-700">
              <span>Fast-Track Permits</span>
              <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </div>
          </div>

          <!-- Module 6 -->
          <div class="p-6 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-md hover:-translate-y-1 transition-all flex flex-col justify-between">
            <div>
              <div class="w-12 h-12 rounded-2xl bg-brand-pastel text-brand-deep flex items-center justify-center mb-4 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">psychology</span>
              </div>
              <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-2">AI Problem Matching</h3>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">Neural vector scoring linking citizen distress vectors to collegiate engineering proficiencies and lab inventory.</p>
            </div>
            <div class="mt-5 pt-3.5 border-t border-[#E5F0FA] dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-brand-deep">
              <span>Cosine Vector Index</span>
              <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </div>
          </div>

          <!-- Module 7 -->
          <div class="p-6 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-md hover:-translate-y-1 transition-all flex flex-col justify-between">
            <div>
              <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center mb-4 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">linear_scale</span>
              </div>
              <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-2">Progress Tracking</h3>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">Milestone-governed execution with verifiable digital proofs of work, GitHub linkage, and geofenced field audits.</p>
            </div>
            <div class="mt-5 pt-3.5 border-t border-[#E5F0FA] dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-indigo-700">
              <span>Proof-of-Work Rails</span>
              <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </div>
          </div>

          <!-- Module 8 -->
          <div class="p-6 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 hover:shadow-sky-md hover:-translate-y-1 transition-all flex flex-col justify-between">
            <div>
              <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-4 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">finance_mode</span>
              </div>
              <h3 class="font-headline font-bold text-base text-slate-900 dark:text-white mb-2">Impact Analytics</h3>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">Automated ESG reporting and Social Return on Investment (SROI) metrics formatted directly for ministerial review.</p>
            </div>
            <div class="mt-5 pt-3.5 border-t border-[#E5F0FA] dark:border-slate-800 flex items-center justify-between text-xs font-semibold text-emerald-700">
              <span>Real-Time ESG Telemetry</span>
              <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </div>
          </div>

        </div>

      </div>
    </section>

    <!-- ----------------------------------------------------------------------- -->
    <!-- SECTION 6: #dashboards (MULTI-STAKEHOLDER COMMAND PORTALS)              -->
    <!-- ----------------------------------------------------------------------- -->
    <section id="dashboards" class="w-full py-16 bg-[#EDF6FC]/60 dark:bg-slate-900/40 border-y border-[#DCEBF7] dark:border-slate-800">
      <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
          <div>
            <div class="flex items-center gap-2 text-brand-deep dark:text-sky-300 mb-1.5">
              <span class="material-symbols-outlined text-[20px]">space_dashboard</span>
              <span class="font-headline font-bold text-xs uppercase tracking-widest">Multi-Role Command Portals</span>
            </div>
            <h2 class="font-headline font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white">Role-Specific Workspaces</h2>
          </div>
          <!-- Portal Switcher Buttons -->
          <div class="flex flex-wrap p-1.5 rounded-2xl bg-white dark:bg-slate-800 border border-[#CAE7F7] dark:border-slate-700 gap-1 shadow-sm">
            <button onclick="switchPortalTab('student')" id="portal-tab-student" class="portal-btn px-4 py-2 rounded-xl text-xs font-bold bg-brand-pastel text-brand-deep transition-all">Student Portal</button>
            <button onclick="switchPortalTab('admin')" id="portal-tab-admin" class="portal-btn px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-brand-deep transition-all">Municipal Desk</button>
            <button onclick="switchPortalTab('faculty')" id="portal-tab-faculty" class="portal-btn px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-brand-deep transition-all">Faculty Mentors</button>
            <button onclick="switchPortalTab('csr')" id="portal-tab-csr" class="portal-btn px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-brand-deep transition-all">CSR Sponsors</button>
          </div>
        </div>

        <!-- Dynamic Portal Views -->
        <div class="p-6 sm:p-8 rounded-2xl glass-card border border-[#CAE7F7] dark:border-slate-800 shadow-sky-sm">
          
          <!-- View 1: Student Portal -->
          <div id="portal-view-student" class="portal-view block space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-[#E2EFF8] dark:border-slate-700">
              <div>
                <span class="text-xs font-bold text-brand-deep uppercase tracking-wider">Student Innovator Cockpit</span>
                <h3 class="font-headline font-bold text-xl text-slate-900 dark:text-white">Parnavi Janbhor | Student Innovator Lead (VSIT)</h3>
              </div>
              <div class="flex items-center gap-3">
                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs">AICTE Credits: 45 / 50 Earned</span>
                <span class="px-3 py-1 rounded-full bg-brand-pastel text-brand-deep font-bold text-xs">Lab Pass Active</span>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
              <div class="p-4 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E8F7] dark:border-slate-700">
                <span class="text-xs font-bold text-slate-400 uppercase">Active Capstone Sprint</span>
                <h4 class="font-headline font-bold text-sm text-slate-900 dark:text-white mt-1">Smart Village IoT Water Purity</h4>
                <p class="text-xs text-slate-500 mt-1">Sprint C2C-881 • Bhiwandi Sector 4</p>
                <div class="w-full bg-slate-100 h-2 rounded-full mt-3 overflow-hidden">
                  <div class="bg-brand-primary h-full rounded-full w-[85%]"></div>
                </div>
              </div>

              <div class="p-4 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E8F7] dark:border-slate-700">
                <span class="text-xs font-bold text-slate-400 uppercase">Stipend & Grant Ledger</span>
                <h4 class="font-headline font-bold text-sm text-emerald-600 mt-1">₹45,000 Disbursed</h4>
                <p class="text-xs text-slate-500 mt-1">₹30,000 in escrow pending Milestone 4 signoff</p>
                <span class="inline-block mt-2 text-[11px] font-bold text-brand-deep">View Escrow Contract →</span>
              </div>

              <div class="p-4 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E8F7] dark:border-slate-700">
                <span class="text-xs font-bold text-slate-400 uppercase">Patent & IP Registry</span>
                <h4 class="font-headline font-bold text-sm text-indigo-600 mt-1">Provisional Patent Filed</h4>
                <p class="text-xs text-slate-500 mt-1">C2C-IP-2026-098: Low-cost Arsenic Electro-Cell</p>
                <span class="inline-block mt-2 text-[11px] font-bold text-indigo-600">Download Provisional Form →</span>
              </div>
            </div>
          </div>

          <!-- View 2: Municipal Desk (Hidden by default) -->
          <div id="portal-view-admin" class="portal-view hidden space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-[#E2EFF8]">
              <div>
                <span class="text-xs font-bold text-sky-700 uppercase tracking-wider">District Administration Clearance Desk</span>
                <h3 class="font-headline font-bold text-xl text-slate-900">Bhiwandi Municipal Corporation (BMC)</h3>
              </div>
              <span class="px-3 py-1 rounded-full bg-sky-100 text-sky-800 font-bold text-xs">SLA Clearance Rate: 91.4%</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
              <div class="p-4 rounded-xl bg-white border border-[#D5E8F7]">
                <span class="text-xs font-bold text-slate-400 uppercase">Pending Testbed NOCs</span>
                <div class="text-2xl font-bold text-slate-900 mt-1">2 Requests</div>
                <p class="text-xs text-slate-500 mt-1">Average clearance time: 28 hours</p>
              </div>
              <div class="p-4 rounded-xl bg-white border border-[#D5E8F7]">
                <span class="text-xs font-bold text-slate-400 uppercase">Grievance Resolutions</span>
                <div class="text-2xl font-bold text-emerald-600 mt-1">114 Resolved</div>
                <p class="text-xs text-slate-500 mt-1">Citizen satisfaction rating: 4.8 / 5.0</p>
              </div>
              <div class="p-4 rounded-xl bg-white border border-[#D5E8F7]">
                <span class="text-xs font-bold text-slate-400 uppercase">Smart City Integration</span>
                <div class="text-2xl font-bold text-brand-deep mt-1">NIC Synced</div>
                <p class="text-xs text-slate-500 mt-1">Direct API feed to state urban dashboard</p>
              </div>
            </div>
          </div>

          <!-- View 3: Faculty Mentors (Hidden by default) -->
          <div id="portal-view-faculty" class="portal-view hidden space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-[#E2EFF8]">
              <div>
                <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Academic Mentor & Lab Suite</span>
                <h3 class="font-headline font-bold text-xl text-slate-900">Dr. S. Kulkarni | Department of Civil & Environmental Engg</h3>
              </div>
              <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-800 font-bold text-xs">NIRF Impact Points: +42</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
              <div class="p-4 rounded-xl bg-white border border-[#D5E8F7]">
                <span class="text-xs font-bold text-slate-400 uppercase">Supervised Capstones</span>
                <div class="text-2xl font-bold text-slate-900 mt-1">6 Active Teams</div>
                <p class="text-xs text-slate-500 mt-1">2 teams deployed in field testing</p>
              </div>
              <div class="p-4 rounded-xl bg-white border border-[#D5E8F7]">
                <span class="text-xs font-bold text-slate-400 uppercase">Lab Hardware Sandbox</span>
                <div class="text-2xl font-bold text-indigo-600 mt-1">4 Sensors Reserved</div>
                <p class="text-xs text-slate-500 mt-1">Spectrophotometer + Spectrometer slots</p>
              </div>
              <div class="p-4 rounded-xl bg-white border border-[#D5E8F7]">
                <span class="text-xs font-bold text-slate-400 uppercase">Academic Publication</span>
                <div class="text-2xl font-bold text-emerald-600 mt-1">2 Co-Authored</div>
                <p class="text-xs text-slate-500 mt-1">Submitted to IEEE Civic Technology 2026</p>
              </div>
            </div>
          </div>

          <!-- View 4: CSR Sponsors (Hidden by default) -->
          <div id="portal-view-csr" class="portal-view hidden space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-[#E2EFF8]">
              <div>
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Corporate CSR & Escrow Console</span>
                <h3 class="font-headline font-bold text-xl text-slate-900">Tata Trusts CleanTech Innovation Fund</h3>
              </div>
              <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 font-bold text-xs">Section 135 Compliant</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
              <div class="p-4 rounded-xl bg-white border border-[#D5E8F7]">
                <span class="text-xs font-bold text-slate-400 uppercase">Allocated CSR Capital</span>
                <div class="text-2xl font-bold text-slate-900 mt-1">₹50,00,000</div>
                <p class="text-xs text-slate-500 mt-1">Across 12 verified student projects</p>
              </div>
              <div class="p-4 rounded-xl bg-white border border-[#D5E8F7]">
                <span class="text-xs font-bold text-slate-400 uppercase">Escrow Locked</span>
                <div class="text-2xl font-bold text-amber-600 mt-1">₹32,50,000</div>
                <p class="text-xs text-slate-500 mt-1">Tranche releases upon milestone proofs</p>
              </div>
              <div class="p-4 rounded-xl bg-white border border-[#D5E8F7]">
                <span class="text-xs font-bold text-slate-400 uppercase">Verified SROI</span>
                <div class="text-2xl font-bold text-emerald-600 mt-1">4.2x Multiple</div>
                <p class="text-xs text-slate-500 mt-1">Tax exemption audit certificates ready</p>
              </div>
            </div>
          </div>

        </div>

      </div>
    </section>

    <!-- ----------------------------------------------------------------------- -->
    <!-- SECTION 7: #tracking (PROJECT LIFECYCLE & IOT TELEMETRY)                -->
    <!-- ----------------------------------------------------------------------- -->
    <section id="tracking" class="w-full py-16">
      <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="p-6 sm:p-8 rounded-2xl glass-card border border-[#CAE7F7] dark:border-slate-800 shadow-sky-sm flex flex-col gap-6">
          
          <!-- Sprint Header -->
          <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-[#E2EFF8] dark:border-slate-800">
            <div>
              <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 font-bold text-xs uppercase">Phase 05: Field Pilot In Progress</span>
                <span class="text-xs font-mono text-slate-400">SPRINT-C2C-881</span>
              </div>
              <h3 class="font-headline font-extrabold text-2xl sm:text-3xl text-slate-900 dark:text-white">Smart Village IoT Water Purity & Distribution</h3>
              <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">Partner: Bhiwandi Municipal Council • Host: Veermata Jijabai Technological Institute (VJTI)</p>
            </div>
            
            <div class="flex items-center gap-4">
              <div class="flex flex-col text-right">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Escrow Burn Rate</span>
                <span class="font-headline font-bold text-xl text-emerald-600">₹1,84,500 / ₹2,50,000</span>
              </div>
              <button onclick="alert('Viewing Cryptographic Audit Ledger for SPRINT-C2C-881. Hash: 0x892a...c31b. All milestone vouchers verified.')" class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-[#CAE7F7] text-brand-deep font-bold text-xs hover:bg-brand-pastel transition-all flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">verified</span>
                <span>Audit Ledger</span>
              </button>
            </div>
          </div>

          <!-- Linear Stepper Progression Bar -->
          <div class="relative py-4 overflow-x-auto">
            <div class="min-w-[760px] flex items-center justify-between relative px-2">
              <div class="absolute top-1/2 left-6 right-6 -translate-y-1/2 h-1 bg-slate-200 dark:bg-slate-700 -z-0"></div>
              <div class="absolute top-1/2 left-6 w-3/5 -translate-y-1/2 h-1 bg-brand-primary -z-0"></div>

              <!-- Node 1 -->
              <div class="relative z-10 flex flex-col items-center">
                <div class="w-9 h-9 rounded-full bg-brand-primary text-white flex items-center justify-center font-bold text-sm shadow-md">✓</div>
                <span class="font-headline text-xs font-bold text-slate-800 dark:text-slate-200 mt-2">Identified</span>
                <span class="text-[10px] text-slate-400">Oct 12</span>
              </div>

              <!-- Node 2 -->
              <div class="relative z-10 flex flex-col items-center">
                <div class="w-9 h-9 rounded-full bg-brand-primary text-white flex items-center justify-center font-bold text-sm shadow-md">✓</div>
                <span class="font-headline text-xs font-bold text-slate-800 dark:text-slate-200 mt-2">Proposed</span>
                <span class="text-[10px] text-slate-400">Oct 24</span>
              </div>

              <!-- Node 3 -->
              <div class="relative z-10 flex flex-col items-center">
                <div class="w-9 h-9 rounded-full bg-brand-primary text-white flex items-center justify-center font-bold text-sm shadow-md">✓</div>
                <span class="font-headline text-xs font-bold text-slate-800 dark:text-slate-200 mt-2">Lab Review</span>
                <span class="text-[10px] text-slate-400">Nov 04</span>
              </div>

              <!-- Node 4 -->
              <div class="relative z-10 flex flex-col items-center">
                <div class="w-9 h-9 rounded-full bg-brand-primary text-white flex items-center justify-center font-bold text-sm shadow-md">✓</div>
                <span class="font-headline text-xs font-bold text-slate-800 dark:text-slate-200 mt-2">CSR Backed</span>
                <span class="text-[10px] text-slate-400">Nov 18</span>
              </div>

              <!-- Node 5 (Active) -->
              <div class="relative z-10 flex flex-col items-center">
                <div class="w-9 h-9 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-amber-200 animate-pulse">●</div>
                <span class="font-headline text-xs font-bold text-amber-700 dark:text-amber-400 mt-2">Pilot Testing</span>
                <span class="text-[10px] font-bold text-amber-600">Active Now</span>
              </div>

              <!-- Node 6 -->
              <div class="relative z-10 flex flex-col items-center">
                <div class="w-9 h-9 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-500 flex items-center justify-center font-bold text-sm">○</div>
                <span class="font-headline text-xs font-medium text-slate-400 mt-2">Full Rollout</span>
                <span class="text-[10px] text-slate-400">Dec 15</span>
              </div>

              <!-- Node 7 -->
              <div class="relative z-10 flex flex-col items-center">
                <div class="w-9 h-9 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-500 flex items-center justify-center font-bold text-sm">○</div>
                <span class="font-headline text-xs font-medium text-slate-400 mt-2">SROI Measured</span>
                <span class="text-[10px] text-slate-400">Jan 02</span>
              </div>
            </div>
          </div>

          <!-- Real-Time Telemetry Node Stats Bento -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
            <div class="p-4 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E8F7] dark:border-slate-700 flex flex-col justify-between">
              <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Field Sensor Telemetry</span>
                <div class="flex items-center gap-2 mt-1">
                  <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                  <span class="font-headline font-bold text-base text-slate-900 dark:text-white">12 Nodes Transmitting</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">99.8% ping uptime across Bhiwandi Sector 4 wells.</p>
              </div>
              <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700 flex justify-between text-xs font-mono">
                <span class="text-slate-600 dark:text-slate-300">Turbidity: 1.4 NTU (Safe)</span>
                <span class="text-emerald-600 font-bold">Arsenic: 0.008 ppm</span>
              </div>
            </div>

            <div class="p-4 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E8F7] dark:border-slate-700 flex flex-col justify-between">
              <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Team Milestone Velocity</span>
                <div class="flex items-center gap-2 mt-1">
                  <span class="font-headline font-bold text-base text-brand-deep dark:text-sky-300">18 / 21 Milestones Complete</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Firmware v2.1 OTA flash completed yesterday.</p>
              </div>
              <div class="w-full bg-slate-100 h-2 rounded-full mt-4 overflow-hidden">
                <div class="bg-brand-primary h-full rounded-full w-[85%]"></div>
              </div>
            </div>

            <div class="p-4 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E8F7] dark:border-slate-700 flex flex-col justify-between">
              <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">District Collector Signoff</span>
                <div class="flex items-center gap-2 mt-1">
                  <span class="material-symbols-outlined text-brand-primary text-[20px]">verified</span>
                  <span class="font-headline font-bold text-base text-slate-900 dark:text-white">Pilot Approved</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Formal municipal permit issued under Smart City Mission.</p>
              </div>
              <a href="#" onclick="alert('Viewing Gazetted Consent PDF: BMC-NOC-2026-W482. Signed by District Collector.'); return false;" class="mt-4 text-xs font-bold text-brand-deep hover:underline flex items-center gap-1">
                <span>View Gazetted Consent Certificate</span>
                <span class="material-symbols-outlined text-[14px]">open_in_new</span>
              </a>
            </div>
          </div>

        </div>

      </div>
    </section>

    <!-- ----------------------------------------------------------------------- -->
    <!-- SECTION 8: #impact-analytics (IMPACT COMMAND CENTER & ESG TELEMETRY)    -->
    <!-- ----------------------------------------------------------------------- -->
    <section id="impact-analytics" class="w-full py-16 bg-[#EDF6FC]/60 dark:bg-slate-900/40 border-y border-[#DCEBF7] dark:border-slate-800">
      <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
          <div>
            <div class="flex items-center gap-2 text-brand-deep dark:text-sky-300 mb-1.5">
              <span class="material-symbols-outlined text-[20px]">analytics</span>
              <span class="font-headline font-bold text-xs uppercase tracking-widest">National SROI Metrics</span>
            </div>
            <h2 class="font-headline font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white">Impact Analytics & Command Center</h2>
          </div>
          <p class="font-body text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-lg">
            Real-time telemetry measuring social return on investment, state-by-state resolution velocities, and UN SDG alignment.
          </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
          
          <!-- 4 Core High-Impact Metric Summary Tiles -->
          <div class="lg:col-span-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800">
              <span class="text-[10px] font-bold text-brand-deep dark:text-sky-300 uppercase tracking-widest">Citizens Benefited</span>
              <div class="font-headline font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white mt-1">48,240</div>
              <span class="text-xs text-emerald-600 font-bold flex items-center gap-1 mt-1">
                <span class="material-symbols-outlined text-[15px]">trending_up</span> +3,180 verified this sprint
              </span>
            </div>
            <div class="p-5 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800">
              <span class="text-[10px] font-bold text-sky-700 dark:text-sky-300 uppercase tracking-widest">Communities Impacted</span>
              <div class="font-headline font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white mt-1">126</div>
              <span class="text-xs text-slate-500 font-medium mt-1 block">Across 14 sovereign states</span>
            </div>
            <div class="p-5 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800">
              <span class="text-[10px] font-bold text-indigo-700 dark:text-indigo-300 uppercase tracking-widest">Problems Solved</span>
              <div class="font-headline font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white mt-1">312</div>
              <span class="text-xs text-emerald-600 font-bold flex items-center gap-1 mt-1">
                <span class="material-symbols-outlined text-[15px]">check_circle</span> 94.2% verified field efficacy
              </span>
            </div>
            <div class="p-5 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800">
              <span class="text-[10px] font-bold text-amber-700 dark:text-amber-400 uppercase tracking-widest">Active Capstone Projects</span>
              <div class="font-headline font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white mt-1">184</div>
              <span class="text-xs text-slate-500 font-medium mt-1 block">Supported by 42 industry partners</span>
            </div>
          </div>

          <!-- Regional State Impact Matrix -->
          <div class="lg:col-span-7 p-6 sm:p-7 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 flex flex-col justify-between">
            <div>
              <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#E2EFF8]">
                <h4 class="font-headline font-bold text-lg text-slate-900 dark:text-white">State-Level Resolution Velocity</h4>
                <span class="px-2.5 py-0.5 rounded-full bg-brand-pastel text-brand-deep text-[11px] font-mono font-bold">Realtime Telemetry</span>
              </div>
              <p class="text-xs text-slate-600 dark:text-slate-300 mb-5">Comparative view of challenges resolved vs active collegiate student chapters.</p>
              
              <!-- State Progress Bars -->
              <div class="space-y-4">
                <div>
                  <div class="flex justify-between text-xs font-bold mb-1">
                    <span class="text-slate-800 dark:text-slate-200">Maharashtra (Mumbai, Pune, Nagpur)</span>
                    <span class="text-brand-deep font-mono">114 Solved • 88 Active</span>
                  </div>
                  <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                    <div class="bg-brand-primary h-full rounded-full w-[78%]"></div>
                  </div>
                </div>

                <div>
                  <div class="flex justify-between text-xs font-bold mb-1">
                    <span class="text-slate-800 dark:text-slate-200">Karnataka (Bengaluru, Mysuru, Hubballi)</span>
                    <span class="text-sky-700 font-mono">82 Solved • 64 Active</span>
                  </div>
                  <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                    <div class="bg-sky-500 h-full rounded-full w-[65%]"></div>
                  </div>
                </div>

                <div>
                  <div class="flex justify-between text-xs font-bold mb-1">
                    <span class="text-slate-800 dark:text-slate-200">Tamil Nadu (Chennai, Coimbatore, Madurai)</span>
                    <span class="text-indigo-700 font-mono">59 Solved • 42 Active</span>
                  </div>
                  <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                    <div class="bg-indigo-500 h-full rounded-full w-[52%]"></div>
                  </div>
                </div>

                <div>
                  <div class="flex justify-between text-xs font-bold mb-1">
                    <span class="text-slate-800 dark:text-slate-200">Uttar Pradesh (Lucknow, Varanasi, Kanpur)</span>
                    <span class="text-amber-700 font-mono">38 Solved • 71 Active</span>
                  </div>
                  <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                    <div class="bg-amber-500 h-full rounded-full w-[41%]"></div>
                  </div>
                </div>

                <div>
                  <div class="flex justify-between text-xs font-bold mb-1">
                    <span class="text-slate-800 dark:text-slate-200">Gujarat (Ahmedabad, Surat, Vadodara)</span>
                    <span class="text-emerald-700 font-mono">34 Solved • 29 Active</span>
                  </div>
                  <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full rounded-full w-[38%]"></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-6 pt-4 border-t border-[#E2EFF8] dark:border-slate-800 flex items-center justify-between text-xs font-mono text-slate-500">
              <span>National Avg Time-to-Pilot: 34 Days</span>
              <span class="text-emerald-600 font-bold">SIH Target: &lt;45 Days (Exceeded)</span>
            </div>
          </div>

          <!-- UN SDG Domain Impact Breakdown -->
          <div class="lg:col-span-5 p-6 sm:p-7 rounded-2xl glass-card border border-[#D5E8F7] dark:border-slate-800 flex flex-col justify-between">
            <div>
              <div class="flex items-center justify-between mb-3 pb-3 border-b border-[#E2EFF8]">
                <h4 class="font-headline font-bold text-lg text-slate-900 dark:text-white">Impact by UN SDG Domains</h4>
                <span class="material-symbols-outlined text-brand-primary text-[22px]">pie_chart</span>
              </div>
              <p class="text-xs text-slate-600 dark:text-slate-300 mb-4">Capital efficiency and citizen density impact mapped by UN Sustainable Development Goals.</p>
              
              <div class="space-y-2.5">
                <div class="p-3 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E8F7] flex items-center justify-between">
                  <div class="flex items-center gap-2.5">
                    <div class="w-3 h-3 rounded-full bg-brand-primary"></div>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Water & Sanitation (SDG 6)</span>
                  </div>
                  <span class="text-xs font-mono font-bold text-brand-deep">34%</span>
                </div>

                <div class="p-3 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E8F7] flex items-center justify-between">
                  <div class="flex items-center gap-2.5">
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Agritech & Soil Health (SDG 2)</span>
                  </div>
                  <span class="text-xs font-mono font-bold text-emerald-600">26%</span>
                </div>

                <div class="p-3 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E8F7] flex items-center justify-between">
                  <div class="flex items-center gap-2.5">
                    <div class="w-3 h-3 rounded-full bg-indigo-500"></div>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Clean Energy Microgrids (SDG 7)</span>
                  </div>
                  <span class="text-xs font-mono font-bold text-indigo-600">21%</span>
                </div>

                <div class="p-3 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E8F7] flex items-center justify-between">
                  <div class="flex items-center gap-2.5">
                    <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Healthcare Diagnostics (SDG 3)</span>
                  </div>
                  <span class="text-xs font-mono font-bold text-amber-600">19%</span>
                </div>
              </div>
            </div>

            <div class="mt-6 p-3.5 rounded-xl bg-brand-tint/80 dark:bg-slate-800/80 border border-[#D5E8F7] flex items-center gap-2.5">
              <span class="material-symbols-outlined text-brand-primary text-[22px]">workspace_premium</span>
              <span class="text-[11px] font-medium text-slate-700 dark:text-slate-300">Audited under Ministry of Education MIC Innovation Metrics Framework.</span>
            </div>
          </div>

        </div>

      </div>
    </section>

  </main>

  <!-- ========================================================================= -->
  <!-- SEARCH MODAL (Triggered by Search Button or Ctrl+K)                       -->
  <!-- ========================================================================= -->
  <div id="search-modal" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-start justify-center pt-24 px-4">
    <div class="w-full max-w-2xl rounded-2xl glass-card p-6 shadow-2xl border border-[#CAE7F7]">
      <div class="flex items-center justify-between pb-3 border-b border-[#E2EFF8] mb-4">
        <div class="flex items-center gap-2.5 w-full">
          <span class="material-symbols-outlined text-brand-primary text-[22px]">search</span>
          <input type="text" id="global-search-input" placeholder="Search challenges, skills, districts, or teams..." class="w-full bg-transparent text-slate-900 dark:text-white font-headline text-base focus:outline-none placeholder:text-slate-400"/>
        </div>
        <button onclick="toggleSearchModal()" class="text-slate-400 hover:text-slate-600 text-xs px-2 py-1 rounded bg-slate-100">ESC</button>
      </div>

      <div class="space-y-2">
        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Quick Shortcuts</span>
        <a href="#challenges" onclick="toggleSearchModal()" class="flex items-center justify-between p-3 rounded-xl hover:bg-brand-tint transition-colors">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-brand-primary">water_drop</span>
            <span class="text-xs font-bold text-slate-800">Water Contamination Problem in Bhiwandi (C2C-2026-W482)</span>
          </div>
          <span class="text-[11px] font-mono text-slate-400">#challenges</span>
        </a>
        <a href="#ai-matching" onclick="toggleSearchModal()" class="flex items-center justify-between p-3 rounded-xl hover:bg-brand-tint transition-colors">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-indigo-500">neurology</span>
            <span class="text-xs font-bold text-slate-800">Student AI Matchmaking Engine (Embedded C, ESP32)</span>
          </div>
          <span class="text-[11px] font-mono text-slate-400">#ai-matching</span>
        </a>
        <a href="#tracking" onclick="toggleSearchModal()" class="flex items-center justify-between p-3 rounded-xl hover:bg-brand-tint transition-colors">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-500">timeline</span>
            <span class="text-xs font-bold text-slate-800">Sprint Tracking: Phase 05 Active Sensor Telemetry</span>
          </div>
          <span class="text-[11px] font-mono text-slate-400">#tracking</span>
        </a>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- FLOATING AI COPILOT DOCK (BOTTOM RIGHT)                                   -->
  <!-- ========================================================================= -->
  <aside aria-label="AI Copilot" class="fixed bottom-6 right-6 z-40">
    <div class="hidden mb-3 w-80 sm:w-96 rounded-2xl glass-card shadow-2xl p-5 flex-col gap-3 border border-[#CAE7F7]" id="copilot-expanded">
      <div class="flex items-center justify-between pb-2 border-b border-[#E2EFF8]">
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-brand-primary animate-pulse"></span>
          <span class="font-headline font-bold text-sm text-slate-900">C2C Copilot AI</span>
          <span class="text-[10px] px-2 py-0.5 rounded bg-brand-pastel text-brand-deep font-bold">v2.4</span>
        </div>
        <button class="text-slate-400 hover:text-slate-600" onclick="toggleCopilot()">
          <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
      </div>
      
      <div class="p-3 rounded-xl bg-brand-tint/60 text-xs text-slate-700 leading-relaxed font-medium">
        "Hi! I'm C2C AI. Let's turn a civic challenge into an actionable solution. How can I guide you today?"
      </div>

      <!-- Quick Action Prompts -->
      <div class="flex flex-col gap-2">
        <button class="w-full text-left px-3 py-2 rounded-xl bg-white hover:bg-brand-tint border border-[#E2EFF8] text-xs font-semibold text-slate-800 transition-colors flex items-center justify-between" onclick="copilotAction('skills')">
          <span>⚡ Match My Department Skills</span>
          <span class="material-symbols-outlined text-[14px] text-brand-primary">chevron_right</span>
        </button>
        <button class="w-full text-left px-3 py-2 rounded-xl bg-white hover:bg-brand-tint border border-[#E2EFF8] text-xs font-semibold text-slate-800 transition-colors flex items-center justify-between" onclick="copilotAction('water')">
          <span>💧 Report Local Water / Sanitation Issue</span>
          <span class="material-symbols-outlined text-[14px] text-brand-primary">chevron_right</span>
        </button>
        <button class="w-full text-left px-3 py-2 rounded-xl bg-white hover:bg-brand-tint border border-[#E2EFF8] text-xs font-semibold text-slate-800 transition-colors flex items-center justify-between" onclick="copilotAction('ticket')">
          <span>🔍 Track C2C-2026-W482 Status</span>
          <span class="material-symbols-outlined text-[14px] text-brand-primary">chevron_right</span>
        </button>
      </div>

      <div class="relative mt-1">
        <input type="text" class="w-full bg-white px-3 py-2 text-xs rounded-xl border border-[#CAE7F7] text-slate-900 focus:outline-none focus:ring-1 focus:ring-brand-primary placeholder:text-slate-400" placeholder="Ask C2C AI anything..." onkeydown="if(event.key==='Enter')alert('Query parsed by C2C Semantic Engine: ' + this.value)"/>
      </div>
    </div>

    <!-- Copilot Button: Styled in Soft Sky Pastel Gradient (#CAE7F7) -->
    <button class="relative flex items-center gap-2.5 px-4 py-3 rounded-full bg-gradient-to-r from-sky-600 to-brand-primary text-white font-headline text-xs font-bold shadow-sky-md hover:scale-105 active:scale-95 transition-all" onclick="toggleCopilot()">
      <span class="material-symbols-outlined text-[20px] animate-spin" style="animation-duration: 20s;">neurology</span>
      <span class="hidden sm:inline">C2C AI Copilot</span>
      <span class="flex h-2 w-2 relative">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
      </span>
    </button>
  </aside>

  <!-- ========================================================================= -->
  <!-- FOOTER                                                                    -->
  <!-- ========================================================================= -->
  <footer class="w-full bg-white dark:bg-slate-950 border-t border-[#D5E8F7] dark:border-slate-800">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 pb-10 border-b border-[#E2EFF8] dark:border-slate-800">
        
        <div class="lg:col-span-2 flex flex-col gap-4">
          <div class="flex items-center gap-2.5">
            <div class="flex items-center justify-center w-8 h-8 rounded-xl bg-brand-pastel text-brand-deep">
              <span class="material-symbols-outlined text-xl">hub</span>
            </div>
            <span class="font-headline font-extrabold text-lg text-slate-900 dark:text-white tracking-tight">CAMPUS2COMMUNITY</span>
          </div>
          <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md leading-relaxed">
            Sovereign digital bridge unifying Smart India Hackathon innovators, public administration, university research, and civic ground telemetry for verified societal impact.
          </p>
          <div class="flex flex-col gap-1 mt-1">
            <span class="text-[10px] font-bold text-brand-deep dark:text-sky-300 uppercase tracking-wider">System Live Pulse</span>
            <div class="flex items-center gap-3 text-xs text-slate-500 font-medium">
              <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>AI Hub Latency: 42ms</span>
              <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-brand-primary"></span>Active Solvers: 14,820</span>
            </div>
          </div>
        </div>

        <div class="flex flex-col gap-3">
          <h4 class="font-headline font-bold text-xs uppercase tracking-wider text-slate-900 dark:text-white">Governance & SIH</h4>
          <ul class="flex flex-col gap-2 text-xs text-slate-500 dark:text-slate-400">
            <li><a href="about.html" class="hover:text-brand-deep transition-colors">About Us & Platform Vision</a></li>
            <li class="hover:text-brand-deep transition-colors cursor-pointer">Ministry of Education</li>
            <li class="hover:text-brand-deep transition-colors cursor-pointer">AICTE Portal Integration</li>
            <li class="hover:text-brand-deep transition-colors cursor-pointer">SIH 2026 Grand Finale</li>
            <li class="hover:text-brand-deep transition-colors cursor-pointer">MIC Innovation Cell</li>
          </ul>
        </div>

        <div class="flex flex-col gap-3">
          <h4 class="font-headline font-bold text-xs uppercase tracking-wider text-slate-900 dark:text-white">Partner Ecosystem</h4>
          <ul class="flex flex-col gap-2 text-xs text-slate-500 dark:text-slate-400">
            <li class="hover:text-brand-deep transition-colors cursor-pointer">State Public Universities</li>
            <li class="hover:text-brand-deep transition-colors cursor-pointer">Industry Research Labs</li>
            <li class="hover:text-brand-deep transition-colors cursor-pointer">Civic NGO Coalition</li>
            <li class="hover:text-brand-deep transition-colors cursor-pointer">Municipal Incubators</li>
          </ul>
        </div>

        <div class="flex flex-col gap-3">
          <h4 class="font-headline font-bold text-xs uppercase tracking-wider text-slate-900 dark:text-white">Ecosystem Dispatch</h4>
          <p class="text-xs text-slate-500 dark:text-slate-400">Receive validated challenge manifests and civic dispatches.</p>
          <div class="flex items-center rounded-xl bg-brand-tint dark:bg-slate-800 border border-[#CAE7F7] p-1">
            <input type="email" placeholder="Enter institutional email" class="w-full bg-transparent px-2.5 py-1 text-slate-900 dark:text-white text-xs focus:outline-none placeholder:text-slate-400"/>
            <button class="px-3 py-1 rounded-lg bg-brand-primary text-white font-bold text-xs hover:bg-sky-700 transition-colors">Join</button>
          </div>
          <span class="text-[10px] text-slate-400">Encrypted delivery via NIC / Gov cloud rails.</span>
        </div>

      </div>

      <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-slate-400 text-xs">
        <div>© 2026 CAMPUS2COMMUNITY (C2C) Sovereign Platform. All rights reserved.</div>
        <div class="flex items-center gap-4">
          <a href="#" class="hover:text-brand-deep transition-colors">Telemetry Privacy</a>
          <a href="#" class="hover:text-brand-deep transition-colors">API Architecture</a>
          <a href="#" class="hover:text-brand-deep transition-colors">Security Compliance</a>
          <a href="#" class="hover:text-brand-deep transition-colors">Status Console</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- ========================================================================= -->
  <!-- CLIENT INTERACTIVE BEHAVIOR & SCROLL-SPY SCRIPT                           -->
  <!-- ========================================================================= -->
  <script>
    // Stakeholder Data Matrix
    const actorData = {
      student: {
        title: "Student Innovator Portal",
        desc: "Direct access to AI-matched community challenges that align with university curriculum credits. Form interdisciplinary teams, unlock hardware stipends, and submit verified engineering solutions.",
        badges: [
          { text: "Earns AICTE Activity Points", color: "bg-brand-pastel text-brand-deep" },
          { text: "Lab Sandbox Access", color: "bg-white dark:bg-slate-800 border border-[#D5E8F7] text-slate-700" },
          { text: "Direct CSR Micro-grants", color: "bg-indigo-100 text-indigo-700" }
        ],
        statVal: "32,480 Active Students",
        statSub: "Spanning 89 disciplines & 412 accredited campuses nationwide."
      },
      community: {
        title: "Community & Citizen Desk",
        desc: "Empowering residents, ward counselors, and panchayat heads to report municipal distress in plain language or local voice recordings with automated geo-spatial tracking.",
        badges: [
          { text: "Voice / Dialect Support", color: "bg-emerald-100 text-emerald-800" },
          { text: "SMS Status Telemetry", color: "bg-white dark:bg-slate-800 border border-[#D5E8F7] text-slate-700" },
          { text: "Direct Public Officer Link", color: "bg-brand-pastel text-brand-deep" }
        ],
        statVal: "126 Active Blocks",
        statSub: "Zero paper bureaucracy with sub-24h verification turnarounds."
      },
      faculty: {
        title: "University Faculty & Lab Mentor Suite",
        desc: "Review student capstone viability, authorize department testing equipment, endorse intellectual property patents, and track state-level academic research rankings.",
        badges: [
          { text: "Institutional NIRF Credits", color: "bg-indigo-100 text-indigo-700" },
          { text: "Provisional IP Filing Rails", color: "bg-brand-pastel text-brand-deep" },
          { text: "Inter-college Co-guides", color: "bg-white dark:bg-slate-800 border border-[#D5E8F7] text-slate-700" }
        ],
        statVal: "1,840 Guided Projects",
        statSub: "Supported across civil, biotech, electrical, and computer science faculties."
      },
      industry: {
        title: "Corporate CSR & R&D Angel Deck",
        desc: "Channel mandatory CSR allocations under Companies Act Section 135 directly into high-efficacy collegiate prototypes with cryptographically verified milestone proofs.",
        badges: [
          { text: "Section 135 Compliant", color: "bg-amber-100 text-amber-800" },
          { text: "Escrow Milestone Payouts", color: "bg-emerald-100 text-emerald-800" },
          { text: "Direct Talent Acquisition", color: "bg-white dark:bg-slate-800 border border-[#D5E8F7] text-slate-700" }
        ],
        statVal: "₹4.8 Cr Committed",
        statSub: "42 corporate sponsors funding sustainable ground deployments."
      },
      admin: {
        title: "District Collector & Civic Dashboard",
        desc: "Single-window clearance for testing permits, road cutting authorizations, and municipal pilot installations with cross-departmental SLA compliance monitoring.",
        badges: [
          { text: "Fast-Track NOC Dispatch", color: "bg-brand-pastel text-brand-deep" },
          { text: "State GIS Overlay", color: "bg-white dark:bg-slate-800 border border-[#D5E8F7] text-slate-700" },
          { text: "Real-time Citizen Ratings", color: "bg-emerald-100 text-emerald-800" }
        ],
        statVal: "18 Civic Bodies Synced",
        statSub: "Municipal corporations operating on C2C instant clearance rails."
      }
    };

    function setActorView(actorKey) {
      const data = actorData[actorKey];
      if (!data) return;

      document.querySelectorAll('.actor-btn').forEach(btn => {
        if (btn.getAttribute('data-actor') === actorKey) {
          btn.className = "actor-btn active px-3.5 py-1.5 rounded-xl bg-brand-pastel text-brand-deep font-bold text-xs shadow-sm transition-all";
        } else {
          btn.className = "actor-btn px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-[#D5E8F7] text-slate-700 dark:text-slate-300 font-semibold text-xs hover:bg-brand-tint transition-all";
        }
      });

      document.getElementById('actor-title').textContent = data.title;
      document.getElementById('actor-desc').textContent = data.desc;
      document.getElementById('actor-stat-val').textContent = data.statVal;
      document.getElementById('actor-stat-sub').textContent = data.statSub;

      const badgesContainer = document.getElementById('actor-badges');
      badgesContainer.innerHTML = '';
      data.badges.forEach(b => {
        const span = document.createElement('span');
        span.className = `px-3 py-1 rounded-lg font-bold text-xs ${b.color}`;
        span.textContent = b.text;
        badgesContainer.appendChild(span);
      });
    }

    // Role-Specific Portals Switcher
    function switchPortalTab(portalKey) {
      const portals = ['student', 'admin', 'faculty', 'csr'];
      portals.forEach(p => {
        const view = document.getElementById('portal-view-' + p);
        const btn = document.getElementById('portal-tab-' + p);
        if (p === portalKey) {
          view.classList.remove('hidden');
          view.classList.add('block');
          btn.className = "portal-btn px-4 py-2 rounded-xl text-xs font-bold bg-brand-pastel text-brand-deep shadow-sm transition-all";
        } else {
          view.classList.add('hidden');
          view.classList.remove('block');
          btn.className = "portal-btn px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-brand-deep transition-all";
        }
      });
    }

    // Live Problem Report Preview
    function updateReportPreview() {
      const cat = document.getElementById('report-category-input').value;
      const loc = document.getElementById('report-location-input').value;
      const affected = document.getElementById('report-affected-input').value;

      document.getElementById('preview-category').textContent = cat;
      document.getElementById('preview-location').textContent = loc ? loc : '19.2968° N, 73.0631° E';
      document.getElementById('preview-affected').textContent = (affected || '0') + ' Verified Citizens';
    }

    function triggerSubmitConfirmation() {
      const randomID = 'C2C-2026-W' + Math.floor(100 + Math.random() * 900);
      document.getElementById('preview-ticket-id').textContent = randomID;
      alert('Problem manifest successfully registered with Sovereign Ticket ID: ' + randomID + '.\nDispatched to AI matching engine & 4 regional universities.');
      document.getElementById('ai-matching').scrollIntoView({behavior: 'smooth'});
    }

    // Floating Copilot Toggle
    function toggleCopilot() {
      const copilot = document.getElementById('copilot-expanded');
      copilot.classList.toggle('hidden');
      copilot.classList.toggle('flex');
    }

    function copilotAction(type) {
      if (type === 'skills') {
        document.getElementById('ai-matching').scrollIntoView({behavior: 'smooth'});
        toggleCopilot();
      } else if (type === 'water') {
        document.getElementById('challenges').scrollIntoView({behavior: 'smooth'});
        toggleCopilot();
        document.getElementById('report-category-input').value = 'Water & Sanitation (SDG 6)';
        updateReportPreview();
      } else if (type === 'ticket') {
        document.getElementById('tracking').scrollIntoView({behavior: 'smooth'});
        toggleCopilot();
      }
    }

    // Notification Dropdown Toggle
    function toggleNotificationDropdown() {
      const notif = document.getElementById('notification-dropdown');
      notif.classList.toggle('hidden');
    }

    // Global Search Modal Toggle
    function toggleSearchModal() {
      const modal = document.getElementById('search-modal');
      modal.classList.toggle('hidden');
      if (!modal.classList.contains('hidden')) {
        setTimeout(() => document.getElementById('global-search-input').focus(), 100);
      }
    }

    // Mobile Menu Toggle
    function toggleMobileMenu() {
      const menu = document.getElementById('mobile-menu');
      menu.classList.toggle('hidden');
      const icon = document.getElementById('mobile-menu-icon');
      icon.textContent = menu.classList.contains('hidden') ? 'menu' : 'close';
    }

    // Light / Dark Theme Switcher
    function toggleTheme() {
      const html = document.documentElement;
      const themeIcon = document.getElementById('theme-icon');
      if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        html.classList.add('light');
        themeIcon.textContent = 'dark_mode';
        localStorage.setItem('c2c-theme', 'light');
      } else {
        html.classList.remove('light');
        html.classList.add('dark');
        themeIcon.textContent = 'light_mode';
        localStorage.setItem('c2c-theme', 'dark');
      }
    }

    // Global Keyboard Listener (Ctrl+K or Cmd+K for search, ESC to close)
    window.addEventListener('keydown', (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        toggleSearchModal();
      } else if (e.key === 'Escape') {
        const modal = document.getElementById('search-modal');
        if (!modal.classList.contains('hidden')) modal.classList.add('hidden');
        const notif = document.getElementById('notification-dropdown');
        if (!notif.classList.contains('hidden')) notif.classList.add('hidden');
      }
    });

    // Dynamic Scroll-Spy: Highlights active section in navigation bar
    const sections = document.querySelectorAll('section[id]');
    const navPills = document.querySelectorAll('#main-nav .nav-pill');

    window.addEventListener('scroll', () => {
      let currentSectionId = '';
      const scrollPosition = window.scrollY + 120;

      sections.forEach(section => {
        const top = section.offsetTop;
        const height = section.offsetHeight;
        if (scrollPosition >= top && scrollPosition < top + height) {
          currentSectionId = section.getAttribute('id');
        }
      });

      if (currentSectionId) {
        navPills.forEach(pill => {
          pill.classList.remove('active');
          if (pill.getAttribute('href') === '#' + currentSectionId) {
            pill.classList.add('active');
          }
        });
      }
    });
  </script>

  <!-- ========================================================================= -->
  <!-- HIGH-PERFORMANCE INTERACTIVE ANIMATIONS & MICRO-INTERACTION ENGINE        -->
  <!-- ========================================================================= -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {

      // 1. SCROLL REVEAL OBSERVER
      const revealElements = document.querySelectorAll('.reveal-init, .reveal-scale, .reveal-left, .reveal-right');
      const revealObserverOptions = {
        threshold: 0.12,
        rootMargin: '0px 0px -40px 0px'
      };

      const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('reveal-visible');
          }
        });
      }, revealObserverOptions);

      revealElements.forEach(el => revealObserver.observe(el));

      // Auto-tag sections and cards for scroll reveal & 3D tilt
      document.querySelectorAll('section').forEach((section, sIdx) => {
        const cards = section.querySelectorAll('.glass-card, .rounded-2xl, .rounded-3xl');
        cards.forEach((card, index) => {
          if (!card.classList.contains('reveal-init') && !card.classList.contains('reveal-scale')) {
            card.classList.add('reveal-init', `stagger-${(index % 6) + 1}`);
            revealObserver.observe(card);
          }
          if (!card.classList.contains('tilt-card')) {
            card.classList.add('tilt-card', 'radial-spotlight');
          }
        });
      });

      // 2. 3D CARD TILT & RADIAL SPOTLIGHT ENGINE
      const tiltCards = document.querySelectorAll('.tilt-card');

      tiltCards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
          const rect = card.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;
          
          card.style.setProperty('--mouse-x', `${x}px`);
          card.style.setProperty('--mouse-y', `${y}px`);

          const centerX = rect.width / 2;
          const centerY = rect.height / 2;
          const rotateX = ((y - centerY) / centerY) * -5;
          const rotateY = ((x - centerX) / centerX) * 5;

          card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) scale3d(1.02, 1.02, 1.02)`;
        });

        card.addEventListener('mouseleave', () => {
          card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
        });
      });

      // 3. ANIMATED METRIC COUNTER ENGINE
      const counterElements = document.querySelectorAll('[data-counter]');

      const animateCounter = (el) => {
        const targetStr = el.getAttribute('data-counter') || el.innerText.trim();
        const numericVal = parseFloat(targetStr.replace(/[^0-9.]/g, ''));
        if (isNaN(numericVal)) return;

        const prefixMatch = targetStr.match(/^[^\d]*/);
        const suffixMatch = targetStr.match(/[^\d]*$/);
        const prefix = prefixMatch ? prefixMatch[0] : '';
        const suffix = suffixMatch ? suffixMatch[0] : '';
        const isDecimal = targetStr.includes('.');
        const decimalPlaces = isDecimal ? (targetStr.split('.')[1] || '').replace(/[^0-9]/g, '').length : 0;

        let startTime = null;
        const duration = 1800;

        const step = (timestamp) => {
          if (!startTime) startTime = timestamp;
          const progress = Math.min((timestamp - startTime) / duration, 1);
          const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
          const currentVal = numericVal * easeProgress;

          let formattedVal = isDecimal ? currentVal.toFixed(decimalPlaces) : Math.floor(currentVal).toLocaleString();
          el.innerText = `${prefix}${formattedVal}${suffix}`;

          if (progress < 1) {
            requestAnimationFrame(step);
          } else {
            el.innerText = targetStr;
          }
        };

        requestAnimationFrame(step);
      };

      const counterObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            animateCounter(entry.target);
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.2 });

      counterElements.forEach(el => counterObserver.observe(el));

      // 4. ANIMATED PROGRESS BARS
      const progressBars = document.querySelectorAll('.animate-progress-bar');
      const progressObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const targetWidth = entry.target.getAttribute('data-width') || entry.target.style.width;
            entry.target.style.width = '0%';
            setTimeout(() => {
              entry.target.style.transition = 'width 1.2s cubic-bezier(0.16, 1, 0.3, 1)';
              entry.target.style.width = targetWidth;
            }, 100);
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.2 });

      progressBars.forEach(bar => progressObserver.observe(bar));

      // 5. MAGNETIC BUTTONS & RIPPLE ENGINE
      const magneticBtns = document.querySelectorAll('.magnetic-btn, button, a.bg-gradient-to-r');
      magneticBtns.forEach(btn => {
        btn.classList.add('magnetic-btn', 'ripple-target');
        
        btn.addEventListener('mousemove', (e) => {
          const rect = btn.getBoundingClientRect();
          const x = e.clientX - rect.left - rect.width / 2;
          const y = e.clientY - rect.top - rect.height / 2;
          btn.style.transform = `translate(${x * 0.15}px, ${y * 0.15}px)`;
        });

        btn.addEventListener('mouseleave', () => {
          btn.style.transform = 'translate(0px, 0px)';
        });

        btn.addEventListener('click', function(e) {
          const circle = document.createElement('span');
          circle.classList.add('ripple-circle');
          const rect = this.getBoundingClientRect();
          const diameter = Math.max(rect.width, rect.height);
          const radius = diameter / 2;

          circle.style.width = circle.style.height = `${diameter}px`;
          circle.style.left = `${e.clientX - rect.left - radius}px`;
          circle.style.top = `${e.clientY - rect.top - radius}px`;

          const existingRipple = this.querySelector('.ripple-circle');
          if (existingRipple) existingRipple.remove();

          this.appendChild(circle);
        });
      });

      // 6. HERO INTERACTIVE PARTICLE CANVAS MESH
      const heroSection = document.getElementById('home');
      if (heroSection) {
        const canvas = document.createElement('canvas');
        canvas.id = 'hero-particle-canvas';
        canvas.style.cssText = 'position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; opacity: 0.45;';
        heroSection.insertBefore(canvas, heroSection.firstChild);

        const ctx = canvas.getContext('2d');
        let width = canvas.width = heroSection.offsetWidth;
        let height = canvas.height = heroSection.offsetHeight;

        window.addEventListener('resize', () => {
          width = canvas.width = heroSection.offsetWidth;
          height = canvas.height = heroSection.offsetHeight;
        });

        let mouse = { x: null, y: null, radius: 140 };
        heroSection.addEventListener('mousemove', (e) => {
          const rect = heroSection.getBoundingClientRect();
          mouse.x = e.clientX - rect.left;
          mouse.y = e.clientY - rect.top;
        });
        heroSection.addEventListener('mouseleave', () => {
          mouse.x = null;
          mouse.y = null;
        });

        const particles = [];
        const particleCount = Math.min(Math.floor(width / 28), 45);

        for (let i = 0; i < particleCount; i++) {
          particles.push({
            x: Math.random() * width,
            y: Math.random() * height,
            radius: Math.random() * 2 + 1.5,
            vx: (Math.random() - 0.5) * 0.6,
            vy: (Math.random() - 0.5) * 0.6
          });
        }

        function drawParticles() {
          ctx.clearRect(0, 0, width, height);
          const isDark = document.documentElement.classList.contains('dark');
          const pColor = isDark ? '56, 189, 248' : '2, 132, 199';

          particles.forEach((p, index) => {
            p.x += p.vx;
            p.y += p.vy;

            if (p.x < 0 || p.x > width) p.vx *= -1;
            if (p.y < 0 || p.y > height) p.vy *= -1;

            if (mouse.x !== null && mouse.y !== null) {
              const dx = mouse.x - p.x;
              const dy = mouse.y - p.y;
              const dist = Math.sqrt(dx * dx + dy * dy);
              if (dist < mouse.radius) {
                const force = (mouse.radius - dist) / mouse.radius;
                p.x -= (dx / dist) * force * 2.5;
                p.y -= (dy / dist) * force * 2.5;
              }
            }

            ctx.beginPath();
            ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(${pColor}, ${isDark ? 0.6 : 0.4})`;
            ctx.fill();

            for (let j = index + 1; j < particles.length; j++) {
              const p2 = particles[j];
              const dx = p.x - p2.x;
              const dy = p.y - p2.y;
              const dist = Math.sqrt(dx * dx + dy * dy);
              if (dist < 120) {
                ctx.beginPath();
                ctx.moveTo(p.x, p.y);
                ctx.lineTo(p2.x, p2.y);
                ctx.strokeStyle = `rgba(${pColor}, ${0.25 * (1 - dist / 120)})`;
                ctx.lineWidth = 0.8;
                ctx.stroke();
              }
            }
          });

          requestAnimationFrame(drawParticles);
        }

        drawParticles();
      }
    });

    // =========================================================================
    // HIGH-PERFORMANCE 2D CANVAS PROBLEM-TO-SOLUTION FLOW ENGINE
    // =========================================================================
    document.addEventListener('DOMContentLoaded', () => {

      const canvas = document.createElement('canvas');
      canvas.id = 'c2c-flow-canvas';
      canvas.style.cssText = 'position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; pointer-events: none; z-index: -1; opacity: 0.95;';
      document.body.insertBefore(canvas, document.body.firstChild);

      const ctx = canvas.getContext('2d');
      let width = canvas.width = window.innerWidth;
      let height = canvas.height = window.innerHeight;

      window.addEventListener('resize', () => {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
      });

      const problemLabels = ["Potholes", "Droughts", "Floods", "Arsenic Water", "Power Outages", "Waste Crisis"];
      const solutionLabels = ["AI Road Repair Mesh", "Solar PCM Storage", "Nano Water Filter", "IoT Oxygen Telemetry", "Clean Grid Micro-Hub"];

      const activeTextFloating = [];

      class FlowParticle {
        constructor() {
          this.reset(true);
        }

        reset(isInitial = false) {
          this.x = isInitial ? Math.random() * width : -30;
          this.baseY = Math.random() * (height * 0.8) + height * 0.1;
          this.y = this.baseY;
          this.speed = Math.random() * 2.2 + 1.6;
          this.radius = Math.random() * 3.8 + 2.4;
          this.sineFrequency = Math.random() * 0.018 + 0.008;
          this.sineAmplitude = Math.random() * 65 + 30;
          this.noiseOffset = Math.random() * 100;
          this.hasSpawnedLabel = false;
        }

        update(time) {
          this.x += this.speed;

          // PHASE 1: LEFT SIDE (PROBLEMS & TURBULENCE)
          if (this.x < width * 0.42) {
            this.y = this.baseY + Math.sin(this.x * this.sineFrequency + time * 2.5 + this.noiseOffset) * this.sineAmplitude;
            this.color = `rgba(239, 68, 68, ${Math.min(this.x / 140, 0.85)})`;
            
            if (!this.hasSpawnedLabel && Math.random() < 0.012 && this.x > 90 && this.x < width * 0.36) {
              const labelText = problemLabels[Math.floor(Math.random() * problemLabels.length)];
              activeTextFloating.push({
                x: this.x,
                y: this.y - 16,
                text: labelText,
                type: 'problem',
                opacity: 1,
                vx: this.speed * 0.95
              });
              this.hasSpawnedLabel = true;
            }
          } 
          // PHASE 2: CENTER AI HUB ORCHESTRATOR (CONDENSE & PULSE)
          else if (this.x >= width * 0.42 && this.x <= width * 0.58) {
            const targetY = height * 0.5;
            this.y += (targetY - this.y) * 0.1;
            this.color = `rgba(56, 189, 248, 0.98)`;
          } 
          // PHASE 3: RIGHT SIDE (SOLUTIONS & CALM DATA VECTORS)
          else {
            this.y += (this.baseY - this.y) * 0.06;
            this.color = `rgba(16, 185, 129, ${Math.min((width - this.x) / 180, 0.9)})`;

            if (!this.hasSpawnedLabel && Math.random() < 0.012 && this.x > width * 0.62 && this.x < width * 0.88) {
              const labelText = solutionLabels[Math.floor(Math.random() * solutionLabels.length)];
              activeTextFloating.push({
                x: this.x,
                y: this.y - 16,
                text: labelText,
                type: 'solution',
                opacity: 1,
                vx: this.speed * 0.95
              });
              this.hasSpawnedLabel = true;
            }
          }

          if (this.x > width + 40) {
            this.reset();
          }
        }

        draw(ctx) {
          ctx.beginPath();
          ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
          ctx.fillStyle = this.color;
          ctx.shadowColor = this.color;
          ctx.shadowBlur = 12;
          ctx.fill();
        }
      }

      const particles = Array.from({ length: 95 }, () => new FlowParticle());
      let animationTime = 0;

      function renderFrame() {
        ctx.clearRect(0, 0, width, height);
        animationTime += 0.016;

        const isDark = document.documentElement.classList.contains('dark');

        // 1. Draw Connecting Vector Lines
        for (let i = 0; i < particles.length; i++) {
          const p1 = particles[i];
          p1.update(animationTime);
          p1.draw(ctx);

          for (let j = i + 1; j < particles.length; j++) {
            const p2 = particles[j];
            const dx = p1.x - p2.x;
            const dy = p1.y - p2.y;
            const dist = Math.sqrt(dx * dx + dy * dy);

            if (dist < 125) {
              ctx.beginPath();
              ctx.moveTo(p1.x, p1.y);
              ctx.lineTo(p2.x, p2.y);
              
              if (p1.x < width * 0.42) {
                ctx.strokeStyle = `rgba(245, 158, 11, ${0.35 * (1 - dist / 125)})`;
              } else if (p1.x <= width * 0.58) {
                ctx.strokeStyle = `rgba(56, 189, 248, ${0.6 * (1 - dist / 125)})`;
              } else {
                ctx.strokeStyle = `rgba(16, 185, 129, ${0.4 * (1 - dist / 125)})`;
              }
              ctx.lineWidth = 1.6;
              ctx.stroke();
            }
          }
        }

        // 2. Draw ENLARGED Center AI Core Orchestrator Pulse Ring
        const centerX = width * 0.5;
        const centerY = height * 0.5;
        const pulseRadius = 110 + Math.sin(animationTime * 3) * 16;

        ctx.save();
        
        // Inner Glowing Radial Core
        const radGrad = ctx.createRadialGradient(centerX, centerY, 10, centerX, centerY, pulseRadius);
        radGrad.addColorStop(0, isDark ? 'rgba(56, 189, 248, 0.45)' : 'rgba(2, 132, 199, 0.25)');
        radGrad.addColorStop(1, 'rgba(56, 189, 248, 0)');
        ctx.fillStyle = radGrad;
        ctx.beginPath();
        ctx.arc(centerX, centerY, pulseRadius, 0, Math.PI * 2);
        ctx.fill();

        // Core Ring 1
        ctx.beginPath();
        ctx.arc(centerX, centerY, pulseRadius, 0, Math.PI * 2);
        ctx.strokeStyle = `rgba(56, 189, 248, 0.85)`;
        ctx.lineWidth = 3.5;
        ctx.shadowColor = '#38BDF8';
        ctx.shadowBlur = 28;
        ctx.stroke();

        // Core Ring 2 (Outer Orbit)
        ctx.beginPath();
        ctx.arc(centerX, centerY, pulseRadius * 1.35, 0, Math.PI * 2);
        ctx.strokeStyle = `rgba(6, 182, 212, 0.5)`;
        ctx.lineWidth = 1.8;
        ctx.setLineDash([12, 18]);
        ctx.stroke();
        ctx.setLineDash([]);

        // Core Ring 3 (Far Halo)
        ctx.beginPath();
        ctx.arc(centerX, centerY, pulseRadius * 1.65, 0, Math.PI * 2);
        ctx.strokeStyle = `rgba(14, 165, 233, 0.25)`;
        ctx.lineWidth = 1;
        ctx.stroke();

        // Core Badge Pill Box
        const textStr = '⚡ C2C CORE AI ORCHESTRATOR';
        ctx.font = 'bold 12px "Plus Jakarta Sans", sans-serif';
        const textMetrics = ctx.measureText(textStr);
        const boxW = textMetrics.width + 24;
        const boxH = 26;

        ctx.fillStyle = isDark ? 'rgba(15, 23, 42, 0.9)' : 'rgba(255, 255, 255, 0.92)';
        ctx.strokeStyle = '#38BDF8';
        ctx.lineWidth = 1.5;
        ctx.beginPath();
        ctx.roundRect(centerX - boxW / 2, centerY + pulseRadius + 18, boxW, boxH, 13);
        ctx.fill();
        ctx.stroke();

        ctx.fillStyle = isDark ? '#38BDF8' : '#0284C7';
        ctx.textAlign = 'center';
        ctx.fillText(textStr, centerX, centerY + pulseRadius + 35);
        ctx.restore();

        // 3. Draw Enriched Floating Text Labels
        for (let i = activeTextFloating.length - 1; i >= 0; i--) {
          const item = activeTextFloating[i];
          item.x += item.vx;
          item.opacity -= 0.0035;

          if (item.opacity <= 0 || item.x > width) {
            activeTextFloating.splice(i, 1);
            continue;
          }

          ctx.save();
          ctx.font = 'bold 12px "Plus Jakarta Sans", sans-serif';
          
          const labelMetrics = ctx.measureText(item.text);
          const lw = labelMetrics.width + 16;
          const lh = 22;

          // Capsule badge background
          if (item.type === 'problem') {
            ctx.fillStyle = isDark ? `rgba(127, 29, 29, ${item.opacity * 0.85})` : `rgba(254, 242, 242, ${item.opacity * 0.95})`;
            ctx.strokeStyle = `rgba(239, 68, 68, ${item.opacity})`;
          } else {
            ctx.fillStyle = isDark ? `rgba(6, 78, 59, ${item.opacity * 0.85})` : `rgba(236, 253, 245, ${item.opacity * 0.95})`;
            ctx.strokeStyle = `rgba(16, 185, 129, ${item.opacity})`;
          }
          
          ctx.lineWidth = 1.2;
          ctx.beginPath();
          ctx.roundRect(item.x - 8, item.y - 14, lw, lh, 8);
          ctx.fill();
          ctx.stroke();

          ctx.fillStyle = item.type === 'problem' ? `rgba(239, 68, 68, ${item.opacity})` : `rgba(16, 185, 129, ${item.opacity})`;
          ctx.fillText(item.text, item.x, item.y);
          ctx.restore();
        }

        requestAnimationFrame(renderFrame);
      }

      renderFrame();
    });
  </script>

  <!-- ========================================================================= -->
  <!-- MULTILINGUAL TRANSLATION ENGINE (EN, HI, MR)                              -->
  <!-- ========================================================================= -->
  <script>
    const c2cTranslations = {
      en: {
        nav_home: "Home",
        nav_challenges: "Challenges",
        nav_ai: "AI Match",
        nav_ecosystem: "Ecosystem",
        nav_modules: "Modules",
        nav_portals: "Portals",
        nav_tracking: "Tracking",
        nav_analytics: "Analytics",
        nav_about: "About Us",
        nav_mission: "Mission",
        nav_timeline: "Timeline",
        nav_pillars: "Pillars",
        nav_leadership: "Leadership",
        btn_report_problem: "Report Problem",
        hero_headline: "Turn Campus Ideas Into Community Solutions.",
        hero_subhead: "Sovereign digital bridge unifying Smart India Hackathon innovators, public administration, university research, and civic ground telemetry for verified societal impact.",
        mesh_tag: "SIH 2026 Innovation Mesh",
        active_challenges_count: "1,284 Verified Challenges Active",
        hero_cta_report: "Report a Ground Problem",
        hero_cta_ai: "Explore AI Matching",
        hero_cta_how: "How C2C Works",
        challenges_tag: "Civic Intake Engine",
        challenges_title: "Civic Problem Intake & Manifests",
        challenges_subtitle: "Empowering ward counselors, village sarpanches, and citizens to register distress vectors with automated GPS tagging and NLP deduplication.",
        form_category_label: "Challenge Category",
        form_location_label: "Location / Ward",
        form_affected_label: "Affected Citizens",
        form_details_label: "Problem Details & Media",
        form_submit_btn: "Submit for Sovereign AI Intake Verification",
        feed_title: "Live Problem Feed",
        ai_tag: "Smart Matching Engine",
        ai_matching_title: "AI Matching Workspace",
        ai_subtitle: "Deep neural matching algorithm pairing SIH student technical skills with municipality problem statements.",
        squad_btn: "Join Solution Squad",
        eco_tag: "SIH 9-Stage Architecture",
        eco_title: "The 9-Stage Impact Pipeline",
        eco_subtitle: "From ground-level citizen distress calls to verified societal deployment, every milestone is auditable and automated.",
        modules_tag: "Enterprise Architecture",
        modules_title: "8 Core Interconnected Impact Modules",
        modules_subtitle: "Engineered for high-volume civic scale, regulatory Section 135 compliance, and frictionless cross-sector execution.",
        portals_tag: "Multi-Role Command Portals",
        portals_title: "Role-Specific Workspaces",
        tracking_tag: "Real-time Monitoring",
        tracking_title: "Live Project Execution",
        analytics_tag: "National SROI Metrics",
        analytics_title: "Impact Analytics & Command Center",
        search_modal_title: "Search Campus2Community Platform",
        footer_copyright: "© 2026 Campus2Community (C2C). Built for Smart India Hackathon 2026.",
        about_hero_tag: "Sovereign Civic Ecosystem",
        about_hero_title: "Empowering India's Youth to Build Resilient Communities",
        about_hero_subtitle: "C2C creates a direct digital bridge between 10,000+ technical colleges and 4,500+ Urban Local Bodies (ULBs).",
        mission_title: "Our Mission & Vision",
        vision_title: "Institutional Framework",
        journey_title: "The C2C Evolutionary Roadmap",
        journey_subtitle: "From a national hackathon concept to India's primary civic tech execution engine.",
        pillars_title: "Core Ecosystem Pillars",
        leadership_title: "Executive Leadership & Mentors",
        faq_title: "Frequently Asked Questions"
      },
      hi: {
        nav_home: "मुख्य पृष्ठ",
        nav_challenges: "चुनौतियां",
        nav_ai: "एआई मिलान",
        nav_ecosystem: "इकोसिस्टम",
        nav_modules: "मॉड्यूल",
        nav_portals: "पोर्टल",
        nav_tracking: "लाइव ट्रैकिंग",
        nav_analytics: "विश्लेषण",
        nav_about: "हमारे बारे में",
        nav_mission: "हमारा लक्ष्य",
        nav_timeline: "समयरेखा",
        nav_pillars: "स्तंभ",
        nav_leadership: "नेतृत्व",
        btn_report_problem: "समस्या दर्ज करें",
        hero_headline: "कॉलेज के विचारों को नागरिक समाधानों में बदलें।",
        hero_subhead: "स्मार्ट इंडिया हैकाथॉन नवप्रवर्तकों, सार्वजनिक प्रशासन, विश्वविद्यालय अनुसंधान और नागरिक जमीनी डेटा को जोड़ने वाला संप्रभु डिजिटल ब्रिज।",
        mesh_tag: "एसआईएच 2026 इनोवेशन मेश",
        active_challenges_count: "1,284 सत्यापित चुनौतियां सक्रिय",
        hero_cta_report: "जमीनी समस्या रिपोर्ट करें",
        hero_cta_ai: "एआई मैचमेकिंग देखें",
        hero_cta_how: "C2C कैसे काम करता है",
        challenges_tag: "नागरिक इनटेक इंजन",
        challenges_title: "नागरिक समस्या इनटेक और मैनिफेस्ट",
        challenges_subtitle: "वार्ड पार्षदों, सरपंचों और नागरिकों को स्वचालित जीपीएस टैगिंग और एनएलपी डीडुप्लीकेशन के साथ समस्याएं दर्ज करने में सक्षम बनाना।",
        form_category_label: "चुनौती की श्रेणी",
        form_location_label: "स्थान / वार्ड",
        form_affected_label: "प्रभावित नागरिक",
        form_details_label: "समस्या विवरण और फोटो/वीडियो",
        form_submit_btn: "संप्रभु एआई इनटेक सत्यापन के लिए जमा करें",
        feed_title: "लाइव समस्या फीड",
        ai_tag: "स्मार्ट मैचिंग इंजन",
        ai_matching_title: "एआई मैचमेकिंग कार्यक्षेत्र",
        ai_subtitle: "छात्रों के तकनीकी कौशल को नगरपालिका समस्या विवरणों से जोड़ने वाला डीप न्यूरल मैचिंग एल्गोरिदम।",
        squad_btn: "समाधान टीम में शामिल हों",
        eco_tag: "एसआईएच 9-चरण आर्किटेक्चर",
        eco_title: "9-चरण प्रभाव पाइपलाइन",
        eco_subtitle: "नागरिक समस्या कॉल से लेकर सत्यापित सामाजिक तैनाती तक, हर चरण ऑडिट योग्य और स्वचालित है।",
        modules_tag: "एंटरप्राइज आर्किटेक्चर",
        modules_title: "8 मुख्य परस्पर जुड़े प्रभाव मॉड्यूल",
        modules_subtitle: "उच्च-स्तरीय नागरिक पैमाने, धारा 135 अनुपालन और सुगम निष्पादन के लिए निर्मित।",
        portals_tag: "मल्टी-रोल कमान पोर्टल",
        portals_title: "भूमिका-विशिष्ट कार्यक्षेत्र",
        tracking_tag: "रियल-टाइम निगरानी",
        tracking_title: "लाइव प्रोजेक्ट निष्पादन",
        analytics_tag: "राष्ट्रीय SROI मेट्रिक्स",
        analytics_title: "प्रभाव विश्लेषण और कमान केंद्र",
        search_modal_title: "Campus2Community प्लेटफॉर्म खोजें",
        footer_copyright: "© 2026 Campus2Community (C2C). स्मार्ट इंडिया हैकाथॉन 2026 के लिए निर्मित।",
        about_hero_tag: "संप्रभु नागरिक इकोसिस्टम",
        about_hero_title: "भारत के युवाओं को मजबूत समुदाय बनाने में सक्षम बनाना",
        about_hero_subtitle: "C2C 10,000+ तकनीकी कॉलेजों और 4,500+ शहरी स्थानीय निकायों के बीच डिजिटल पुल बनाता है।",
        mission_title: "हमारा मिशन और विजन",
        vision_title: "संस्थागत ढांचा",
        journey_title: "C2C विकास यात्रा",
        journey_subtitle: "राष्ट्रीय हैकाथॉन अवधारणा से भारत का प्राथमिक नागरिक तकनीक निष्पादन इंजन।",
        pillars_title: "मुख्य इकोसिस्टम स्तंभ",
        leadership_title: "कार्यकारी नेतृत्व और संरक्षक",
        faq_title: "अक्सर पूछे जाने वाले प्रश्न"
      },
      mr: {
        nav_home: "मुख्यपृष्ठ",
        nav_challenges: "आव्हाने",
        nav_ai: "एआय जुळणी",
        nav_ecosystem: "इकोसिस्टम",
        nav_modules: "मॉड्यूल्स",
        nav_portals: "पोर्टल्स",
        nav_tracking: "ट्रॅकिंग",
        nav_analytics: "विश्लेषण",
        nav_about: "आमच्याबद्दल",
        nav_mission: "ध्येय",
        nav_timeline: "वेळापत्रक",
        nav_pillars: "स्तंभ",
        nav_leadership: "नेतृत्व",
        btn_report_problem: "समस्या नोंदवा",
        hero_headline: "कॉलेजच्या कल्पनांना सामाजिक उपायांमध्ये बदला.",
        hero_subhead: "स्मार्ट इंडिया हॅकाथॉन संशोधकांना थेट नगरपालिका प्रशासनाशी जोडून नागरिकांच्या समस्या सोडवणारा डिजिटल सेतू.",
        mesh_tag: "SIH 2026 इनोपल्स मेश",
        active_challenges_count: "1,284 पडताळलेली आव्हाने सक्रिय",
        hero_cta_report: "जमिनीवरील समस्या नोंदवा",
        hero_cta_ai: "एआय मॅचिंग एक्सप्लोर करा",
        hero_cta_how: "C2C कसे कार्य करते",
        challenges_tag: "नागरी इनटेक इंजिन",
        challenges_title: "नागरी समस्या नोंदणी व मॅनिफेस्ट",
        challenges_subtitle: "जीपीएस टॅगिंग व एनएलपी पडताळणीसह नागरी आव्हाने सहज नोंदवण्याची सुविधा.",
        form_category_label: "आव्हानाची श्रेणी",
        form_location_label: "स्थान / प्रभाग",
        form_affected_label: "बाधित नागरिक",
        form_details_label: "समस्या तपशील व फोटो/व्हिडिओ",
        form_submit_btn: "एआय इनटेक पडताळणीसाठी सबमिट करा",
        feed_title: "थेट समस्या फीड",
        ai_tag: "स्मार्ट मॅचिंग इंजिन",
        ai_matching_title: "एआय मॅचमेकिंग वर्कस्पेस",
        ai_subtitle: "विद्यार्थ्यांचे कौशल्य आणि पालिकेच्या समस्या जोडणारा डीप न्यूरल अल्गोरिदम.",
        squad_btn: "समाधान टीममध्ये सामील व्हा",
        eco_tag: "SIH 9-टप्प्यांचे आर्किटेक्चर",
        eco_title: "9-टप्प्यांची प्रभाव पायपलाईन",
        eco_subtitle: "समस्या नोंदणीपासून ते थेट प्रत्यक्ष अंमलबजावणीपर्यंत सर्व टप्पे पारदर्शक व स्वयंचलित.",
        modules_tag: "एंटरप्राइज आर्किटेक्चर",
        modules_title: "8 मुख्य जोडलेले प्रभाव मॉड्यूल्स",
        modules_subtitle: "नागरी प्रभाव, कलम 135 सुसंगतता आणि सुलभ कार्यपद्धतीसाठी डिझाइन केलेले.",
        portals_tag: "मल्टी-रोल कमांड पोर्टल्स",
        portals_title: "भूमिका-विशिष्ट वर्कस्पेस",
        tracking_tag: "रिअल-टाईम मॉनिटरिंग",
        tracking_title: "थेट प्रकल्प अंमलबजावणी",
        analytics_tag: "राष्ट्रीय SROI मेट्रिक्स",
        analytics_title: "प्रभाव विश्लेषण व कमांड सेंटर",
        search_modal_title: "Campus2Community प्लॅटफॉर्म शोधा",
        footer_copyright: "© 2026 Campus2Community (C2C). स्मार्ट इंडिया हॅकाथॉन 2026 साठी निर्मित.",
        about_hero_tag: "नागरी इकोसिस्टम",
        about_hero_title: "भारतातील तरुणांना मजबूत समाज घडवण्यासाठी सक्षम करणे",
        about_hero_subtitle: "C2C 10,000+ तांत्रिक कॉलेजेस आणि 4,500+ महापालिकांमध्ये थेट डिजिटल सेतू निर्माण करते.",
        mission_title: "आमचे ध्येय आणि संकल्पना",
        vision_title: "संस्थात्मक रचना",
        journey_title: "C2C चा विकास प्रवास",
        journey_subtitle: "राष्ट्रीय हॅकाथॉन कल्पनेपासून भारताचे मुख्य नागरी तंत्रज्ञान इंजिन.",
        pillars_title: "मुख्य इकोसिस्टम स्तंभ",
        leadership_title: "कार्यकारी नेतृत्व आणि मार्गदर्शक",
        faq_title: "वारंवार विचारले जाणारे प्रश्न"
      }
    };

                const c2cTextDictionary = {
    "hi": {
      "To establish a nationwide digital grid where zero citizen distress calls go unanswered, and every engineering student graduates with verified, real-world societal problem-solving experience backed by government NOC clearance and institutional recognition.": "एक देशव्यापी डिजिटल ग्रिड स्थापित करना जहां नागरिकों की कोई भी समस्या अनसुनी न रहे, और प्रत्येक इंजीनियरिंग छात्र सरकारी एनओसी मंजूरी और संस्थागत मान्यता के साथ सत्यापित सामाजिक समस्याओं को हल करने का अनुभव प्राप्त करे।",
      "To seamlessly unify the Ministry of Education, AICTE academic frameworks, Municipal Corporations, District Collectors, and Corporate CSR wings under a single cryptographic, AI-driven workflow that matches ground challenges to college labs in real time.": "शिक्षा मंत्रालय, एआईसीटीई अकादमिक ढांचे, नगर निगमों, जिला कलेक्टरों और कॉरपोरेट सीएसआर विंग्स को एक एआई-संचालित वर्कफ़्लो के तहत निर्बाध रूप से जोड़ना जो वास्तविक समय में कॉलेज लैब से जमीनी चुनौतियों का मिलान करता है।",
      "Severe industrial arsenic runoff affecting Bhiwandi rural blocks. Recommended project architecture: solar-powered UV/Arsenic electro-flocculation unit paired with real-time sub-surface telemetry nodes reporting to district dashboard.": "भिवंडी ग्रामीण क्षेत्रों को प्रभावित करने वाला गंभीर औद्योगिक आर्सेनिक रिसाव। अनुशंसित परियोजना वास्तुकला: सौर-संचालित यूवी/आर्सेनिक इलेक्ट्रो-फ्लोकुलेशन इकाई जो जिला डैशबोर्ड को रिपोर्ट करने वाले रियल-टाइम टेलीमेट्री नोड्स के साथ जुड़ी है।",
      "C2C is integrated directly with AICTE Activity Point guidelines. When a student team completes a ground solution endorsed by their faculty guide and municipal inspector, credits are pushed directly into their university transcript.": "C2C को सीधे एआईसीटीई गतिविधि बिंदु दिशानिर्देशों के साथ एकीकृत किया गया है। जब कोई छात्र टीम अपने संकाय गाइड और नगर पालिका निरीक्षक द्वारा समर्थित समाधान पूरा करती है, तो क्रेडिट सीधे उनके विश्वविद्यालय टेप में स्थानांतरित कर दिए जाते हैं।",
      "Citizens, municipal counselors, or local ward heads submit ground issues via text, photos, or voice notes. The C2C AI semantic engine geotags the issue, removes duplicate entries, and verifies municipal urgency within 24 hours.": "नागरिक, नगर पार्षद, या स्थानीय वार्ड प्रमुख टेक्स्ट, फोटो या वॉयस नोट्स के माध्यम से जमीनी मुद्दे प्रस्तुत करते हैं। C2C AI इंजन इस मुद्दे को जियोटैग करता है, डुप्लिकेट प्रविष्टियों को हटाता है, और 24 घंटे के भीतर नगरपालिका की तात्कालिकता सत्यापित करता है।",
      "Yes. All CSR contributions are managed via escrow contracts compliance-mapped to Companies Act Section 135. Automated milestone reports, municipal utilization certificates, and receipt tokens are generated upon every payout.": "हां। सभी सीएसआर योगदान कंपनियों के अधिनियम की धारा 135 के अनुपालन-मैप किए गए एस्क्रो अनुबंधों के माध्यम से प्रबंधित किए जाते हैं। प्रत्येक भुगतान पर स्वचालित मील का पत्थर रिपोर्ट, नगर पालिका उपयोग प्रमाण पत्र और रसीद टोकन उत्पन्न होते हैं।",
      "Institutional heads can register directly on the C2C portal using their official domain email (.edu.in or .gov.in). Account clearance takes less than 2 hours following automated credential verification.": "संस्थागत प्रमुख अपने आधिकारिक डोमेन ईमेल (.edu.in या .gov.in) का उपयोग करके सीधे C2C पोर्टल पर पंजीकरण कर सकते हैं। स्वचालित क्रेडेंशियल सत्यापन के बाद खाता मंजूरी में 2 घंटे से भी कम समय लगता है।",
      "Direct access to AI-matched community challenges that align with university curriculum credits. Form interdisciplinary teams, unlock hardware stipends, and submit verified engineering solutions.": "विश्वविद्यालय के पाठ्यक्रम क्रेडिट के साथ संरेखित एआई-मिलान वाली सामुदायिक चुनौतियों तक सीधी पहुंच। अंतर-विषय टीमों का गठन करें, हार्डवेयर वजीफा अनलॉक करें और सत्यापित इंजीनियरिंग समाधान प्रस्तुत करें।",
      "Sovereign digital bridge unifying Smart India Hackathon innovators, public administration, university research, and civic ground telemetry for verified societal impact.": "स्मार्ट इंडिया हैकाथॉन नवप्रवर्तकों, सार्वजनिक प्रशासन, विश्वविद्यालय अनुसंधान और नागरिक जमीनी टेलीमेट्री को एकजुट करने वाला डिजिटल सेतु।",
      "Transforms vernacular or voice distress reports into structured engineering specs, automatically routing them to department labs based on faculty domain expertise.": "स्थानीय भाषा या वॉयस रिपोर्टों को संरचित इंजीनियरिंग स्पेक्स में परिवर्तित करता है, जो संकाय की विशेषज्ञता के आधार पर स्वचालित रूप से विभाग प्रयोगशालाओं को रूट करता है।",
      "Empowering ward counselors, village sarpanches, and citizens to register distress vectors with automated GPS tagging and NLP deduplication.": "वार्ड पार्षदों, ग्राम सरपंचों और नागरिकों को स्वचालित जीपीएस टैगिंग और एनएलपी डुप्लीकेशन के साथ संकट के मुद्दों को पंजीकृत करने के लिए सशक्त बनाना।",
      "Direct CSR funding, tech hardware grants, and executive engineering mentorship under formalized Section 135 compliance.": "औपचारिक धारा 135 अनुपालन के तहत प्रत्यक्ष सीएसआर वित्तपोषण, तकनीक हार्डवेयर अनुदान और कार्यकारी इंजीनियरिंग सलाह।",
      "Real-time telemetry measuring social return on investment, state-by-state resolution velocities, and UN SDG alignment.": "निवेश पर सामाजिक रिटर्न, राज्य-वार समाधान वेग और संयुक्त राष्ट्र एसडीजी संरेखण को मापने वाली रियल-टाइम टेलीमेट्री।",
      "From ground-level citizen distress calls to verified societal deployment, every milestone is auditable and automated.": "जमीनी स्तर के नागरिक संकट कॉल से लेकर सत्यापित सामाजिक तैनाती तक, हर मील का पत्थर ऑडिट योग्य और स्वचालित है।",
      "Engineered for high-volume civic scale, regulatory Section 135 compliance, and frictionless cross-sector execution.": "उच्च-स्तरीय नागरिक पैमाने, नियामक धारा 135 अनुपालन और घर्षण रहित क्रॉस-सेक्टर निष्पादन के लिए इंजीनियर।",
      "Discover and report ground-level issues with spatial GIS mapping, population density indexing, and urgency scoring.": "स्थानिक जीआईएस मैपिंग, जनसंख्या घनत्व इंडेक्सिंग और तात्कालिकता स्कोरिंग के साथ जमीनी स्तर के मुद्दों की खोज करें और रिपोर्ट करें।",
      "Geo-tagged submissions via web portal, vernacular voice notes, or WhatsApp bots by panchayats and local residents.": "पंचायत और स्थानीय निवासियों द्वारा वेब पोर्टल, स्थानीय वॉयस नोट्स या व्हाट्सएप बॉट्स के माध्यम से जियो-टैग की गई प्रविष्टियां।",
      "Automated ESG reporting and Social Return on Investment (SROI) metrics formatted directly for ministerial review.": "मंत्रालयी समीक्षा के लिए सीधे तैयार की गई स्वचालित ईएसजी रिपोर्टिंग और निवेश पर सामाजिक रिटर्न (एसआरओआई) मेट्रिक्स।",
      "Neural vector scoring linking citizen distress vectors to collegiate engineering proficiencies and lab inventory.": "नागरिक संकट वैक्टरों को कॉलेज इंजीनियरिंग दक्षताओं और प्रयोगशाला सूची से जोड़ने वाली तंत्रिका वेक्टर स्कोरिंग।",
      "Pitch multidisciplinary solutions, earn verified AICTE academic credit points, and register provisional patents.": "बहु-विषयक समाधान पेश करें, सत्यापित एआईसीटीई अकादमिक क्रेडिट अंक अर्जित करें और अनंतिम पेटेंट पंजीकृत करें।",
      "Milestone-governed execution with verifiable digital proofs of work, GitHub linkage, and geofenced field audits.": "काम के सत्यापन योग्य डिजिटल प्रमाण, गिटहब लिंकेज और जियोफेंस्ड क्षेत्र ऑडिट के साथ मील का पत्थर-शासित निष्पादन।",
      "NLP models perform cross-regional deduplication, priority grading, and cross-reference with municipal datasets.": "एनएलपी मॉडल क्रॉस-रीजनल डुप्लीकेशन, प्राथमिकता ग्रेडिंग और नगर पालिका डेटासेट के साथ क्रॉस-संदर्भ करते हैं।",
      "Connect department research labs with municipal challenges. Turn final-year capstone theses into field pilots.": "विभाग अनुसंधान प्रयोगशालाओं को नगर पालिका चुनौतियों से जोड़ें। अंतिम वर्ष के कैपस्टोन थेसिस को फील्ड पायलटों में बदलें।",
      "Direct portal for District Collectors and Municipal Commissioners to validate pilot proposals within 48 hours.": "जिला कलेक्टरों और नगर आयुक्तों के लिए 48 घंटों के भीतर पायलट प्रस्तावों को मान्य करने का सीधा पोर्टल।",
      "Algorithms pair the verified challenge with student engineering cohorts, department labs, and faculty guides.": "एल्गोरिदम सत्यापित चुनौती का छात्र इंजीनियरिंग समूहों, विभाग प्रयोगशालाओं और संकाय गाइडों के साथ मिलान करते हैं।",
      "Interdisciplinary squads assemble (e.g., 3 engineers + 1 data scientist) under an accredited faculty mentor.": "एक मान्यता प्राप्त संकाय संरक्षक के तहत अंतर-विषय दल (जैसे, 3 इंजीनियर + 1 डेटा वैज्ञानिक) एकत्र होते हैं।",
      "Immutable impact quantified: liters purified, kWh saved, citizen hours recovered, and Social ROI calculated.": "अपरिवर्तनीय प्रभाव परिमाणित: शुद्ध लीटर, बचाई गई kWh, नागरिक घंटे बरामद, और सामाजिक ROI की गणना।",
      "Prototypes live in community. Real-time IoT feedback and citizen satisfaction ratings stream to dashboards.": "प्रोटोटाइप समुदाय में लाइव हैं। रियल-टाइम आईओटी फीडबैक और नागरिक संतुष्टि रेटिंग डैशबोर्ड पर आती हैं।",
      "University lab facilities and professors certify hardware safety, sensor calibration, and code stability.": "विश्वविद्यालय प्रयोगशाला सुविधाएं और प्रोफेसर हार्डवेयर सुरक्षा, सेंसर अंशांकन और कोड स्थिरता को प्रमाणित करते हैं।",
      "Upon dispatch, 4 shortlisted universities within a 50km radius and 2 CSR partners receive automated RFPs.": "प्रेषण पर, 50 किमी के दायरे में 4 शॉर्टलिस्ट किए गए विश्वविद्यालय और 2 सीएसआर भागीदारों को स्वचालित आरएफपी प्राप्त होते हैं।",
      "Autonomous edge-AI drone scans for pink bollworm detection across smallholder cotton belts in Vidarbha.": "विदर्भ में छोटे कपास क्षेत्रों में गुलाबी बॉलवर्म का पता लगाने के लिए स्वायत्त एज-एआई ड्रोन स्कैन।",
      "\"Hi! I'm C2C AI. Let's turn a civic challenge into an actionable solution. How can I guide you today?\"": "\"नमस्ते! मैं C2C AI हूँ। आइए एक नागरिक चुनौती को कार्रवाई योग्य समाधान में बदलें। मैं आज आपका मार्गदर्शन कैसे कर सकता हूँ?\"",
      "Corporate CSR capital, cloud compute credits, and hardware toolkits route directly to project escrow.": "कॉरपोरेट सीएसआर पूंजी, क्लाउड कंप्यूट क्रेडिट और हार्डवेयर टूलकिट सीधे प्रोजेक्ट एस्क्रो को रूट करते हैं।",
      "Continuous cellular temperature telemetry for primary healthcare storage in Gadchiroli tribal belts.": "गडचिरोली आदिवासी क्षेत्रों में प्राथमिक स्वास्थ्य देखभाल भंडारण के लिए निरंतर सेलुलर तापमान टेलीमेट्री।",
      "District Collectors authorize testing grounds and road-cutting NOCs with zero bureaucratic delay.": "जिला कलेक्टर शून्य नौकरशाही देरी के साथ परीक्षण मैदान और सड़क काटने की एनओसी को अधिकृत करते हैं।",
      "Partner: Bhiwandi Municipal Council • Host: Veermata Jijabai Technological Institute (VJTI)": "भागीदार: भिवंडी नगर परिषद • मेजबान: वीरमाता जीजाबाई टेक्नोलॉजिकल इंस्टीट्यूट (VJTI)",
      "Sovereign Civic Orchestrator": "संप्रभु नागरिक आर्केस्ट्रेटर",
      "Bridging Campus Innovation & Community Distress": "कैंपस इनोवेशन और सामुदायिक संकट को जोड़ना",
      "Real-World Problem to Verified Solution Pipeline": "वास्तविक समस्या से सत्यापित समाधान पाइपलाइन",
      "Live Sovereign Infrastructure Telemetry": "लाइव संप्रभु बुनियादी ढांचा टेलीमेट्री",
      "Grassroots Challenge Intake Pipeline": "तृणमूल चुनौती इनटेक पाइपलाइन",
      "National Social ROI & SDG Dashboard": "राष्ट्रीय सामाजिक ROI और SDG डैशबोर्ड",
      "Architected for Every Sovereign Stakeholder": "हर संप्रभु हितधारक के लिए डिज़ाइन किया गया",
      "4-Phase Sovereign Governance Lifecycle": "4-चरण संप्रभु शासन जीवनचक्र",
      "Frequently Asked Questions": "अक्सर पूछे जाने वाले प्रश्न",
      "Ready to Transform Your Campus Innovation?": "क्या आप अपने कैंपस इनोवेशन को बदलने के लिए तैयार हैं?",
      "About Campus2Community": "Campus2Community के बारे में",
      "Core Vision & Mission": "मुख्य विजन और मिशन",
      "Our Vision": "हमारा विजन",
      "Our Mission": "हमारा मिशन",
      "Core Platform Pillars": "मुख्य प्लेटफॉर्म स्तंभ",
      "Evolution & Milestones": "विकास और उपलब्धियां",
      "Unified Platform Core Team": "एकीकृत प्लेटफॉर्म कोर टीम",
      "GOVERNMENT APPROVED CIVIC INFRASTRUCTURE": "सरकार द्वारा स्वीकृत नागरिक बुनियादी ढांचा",
      "SIH 2026 FIELD INTAKE PIPELINE": "SIH 2026 फील्ड इनटेक पाइपलाइन",
      "LIVE TELEMETRY & DISPATCH MATRIX": "लाइव टेलीमेट्री और प्रेषण मैट्रिक्स",
      "QUANTIFIABLE SOCIETAL ROI": "मात्रात्मक सामाजिक ROI",
      "UNIFIED STAKEHOLDER VALUE": "एकीकृत हितधारक मूल्य",
      "GROUND-TO-GOVERNMENT LIFECYCLE": "जमीन से सरकार जीवनचक्र",
      "EVERYTHING YOU NEED TO KNOW": "सब कुछ जो आपको जानना आवश्यक है",
      "JOIN THE SOVEREIGN MESH": "संप्रभु मेश में शामिल हों",
      "CAMPUS2COMMUNITY ECOSYSTEM": "कैंपस2कम्युनिटी पारिस्थितिकी तंत्र",
      "VIDYALANKAR INSTITUTE OF TECHNOLOGY (VSIT)": "विद्यालंकार इंस्टीट्यूट ऑफ टेक्नोलॉजी (VSIT)",
      "Students & Innovators": "छात्र और नवप्रवर्तक",
      "Universities & Labs": "विश्वविद्यालय और प्रयोगशालाएं",
      "Faculty Mentorship": "संकाय मार्गदर्शन",
      "Field NGO Coalition": "क्षेत्र गैर सरकारी संगठन गठबंधन",
      "Social Auditing": "सामाजिक लेखा परीक्षा",
      "Industry CSR Escrow": "उद्योग सीएसआर एस्क्रो",
      "Sec 135 Compliance": "धारा 135 अनुपालन",
      "Government NOC": "सरकारी एनओसी",
      "District Administration": "जिला प्रशासन",
      "Urban Local Bodies": "शहरी स्थानीय निकाय",
      "Active Solvers": "सक्रिय समाधानकर्ता",
      "Campuses": "कैंपस",
      "Verifiers": "सत्यापनकर्ता",
      "Committed": "प्रतिबद्ध",
      "CITIZENS BENEFITED": "लाभान्वित नागरिक",
      "COMMUNITIES IMPACTED": "प्रभावित समुदाय",
      "PROBLEMS SOLVED": "हल की गई समस्याएं",
      "ACTIVE CAPSTONE PROJECTS": "सक्रिय कैपस्टोन परियोजनाएं",
      "WATER & SANITATION (SDG 6)": "जल और स्वच्छता (SDG 6)",
      "AGRITECH & SOIL HEALTH (SDG 2)": "एग्रीटेक और मृदा स्वास्थ्य (SDG 2)",
      "SMART INFRASTRUCTURE (SDG 11)": "स्मार्ट इंफ्रास्ट्रक्चर (SDG 11)",
      "RURAL HEALTHCARE (SDG 3)": "ग्रामीण स्वास्थ्य देखभाल (SDG 3)",
      "All Categories": "सभी श्रेणियां",
      "Water Systems": "जल प्रणालियां",
      "Urban Roads": "शहरी सड़कें",
      "Solar Microgrids": "सौर माइक्रोग्रिड",
      "Rural Health": "ग्रामीण स्वास्थ्य",
      "Civic Infrastructure": "नागरिक बुनियादी ढांचा",
      "Agriculture Tech": "कृषि तकनीक",
      "Filter by Keyword or District...": "कीवर्ड या जिले द्वारा फ़िल्टर करें...",
      "Search Problems...": "समस्याएँ खोजें...",
      "Search tickets, districts, SDGs...": "टिकट, जिले, एसडीजी खोजें...",
      "Select Priority": "प्राथमिकता चुनें",
      "Critical (ULB 24h SLA)": "गंभीर (ULB 24h SLA)",
      "High Urgency": "उच्च तात्कालिकता",
      "Medium Urgency": "मध्यम तात्कालिकता",
      "Low Urgency": "कम तात्कालिकता",
      "Explore Problem Mesh": "समस्या मेश का अन्वेषण करें",
      "Submit Field Distress Report": "फील्ड संकट रिपोर्ट जमा करें",
      "Submit Ticket to Sovereign Mesh": "संप्रभु मेश में टिकट जमा करें",
      "Trigger Auto-NOC Dispatch": "ऑटो-एनओसी प्रेषण ट्रिगर करें",
      "View Gazetted Consent Certificate": "राजपत्रित सहमति प्रमाणपत्र देखें",
      "Join 32,000+ Student Engineers": "32,000+ छात्र इंजीनियरों से जुड़ें",
      "Explore SIH 2026 Portal": "SIH 2026 पोर्टल का अन्वेषण करें",
      "Register Institution / Student Team": "संस्थान / छात्र टीम पंजीकृत करें",
      "Overview": "अवलोकन",
      "Intake Pipeline": "इनटेक पाइपलाइन",
      "AI Orchestrator": "एआई आर्केस्ट्रेटर",
      "Impact Telemetry": "प्रभाव टेलीमेट्री",
      "Stakeholders": "हितधारक",
      "Governance & SIH": "प्रशासन और SIH",
      "About Us & Platform Vision": "हमारे बारे में और मंच विज़न",
      "Code Base & Tech Specs": "कोड बेस और तकनीकी विनिर्देश",
      "Ministry of Education": "शिक्षा मंत्रालय",
      "AICTE Portal Integration": "एआईसीटीई पोर्टल एकत्रीकरण",
      "SIH 2026 Grand Finale": "SIH 2026 ग्रँड फिनाले",
      "Students": "छात्र",
      "Innovators": "नवप्रवर्तक",
      "Universities": "विश्वविद्यालय",
      "Labs": "प्रयोगशालाएं",
      "Faculty": "संकाय",
      "Mentorship": "मार्गदर्शन",
      "Field": "क्षेत्र",
      "NGO": "एनजीओ",
      "Coalition": "गठबंधन",
      "Social": "सामाजिक",
      "Auditing": "लेखा परीक्षा",
      "Industry": "उद्योग",
      "Escrow": "एस्क्रो",
      "Compliance": "अनुपालन",
      "Government": "सरकार",
      "District": "जिला",
      "Administration": "प्रशासन",
      "Urban": "शहरी",
      "Local": "स्थानीय",
      "Bodies": "निकाय",
      "Active": "सक्रिय",
      "Solvers": "समाधानकर्ता",
      "Ticket": "टिकट",
      "Problem": "समस्या",
      "Solution": "समाधान",
      "Status": "स्थिति",
      "Verified": "सत्यापित",
      "Pending": "लंबित",
      "Resolved": "हल किया गया",
      "Details": "विवरण",
      "Filter": "फ़िल्टर",
      "Search": "खोजें",
      "Submit": "जमा करें",
      "Close": "बंद करें",
      "Back": "वापस",
      "Next": "अगला",
      "Previous": "पिछला",
      "All": "सभी",
      "Critical": "गंभीर",
      "High": "उच्च",
      "Medium": "मध्यम",
      "Low": "कम",
      "Parnavi Janbhor": "पर्णवी जाणभोर",
      "Tanmay Shirgudi": "तन्मय शिरगुडी",
      "Shlok Jadhav": "श्लोक जाधव",
      "Shrutesh Gaikwad": "श्रुतेश गायकवाड",
      "Kashaf Khan": "कशफ खान",
      "Aqsa Haji": "अक्सा हाजी",
      "contact no: 9022634336": "संपर्क क्र: 9022634336",
      "contact no: 8850604104": "संपर्क क्र: 8850604104",
      "contact no: 9137025807": "संपर्क क्र: 9137025807",
      "contact no: 8828359404": "संपर्क क्र: 8828359404",
      "contact no: 8291079379": "संपर्क क्र: 8291079379",
      "contact no: 9137070110": "संपर्क क्र: 9137070110",
      "Project Lead & AI Specialist": "प्रोजेक्ट लीड और एआई विशेषज्ञ",
      "Full-Stack Systems Architect": "फुल-स्टैक सिस्टम आर्किटेक्ट",
      "Frontend & UX Lead": "फ्रंटएंड और यूएक्स लीड",
      "Backend & Escrow Engineer": "बैकएंड और एस्क्रो इंजीनियर",
      "GIS & Field Operations Lead": "जीआईएस और फील्ड ऑपरेशंस लीड",
      "Public Policy & Outreach Coordinator": "सार्वजनिक नीति और आउटरीच समन्वयक",
      "Vidyalankar Institute of Technology": "विद्यालंकार इंस्टीट्यूट ऑफ टेक्नोलॉजी",
      "Initial NLP prototype tested with 12 engineering colleges in Mumbai Metropolitan Region. 84 field problems cataloged.": "मुंबई महानगर क्षेत्र के 12 इंजीनियरिंग कॉलेजों में शुरुआती एनएलपी प्रोटोटाइप का परीक्षण किया गया। 84 जमीनी समस्याओं को सूचीबद्ध किया गया।",
      "Onboarded 18 District Collectorates and established the automated Section 135 CSR escrow protocol with 6 corporate partners.": "18 जिला कलेक्टरों को शामिल किया गया और 6 कॉर्पोरेट भागीदारों के साथ स्वचालित धारा 135 सीएसआर एस्क्रो प्रोटोकॉल स्थापित किया गया।",
      "₹4.8 Crore in corporate funding locked into project escrows; 412 campuses linked across Maharashtra and Gujarat.": "₹4.8 करोड़ का कॉर्पोरेट फंड प्रोजेक्ट एस्क्रो में लॉक किया गया; महाराष्ट्र और गुजरात में 412 कैंपस जोड़े गए।",
      "National SIH expansion targeting 1,200+ engineering institutions and integration with Ministry of Education digital portal.": "1,200+ इंजीनियरिंग संस्थानों और शिक्षा मंत्रालय के डिजिटल पोर्टल के साथ एकीकरण को लक्षित करने वाला राष्ट्रीय एसआईएच विस्तार।",
      "How does Campus2Community verify the legitimacy of ground issues?": "Campus2Community जमीनी मुद्दों की वैधता की पुष्टि कैसे करता है?",
      "How do student engineers receive academic credits for C2C projects?": "छात्र इंजीनियरों को C2C परियोजनाओं के लिए अकादमिक क्रेडिट कैसे मिलते हैं?",
      "Are CSR funds provided directly to students or through institutional escrows?": "क्या सीएसआर फंड सीधे छात्रों को या संस्थागत एस्क्रो के माध्यम से दिए जाते हैं?",
      "How quickly can a District Collector or Municipal Body onboard to C2C?": "जिला कलेक्टर या नगर निकाय C2C में कितनी जल्दी शामिल हो सकते हैं?",
      "1. Ground Distress Capture": "1. ग्राउंड डिस्ट्रेस कैप्चर",
      "2. AI Matching & Dispatch": "2. एआई मैचिंग और डिस्पैच",
      "3. Sovereign Escrow & Execution": "3. संप्रभु एस्क्रो और निष्पादन",
      "4. Impact Measurement": "4. प्रभाव मापन",
      "ULB SLA: 48h": "ULB SLA: 48 घंटे",
      "ULB SLA: 24h": "ULB SLA: 24 घंटे",
      "ULB SLA: 72h": "ULB SLA: 72 घंटे",
      "₹4.5L Escrow": "₹4.5 लाख एस्क्रो",
      "₹8.2L Escrow": "₹8.2 लाख एस्क्रो",
      "₹2.8L Escrow": "₹2.8 लाख एस्क्रो",
      "Verify & Allocate CSR Fund": "सत्यापित करें और सीएसआर फंड आवंटित करें",
      "Bhiwandi Rural, District Thane": "भिवंडी ग्रामीण, जिला ठाणे",
      "Palghar Coastal Belt": "पालघर तटीय पट्टी",
      "Gadchiroli Tribal Block": "गडचिरोली जनजातीय ब्लॉक",
      "FIELD SENSOR TELEMETRY": "फील्ड सेंसर टेलीमेट्री",
      "12 Nodes Transmitting": "12 नोड्स प्रसारित हो रहे हैं",
      "TEAM MILESTONE VELOCITY": "टीम मील का पत्थर वेग",
      "DISTRICT COLLECTOR SIGNOFF": "जिला कलेक्टर हस्ताक्षर",
      "Water & Sanitation (SDG 6)": "जल और स्वच्छता (SDG 6)",
      "Agritech & Soil Health (SDG 2)": "एग्रीटेक और मृदा स्वास्थ्य (SDG 2)",
      "Smart Infrastructure (SDG 11)": "स्मार्ट इंफ्रास्ट्रक्चर (SDG 11)",
      "Rural Healthcare (SDG 3)": "ग्रामीण स्वास्थ्य देखभाल (SDG 3)",
      "For Students & Innovators": "छात्रों और नवप्रवर्तकों के लिए",
      "For Universities & Research Labs": "विश्वविद्यालयों और अनुसंधान प्रयोगशालाओं के लिए",
      "For Municipal Bodies & District Collectors": "नगर निकायों और जिला कलेक्टरों के लिए",
      "For Corporate CSR & Industry ESCROW": "कॉर्पोरेट सीएसआर और उद्योग एस्क्रो के लिए",
      "Distress Category": "संकट श्रेणी",
      "Location / Geo-Tag": "स्थान / जियो-टैग",
      "Estimated Affected Population": "अनुमानित प्रभावित जनसंख्या",
      "Problem Description & Evidence": "समस्या विवरण और साक्ष्य",
      "Attach Photo / Sensor Data": "फोटो / सेंसर डेटा संलग्न करें",
      "Select District...": "जिला चुनें...",
      "Enter issue details...": "समस्या का विवरण दर्ज करें...",
      "e.g. 5,000 residents": "उदा. 5,000 निवासी",
      "Select Priority Level": "प्राथमिकता स्तर चुनें",
      "Learn More": "और जानें",
      "Read Documentation": "दस्तावेज़ पढ़ें",
      "Get Started": "शुरू करें",
      "Contact Support": "सहायता से संपर्क करें",
      "View Details": "विवरण देखें",
      "Download Certificate": "प्रमाणपत्र डाउनलोड करें",
      "Verified Escrow": "सत्यापित एस्क्रो",
      "AI Matched": "एआई मिलान",
      "Pending Signoff": "लंबित हस्ताक्षर",
      "Active Pilot": "सक्रिय पायलट",
      "Phase 1: Foundation": "चरण 1: नींव",
      "Phase 2: Scale": "चरण 2: स्केल",
      "Phase 3: Integration": "चरण 3: एकीकरण",
      "Phase 4: Sovereign Grid": "चरण 4: संप्रभु ग्रिड",
      "Q1 2025": "Q1 2025",
      "Q3 2025": "Q3 2025",
      "Q1 2026": "Q1 2026",
      "Q4 2026": "Q4 2026",
      "Corporate CSR funds are held in automated escrow accounts and released directly to student teams upon verification of prototype deployment by municipal officers.": "Corporate सीएसआर funds are held in automated escrow accounts and released directly to student teams upon verification of prototype deployment by municipal officers.",
      "Completed ground solutions automatically grant AICTE Activity Points, count toward capstone credits, and boost university NIRF innovation rankings.": "Completed ground solutions automatically grant एआईसीटीई Activity Points, count toward capstone credits, and boost university NIRF innovation rankings.",
      "High concentration of industrial arsenic and turbidity observed after recent drainage overflow. Local PHC reports 24 gastroenteritis cases in 72h.": "उच्च concentration of industrial arsenic and turbidity observed after recent drainage overflow. Local PHC reports 24 gastroenteritis cases in 72h.",
      "District Collectors grant instant digital testing permits for road cutting, sensor installation, or water body sampling with full legal backing.": "जिला Collectors grant instant digital testing permits for road cutting, sensor installation, or water body sampling with full legal backing.",
      "Conceived during Smart India Hackathon to solve the gap between student capstone projects and actual municipal distress tickets.": "Conceived during Smart India हैकाथॉन to solve the gap between student capstone projects and actual municipal distress tickets.",
      "Scaling C2C to every AICTE accredited institution in India, turning every engineering college into a civic problem-solving hub.": "Scaling C2C to every एआईसीटीई accredited institution in India, turning every engineering college into a civic problem-solving hub.",
      "Partnered with corporate CSR boards to disburse micro-grants directly into student team escrow accounts upon milestone proof.": "Partnered with corporate सीएसआर boards to disburse micro-grants directly into student team escrow accounts upon milestone proof.",
      "Retain full intellectual property over your engineering solutions with automated patent drafting support from mentor labs.": "Retain full intellectual property over your engineering solutions with automated patent drafting support from mentor labs.",
      "Get cutting-edge smart city prototypes built and tested for local municipal issues at zero cost to the civic exchequer.": "Get cutting-edge smart city prototypes built and tested for local municipal issues at zero cost to the civic exchequer.",
      "Join 32,000+ student engineers, 400+ colleges, and 18 district municipal bodies driving sovereign civic transformation.": "Join 32,000+ student engineers, 400+ colleges, and 18 district municipal bodies driving sovereign civic transformation.",
      "Earn mandatory AICTE degree activity points by resolving verified community distress tickets in your regional district.": "Earn mandatory एआईसीटीई degree activity points by resolving verified community distress tickets in your regional district.",
      "Boost college NIRF research rankings with verified civic prototype deployments and cross-departmental research papers.": "Boost college NIRF research rankings with verified civic prototype deployments and cross-departmental research papers.",
      "Pioneered direct District Collector NOC approvals allowing student teams to test hardware in water plants and roads.": "Pioneered direct जिला कलेक्टर NOC approvals allowing student teams to test hardware in water plants and roads.",
      "Unlock micro-grants from ₹25,000 to ₹2.5 Lakhs directly for sensor purchasing, PCB fabrication, and field testing.": "Unlock micro-grants from ₹25,000 to ₹2.5 Lakhs directly for sensor purchasing, PCB fabrication, and field testing.",
      "Identify and hire top-tier engineering talent working on high-impact sustainable technologies before graduation.": "Identify and hire top-tier engineering talent working on high-impact sustainable technologies before graduation.",
      "We eliminate traditional bureaucratic bottlenecks between academic labs and municipal public works departments.": "हम अकादमिक प्रयोगशालाओं और नगर पालिका लोक निर्माण विभागों के बीच पारंपरिक नौकरशाही बाधाओं को समाप्त करते हैं।",
      "Co-guide multi-disciplinary student teams across civil, electrical, biotech, and computer science departments.": "Co-guide multi-disciplinary student teams across civil, electrical, biotech, and computer science departments.",
      "Funds are locked safely in escrow and disbursed only after multi-stakeholder verification of ground results.": "Funds are locked safely in escrow and disbursed only after multi-stakeholder verification of ground results.",
      "Monitor ground resolution progress in real time with automated SMS updates and live GIS dashboard tracking.": "Monitor ground resolution progress in real time with automated SMS updates and live GIS dashboard tracking.",
      "Unlock corporate CSR hardware grants to upgrade departmental testing facilities and prototyping sandboxes.": "Unlock corporate सीएसआर hardware grants to upgrade departmental testing facilities and prototyping sandboxes.",
      "Ensure 100% statutory compliance with audit-ready cryptographic milestone proofs and municipal receipts.": "Ensure 100% statutory compliance with audit-ready cryptographic milestone proofs and municipal receipts.",
      "District Collectors issue rapid digital testing clearances without tedious physical paper loops.": "जिला Collectors issue rapid digital testing clearances without tedious physical paper loops.",
      "Trace how C2C grew from a SIH 2026 problem statement into a full-scale civic technology mesh.": "Trace how C2C grew from a एसआईएच 2026 problem statement into a full-scale civic technology mesh.",
      "Built with enterprise-grade reliability, transparent governance, and AI-driven intelligence.": "Built with enterprise-grade reliability, transparent governance, and AI-driven intelligence.",
      "Capital efficiency and citizen density impact mapped by UN Sustainable Development Goals.": "Capital efficiency and citizen density impact mapped by UN Sustainable Development Goals.",
      "🌐 Live AI matching mesh connecting over 400 universities with real-time GIS telemetry.": "🌐 Live AI matching mesh connecting over 400 universities with real-time GIS telemetry.",
      "💎 ₹4.8 Crore in corporate funding locked into verified student prototype deployments.": "💎 ₹4.8 Crore in corporate funding locked into verified student prototype deployments.",
      "🏛️ Onboarded 18 District Collectorates with single-window NOC automated dispatches.": "🏛️ Onboarded 18 जिला Collectorates with single-window NOC automated dispatches.",
      "Comparative view of challenges resolved vs active collegiate student chapters.": "Comparative view of challenges resolved vs active collegiate student chapters.",
      "Select a stakeholder group to view tailored workflows and platform benefits.": "Select a stakeholder group to view tailored workflows and platform benefits.",
      "⚡ Initial NLP prototype tested with 12 engineering colleges in Maharashtra.": "⚡ Initial NLP prototype tested with 12 engineering colleges in Maharashtra.",
      "© 2026 CAMPUS2COMMUNITY (C2C) Sovereign Platform. All rights reserved.": "© 2026 CAMPUS2COMMUNITY (C2C) संप्रभु Platform. All rights reserved.",
      "Audited under Ministry of Education MIC Innovation Metrics Framework.": "Audited under मंत्रालय of शिक्षा MIC Innovation मेट्रिक्स Framework.",
      "Everything you need to know about joining or supporting the C2C mesh.": "Everything you need to know about joining or supporting the C2C mesh.",
      "Attach Lab Water Sample / Geo-Tagged Photo / Vernacular Audio Memo": "Attach प्रयोगशाला जल Sample / Geo-Tagged Photo / Vernacular Audio Memo",
      "Spanning 89 engineering disciplines & 412 accredited universities.": "Spanning 89 engineering disciplines & 412 accredited universities.",
      "Cross-checked with Jal Jeevan Mission national database in 0.4s.": "Cross-checked with Jal Jeevan मिशन national database in 0.4s.",
      "How do student teams get academic credit for solving problems?": "How do student teams get academic credit for solving problems?",
      "Are corporate CSR contributions audit-ready under Section 135?": "Are corporate सीएसआर contributions audit-ready under Section 135?",
      "Smart IoT Groundwater Filtration & Turbidity Telemetry Grid": "Smart IoT Groundwater Filtration & Turbidity टेलीमेट्री Grid",
      "Receive validated challenge manifests and civic dispatches.": "Receive validated challenge manifests and civic dispatches.",
      "Dr. S. Kulkarni | Department of Civil & Environmental Engg": "Dr. S. Kulkarni | Department of Civil & Environmental Engg",
      "New challenge C2C-2026-904 matched with IIT Bombay labs.": "New challenge C2C-2026-904 matched with IIT Bombay labs.",
      "Formal municipal permit issued under Smart City Mission.": "Formal municipal permit issued under Smart City मिशन.",
      "Water Contamination Problem in Bhiwandi (C2C-2026-W482)": "जल Contamination Problem in Bhiwandi (C2C-2026-W482)",
      "About Us | CAMPUS2COMMUNITY (C2C) Sovereign Platform": "About Us | CAMPUS2COMMUNITY (C2C) संप्रभु Platform",
      "From Hackathon Prototype to Sovereign Infrastructure": "From हैकाथॉन Prototype to संप्रभु बुनियादी ढांचा",
      "How can a new university or municipal body join C2C?": "How can a new university or municipal body join C2C?",
      "Instant EXIF GPS extraction & NIC server encryption": "Instant EXIF GPS extraction & NIC server encryption",
      "Students who are innovators, making society better.": "छात्रों who are innovators, making society better.",
      "CAMPUS2COMMUNITY (C2C) | Civic Innovation Platform": "CAMPUS2COMMUNITY (C2C) | Civic Innovation Platform",
      "Search C2C mission, leadership, pillars, or FAQ...": "Search C2C mission, leadership, pillars, or FAQ...",
      "Sprint Tracking: Phase 05 Active Sensor Telemetry": "Sprint Tracking: Phase 05 सक्रिय सेंसर टेलीमेट्री",
      "99.8% ping uptime across Bhiwandi Sector 4 wells.": "99.8% ping uptime across Bhiwandi Sector 4 wells.",
      "How are problem statements reported and verified?": "How are problem statements reported and verified?",
      "Student AI Matchmaking Engine (Embedded C, ESP32)": "छात्र AI Matchmaking Engine (Embedded C, ESP32)",
      "Search challenges, skills, districts, or teams...": "Search challenges, skills, districts, or teams...",
      "Parnavi Janbhor | Student Innovator Lead (VSIT)": "Parnavi Janbhor | छात्र Innovator लीड (VSIT)",
      "Driven by Impact. Orchestrated by Intelligence.": "प्रभाव द्वारा संचालित। बुद्धिमत्ता द्वारा आर्केस्ट्रेटेड।",
      "approved pilot testing for arsenic filtration.": "approved pilot testing for arsenic filtration.",
      "C2C-IP-2026-098: Low-cost Arsenic Electro-Cell": "C2C-IP-2026-098: Low-cost Arsenic Electro-Cell",
      "₹30,000 in escrow pending Milestone 4 signoff": "₹30,000 in escrow pending Milestone 4 signoff",
      "Encrypted delivery via NIC / Gov cloud rails.": "Encrypted delivery via NIC / Gov cloud rails.",
      "Smart Village IoT Water Purity & Distribution": "Smart Village IoT जल Purity & Distribution",
      "Firmware v2.1 OTA flash completed yesterday.": "Firmware v2.1 OTA flash completed yesterday.",
      "Translate Language (English, Hindi, Marathi)": "Translate Language (English, Hindi, Marathi)",
      "Submit for Sovereign AI Intake Verification": "Submit for संप्रभु AI Intake Verification",
      "Vidyalankar Institute of Technology (VSIT)": "Vidyalankar Institute of Technology (VSIT)",
      "Tamil Nadu (Chennai, Coimbatore, Madurai)": "Tamil Nadu (Chennai, Coimbatore, Madurai)",
      "NLP Urgency Index: 98.4 (Tier 1 Priority)": "NLP तात्कालिकता Index: 98.4 (Tier 1 प्राथमिकता)",
      "Uttar Pradesh (Lucknow, Varanasi, Kanpur)": "Uttar Pradesh (Lucknow, Varanasi, Kanpur)",
      "Direct API feed to state urban dashboard": "Direct API feed to state urban dashboard",
      "Matched Competencies for Parnavi Janbhor": "Matched Competencies for Parnavi Janbhor",
      "💧 Report Local Water / Sanitation Issue": "💧 Report Local जल / Sanitation Issue",
      "Submitted to IEEE Civic Technology 2026": "Submitted to IEEE Civic Technology 2026",
      "Karnataka (Bengaluru, Mysuru, Hubballi)": "Karnataka (Bengaluru, Mysuru, Hubballi)",
      "Tranche releases upon milestone proofs": "Tranche releases upon milestone proofs",
      "District Administration Clearance Desk": "जिला Administration Clearance Desk",
      "Spectrophotometer + Spectrometer slots": "Spectrophotometer + Spectrometer slots",
      "Tax exemption audit certificates ready": "Tax exemption audit certificates ready",
      "Citizen satisfaction rating: 4.8 / 5.0": "Citizen satisfaction rating: 4.8 / 5.0",
      "Tata Trusts CleanTech Innovation Fund": "Tata Trusts CleanTech Innovation फंड",
      "Bhiwandi Municipal Corporation (BMC)": "Bhiwandi नगर पालिका निगम (BMC)",
      "AI-Recommended Solution Squad Matrix": "AI-Recommended Solution Squad Matrix",
      "Pest Infestation Early Warning LiDAR": "Pest Infestation Early Warning LiDAR",
      "Gujarat (Ahmedabad, Surat, Vadodara)": "Gujarat (Ahmedabad, Surat, Vadodara)",
      "8 Core Interconnected Impact Modules": "8 मुख्य Interconnected प्रभाव Modules",
      "Cold-Chain Vaccine Telemetry Monitor": "Cold-Chain Vaccine टेलीमेट्री Monitor",
      "Bridging Campuses & Communities for": "Bridging Campuses & Communities for",
      "Approved Hardware Prototyping Grant": "Approved Hardware Prototyping Grant",
      "National Avg Time-to-Pilot: 34 Days": "राष्ट्रीय Avg Time-to-Pilot: 34 दिन",
      "Across 12 verified student projects": "Across 12 verified student projects",
      "Rural Education & Digital Literacy": "ग्रामीण शिक्षा & Digital Literacy",
      "Experience C2C in Stakeholder Mode": "Experience C2C in Stakeholder Mode",
      "Maharashtra (Mumbai, Pune, Nagpur)": "Maharashtra (Mumbai, Pune, Nagpur)",
      "Sprint C2C-881 • Bhiwandi Sector 4": "Sprint C2C-881 • Bhiwandi Sector 4",
      "Clean Energy & Microgrids (SDG 7)": "Clean Energy & Microgrids (एसडीजी 7)",
      "Built for Every Pillar of Society": "Built for Every स्तंभ of Society",
      "2 teams deployed in field testing": "2 teams deployed in field testing",
      "Phase 05: Field Pilot In Progress": "Phase 05: Field Pilot In Progress",
      "Impact Analytics & Command Center": "प्रभाव Analytics & Command Center",
      "Supported by 42 industry partners": "Supported by 42 industry partners",
      "1,284 Verified Challenges Active": "1,284 सत्यापित Challenges सक्रिय",
      "Average clearance time: 28 hours": "Average clearance time: 28 hours",
      "Civic Problem Intake & Manifests": "Civic Problem Intake & Manifests",
      "Healthcare & Diagnostics (SDG 3)": "स्वास्थ्य सेवा & Diagnostics (एसडीजी 3)",
      "State-Level Resolution Velocity": "State-Level Resolution Velocity",
      "Clean Energy Microgrids (SDG 7)": "Clean Energy Microgrids (एसडीजी 7)",
      "Companies Act Sec 135 Compliant": "Companies Act Sec 135 Compliant",
      "State the core issue clearly...": "राज्य the core issue clearly...",
      "SIH Target: <45 Days (Exceeded)": "एसआईएच Target: <45 दिन (Exceeded)",
      "4 Pillars of the C2C Ecosystem": "4 स्तंभ of the C2C Ecosystem",
      "Automatic Ground Deduplication": "Automatic Ground Deduplication",
      "Healthcare Diagnostics (SDG 3)": "स्वास्थ्य सेवा Diagnostics (एसडीजी 3)",
      "Challenges & Problem Reporting": "Challenges & Problem Reporting",
      "Smart Village IoT Water Purity": "Smart Village IoT जल Purity",
      "Environmental Engg + IoT Nodes": "Environmental Engg + IoT Nodes",
      "Corporate CSR & Escrow Console": "Corporate सीएसआर & एस्क्रो Console",
      "Generated Sovereign Ticket ID": "Generated संप्रभु Ticket ID",
      "SIH 2026 Innovation Challenge": "एसआईएच 2026 Innovation Challenge",
      "AICTE Credits: 45 / 50 Earned": "एआईसीटीई Credits: 45 / 50 Earned",
      "SIH 2026 Sovereign Initiative": "एसआईएच 2026 संप्रभु Initiative",
      "Moderate (Infrastructure Lag)": "Moderate (बुनियादी ढांचा Lag)",
      "94.2% verified field efficacy": "94.2% verified field efficacy",
      "Empowering 32,000+ Innovators": "32,000+ नवप्रवर्तकों को सशक्त बनाना",
      "AI Engine Verifies & Catalogs": "AI Engine Verifies & Catalogs",
      "Explore Active Platform Mesh": "Explore सक्रिय Platform Mesh",
      "AICTE Activity Points Earned": "एआईसीटीई Activity Points Earned",
      "🔍 Track C2C-2026-W482 Status": "🔍 Track C2C-2026-W482 स्थिति",
      "Real-time Telemetry: Healthy": "Real-time टेलीमेट्री: Healthy",
      "⚡ Match My Department Skills": "⚡ Match My Department Skills",
      "Earns AICTE Activity Points": "Earns एआईसीटीई Activity Points",
      "Deduplication & NLP Scoring": "Deduplication & NLP Scoring",
      "Download Provisional Form →": "Download Provisional Form →",
      "+3,180 verified this sprint": "+3,180 verified this sprint",
      "High (Impacting 500+ Daily)": "उच्च (Impacting 500+ Daily)",
      "The 9-Stage Impact Pipeline": "The 9-Stage प्रभाव Pipeline",
      "Explore Stakeholder Portals": "Explore Stakeholder Portals",
      "18 / 21 Milestones Complete": "18 / 21 Milestones Complete",
      "Field Deployment & Feedback": "Field Deployment & Feedback",
      "3 mins ago • SPRINT-C2C-881": "3 mins ago • SPRINT-C2C-881",
      "Academic Mentor & Lab Suite": "Academic Mentor & प्रयोगशाला Suite",
      "Critical (Immediate Hazard)": "गंभीर (Immediate Hazard)",
      "Lab Validation & Mentorship": "प्रयोगशाला Validation & Mentorship",
      "View All Stream Telemetry →": "View All Stream टेलीमेट्री →",
      "18 District Municipalities": "18 जिला Municipalities",
      "Active Persona Perspective": "सक्रिय Persona Perspective",
      "Q2 2026 • Municipal Pilots": "Q2 2026 • नगर पालिका Pilots",
      "Across 14 sovereign states": "Across 14 sovereign states",
      "District Collector Signoff": "जिला कलेक्टर Signoff",
      "Multi-Role Command Portals": "Multi-Role Command Portals",
      "Fast-Track Test Clearances": "Fast-Track Test Clearances",
      "Dr. S. Kulkarni (Assigned)": "Dr. S. Kulkarni (Assigned)",
      "Section 135 Escrow Backed": "Section 135 एस्क्रो Backed",
      "Student Innovator Cockpit": "छात्र Innovator Cockpit",
      "Multi-Actor Role Switcher": "Multi-Actor Role Switcher",
      "Community Reports Problem": "Community Reports Problem",
      "Location / Gram Panchayat": "Location / Gram Panchayat",
      "Observed Distress Details": "Observed Distress Details",
      "State Public Universities": "राज्य Public विश्वविद्यालयों",
      "SLA Clearance Rate: 91.4%": "एसएलए Clearance Rate: 91.4%",
      "Unified Stakeholder Value": "Unified Stakeholder Value",
      "Urban Waste & Circularity": "शहरी Waste & Circularity",
      "Direct student allocation": "Direct student allocation",
      "Enter institutional email": "Enter institutional email",
      "Present • Nationwide Grid": "Present • Nationwide Grid",
      "Q3 2026 • Corporate Rails": "Q3 2026 • Corporate Rails",
      "Turbidity: 1.4 NTU (Safe)": "Turbidity: 1.4 NTU (Safe)",
      "State-Wide Mesh Expansion": "State-Wide Mesh Expansion",
      "Provisional Patent Filed": "Provisional Patent Filed",
      "Industry CSR Sponsorship": "Industry सीएसआर Sponsorship",
      "Escrow Milestone Funding": "एस्क्रो Milestone वित्तपोषण",
      "contact no : 96992 56880": "contact no : 96992 56880",
      "SIH 9-Stage Architecture": "एसआईएच 9-Stage Architecture",
      "Direct Hardware Stipends": "Direct Hardware Stipends",
      "contact no : 93218 61070": "contact no : 93218 61070",
      "Semantic Cosine Matching": "Semantic Cosine Matching",
      "SIH 2026 Innovation Mesh": "एसआईएच 2026 Innovation Mesh",
      "Inter-College Mentorship": "Inter-College Mentorship",
      "Role-Specific Workspaces": "Role-Specific Workspaces",
      "Institutional NIRF Score": "Institutional NIRF Score",
      "Real-World Civic Impact.": "Real-World Civic प्रभाव.",
      "Impact by UN SDG Domains": "प्रभाव by UN एसडीजी Domains",
      "Active Capstone Projects": "सक्रिय Capstone परियोजनाओं",
      "C2C Neural Graph Network": "C2C Neural Graph Network",
      "Sec 135 CSR Escrow Rails": "Sec 135 सीएसआर एस्क्रो Rails",
      "Single-Window Clearances": "Single-Window Clearances",
      "Student Innovator Portal": "छात्र Innovator पोर्टल",
      "Report a Ground Problem": "Report a Ground Problem",
      "Single-Window NOC Rails": "Single-Window NOC Rails",
      "Problem Summary / Title": "Problem Summary / Title",
      "contact no : 9152300523": "contact no : 9152300523",
      "Team Milestone Velocity": "टीम Milestone Velocity",
      "Escrow Milestone Safety": "एस्क्रो Milestone Safety",
      "3,400 Verified Citizens": "3,400 सत्यापित Citizens",
      "Academic Signoff Issued": "Academic Signoff Issued",
      "Automated SLA Telemetry": "Automated एसएलए टेलीमेट्री",
      "Enterprise Architecture": "Enterprise Architecture",
      "contact no : 8108759790": "contact no : 8108759790",
      "NIRF Impact Points: +42": "NIRF प्रभाव Points: +42",
      "contact no : 8355947969": "contact no : 8355947969",
      "GPS Geo-Spatial Tagging": "GPS Geo-Spatial Tagging",
      "Direct CSR Micro-grants": "Direct सीएसआर Micro-grants",
      "Telemetry Measures SROI": "टेलीमेट्री Measures SROI",
      "contact no : 7400429237": "contact no : 7400429237",
      "Open Stakeholder Portal": "Open Stakeholder पोर्टल",
      "Real-Time ESG Telemetry": "Real-Time ESG टेलीमेट्री",
      "Ground Problem Sourcing": "Ground Problem Sourcing",
      "412 Universities Online": "412 विश्वविद्यालयों Online",
      "Parnavi Janbhor Profile": "Parnavi Janbhor Profile",
      "Zero R&D Budget Burden": "Zero R&D Budget Burden",
      "Zero Paper Bureaucracy": "Zero Paper Bureaucracy",
      "Parnavi Janbhor (VSIT)": "Parnavi Janbhor (VSIT)",
      "Live Persona Telemetry": "Live Persona टेलीमेट्री",
      "Sovereign Citizen Desk": "संप्रभु Citizen Desk",
      "Search System (Ctrl+K)": "Search प्रणाली (Ctrl+K)",
      "Turn Campus Ideas Into": "Turn Campus Ideas Into",
      "Smart City Integration": "Smart City Integration",
      "Industry Research Labs": "Industry अनुसंधान प्रयोगशालाएं",
      "Semantic Vector Engine": "Semantic वेक्टर Engine",
      "Active Solvers: 14,820": "सक्रिय Solvers: 14,820",
      "Closed-Loop Validation": "Closed-Loop Validation",
      "95% Vector Match Score": "95% वेक्टर Match Score",
      "Active Solver Profile:": "सक्रिय Solver Profile:",
      "View Escrow Contract →": "View एस्क्रो Contract →",
      "32,480 Active Students": "32,480 सक्रिय छात्रों",
      "114 Solved • 88 Active": "114 Solved • 88 सक्रिय",
      "Ask C2C AI anything...": "Ask C2C AI anything...",
      "₹2.5L Escrow Milestone": "₹2.5L एस्क्रो Milestone",
      "19.2968° N, 73.0631° E": "19.2968° N, 73.0631° E",
      "Field Sensor Telemetry": "Field सेंसर टेलीमेट्री",
      "9-Stage Ecosystem Flow": "9-Stage Ecosystem Flow",
      "Ground Issues Resolved": "Ground Issues हल किया गया",
      "GIS Coordinates Synced": "GIS Coordinates Synced",
      "Stipend & Grant Ledger": "Stipend & Grant Ledger",
      "Active Capstone Sprint": "सक्रिय Capstone Sprint",
      "AICTE Activity Points": "एआईसीटीई Activity Points",
      "Python MQTT Telemetry": "Python MQTT टेलीमेट्री",
      "Fast-Track AI Routing": "Fast-Track AI Routing",
      "Early Talent Pipeline": "Early Talent Pipeline",
      "82 Solved • 64 Active": "82 Solved • 64 सक्रिय",
      "38 Solved • 71 Active": "38 Solved • 71 सक्रिय",
      "Grievance Resolutions": "Grievance Resolutions",
      "Smart India Hackathon": "Smart India हैकाथॉन",
      "Provisional IP Rights": "Provisional IP Rights",
      "Civic SLA Integration": "Civic एसएलए Integration",
      "Section 135 Compliant": "Section 135 Compliant",
      "Field Prototypes Live": "Field Prototypes Live",
      "CSR & Angel Investors": "सीएसआर & Angel Investors",
      "₹1,84,500 / ₹2,50,000": "₹1,84,500 / ₹2,50,000",
      "Industry Partnerships": "Industry Partnerships",
      "34 Solved • 29 Active": "34 Solved • 29 सक्रिय",
      "Live Intake Telemetry": "Live Intake टेलीमेट्री",
      "AI Smart Matching Hub": "AI Smart Matching Hub",
      "32,480 Active Solvers": "32,480 सक्रिय Solvers",
      "59 Solved • 42 Active": "59 Solved • 42 सक्रिय",
      "District NOC Dispatch": "जिला NOC Dispatch",
      "Municipalities & Govt": "Municipalities & सरकार",
      "AI Matching Workspace": "AI Matching Workspace",
      "Domain Classification": "Domain Classification",
      "Allocated CSR Capital": "Allocated सीएसआर Capital",
      "Student Team Ideation": "छात्र टीम Ideation",
      "Across 9 civic tracks": "Across 9 civic tracks",
      "National SROI Metrics": "राष्ट्रीय SROI मेट्रिक्स",
      "Govt Pilot Greenlight": "सरकार Pilot Greenlight",
      "Review Squad & Apply": "Review Squad & Apply",
      "Lab Hardware Sandbox": "प्रयोगशाला Hardware Sandbox",
      "CSR Grants Disbursed": "सीएसआर Grants Disbursed",
      "Pending Testbed NOCs": "लंबित Testbed NOCs",
      "1-Click Digital NOCs": "1-Click Digital NOCs",
      "Community Solutions.": "Community Solutions.",
      "AI-Driven Matchmaker": "AI-Driven Matchmaker",
      "Academic Publication": "Academic Publication",
      "Launch Live Platform": "Launch Live Platform",
      "Lab Shared Resources": "प्रयोगशाला Shared Resources",
      "Stakeholder Benefits": "Stakeholder Benefits",
      "Supervised Capstones": "Supervised Capstones",
      "Launch Live App Mesh": "Launch Live App Mesh",
      "Communities Impacted": "Communities Impacted",
      "Lab Equipment Grants": "प्रयोगशाला Equipment Grants",
      "NIRF & Credit Matrix": "NIRF & Credit Matrix",
      "AI Hub Latency: 42ms": "AI Hub Latency: 42ms",
      "Solutions Formulated": "Solutions Formulated",
      "Municipal Incubators": "नगर पालिका Incubators",
      "126 Connected Blocks": "126 Connected Blocks",
      "Recommended Lab Unit": "Recommended प्रयोगशाला Unit",
      "Patent & IP Registry": "Patent & IP Registry",
      "Hydrology Flow Data": "Hydrology Flow Data",
      "Join Solution Squad": "Join Solution Squad",
      "Cosine Vector Index": "Cosine वेक्टर Index",
      "System Architecture": "प्रणाली Architecture",
      "Audited ESG Returns": "Audited ESG Returns",
      "AI Problem Matching": "AI Problem Matching",
      "Explore AI Matching": "Explore AI Matching",
      "dashboard_customize": "dashboard_customize",
      "AICTE Academic Sync": "एआईसीटीई Academic Sync",
      "Proof-of-Work Rails": "Proof-of-Work Rails",
      "Accredited Campuses": "Accredited Campuses",
      "Citizen Communities": "Citizen Communities",
      "Avg. Cycle: 38 Days": "Avg. Cycle: 38 दिन",
      "MIC Innovation Cell": "MIC Innovation Cell",
      "Civic NGO Coalition": "Civic NGO Coalition",
      "Stakeholder Portals": "Stakeholder Portals",
      "Impacted Population": "Impacted Population",
      "Direct Civic Portal": "Direct Civic पोर्टल",
      "Security Compliance": "Security अनुपालन",
      "volunteer_activism": "volunteer_activism",
      "Solar MPPT Systems": "सौर MPPT प्रणालियां",
      "High Civic Urgency": "उच्च Civic तात्कालिकता",
      "Ecosystem Dispatch": "Ecosystem Dispatch",
      "Embedded C / ESP32": "Embedded C / ESP32",
      "Student Innovators": "छात्र Innovators",
      "About C2C Platform": "About C2C Platform",
      "Tata R&D CleanTech": "Tata R&D CleanTech",
      "Leadership Council": "Leadership Council",
      "18 District Admins": "18 जिला Admins",
      "CSR Escrow Audited": "सीएसआर एस्क्रो Audited",
      "Fast-Track Permits": "Fast-Track Permits",
      "Realtime Telemetry": "Realtime टेलीमेट्री",
      "Community Problems": "Community Problems",
      "Student Innovation": "छात्र Innovation",
      "Communities Synced": "Communities Synced",
      "University Faculty": "विश्वविद्यालय संकाय",
      "Arsenic: 0.008 ppm": "Arsenic: 0.008 ppm",
      "Lab Sandbox Access": "प्रयोगशाला Sandbox Access",
      "Sec 135 CSR Escrow": "Sec 135 सीएसआर एस्क्रो",
      "SIH 2026 Inception": "एसआईएच 2026 Inception",
      "Govt & NGO Connect": "सरकार & NGO Connect",
      "AICTE Credit Rails": "एआईसीटीई Credit Rails",
      "100% Geo-Validated": "100% Geo-Validated",
      "3 Positions Vacant": "3 Positions Vacant",
      "Citizens Benefited": "Citizens Benefited",
      "4 Sensors Reserved": "4 सेंसर Reserved",
      "Flutter Mobile App": "Flutter Mobile App",
      "ID: C2C-2026-W482": "ID: C2C-2026-W482",
      "₹45,000 Disbursed": "₹45,000 Disbursed",
      "Citizens Affected": "Citizens Affected",
      "Faculty & Mentors": "संकाय & Mentors",
      "Problems Reported": "Problems Reported",
      "System Live Pulse": "प्रणाली Live Pulse",
      "Student Engineers": "छात्र इंजीनियरों",
      "Community Citizen": "Community Citizen",
      "Tata CSR released": "Tata सीएसआर released",
      "Purpose & Mandate": "उद्देश्य और जनादेश",
      "workspace_premium": "workspace_premium",
      "₹4.8 Cr Committed": "₹4.8 Cr Committed",
      "Q1 2026 • Genesis": "Q1 2026 • Genesis",
      "Partner Ecosystem": "Partner Ecosystem",
      "to student teams.": "to student teams.",
      "University Collab": "विश्वविद्यालय Collab",
      "Evolution Journey": "Evolution Journey",
      "CSR Escrow Grants": "सीएसआर एस्क्रो Grants",
      "Student Innovator": "छात्र Innovator",
      "Progress Tracking": "Progress Tracking",
      "Mission Statement": "मिशन Statement",
      "Sec 135 Compliant": "Sec 135 Compliant",
      "Telemetry Privacy": "टेलीमेट्री Privacy",
      "Our Core Mandate": "हमारा मुख्य जनादेश",
      "Sub-24h Dispatch": "Sub-24h Dispatch",
      "Mesh v4.2 Active": "Mesh v4.2 सक्रिय",
      "4.2M Queries/day": "4.2M Queries/day",
      "Escrow Burn Rate": "एस्क्रो Burn Rate",
      "Platform Modules": "Platform Modules",
      "Healthcare Track": "स्वास्थ्य सेवा Track",
      "API Architecture": "API Architecture",
      "Campus2Community": "Campus2Community",
      "CSR Escrow Rails": "सीएसआर एस्क्रो Rails",
      "Impact Analytics": "प्रभाव Analytics",
      "Mission & Vision": "मिशन & विजन",
      "Municipal Bodies": "नगर पालिका Bodies",
      "CAMPUS2COMMUNITY": "CAMPUS2COMMUNITY",
      "Explore AI Model": "Explore AI Model",
      "Live Dispatches": "Live Dispatches",
      "Maharashtra BMC": "Maharashtra BMC",
      "Industry Mentor": "Industry Mentor",
      "space_dashboard": "space_dashboard",
      "AI Matching Hub": "AI Matching Hub",
      "Faculty Advisor": "संकाय Advisor",
      "Geo Coordinates": "Geo Coordinates",
      "Quick Shortcuts": "Quick Shortcuts",
      "+14% this month": "+14% this month",
      "Verified Impact": "सत्यापित प्रभाव",
      "account_balance": "account_balance",
      "Problems Solved": "Problems Solved",
      "Select Language": "Select Language",
      "Faculty Mentors": "संकाय Mentors",
      "Lab Pass Active": "प्रयोगशाला Pass सक्रिय",
      "Domain Category": "Domain Category",
      "Agritech Track": "Agritech Track",
      "corporate_fare": "corporate_fare",
      "Quick Searches": "Quick Searches",
      "Clear Insights": "Clear Insights",
      "Student Portal": "छात्र पोर्टल",
      "District Admin": "जिला Admin",
      "Pilot Approved": "Pilot Approved",
      "Municipal Desk": "नगर पालिका Desk",
      "Status Console": "स्थिति Console",
      "YOLOv8 Edge AI": "YOLOv8 Edge AI",
      "Industry / CSR": "Industry / सीएसआर",
      "SPRINT-C2C-881": "SPRINT-C2C-881",
      "Valid Manifest": "Valid Manifest",
      "C2C Copilot AI": "C2C Copilot AI",
      "Report Problem": "Report Problem",
      "C2C AI Copilot": "C2C AI Copilot",
      "6 Active Teams": "6 सक्रिय Teams",
      "report_problem": "report_problem",
      "Severity Level": "Severity Level",
      "local_library": "local_library",
      "Platform Home": "Platform Home",
      "Close Profile": "Close Profile",
      "C2C-2026-W482": "C2C-2026-W482",
      "Pilot Testing": "Pilot Testing",
      "4.2x Multiple": "4.2x Multiple",
      "person_search": "person_search",
      "location_city": "location_city",
      "verified_user": "verified_user",
      "Verified SROI": "सत्यापित SROI",
      "Founding Team": "Founding टीम",
      "chevron_right": "chevron_right",
      "rocket_launch": "rocket_launch",
      "Home Overview": "Home Overview",
      "Live Tracking": "Live Tracking",
      "How C2C Works": "How C2C Works",
      "Escrow Locked": "एस्क्रो Locked",
      "notifications": "notifications",
      "SROI Measured": "SROI Measured",
      "arrow_forward": "arrow_forward",
      "2 Co-Authored": "2 Co-Authored",
      "Core Pillars": "मुख्य स्तंभ",
      "85 Verifiers": "85 Verifiers",
      "finance_mode": "finance_mode",
      "Toggle Theme": "Toggle Theme",
      "114 Resolved": "114 हल किया गया",
      "Audit Ledger": "Audit Ledger",
      "Full Rollout": "Full Rollout",
      "check_circle": "check_circle",
      "CSR Sponsors": "सीएसआर Sponsors",
      "arrow_upward": "arrow_upward",
      "cloud_upload": "cloud_upload",
      "account_tree": "account_tree",
      "C2C Timeline": "C2C Timeline",
      "412 Campuses": "412 Campuses",
      "linear_scale": "linear_scale",
      "#ai-matching": "#ai-matching",
      "engineering": "engineering",
      "open_in_new": "open_in_new",
      "expand_more": "expand_more",
      "BLE Sensors": "BLE सेंसर",
      "Launch Mesh": "Launch Mesh",
      "g_translate": "g_translate",
      "query_stats": "query_stats",
      "location_on": "location_on",
      "18 mins ago": "18 mins ago",
      "#challenges": "#challenges",
      "42 mins ago": "42 mins ago",
      "ROS2 Drones": "ROS2 Drones",
      "trending_up": "trending_up",
      "history_edu": "history_edu",
      "diversity_3": "diversity_3",
      "₹50,00,000": "₹50,00,000",
      "group_work": "group_work",
      "Leadership": "Leadership",
      "2 Requests": "2 Requests",
      "co_present": "co_present",
      "add_circle": "add_circle",
      "Identified": "Identified",
      "foundation": "foundation",
      "Challenges": "Challenges",
      "device_hub": "device_hub",
      "lock_clock": "lock_clock",
      "₹32,50,000": "₹32,50,000",
      "psychology": "psychology",
      "domain_add": "domain_add",
      "NIC Synced": "NIC Synced",
      "visibility": "visibility",
      "Active Now": "सक्रिय Now",
      "CSR Backed": "सीएसआर Backed",
      "Lab Review": "प्रयोगशाला Review",
      "water_drop": "water_drop",
      "handshake": "handshake",
      "Live Mesh": "Live Mesh",
      "apartment": "apartment",
      "#tracking": "#tracking",
      "dark_mode": "dark_mode",
      "82% Match": "82% Match",
      "Analytics": "Analytics",
      "pie_chart": "pie_chart",
      "₹2,50,000": "₹2,50,000",
      "About C2C": "About C2C",
      "lightbulb": "lightbulb",
      "Ecosystem": "Ecosystem",
      "analytics": "analytics",
      "neurology": "neurology",
      "88% Match": "88% Match",
      "PHASE 08": "PHASE 08",
      "PHASE 01": "PHASE 01",
      "AI Match": "AI Match",
      "payments": "payments",
      "verified": "verified",
      "pin_drop": "pin_drop",
      "About Us": "About Us",
      "C2C Core": "C2C मुख्य",
      "PHASE 09": "PHASE 09",
      "Proposed": "Proposed",
      "PHASE 06": "PHASE 06",
      "timeline": "timeline",
      "PHASE 07": "PHASE 07",
      "PHASE 03": "PHASE 03",
      "PHASE 05": "PHASE 05",
      "task_alt": "task_alt",
      "PHASE 02": "PHASE 02",
      "Timeline": "Timeline",
      "PHASE 04": "PHASE 04",
      "Tracking": "Tracking",
      "Pillars": "स्तंभ",
      "Modules": "Modules",
      "science": "science",
      "factory": "factory",
      "Portals": "Portals",
      "balance": "balance",
      "English": "English",
      "sensors": "sensors",
      "biotech": "biotech",
      "32,480+": "32,480+",
      "₹4.8 Cr": "₹4.8 Cr",
      "Mission": "मिशन",
      "Jan 02": "Jan 02",
      "48,240": "48,240",
      "Nov 04": "Nov 04",
      "Oct 12": "Oct 12",
      "shield": "shield",
      "patent": "patent",
      "Dec 15": "Dec 15",
      "school": "school",
      "Oct 24": "Oct 24",
      "policy": "policy",
      "target": "target",
      "हिन्दी": "हिन्दी",
      "search": "search",
      "Nov 18": "Nov 18",
      "About": "About",
      "close": "close",
      "gavel": "gavel",
      "4 New": "4 New",
      "मराठी": "मराठी",
      "group": "group",
      "speed": "speed",
      "badge": "badge",
      "1,284": "1,284",
      "help": "help",
      "Home": "Home",
      "v2.4": "v2.4",
      "info": "info",
      "412+": "412+",
      "Join": "Join",
      "home": "home",
      "menu": "menu",
      "flag": "flag",
      "TEAM": "TEAM",
      "bolt": "bolt",
      "send": "send",
      "map": "map",
      "34%": "34%",
      "FAQ": "FAQ",
      "742": "742",
      "19%": "19%",
      "21%": "21%",
      "184": "184",
      "126": "126",
      "312": "312",
      "Hub": "Hub",
      "C2C": "C2C",
      "ESC": "ESC",
      "hub": "hub",
      "26%": "26%",
      "EN": "EN",
      "🇮🇳": "🇮🇳",
      "HI": "HI",
      "MR": "MR",
      "89": "89",
      "🇬🇧": "🇬🇧",
      "Driven by": "द्वारा संचालित",
      "Orchestrated by": "द्वारा आर्केस्ट्रेटेड",
      "Bridging Campus & Community for Real-World Civic Impact.": "वास्तविक नागरिक प्रभाव के लिए कैंपस और समुदाय को जोड़ना।",
      "Bridging Campus & Community": "कैंपस और समुदाय को जोड़ना",
      "for Real-World Civic Impact.": "वास्तविक नागरिक प्रभाव के लिए।",
      "Real-World": "वास्तविक",
      "Real-world": "वास्तविक",
      "real-world": "वास्तविक",
      "12-Digit Aadhaar Card Number": "12-अंकों का आधार कार्ड नंबर",
      "Aadhaar Card Number / Sovereign ID": "आधार कार्ड नंबर / संप्रभु आईडी",
      "Student Innovator Mode: Login with 12-digit Aadhaar Card Number linked to DigiLocker / DigiEdu.": "छात्र नवप्रवर्तक मोड: डिजीलॉकर से जुड़े 12-अंकीय आधार कार्ड नंबर के साथ लॉगिन करें।",
      "Faculty Mentor Mode: Login with 12-digit Aadhaar Card Number linked to AICTE Faculty Registry.": "संकाय संरक्षक मोड: एआईसीटीई फैकल्टी रजिस्ट्री से जुड़े आधार कार्ड नंबर के साथ लॉगिन करें।",
      "Government & ULB Mode: Login with Aadhaar Card Number linked to MeriPehchaan National Officer Registry.": "सरकार और यूएलबी मोड: मेरीपहचान अधिकारी रजिस्ट्री से जुड़े आधार नंबर के साथ लॉगिन करें।",
      "Corporate CSR Partner Mode: Login with Aadhaar Card Number linked to Corporate Section 135 Escrow Signatory.": "कॉर्पोरेट सीएसआर मोड: धारा 135 एस्क्रो हस्ताक्षरकर्ता से जुड़े आधार नंबर के साथ लॉगिन करें।",
      "Sign In to Sovereign Mesh": "संप्रभु मेश में साइन इन करें",
      "Sovereign Identity Authentication": "संप्रभु पहचान प्रमाणीकरण",
      "Access your personalized dashboard based on your registered ecosystem persona.": "अपने पंजीकृत पारिस्थितिकी तंत्र व्यक्तित्व के आधार पर अपने व्यक्तिगत डैशबोर्ड तक पहुंचें।",
      "Student Innovator Mode: Login with university email or PRN.": "छात्र नवप्रवर्तक मोड: विश्वविद्यालय ईमेल या पीआरएन के साथ लॉगिन करें।",
      "Institutional Email / User ID": "संस्थागत ईमेल / यूजर आईडी",
      "Security Password": "सुरक्षा पासवर्ड",
      "Remember Sovereign Session": "संप्रभु सत्र याद रखें",
      "Forgot Key?": "कुंजी भूल गए?",
      "Or Sign In With": "या इसके साथ साइन इन करें",
      "Don't have an institutional account?": "क्या आपके पास संस्थागत खाता नहीं है?"
},
    "mr": {
      "To establish a nationwide digital grid where zero citizen distress calls go unanswered, and every engineering student graduates with verified, real-world societal problem-solving experience backed by government NOC clearance and institutional recognition.": "नागरिकांची कोणतीही तक्रार अनुत्तरित राहणार नाही अशी देशव्यापी डिजिटल ग्रिड स्थापन करणे, आणि प्रत्येक अभियांत्रिकी विद्यार्थ्याला शासकीय NOC मंजुरी व संस्थात्मक मान्यतेसह प्रत्यक्ष सामाजिक समस्या सोडवण्याचा अनुभव मिळवून देणे.",
      "To seamlessly unify the Ministry of Education, AICTE academic frameworks, Municipal Corporations, District Collectors, and Corporate CSR wings under a single cryptographic, AI-driven workflow that matches ground challenges to college labs in real time.": "शिक्षण मंत्रालय, AICTE शैक्षणिक फ्रेमवर्क, महानगरपालिका, जिल्हा जिल्हाधिकारी आणि कॉर्पोरेट CSR विभागांना एकाच AI-चलित वर्कफ्लोमध्ये एकत्रित करणे जे प्रत्यक्ष समस्यांचे कॉलेज लॅब्सशी रीअल-टाइम जुळवणी करते.",
      "Severe industrial arsenic runoff affecting Bhiwandi rural blocks. Recommended project architecture: solar-powered UV/Arsenic electro-flocculation unit paired with real-time sub-surface telemetry nodes reporting to district dashboard.": "भिवंडी ग्रामीण भागाला प्रभावित करणारा गंभीर औद्योगिक आर्सेनिक प्रवाह. शिफारस केलेले प्रकल्प स्वरूप: सौरउर्जेवर चालणारे UV/आर्सेनिक इलेक्ट्रो-फ्लॉक्युलशन युनिट जे जिल्हा डॅशबोर्डला रिपोर्ट करणाऱ्या रिअल-टाइम टेलिमेट्री नोड्सशी जोडलेले आहे.",
      "C2C is integrated directly with AICTE Activity Point guidelines. When a student team completes a ground solution endorsed by their faculty guide and municipal inspector, credits are pushed directly into their university transcript.": "C2C हे थेट AICTE अ‍ॅक्टिव्हिटी पॉइंट मार्गदर्शक तत्त्वांशी एकत्रित केले आहे. जेव्हा एखादी विद्यार्थी टीम त्यांच्या प्राध्यापक मार्गदर्शक आणि पालिका निरीक्षकाने मंजूर केलेले समाधान पूर्ण करते, तेव्हा क्रेडिट्स थेट त्यांच्या विद्यापीठ ट्रान्सक्रिप्टमध्ये जोडले जातात.",
      "Citizens, municipal counselors, or local ward heads submit ground issues via text, photos, or voice notes. The C2C AI semantic engine geotags the issue, removes duplicate entries, and verifies municipal urgency within 24 hours.": "नागरिक, नगरसेवक किंवा स्थानिक प्रभाग प्रमुख मजकूर, फोटो किंवा व्हॉइस नोट्सद्वारे समस्या सबमिट करतात. C2C AI इंजिन या समस्येला जिओटॅग करते, डुप्लिकेट नोंदी हटवते आणि 24 तासांच्या आत पालिकेची तातडी पडताळते.",
      "Yes. All CSR contributions are managed via escrow contracts compliance-mapped to Companies Act Section 135. Automated milestone reports, municipal utilization certificates, and receipt tokens are generated upon every payout.": "होय. सर्व CSR योगदान कंपनी कायदा कलम 135 चे पालन करणाऱ्या एस्क्रॉ करारांद्वारे व्यवस्थापित केले जातात. प्रत्येक पेआउटवर स्वयंचलित टप्पा अहवाल, पालिका वापर प्रमाणपत्रे आणि पावती टोकन्स तयार होतात.",
      "Institutional heads can register directly on the C2C portal using their official domain email (.edu.in or .gov.in). Account clearance takes less than 2 hours following automated credential verification.": "संस्थात्मक प्रमुख त्यांच्या अधिकृत ईमेल (.edu.in किंवा .gov.in) द्वारे थेट C2C पोर्टलवर नोंदणी करू शकतात. स्वयंचलित क्रेडेन्शियल पडताळणीनंतर खाते मंजुरीस 2 तासांपेक्षा कमी वेळ लागतो.",
      "Direct access to AI-matched community challenges that align with university curriculum credits. Form interdisciplinary teams, unlock hardware stipends, and submit verified engineering solutions.": "विद्यापीठाच्या अभ्यासक्रम क्रेडिट्सशी सुसंगत असलेल्या AI-मॅच केलेल्या सामाजिक आव्हानांमध्ये थेट प्रवेश. आंतरविद्याशाखीय टीम तयार करा, हार्डवेअर विद्यावेतन अनलॉक करा आणि सत्यापित अभियांत्रिकी उपाय सबमिट करा.",
      "Sovereign digital bridge unifying Smart India Hackathon innovators, public administration, university research, and civic ground telemetry for verified societal impact.": "स्मार्ट इंडिया हॅकाथॉन शोधक, सार्वजनिक प्रशासन, विद्यापीठ संशोधन आणि नागरिक टेलिमेट्री यांना एकत्रित करणारा डिजिटल सेतू.",
      "Transforms vernacular or voice distress reports into structured engineering specs, automatically routing them to department labs based on faculty domain expertise.": "स्थानिक भाषा किंवा व्हॉइस अहवालांचे संरचित अभियांत्रिकी वैशिष्ट्यांमध्ये रूपांतर करते, जे प्राध्यापकांच्या तज्ज्ञतेनुसार विभाग प्रयोगशाळांकडे स्वयंचलितपणे पाठवले जाते.",
      "Empowering ward counselors, village sarpanches, and citizens to register distress vectors with automated GPS tagging and NLP deduplication.": "नगरसेवक, सरपंच आणि नागरिकांना स्वयंचलित GPS टॅगिंग आणि NLP द्वारे समस्या नोंदवण्यासाठी सक्षम करणे.",
      "Direct CSR funding, tech hardware grants, and executive engineering mentorship under formalized Section 135 compliance.": "औपचारिक कलम 135 पालनांतर्गत थेट CSR निधी, तंत्रज्ञान हार्डवेअर अनुदान आणि कार्यकारी अभियांत्रिकी मार्गदर्शन.",
      "Real-time telemetry measuring social return on investment, state-by-state resolution velocities, and UN SDG alignment.": "सामाजिक परतावा, राज्यनिहाय निराकरण वेग आणि UN SDG सुसंगतता मोजणारी रिअल-टाइम टेलिमेट्री.",
      "From ground-level citizen distress calls to verified societal deployment, every milestone is auditable and automated.": "नागरिकांच्या तक्रारींपासून ते सत्यापित सामाजिक अंमलबजावणीपर्यंत, प्रत्येक टप्पा तपासण्यायोग्य आणि स्वयंचलित आहे.",
      "Engineered for high-volume civic scale, regulatory Section 135 compliance, and frictionless cross-sector execution.": "मोठ्या प्रमाणावरील नागरिक समस्या, कलम 135 चे नियम पालन आणि सुरळीत अंमलबजावणीसाठी डिझाइन केलेले.",
      "Discover and report ground-level issues with spatial GIS mapping, population density indexing, and urgency scoring.": "जीआयएस मॅपिंग, लोकसंख्या घनता निर्देशांक आणि तातडीच्या स्कोअरिंगसह समस्या शोधा आणि नोंदवा.",
      "Geo-tagged submissions via web portal, vernacular voice notes, or WhatsApp bots by panchayats and local residents.": "ग्रामपंचायती आणि स्थानिक रहिवाशांद्वारे वेब पोर्टल, व्हॉइस नोट्स किंवा व्हॉट्सअ‍ॅप बॉट्सद्वारे जिओ-टॅग केलेल्या नोंदी.",
      "Automated ESG reporting and Social Return on Investment (SROI) metrics formatted directly for ministerial review.": "मंत्रालयीन पुनरावलोकनासाठी थेट तयार केलेले स्वयंचलित ESG अहवाल आणि सामाजिक परतावा (SROI) मेट्रिक्स.",
      "Neural vector scoring linking citizen distress vectors to collegiate engineering proficiencies and lab inventory.": "नागरिकांच्या समस्यांना कॉलेजच्या अभियांत्रिकी कौशल्य आणि प्रयोगशाळांशी जोडणारे न्यूरल व्हेक्टर स्कोअरिंग.",
      "Pitch multidisciplinary solutions, earn verified AICTE academic credit points, and register provisional patents.": "बहुविद्याशाखीय उपाय सादर करा, सत्यापित AICTE शैक्षणिक क्रेडिट पॉइंट्स मिळवा आणि तात्पुरते पेटंट नोंदवा.",
      "Milestone-governed execution with verifiable digital proofs of work, GitHub linkage, and geofenced field audits.": "कामाचे डिजिटल पुरावे, गिटहब लिंकेज आणि जिओफेंस केलेल्या फील्ड ऑडिटसह टप्पानिहाय अंमलबजावणी.",
      "NLP models perform cross-regional deduplication, priority grading, and cross-reference with municipal datasets.": "NLP मॉडेल प्रादेशिक डुप्लिकेट काढणे, प्राधान्य क्रमवारी आणि पालिका डेटासेटशी पडताळणी करतात.",
      "Connect department research labs with municipal challenges. Turn final-year capstone theses into field pilots.": "विभाग संशोधन लॅब्सना पालिकेच्या आव्हानांशी जोडा. अंतिम वर्षाच्या प्रोजेक्ट्सचे प्रत्यक्ष पायलट प्रोजेक्ट्समध्ये रूपांतर करा.",
      "Direct portal for District Collectors and Municipal Commissioners to validate pilot proposals within 48 hours.": "जिल्हाधिकारी आणि पालिका आयुक्तांना 48 तासांच्या आत पायलट प्रस्तावांना मान्यता देण्यासाठी थेट पोर्टल.",
      "Algorithms pair the verified challenge with student engineering cohorts, department labs, and faculty guides.": "अल्गोरिदम सत्यापित आव्हानाची विद्यार्थी अभियांत्रिकी गट, विभाग लॅब्स आणि प्राध्यापक मार्गदर्शकांशी जोडणी करतात.",
      "Interdisciplinary squads assemble (e.g., 3 engineers + 1 data scientist) under an accredited faculty mentor.": "प्राध्यापक मार्गदर्शकाखाली आंतरविद्याशाखीय पथके (उदा. 3 अभियंते + 1 डेटा सायंटिस्ट) एकत्र येतात.",
      "Immutable impact quantified: liters purified, kWh saved, citizen hours recovered, and Social ROI calculated.": "कायमस्वरूपी प्रभाव: शुद्ध केलेले लिटर, वाचवलेली kWh, वाचवलेले नागरिक तास आणि मोजलेला सामाजिक ROI.",
      "Prototypes live in community. Real-time IoT feedback and citizen satisfaction ratings stream to dashboards.": "प्रोटोटाइप समाजात कार्यरत आहेत. रिअल-टाइम IoT अभिप्राय आणि नागरिक समाधान रेटिंग डॅशबोर्डवर दिसतात.",
      "University lab facilities and professors certify hardware safety, sensor calibration, and code stability.": "विद्यापीठ प्रयोगशाळा सुविधा आणि प्राध्यापक हार्डवेअर सुरक्षितता, सेन्सर कॅलिब्रेशन आणि कोड स्थिरतेचे प्रमाणन करतात.",
      "Upon dispatch, 4 shortlisted universities within a 50km radius and 2 CSR partners receive automated RFPs.": "पाठवल्यावर, 50 किमी परिसरातील 4 निवडलेली विद्यापीठे आणि 2 CSR भागीदारांना स्वयंचलित RFP प्राप्त होतात.",
      "Autonomous edge-AI drone scans for pink bollworm detection across smallholder cotton belts in Vidarbha.": "विदर्भातील कापूस पट्ट्यात गुलाबी बोंडअळी शोधण्यासाठी स्वयंचलित एज-AI ड्रोन स्कॅन.",
      "\"Hi! I'm C2C AI. Let's turn a civic challenge into an actionable solution. How can I guide you today?\"": "\"नमस्कार! मी C2C AI आहे. चला नागरी आव्हानाचे कृतीयोग्य समाधानात रूपांतर करूया. आज मी तुम्हाला कसा मार्गदर्शक ठरू शकतो?\"",
      "Corporate CSR capital, cloud compute credits, and hardware toolkits route directly to project escrow.": "कॉर्पोरेट CSR भांडवल, क्लाउड कॉम्प्युट क्रेडिट्स आणि हार्डवेअर टूलकिट्स थेट प्रकल्प एस्क्रॉकडे जातात.",
      "Continuous cellular temperature telemetry for primary healthcare storage in Gadchiroli tribal belts.": "गडचिरोली आदिवासी भागातील प्राथमिक आरोग्य सेवा साठवणुकीसाठी निरंतर तापमान टेलिमेट्री.",
      "District Collectors authorize testing grounds and road-cutting NOCs with zero bureaucratic delay.": "जिल्हाधिकारी कोणतीही प्रशासकीय दिरंगाई न करता चाचणी जागा आणि रस्ता खोदण्याची NOC मंजूर करतात.",
      "Partner: Bhiwandi Municipal Council • Host: Veermata Jijabai Technological Institute (VJTI)": "भागीदार: भिवंडी नगर परिषद • होस्ट: वीरमाता जिजाबाई तंत्रज्ञान संस्था (VJTI)",
      "Sovereign Civic Orchestrator": "संप्रभु नागरी आर्केस्ट्रेटर",
      "Bridging Campus Innovation & Community Distress": "कैंपस नावीन्यता आणि नागरी समस्यांची जोडणी",
      "Real-World Problem to Verified Solution Pipeline": "प्रत्यक्ष समस्येपासून सत्यापित उपायापर्यंतची पाइपलाइन",
      "Live Sovereign Infrastructure Telemetry": "थेट संप्रभु पायाभूत सुविधा टेलिमेट्री",
      "Grassroots Challenge Intake Pipeline": "तळागाळातील आव्हान स्वीकारणारी पाइपलाइन",
      "National Social ROI & SDG Dashboard": "राष्ट्रीय सामाजिक ROI आणि SDG डॅशबोर्ड",
      "Architected for Every Sovereign Stakeholder": "प्रत्येक संप्रभु भागधारकासाठी डिझाइन केलेले",
      "4-Phase Sovereign Governance Lifecycle": "4-टप्प्यांचे संप्रभु शासन जीवनचक्र",
      "Frequently Asked Questions": "वारंवार विचारले जाणारे प्रश्न",
      "Ready to Transform Your Campus Innovation?": "तुमच्या कॉलेजमधील इनोव्हेशनला सामाजिक उपायात बदलण्यास तयार आहात?",
      "About Campus2Community": "Campus2Community बद्दल",
      "Core Vision & Mission": "मुख्य ध्येय आणि उद्दिष्टे",
      "Our Vision": "आमचे ध्येय",
      "Our Mission": "आमचे उद्दिष्ट",
      "Core Platform Pillars": "मुख्य प्लॅटफॉर्म स्तंभ",
      "Evolution & Milestones": "विकास आणि टप्पे",
      "Unified Platform Core Team": "एकीकृत प्लॅटफॉर्म कोर टीम",
      "GOVERNMENT APPROVED CIVIC INFRASTRUCTURE": "शासकीय मान्यताप्राप्त नागरी पायाभूत सुविधा",
      "SIH 2026 FIELD INTAKE PIPELINE": "SIH 2026 फील्ड इनटेक पाइपलाइन",
      "LIVE TELEMETRY & DISPATCH MATRIX": "थेट टेलिमेट्री आणि डिस्पॅच मॅट्रिक्स",
      "QUANTIFIABLE SOCIETAL ROI": "संख्यात्मक सामाजिक ROI",
      "UNIFIED STAKEHOLDER VALUE": "एकीकृत भागधारक मूल्य",
      "GROUND-TO-GOVERNMENT LIFECYCLE": "जमीन ते शासन जीवनचक्र",
      "EVERYTHING YOU NEED TO KNOW": "तुम्हाला माहिती असणे आवश्यक सर्व काही",
      "JOIN THE SOVEREIGN MESH": "संप्रभु जाळ्यात सामील व्हा",
      "CAMPUS2COMMUNITY ECOSYSTEM": "कैंपस2कम्युनिटी परिसंस्था",
      "VIDYALANKAR INSTITUTE OF TECHNOLOGY (VSIT)": "विद्यालंकार तंत्रज्ञान संस्था (VSIT)",
      "Students & Innovators": "विद्यार्थी आणि नवसंशोधक",
      "Universities & Labs": "विद्यापीठे आणि लॅब्स",
      "Faculty Mentorship": "प्राध्यापक मार्गदर्शन",
      "Field NGO Coalition": "क्षेत्रीय एनजीओ आघाडी",
      "Social Auditing": "सामाजिक परीक्षण",
      "Industry CSR Escrow": "उद्योग CSR एस्क्रॉ",
      "Sec 135 Compliance": "कलम 135 पालन",
      "Government NOC": "शासकीय NOC",
      "District Administration": "जिल्हा प्रशासन",
      "Urban Local Bodies": "नागरी स्थानिक संस्था",
      "Active Solvers": "सक्रिय निवारक",
      "Campuses": "कैंपस",
      "Verifiers": "पडताळणीकर्ते",
      "Committed": "कटिबद्ध",
      "CITIZENS BENEFITED": "लाभान्वित नागरिक",
      "COMMUNITIES IMPACTED": "प्रभावित समुदाय",
      "PROBLEMS SOLVED": "सोडवलेल्या समस्या",
      "ACTIVE CAPSTONE PROJECTS": "सक्रिय प्रकल्प",
      "WATER & SANITATION (SDG 6)": "पाणी व स्वच्छता (SDG 6)",
      "AGRITECH & SOIL HEALTH (SDG 2)": "कृषी तंत्रज्ञान व मृदा (SDG 2)",
      "SMART INFRASTRUCTURE (SDG 11)": "स्मार्ट पायाभूत सुविधा (SDG 11)",
      "RURAL HEALTHCARE (SDG 3)": "ग्रामीण आरोग्य (SDG 3)",
      "All Categories": "सर्व प्रकार",
      "Water Systems": "जल प्रणाली",
      "Urban Roads": "शहरी रस्ते",
      "Solar Microgrids": "सौर मायक्रोग्रिड्स",
      "Rural Health": "ग्रामीण आरोग्य",
      "Civic Infrastructure": "नागरी पायाभूत सुविधा",
      "Agriculture Tech": "कृषी तंत्रज्ञान",
      "Filter by Keyword or District...": "शब्द किंवा जिल्ह्यानुसार शोधा...",
      "Search Problems...": "समस्या शोधा...",
      "Search tickets, districts, SDGs...": "तिकीट, जिल्हे, SDGs शोधा...",
      "Select Priority": "प्राधान्य निवडा",
      "Critical (ULB 24h SLA)": "अत्यंत तातडीचे (ULB 24तास SLA)",
      "High Urgency": "उच्च तातडी",
      "Medium Urgency": "मध्यम तातडी",
      "Low Urgency": "कमी तातडी",
      "Explore Problem Mesh": "समस्या जाळे पाहा",
      "Submit Field Distress Report": "समस्या अहवाल सबमिट करा",
      "Submit Ticket to Sovereign Mesh": "जाळ्यात तिकीट सबमिट करा",
      "Trigger Auto-NOC Dispatch": "ऑटो-NOC पाठवा",
      "View Gazetted Consent Certificate": "राजपत्रित संमती प्रमाणपत्र पाहा",
      "Join 32,000+ Student Engineers": "32,000+ विद्यार्थी अभियंत्यांमध्ये सामील व्हा",
      "Explore SIH 2026 Portal": "SIH 2026 पोर्टल पाहा",
      "Register Institution / Student Team": "संस्था / विद्यार्थी संघ नोंदणी करा",
      "Overview": "आढावा",
      "Intake Pipeline": "इनटेक पाइपलाइन",
      "AI Orchestrator": "AI आर्केस्ट्रेटर",
      "Impact Telemetry": "प्रभाव टेलिमेट्री",
      "Stakeholders": "भागधारक",
      "Governance & SIH": "प्रशासन आणि SIH",
      "About Us & Platform Vision": "आमच्याबद्दल व व्हिजन",
      "Code Base & Tech Specs": "कोड बेस आणि तंत्रज्ञान",
      "Ministry of Education": "शिक्षण मंत्रालय",
      "AICTE Portal Integration": "AICTE पोर्टल एकत्रीकरण",
      "SIH 2026 Grand Finale": "SIH 2026 ग्रँड फिनाले",
      "Students": "विद्यार्थी",
      "Innovators": "नवसंशोधक",
      "Universities": "विद्यापीठे",
      "Labs": "प्रयोगशाळा",
      "Faculty": "प्राध्यापक",
      "Mentorship": "मार्गदर्शन",
      "Field": "क्षेत्र",
      "NGO": "एनजीओ",
      "Coalition": "आघाडी",
      "Social": "सामाजिक",
      "Auditing": "परीक्षण",
      "Industry": "उद्योग",
      "Escrow": "एस्क्रॉ",
      "Compliance": "पालन",
      "Government": "शासन",
      "District": "जिल्हा",
      "Administration": "प्रशासन",
      "Urban": "शहरी",
      "Local": "स्थानिक",
      "Bodies": "संस्था",
      "Active": "सक्रिय",
      "Solvers": "निवारक",
      "Ticket": "तिकीट",
      "Problem": "समस्या",
      "Solution": "उपाय",
      "Status": "स्थिती",
      "Verified": "सत्यापित",
      "Pending": "प्रलंबित",
      "Resolved": "सोडवले",
      "Details": "तपशील",
      "Filter": "शोधा",
      "Search": "शोधा",
      "Submit": "सबमिट करा",
      "Close": "बंद करा",
      "Back": "मागे",
      "Next": "पुढील",
      "Previous": "मागील",
      "All": "सर्व",
      "Critical": "अत्यंत तातडीचे",
      "High": "उच्च",
      "Medium": "मध्यम",
      "Low": "कमी",
      "Parnavi Janbhor": "पर्णवी जाणभोर",
      "Tanmay Shirgudi": "तन्मय शिरगुडी",
      "Shlok Jadhav": "श्लोक जाधव",
      "Shrutesh Gaikwad": "श्रुतेश गायकवाड",
      "Kashaf Khan": "कशफ खान",
      "Aqsa Haji": "अक्सा हाजी",
      "contact no: 9022634336": "संपर्क क्र: 9022634336",
      "contact no: 8850604104": "संपर्क क्र: 8850604104",
      "contact no: 9137025807": "संपर्क क्र: 9137025807",
      "contact no: 8828359404": "संपर्क क्र: 8828359404",
      "contact no: 8291079379": "संपर्क क्र: 8291079379",
      "contact no: 9137070110": "संपर्क क्र: 9137070110",
      "Project Lead & AI Specialist": "प्रकल्प प्रमुख आणि AI तज्ज्ञ",
      "Full-Stack Systems Architect": "फुल-स्टॅक सिस्टीम आर्किटेक्ट",
      "Frontend & UX Lead": "फ्रंटएंड आणि UX प्रमुख",
      "Backend & Escrow Engineer": "बॅकएंड आणि एस्क्रॉ अभियंता",
      "GIS & Field Operations Lead": "GIS आणि फील्ड ऑपरेशन्स प्रमुख",
      "Public Policy & Outreach Coordinator": "सार्वजनिक धोरण आणि संपर्क समन्वयक",
      "Vidyalankar Institute of Technology": "विद्यालंकार तंत्रज्ञान संस्था",
      "Initial NLP prototype tested with 12 engineering colleges in Mumbai Metropolitan Region. 84 field problems cataloged.": "मुंबई महानगर क्षेत्रातील 12 अभियांत्रिकी महाविद्यालयांमध्ये सुरुवातीच्या NLP प्रोटोटाइपची चाचणी. 84 समस्यांची नोंद.",
      "Onboarded 18 District Collectorates and established the automated Section 135 CSR escrow protocol with 6 corporate partners.": "18 जिल्हाधिकारी कार्यालये जोडली आणि 6 कॉर्पोरेट भागीदारांसह स्वयंचलित कलम 135 CSR एस्क्रॉ प्रोटोकॉल स्थापित केला.",
      "₹4.8 Crore in corporate funding locked into project escrows; 412 campuses linked across Maharashtra and Gujarat.": "₹4.8 कोटी कॉर्पोरेट निधी प्रकल्प एस्क्रॉमध्ये जमा; महाराष्ट्र आणि गुजरातमध्ये 412 कॅम्पस जोडले.",
      "National SIH expansion targeting 1,200+ engineering institutions and integration with Ministry of Education digital portal.": "1,200+ अभियांत्रिकी संस्था आणि शिक्षण मंत्रालयाच्या डिजिटल पोर्टलशी एकत्रीकरणाचे राष्ट्रीय SIH उद्दिष्ट.",
      "How does Campus2Community verify the legitimacy of ground issues?": "Campus2Community समस्यांची तातडी आणि सत्यता कशी पडताळते?",
      "How do student engineers receive academic credits for C2C projects?": "विद्यार्थी अभियंत्यांना C2C प्रकल्पांसाठी शैक्षणिक क्रेडिट्स कसे मिळतात?",
      "Are CSR funds provided directly to students or through institutional escrows?": "CSR निधी थेट विद्यार्थ्यांना दिला जातो की संस्थागत एस्क्रॉद्वारे?",
      "How quickly can a District Collector or Municipal Body onboard to C2C?": "जिल्हाधिकारी किंवा नगरपालिका C2C प्लॅटफॉर्मवर किती लवकर नोंदणी करू शकतात?",
      "1. Ground Distress Capture": "1. समस्या नोंदणी",
      "2. AI Matching & Dispatch": "2. AI मॅचिंग आणि डिस्पॅच",
      "3. Sovereign Escrow & Execution": "3. एस्क्रॉ आणि अंमलबजावणी",
      "4. Impact Measurement": "4. प्रभाव मोजणी",
      "ULB SLA: 48h": "ULB SLA: 48तास",
      "ULB SLA: 24h": "ULB SLA: 24तास",
      "ULB SLA: 72h": "ULB SLA: 72तास",
      "₹4.5L Escrow": "₹4.5 लाख एस्क्रॉ",
      "₹8.2L Escrow": "₹8.2 लाख एस्क्रॉ",
      "₹2.8L Escrow": "₹2.8 लाख एस्क्रॉ",
      "Verify & Allocate CSR Fund": "पडताळणी करा आणि CSR निधी वाटप करा",
      "Bhiwandi Rural, District Thane": "भिवंडी ग्रामीण, जिल्हा ठाणे",
      "Palghar Coastal Belt": "पालघर किनारी पट्टी",
      "Gadchiroli Tribal Block": "गडचिरोली आदिवासी भाग",
      "FIELD SENSOR TELEMETRY": "क्षेत्र सेन्सर टेलिमेट्री",
      "12 Nodes Transmitting": "12 नोड्स प्रेषित होत आहेत",
      "TEAM MILESTONE VELOCITY": "टीम टप्पा वेग",
      "DISTRICT COLLECTOR SIGNOFF": "जिल्हाधिकारी स्वाक्षरी",
      "Water & Sanitation (SDG 6)": "पाणी व स्वच्छता (SDG 6)",
      "Agritech & Soil Health (SDG 2)": "कृषी तंत्रज्ञान व मृदा (SDG 2)",
      "Smart Infrastructure (SDG 11)": "स्मार्ट पायाभूत सुविधा (SDG 11)",
      "Rural Healthcare (SDG 3)": "ग्रामीण आरोग्य (SDG 3)",
      "For Students & Innovators": "विद्यार्थी आणि नवसंशोधकांसाठी",
      "For Universities & Research Labs": "विद्यापीठे आणि संशोधन लॅब्ससाठी",
      "For Municipal Bodies & District Collectors": "नगरपालिका आणि जिल्हाधिकाऱ्यांसाठी",
      "For Corporate CSR & Industry ESCROW": "कॉर्पोरेट CSR आणि उद्योग एस्क्रॉसाठी",
      "Distress Category": "समस्या प्रकार",
      "Location / Geo-Tag": "स्थान / जिओ-टॅग",
      "Estimated Affected Population": "अंदाजित बाधित लोकसंख्या",
      "Problem Description & Evidence": "समस्या वर्णन आणि पुरावे",
      "Attach Photo / Sensor Data": "फोटो / सेन्सर डेटा जोडा",
      "Select District...": "जिल्हा निवडा...",
      "Enter issue details...": "समस्या तपशील प्रविष्ट करा...",
      "e.g. 5,000 residents": "उदा. 5,000 रहिवासी",
      "Select Priority Level": "प्राधान्य पातळी निवडा",
      "Learn More": "अधिक जाणून घ्या",
      "Read Documentation": "दस्तऐवज वाचा",
      "Get Started": "सुरू करा",
      "Contact Support": "सपोर्टशी संपर्क साधा",
      "View Details": "तपशील पाहा",
      "Download Certificate": "प्रमाणपत्र डाउनलोड करा",
      "Verified Escrow": "सत्यापित एस्क्रॉ",
      "AI Matched": "AI जुळणी",
      "Pending Signoff": "प्रलंबित स्वाक्षरी",
      "Active Pilot": "सक्रिय पायलट",
      "Phase 1: Foundation": "टप्पा 1: पाया",
      "Phase 2: Scale": "टप्पा 2: विस्तार",
      "Phase 3: Integration": "टप्पा 3: एकत्रीकरण",
      "Phase 4: Sovereign Grid": "टप्पा 4: संप्रभु ग्रिड",
      "Q1 2025": "Q1 2025",
      "Q3 2025": "Q3 2025",
      "Q1 2026": "Q1 2026",
      "Q4 2026": "Q4 2026",
      "Corporate CSR funds are held in automated escrow accounts and released directly to student teams upon verification of prototype deployment by municipal officers.": "Corporate CSR funds are held in automated escrow accounts and released directly to student teams upon verification of prototype deployment by municipal officers.",
      "Completed ground solutions automatically grant AICTE Activity Points, count toward capstone credits, and boost university NIRF innovation rankings.": "Completed ground solutions automatically grant AICTE Activity Points, count toward capstone credits, and boost university NIRF innovation rankings.",
      "High concentration of industrial arsenic and turbidity observed after recent drainage overflow. Local PHC reports 24 gastroenteritis cases in 72h.": "उच्च concentration of industrial arsenic and turbidity observed after recent drainage overflow. Local PHC reports 24 gastroenteritis cases in 72h.",
      "District Collectors grant instant digital testing permits for road cutting, sensor installation, or water body sampling with full legal backing.": "जिल्हा Collectors grant instant digital testing permits for road cutting, sensor installation, or water body sampling with full legal backing.",
      "Conceived during Smart India Hackathon to solve the gap between student capstone projects and actual municipal distress tickets.": "Conceived during Smart India हॅकाथॉन to solve the gap between student capstone projects and actual municipal distress tickets.",
      "Scaling C2C to every AICTE accredited institution in India, turning every engineering college into a civic problem-solving hub.": "Scaling C2C to every AICTE accredited institution in India, turning every engineering college into a civic problem-solving hub.",
      "Partnered with corporate CSR boards to disburse micro-grants directly into student team escrow accounts upon milestone proof.": "Partnered with corporate CSR boards to disburse micro-grants directly into student team escrow accounts upon milestone proof.",
      "Retain full intellectual property over your engineering solutions with automated patent drafting support from mentor labs.": "Retain full intellectual property over your engineering solutions with automated patent drafting support from mentor labs.",
      "Get cutting-edge smart city prototypes built and tested for local municipal issues at zero cost to the civic exchequer.": "Get cutting-edge smart city prototypes built and tested for local municipal issues at zero cost to the civic exchequer.",
      "Join 32,000+ student engineers, 400+ colleges, and 18 district municipal bodies driving sovereign civic transformation.": "Join 32,000+ student engineers, 400+ colleges, and 18 district municipal bodies driving sovereign civic transformation.",
      "Earn mandatory AICTE degree activity points by resolving verified community distress tickets in your regional district.": "Earn mandatory AICTE degree activity points by resolving verified community distress tickets in your regional district.",
      "Boost college NIRF research rankings with verified civic prototype deployments and cross-departmental research papers.": "Boost college NIRF research rankings with verified civic prototype deployments and cross-departmental research papers.",
      "Pioneered direct District Collector NOC approvals allowing student teams to test hardware in water plants and roads.": "Pioneered direct जिल्हा जिल्हाधिकारी NOC approvals allowing student teams to test hardware in water plants and roads.",
      "Unlock micro-grants from ₹25,000 to ₹2.5 Lakhs directly for sensor purchasing, PCB fabrication, and field testing.": "Unlock micro-grants from ₹25,000 to ₹2.5 Lakhs directly for sensor purchasing, PCB fabrication, and field testing.",
      "Identify and hire top-tier engineering talent working on high-impact sustainable technologies before graduation.": "Identify and hire top-tier engineering talent working on high-impact sustainable technologies before graduation.",
      "We eliminate traditional bureaucratic bottlenecks between academic labs and municipal public works departments.": "आम्ही शैक्षणिक लॅब्स आणि पालिका सार्वजनिक बांधकाम विभागांमधील प्रशासकीय अडचणी दूर करतो.",
      "Co-guide multi-disciplinary student teams across civil, electrical, biotech, and computer science departments.": "Co-guide multi-disciplinary student teams across civil, electrical, biotech, and computer science departments.",
      "Funds are locked safely in escrow and disbursed only after multi-stakeholder verification of ground results.": "Funds are locked safely in escrow and disbursed only after multi-stakeholder verification of ground results.",
      "Monitor ground resolution progress in real time with automated SMS updates and live GIS dashboard tracking.": "Monitor ground resolution progress in real time with automated SMS updates and live GIS dashboard tracking.",
      "Unlock corporate CSR hardware grants to upgrade departmental testing facilities and prototyping sandboxes.": "Unlock corporate CSR hardware grants to upgrade departmental testing facilities and prototyping sandboxes.",
      "Ensure 100% statutory compliance with audit-ready cryptographic milestone proofs and municipal receipts.": "Ensure 100% statutory compliance with audit-ready cryptographic milestone proofs and municipal receipts.",
      "District Collectors issue rapid digital testing clearances without tedious physical paper loops.": "जिल्हा Collectors issue rapid digital testing clearances without tedious physical paper loops.",
      "Trace how C2C grew from a SIH 2026 problem statement into a full-scale civic technology mesh.": "Trace how C2C grew from a SIH 2026 problem statement into a full-scale civic technology mesh.",
      "Built with enterprise-grade reliability, transparent governance, and AI-driven intelligence.": "Built with enterprise-grade reliability, transparent governance, and AI-driven intelligence.",
      "Capital efficiency and citizen density impact mapped by UN Sustainable Development Goals.": "Capital efficiency and citizen density impact mapped by UN Sustainable Development Goals.",
      "🌐 Live AI matching mesh connecting over 400 universities with real-time GIS telemetry.": "🌐 Live AI matching mesh connecting over 400 universities with real-time GIS telemetry.",
      "💎 ₹4.8 Crore in corporate funding locked into verified student prototype deployments.": "💎 ₹4.8 Crore in corporate funding locked into verified student prototype deployments.",
      "🏛️ Onboarded 18 District Collectorates with single-window NOC automated dispatches.": "🏛️ Onboarded 18 जिल्हा Collectorates with single-window NOC automated dispatches.",
      "Comparative view of challenges resolved vs active collegiate student chapters.": "Comparative view of challenges resolved vs active collegiate student chapters.",
      "Select a stakeholder group to view tailored workflows and platform benefits.": "Select a stakeholder group to view tailored workflows and platform benefits.",
      "⚡ Initial NLP prototype tested with 12 engineering colleges in Maharashtra.": "⚡ Initial NLP prototype tested with 12 engineering colleges in Maharashtra.",
      "© 2026 CAMPUS2COMMUNITY (C2C) Sovereign Platform. All rights reserved.": "© 2026 CAMPUS2COMMUNITY (C2C) संप्रभु Platform. All rights reserved.",
      "Audited under Ministry of Education MIC Innovation Metrics Framework.": "Audited under मंत्रालय of शिक्षण MIC Innovation मेट्रिक्स Framework.",
      "Everything you need to know about joining or supporting the C2C mesh.": "Everything you need to know about joining or supporting the C2C mesh.",
      "Attach Lab Water Sample / Geo-Tagged Photo / Vernacular Audio Memo": "Attach प्रयोगशाळा पाणी Sample / Geo-Tagged Photo / Vernacular Audio Memo",
      "Spanning 89 engineering disciplines & 412 accredited universities.": "Spanning 89 engineering disciplines & 412 accredited universities.",
      "Cross-checked with Jal Jeevan Mission national database in 0.4s.": "Cross-checked with Jal Jeevan उद्दिष्ट national database in 0.4s.",
      "How do student teams get academic credit for solving problems?": "How do student teams get academic credit for solving problems?",
      "Are corporate CSR contributions audit-ready under Section 135?": "Are corporate CSR contributions audit-ready under Section 135?",
      "Smart IoT Groundwater Filtration & Turbidity Telemetry Grid": "Smart IoT Groundwater Filtration & Turbidity टेलिमेट्री Grid",
      "Receive validated challenge manifests and civic dispatches.": "Receive validated challenge manifests and civic dispatches.",
      "Dr. S. Kulkarni | Department of Civil & Environmental Engg": "Dr. S. Kulkarni | Department of Civil & Environmental Engg",
      "New challenge C2C-2026-904 matched with IIT Bombay labs.": "New challenge C2C-2026-904 matched with IIT Bombay labs.",
      "Formal municipal permit issued under Smart City Mission.": "Formal municipal permit issued under Smart City उद्दिष्ट.",
      "Water Contamination Problem in Bhiwandi (C2C-2026-W482)": "पाणी Contamination Problem in Bhiwandi (C2C-2026-W482)",
      "About Us | CAMPUS2COMMUNITY (C2C) Sovereign Platform": "About Us | CAMPUS2COMMUNITY (C2C) संप्रभु Platform",
      "From Hackathon Prototype to Sovereign Infrastructure": "From हॅकाथॉन Prototype to संप्रभु पायाभूत सुविधा",
      "How can a new university or municipal body join C2C?": "How can a new university or municipal body join C2C?",
      "Instant EXIF GPS extraction & NIC server encryption": "Instant EXIF GPS extraction & NIC server encryption",
      "Students who are innovators, making society better.": "विद्यार्थी who are innovators, making society better.",
      "CAMPUS2COMMUNITY (C2C) | Civic Innovation Platform": "CAMPUS2COMMUNITY (C2C) | Civic Innovation Platform",
      "Search C2C mission, leadership, pillars, or FAQ...": "Search C2C mission, leadership, pillars, or FAQ...",
      "Sprint Tracking: Phase 05 Active Sensor Telemetry": "Sprint Tracking: Phase 05 सक्रिय सेन्सर टेलिमेट्री",
      "99.8% ping uptime across Bhiwandi Sector 4 wells.": "99.8% ping uptime across Bhiwandi Sector 4 wells.",
      "How are problem statements reported and verified?": "How are problem statements reported and verified?",
      "Student AI Matchmaking Engine (Embedded C, ESP32)": "विद्यार्थी AI Matchmaking Engine (Embedded C, ESP32)",
      "Search challenges, skills, districts, or teams...": "Search challenges, skills, districts, or teams...",
      "Parnavi Janbhor | Student Innovator Lead (VSIT)": "Parnavi Janbhor | विद्यार्थी Innovator प्रमुख (VSIT)",
      "Driven by Impact. Orchestrated by Intelligence.": "प्रभावाद्वारे संचलित. बुद्धिमत्तेद्वारे आर्केस्ट्रेट केलेले.",
      "approved pilot testing for arsenic filtration.": "approved pilot testing for arsenic filtration.",
      "C2C-IP-2026-098: Low-cost Arsenic Electro-Cell": "C2C-IP-2026-098: Low-cost Arsenic Electro-Cell",
      "₹30,000 in escrow pending Milestone 4 signoff": "₹30,000 in escrow pending Milestone 4 signoff",
      "Encrypted delivery via NIC / Gov cloud rails.": "Encrypted delivery via NIC / Gov cloud rails.",
      "Smart Village IoT Water Purity & Distribution": "Smart Village IoT पाणी Purity & Distribution",
      "Firmware v2.1 OTA flash completed yesterday.": "Firmware v2.1 OTA flash completed yesterday.",
      "Translate Language (English, Hindi, Marathi)": "Translate Language (English, Hindi, Marathi)",
      "Submit for Sovereign AI Intake Verification": "Submit for संप्रभु AI Intake Verification",
      "Vidyalankar Institute of Technology (VSIT)": "Vidyalankar Institute of Technology (VSIT)",
      "Tamil Nadu (Chennai, Coimbatore, Madurai)": "Tamil Nadu (Chennai, Coimbatore, Madurai)",
      "NLP Urgency Index: 98.4 (Tier 1 Priority)": "NLP तातडी Index: 98.4 (Tier 1 प्राधान्य)",
      "Uttar Pradesh (Lucknow, Varanasi, Kanpur)": "Uttar Pradesh (Lucknow, Varanasi, Kanpur)",
      "Direct API feed to state urban dashboard": "Direct API feed to state urban dashboard",
      "Matched Competencies for Parnavi Janbhor": "Matched Competencies for Parnavi Janbhor",
      "💧 Report Local Water / Sanitation Issue": "💧 Report Local पाणी / Sanitation Issue",
      "Submitted to IEEE Civic Technology 2026": "Submitted to IEEE Civic Technology 2026",
      "Karnataka (Bengaluru, Mysuru, Hubballi)": "Karnataka (Bengaluru, Mysuru, Hubballi)",
      "Tranche releases upon milestone proofs": "Tranche releases upon milestone proofs",
      "District Administration Clearance Desk": "जिल्हा Administration Clearance Desk",
      "Spectrophotometer + Spectrometer slots": "Spectrophotometer + Spectrometer slots",
      "Tax exemption audit certificates ready": "Tax exemption audit certificates ready",
      "Citizen satisfaction rating: 4.8 / 5.0": "Citizen satisfaction rating: 4.8 / 5.0",
      "Tata Trusts CleanTech Innovation Fund": "Tata Trusts CleanTech Innovation निधी",
      "Bhiwandi Municipal Corporation (BMC)": "Bhiwandi नगरपालिका महानगरपालिका (BMC)",
      "AI-Recommended Solution Squad Matrix": "AI-Recommended Solution Squad Matrix",
      "Pest Infestation Early Warning LiDAR": "Pest Infestation Early Warning LiDAR",
      "Gujarat (Ahmedabad, Surat, Vadodara)": "Gujarat (Ahmedabad, Surat, Vadodara)",
      "8 Core Interconnected Impact Modules": "8 मुख्य Interconnected प्रभाव Modules",
      "Cold-Chain Vaccine Telemetry Monitor": "Cold-Chain Vaccine टेलिमेट्री Monitor",
      "Bridging Campuses & Communities for": "Bridging Campuses & Communities for",
      "Approved Hardware Prototyping Grant": "Approved Hardware Prototyping Grant",
      "National Avg Time-to-Pilot: 34 Days": "राष्ट्रीय Avg Time-to-Pilot: 34 दिवस",
      "Across 12 verified student projects": "Across 12 verified student projects",
      "Rural Education & Digital Literacy": "ग्रामीण शिक्षण & Digital Literacy",
      "Experience C2C in Stakeholder Mode": "Experience C2C in Stakeholder Mode",
      "Maharashtra (Mumbai, Pune, Nagpur)": "Maharashtra (Mumbai, Pune, Nagpur)",
      "Sprint C2C-881 • Bhiwandi Sector 4": "Sprint C2C-881 • Bhiwandi Sector 4",
      "Clean Energy & Microgrids (SDG 7)": "Clean Energy & Microgrids (SDG 7)",
      "Built for Every Pillar of Society": "Built for Every स्तंभ of Society",
      "2 teams deployed in field testing": "2 teams deployed in field testing",
      "Phase 05: Field Pilot In Progress": "Phase 05: Field Pilot In Progress",
      "Impact Analytics & Command Center": "प्रभाव Analytics & Command Center",
      "Supported by 42 industry partners": "Supported by 42 industry partners",
      "1,284 Verified Challenges Active": "1,284 सत्यापित Challenges सक्रिय",
      "Average clearance time: 28 hours": "Average clearance time: 28 hours",
      "Civic Problem Intake & Manifests": "Civic Problem Intake & Manifests",
      "Healthcare & Diagnostics (SDG 3)": "आरोग्य सेवा & Diagnostics (SDG 3)",
      "State-Level Resolution Velocity": "State-Level Resolution Velocity",
      "Clean Energy Microgrids (SDG 7)": "Clean Energy Microgrids (SDG 7)",
      "Companies Act Sec 135 Compliant": "Companies Act Sec 135 Compliant",
      "State the core issue clearly...": "राज्य the core issue clearly...",
      "SIH Target: <45 Days (Exceeded)": "SIH Target: <45 दिवस (Exceeded)",
      "4 Pillars of the C2C Ecosystem": "4 स्तंभ of the C2C Ecosystem",
      "Automatic Ground Deduplication": "Automatic Ground Deduplication",
      "Healthcare Diagnostics (SDG 3)": "आरोग्य सेवा Diagnostics (SDG 3)",
      "Challenges & Problem Reporting": "Challenges & Problem Reporting",
      "Smart Village IoT Water Purity": "Smart Village IoT पाणी Purity",
      "Environmental Engg + IoT Nodes": "Environmental Engg + IoT Nodes",
      "Corporate CSR & Escrow Console": "Corporate CSR & एस्क्रॉ Console",
      "Generated Sovereign Ticket ID": "Generated संप्रभु Ticket ID",
      "SIH 2026 Innovation Challenge": "SIH 2026 Innovation Challenge",
      "AICTE Credits: 45 / 50 Earned": "AICTE Credits: 45 / 50 Earned",
      "SIH 2026 Sovereign Initiative": "SIH 2026 संप्रभु Initiative",
      "Moderate (Infrastructure Lag)": "Moderate (पायाभूत सुविधा Lag)",
      "94.2% verified field efficacy": "94.2% verified field efficacy",
      "Empowering 32,000+ Innovators": "32,000+ नवसंशोधकांना सक्षम करणे",
      "AI Engine Verifies & Catalogs": "AI Engine Verifies & Catalogs",
      "Explore Active Platform Mesh": "Explore सक्रिय Platform Mesh",
      "AICTE Activity Points Earned": "AICTE Activity Points Earned",
      "🔍 Track C2C-2026-W482 Status": "🔍 Track C2C-2026-W482 स्थिती",
      "Real-time Telemetry: Healthy": "Real-time टेलिमेट्री: Healthy",
      "⚡ Match My Department Skills": "⚡ Match My Department Skills",
      "Earns AICTE Activity Points": "Earns AICTE Activity Points",
      "Deduplication & NLP Scoring": "Deduplication & NLP Scoring",
      "Download Provisional Form →": "Download Provisional Form →",
      "+3,180 verified this sprint": "+3,180 verified this sprint",
      "High (Impacting 500+ Daily)": "उच्च (Impacting 500+ Daily)",
      "The 9-Stage Impact Pipeline": "The 9-Stage प्रभाव Pipeline",
      "Explore Stakeholder Portals": "Explore Stakeholder Portals",
      "18 / 21 Milestones Complete": "18 / 21 Milestones Complete",
      "Field Deployment & Feedback": "Field Deployment & Feedback",
      "3 mins ago • SPRINT-C2C-881": "3 mins ago • SPRINT-C2C-881",
      "Academic Mentor & Lab Suite": "Academic Mentor & प्रयोगशाळा Suite",
      "Critical (Immediate Hazard)": "अत्यंत तातडीचे (Immediate Hazard)",
      "Lab Validation & Mentorship": "प्रयोगशाळा Validation & Mentorship",
      "View All Stream Telemetry →": "View All Stream टेलिमेट्री →",
      "18 District Municipalities": "18 जिल्हा Municipalities",
      "Active Persona Perspective": "सक्रिय Persona Perspective",
      "Q2 2026 • Municipal Pilots": "Q2 2026 • नगरपालिका Pilots",
      "Across 14 sovereign states": "Across 14 sovereign states",
      "District Collector Signoff": "जिल्हा जिल्हाधिकारी Signoff",
      "Multi-Role Command Portals": "Multi-Role Command Portals",
      "Fast-Track Test Clearances": "Fast-Track Test Clearances",
      "Dr. S. Kulkarni (Assigned)": "Dr. S. Kulkarni (Assigned)",
      "Section 135 Escrow Backed": "Section 135 एस्क्रॉ Backed",
      "Student Innovator Cockpit": "विद्यार्थी Innovator Cockpit",
      "Multi-Actor Role Switcher": "Multi-Actor Role Switcher",
      "Community Reports Problem": "Community Reports Problem",
      "Location / Gram Panchayat": "Location / Gram Panchayat",
      "Observed Distress Details": "Observed Distress Details",
      "State Public Universities": "राज्य Public विद्यापीठे",
      "SLA Clearance Rate: 91.4%": "SLA Clearance Rate: 91.4%",
      "Unified Stakeholder Value": "Unified Stakeholder Value",
      "Urban Waste & Circularity": "शहरी Waste & Circularity",
      "Direct student allocation": "Direct student allocation",
      "Enter institutional email": "Enter institutional email",
      "Present • Nationwide Grid": "Present • Nationwide Grid",
      "Q3 2026 • Corporate Rails": "Q3 2026 • Corporate Rails",
      "Turbidity: 1.4 NTU (Safe)": "Turbidity: 1.4 NTU (Safe)",
      "State-Wide Mesh Expansion": "State-Wide Mesh Expansion",
      "Provisional Patent Filed": "Provisional Patent Filed",
      "Industry CSR Sponsorship": "Industry CSR Sponsorship",
      "Escrow Milestone Funding": "एस्क्रॉ Milestone निधी",
      "contact no : 96992 56880": "contact no : 96992 56880",
      "SIH 9-Stage Architecture": "SIH 9-Stage Architecture",
      "Direct Hardware Stipends": "Direct Hardware Stipends",
      "contact no : 93218 61070": "contact no : 93218 61070",
      "Semantic Cosine Matching": "Semantic Cosine Matching",
      "SIH 2026 Innovation Mesh": "SIH 2026 Innovation Mesh",
      "Inter-College Mentorship": "Inter-College Mentorship",
      "Role-Specific Workspaces": "Role-Specific Workspaces",
      "Institutional NIRF Score": "Institutional NIRF Score",
      "Real-World Civic Impact.": "Real-World Civic प्रभाव.",
      "Impact by UN SDG Domains": "प्रभाव by UN SDG Domains",
      "Active Capstone Projects": "सक्रिय Capstone प्रकल्प",
      "C2C Neural Graph Network": "C2C Neural Graph Network",
      "Sec 135 CSR Escrow Rails": "Sec 135 CSR एस्क्रॉ Rails",
      "Single-Window Clearances": "Single-Window Clearances",
      "Student Innovator Portal": "विद्यार्थी Innovator पोर्टल",
      "Report a Ground Problem": "Report a Ground Problem",
      "Single-Window NOC Rails": "Single-Window NOC Rails",
      "Problem Summary / Title": "Problem Summary / Title",
      "contact no : 9152300523": "contact no : 9152300523",
      "Team Milestone Velocity": "टीम Milestone Velocity",
      "Escrow Milestone Safety": "एस्क्रॉ Milestone Safety",
      "3,400 Verified Citizens": "3,400 सत्यापित Citizens",
      "Academic Signoff Issued": "Academic Signoff Issued",
      "Automated SLA Telemetry": "Automated SLA टेलिमेट्री",
      "Enterprise Architecture": "Enterprise Architecture",
      "contact no : 8108759790": "contact no : 8108759790",
      "NIRF Impact Points: +42": "NIRF प्रभाव Points: +42",
      "contact no : 8355947969": "contact no : 8355947969",
      "GPS Geo-Spatial Tagging": "GPS Geo-Spatial Tagging",
      "Direct CSR Micro-grants": "Direct CSR Micro-grants",
      "Telemetry Measures SROI": "टेलिमेट्री Measures SROI",
      "contact no : 7400429237": "contact no : 7400429237",
      "Open Stakeholder Portal": "Open Stakeholder पोर्टल",
      "Real-Time ESG Telemetry": "Real-Time ESG टेलिमेट्री",
      "Ground Problem Sourcing": "Ground Problem Sourcing",
      "412 Universities Online": "412 विद्यापीठे Online",
      "Parnavi Janbhor Profile": "Parnavi Janbhor Profile",
      "Zero R&D Budget Burden": "Zero R&D Budget Burden",
      "Zero Paper Bureaucracy": "Zero Paper Bureaucracy",
      "Parnavi Janbhor (VSIT)": "Parnavi Janbhor (VSIT)",
      "Live Persona Telemetry": "Live Persona टेलिमेट्री",
      "Sovereign Citizen Desk": "संप्रभु Citizen Desk",
      "Search System (Ctrl+K)": "Search प्रणाली (Ctrl+K)",
      "Turn Campus Ideas Into": "Turn Campus Ideas Into",
      "Smart City Integration": "Smart City Integration",
      "Industry Research Labs": "Industry संशोधन प्रयोगशाळा",
      "Semantic Vector Engine": "Semantic व्हेक्टर Engine",
      "Active Solvers: 14,820": "सक्रिय Solvers: 14,820",
      "Closed-Loop Validation": "Closed-Loop Validation",
      "95% Vector Match Score": "95% व्हेक्टर Match Score",
      "Active Solver Profile:": "सक्रिय Solver Profile:",
      "View Escrow Contract →": "View एस्क्रॉ Contract →",
      "32,480 Active Students": "32,480 सक्रिय विद्यार्थी",
      "114 Solved • 88 Active": "114 Solved • 88 सक्रिय",
      "Ask C2C AI anything...": "Ask C2C AI anything...",
      "₹2.5L Escrow Milestone": "₹2.5L एस्क्रॉ Milestone",
      "19.2968° N, 73.0631° E": "19.2968° N, 73.0631° E",
      "Field Sensor Telemetry": "Field सेन्सर टेलिमेट्री",
      "9-Stage Ecosystem Flow": "9-Stage Ecosystem Flow",
      "Ground Issues Resolved": "Ground Issues सोडवले",
      "GIS Coordinates Synced": "GIS Coordinates Synced",
      "Stipend & Grant Ledger": "Stipend & Grant Ledger",
      "Active Capstone Sprint": "सक्रिय Capstone Sprint",
      "AICTE Activity Points": "AICTE Activity Points",
      "Python MQTT Telemetry": "Python MQTT टेलिमेट्री",
      "Fast-Track AI Routing": "Fast-Track AI Routing",
      "Early Talent Pipeline": "Early Talent Pipeline",
      "82 Solved • 64 Active": "82 Solved • 64 सक्रिय",
      "38 Solved • 71 Active": "38 Solved • 71 सक्रिय",
      "Grievance Resolutions": "Grievance Resolutions",
      "Smart India Hackathon": "Smart India हॅकाथॉन",
      "Provisional IP Rights": "Provisional IP Rights",
      "Civic SLA Integration": "Civic SLA Integration",
      "Section 135 Compliant": "Section 135 Compliant",
      "Field Prototypes Live": "Field Prototypes Live",
      "CSR & Angel Investors": "CSR & Angel Investors",
      "₹1,84,500 / ₹2,50,000": "₹1,84,500 / ₹2,50,000",
      "Industry Partnerships": "Industry Partnerships",
      "34 Solved • 29 Active": "34 Solved • 29 सक्रिय",
      "Live Intake Telemetry": "Live Intake टेलिमेट्री",
      "AI Smart Matching Hub": "AI Smart Matching Hub",
      "32,480 Active Solvers": "32,480 सक्रिय Solvers",
      "59 Solved • 42 Active": "59 Solved • 42 सक्रिय",
      "District NOC Dispatch": "जिल्हा NOC Dispatch",
      "Municipalities & Govt": "Municipalities & शासन",
      "AI Matching Workspace": "AI Matching Workspace",
      "Domain Classification": "Domain Classification",
      "Allocated CSR Capital": "Allocated CSR Capital",
      "Student Team Ideation": "विद्यार्थी टीम Ideation",
      "Across 9 civic tracks": "Across 9 civic tracks",
      "National SROI Metrics": "राष्ट्रीय SROI मेट्रिक्स",
      "Govt Pilot Greenlight": "शासन Pilot Greenlight",
      "Review Squad & Apply": "Review Squad & Apply",
      "Lab Hardware Sandbox": "प्रयोगशाळा Hardware Sandbox",
      "CSR Grants Disbursed": "CSR Grants Disbursed",
      "Pending Testbed NOCs": "प्रलंबित Testbed NOCs",
      "1-Click Digital NOCs": "1-Click Digital NOCs",
      "Community Solutions.": "Community Solutions.",
      "AI-Driven Matchmaker": "AI-Driven Matchmaker",
      "Academic Publication": "Academic Publication",
      "Launch Live Platform": "Launch Live Platform",
      "Lab Shared Resources": "प्रयोगशाळा Shared Resources",
      "Stakeholder Benefits": "Stakeholder Benefits",
      "Supervised Capstones": "Supervised Capstones",
      "Launch Live App Mesh": "Launch Live App Mesh",
      "Communities Impacted": "Communities Impacted",
      "Lab Equipment Grants": "प्रयोगशाळा Equipment Grants",
      "NIRF & Credit Matrix": "NIRF & Credit Matrix",
      "AI Hub Latency: 42ms": "AI Hub Latency: 42ms",
      "Solutions Formulated": "Solutions Formulated",
      "Municipal Incubators": "नगरपालिका Incubators",
      "126 Connected Blocks": "126 Connected Blocks",
      "Recommended Lab Unit": "Recommended प्रयोगशाळा Unit",
      "Patent & IP Registry": "Patent & IP Registry",
      "Hydrology Flow Data": "Hydrology Flow Data",
      "Join Solution Squad": "Join Solution Squad",
      "Cosine Vector Index": "Cosine व्हेक्टर Index",
      "System Architecture": "प्रणाली Architecture",
      "Audited ESG Returns": "Audited ESG Returns",
      "AI Problem Matching": "AI Problem Matching",
      "Explore AI Matching": "Explore AI Matching",
      "dashboard_customize": "dashboard_customize",
      "AICTE Academic Sync": "AICTE Academic Sync",
      "Proof-of-Work Rails": "Proof-of-Work Rails",
      "Accredited Campuses": "Accredited Campuses",
      "Citizen Communities": "Citizen Communities",
      "Avg. Cycle: 38 Days": "Avg. Cycle: 38 दिवस",
      "MIC Innovation Cell": "MIC Innovation Cell",
      "Civic NGO Coalition": "Civic NGO Coalition",
      "Stakeholder Portals": "Stakeholder Portals",
      "Impacted Population": "Impacted Population",
      "Direct Civic Portal": "Direct Civic पोर्टल",
      "Security Compliance": "Security पालन",
      "volunteer_activism": "volunteer_activism",
      "Solar MPPT Systems": "सौर MPPT प्रणाली",
      "High Civic Urgency": "उच्च Civic तातडी",
      "Ecosystem Dispatch": "Ecosystem Dispatch",
      "Embedded C / ESP32": "Embedded C / ESP32",
      "Student Innovators": "विद्यार्थी Innovators",
      "About C2C Platform": "About C2C Platform",
      "Tata R&D CleanTech": "Tata R&D CleanTech",
      "Leadership Council": "Leadership Council",
      "18 District Admins": "18 जिल्हा Admins",
      "CSR Escrow Audited": "CSR एस्क्रॉ Audited",
      "Fast-Track Permits": "Fast-Track Permits",
      "Realtime Telemetry": "Realtime टेलिमेट्री",
      "Community Problems": "Community Problems",
      "Student Innovation": "विद्यार्थी Innovation",
      "Communities Synced": "Communities Synced",
      "University Faculty": "विद्यापीठ प्राध्यापक",
      "Arsenic: 0.008 ppm": "Arsenic: 0.008 ppm",
      "Lab Sandbox Access": "प्रयोगशाळा Sandbox Access",
      "Sec 135 CSR Escrow": "Sec 135 CSR एस्क्रॉ",
      "SIH 2026 Inception": "SIH 2026 Inception",
      "Govt & NGO Connect": "शासन & NGO Connect",
      "AICTE Credit Rails": "AICTE Credit Rails",
      "100% Geo-Validated": "100% Geo-Validated",
      "3 Positions Vacant": "3 Positions Vacant",
      "Citizens Benefited": "Citizens Benefited",
      "4 Sensors Reserved": "4 सेन्सर्स Reserved",
      "Flutter Mobile App": "Flutter Mobile App",
      "ID: C2C-2026-W482": "ID: C2C-2026-W482",
      "₹45,000 Disbursed": "₹45,000 Disbursed",
      "Citizens Affected": "Citizens Affected",
      "Faculty & Mentors": "प्राध्यापक & Mentors",
      "Problems Reported": "Problems Reported",
      "System Live Pulse": "प्रणाली Live Pulse",
      "Student Engineers": "विद्यार्थी अभियंते",
      "Community Citizen": "Community Citizen",
      "Tata CSR released": "Tata CSR released",
      "Purpose & Mandate": "उद्देश आणि मांडणी",
      "workspace_premium": "workspace_premium",
      "₹4.8 Cr Committed": "₹4.8 Cr Committed",
      "Q1 2026 • Genesis": "Q1 2026 • Genesis",
      "Partner Ecosystem": "Partner Ecosystem",
      "to student teams.": "to student teams.",
      "University Collab": "विद्यापीठ Collab",
      "Evolution Journey": "Evolution Journey",
      "CSR Escrow Grants": "CSR एस्क्रॉ Grants",
      "Student Innovator": "विद्यार्थी Innovator",
      "Progress Tracking": "Progress Tracking",
      "Mission Statement": "उद्दिष्ट Statement",
      "Sec 135 Compliant": "Sec 135 Compliant",
      "Telemetry Privacy": "टेलिमेट्री Privacy",
      "Our Core Mandate": "आमचे मुख्य उद्दिष्ट",
      "Sub-24h Dispatch": "Sub-24h Dispatch",
      "Mesh v4.2 Active": "Mesh v4.2 सक्रिय",
      "4.2M Queries/day": "4.2M Queries/day",
      "Escrow Burn Rate": "एस्क्रॉ Burn Rate",
      "Platform Modules": "Platform Modules",
      "Healthcare Track": "आरोग्य सेवा Track",
      "API Architecture": "API Architecture",
      "Campus2Community": "Campus2Community",
      "CSR Escrow Rails": "CSR एस्क्रॉ Rails",
      "Impact Analytics": "प्रभाव Analytics",
      "Mission & Vision": "उद्दिष्ट & ध्येय",
      "Municipal Bodies": "नगरपालिका Bodies",
      "CAMPUS2COMMUNITY": "CAMPUS2COMMUNITY",
      "Explore AI Model": "Explore AI Model",
      "Live Dispatches": "Live Dispatches",
      "Maharashtra BMC": "Maharashtra BMC",
      "Industry Mentor": "Industry Mentor",
      "space_dashboard": "space_dashboard",
      "AI Matching Hub": "AI Matching Hub",
      "Faculty Advisor": "प्राध्यापक Advisor",
      "Geo Coordinates": "Geo Coordinates",
      "Quick Shortcuts": "Quick Shortcuts",
      "+14% this month": "+14% this month",
      "Verified Impact": "सत्यापित प्रभाव",
      "account_balance": "account_balance",
      "Problems Solved": "Problems Solved",
      "Select Language": "Select Language",
      "Faculty Mentors": "प्राध्यापक Mentors",
      "Lab Pass Active": "प्रयोगशाळा Pass सक्रिय",
      "Domain Category": "Domain Category",
      "Agritech Track": "Agritech Track",
      "corporate_fare": "corporate_fare",
      "Quick Searches": "Quick Searches",
      "Clear Insights": "Clear Insights",
      "Student Portal": "विद्यार्थी पोर्टल",
      "District Admin": "जिल्हा Admin",
      "Pilot Approved": "Pilot Approved",
      "Municipal Desk": "नगरपालिका Desk",
      "Status Console": "स्थिती Console",
      "YOLOv8 Edge AI": "YOLOv8 Edge AI",
      "Industry / CSR": "Industry / CSR",
      "SPRINT-C2C-881": "SPRINT-C2C-881",
      "Valid Manifest": "Valid Manifest",
      "C2C Copilot AI": "C2C Copilot AI",
      "Report Problem": "Report Problem",
      "C2C AI Copilot": "C2C AI Copilot",
      "6 Active Teams": "6 सक्रिय Teams",
      "report_problem": "report_problem",
      "Severity Level": "Severity Level",
      "local_library": "local_library",
      "Platform Home": "Platform Home",
      "Close Profile": "Close Profile",
      "C2C-2026-W482": "C2C-2026-W482",
      "Pilot Testing": "Pilot Testing",
      "4.2x Multiple": "4.2x Multiple",
      "person_search": "person_search",
      "location_city": "location_city",
      "verified_user": "verified_user",
      "Verified SROI": "सत्यापित SROI",
      "Founding Team": "Founding टीम",
      "chevron_right": "chevron_right",
      "rocket_launch": "rocket_launch",
      "Home Overview": "Home Overview",
      "Live Tracking": "Live Tracking",
      "How C2C Works": "How C2C Works",
      "Escrow Locked": "एस्क्रॉ Locked",
      "notifications": "notifications",
      "SROI Measured": "SROI Measured",
      "arrow_forward": "arrow_forward",
      "2 Co-Authored": "2 Co-Authored",
      "Core Pillars": "मुख्य स्तंभ",
      "85 Verifiers": "85 Verifiers",
      "finance_mode": "finance_mode",
      "Toggle Theme": "Toggle Theme",
      "114 Resolved": "114 सोडवले",
      "Audit Ledger": "Audit Ledger",
      "Full Rollout": "Full Rollout",
      "check_circle": "check_circle",
      "CSR Sponsors": "CSR Sponsors",
      "arrow_upward": "arrow_upward",
      "cloud_upload": "cloud_upload",
      "account_tree": "account_tree",
      "C2C Timeline": "C2C Timeline",
      "412 Campuses": "412 Campuses",
      "linear_scale": "linear_scale",
      "#ai-matching": "#ai-matching",
      "engineering": "engineering",
      "open_in_new": "open_in_new",
      "expand_more": "expand_more",
      "BLE Sensors": "BLE सेन्सर्स",
      "Launch Mesh": "Launch Mesh",
      "g_translate": "g_translate",
      "query_stats": "query_stats",
      "location_on": "location_on",
      "18 mins ago": "18 mins ago",
      "#challenges": "#challenges",
      "42 mins ago": "42 mins ago",
      "ROS2 Drones": "ROS2 Drones",
      "trending_up": "trending_up",
      "history_edu": "history_edu",
      "diversity_3": "diversity_3",
      "₹50,00,000": "₹50,00,000",
      "group_work": "group_work",
      "Leadership": "Leadership",
      "2 Requests": "2 Requests",
      "co_present": "co_present",
      "add_circle": "add_circle",
      "Identified": "Identified",
      "foundation": "foundation",
      "Challenges": "Challenges",
      "device_hub": "device_hub",
      "lock_clock": "lock_clock",
      "₹32,50,000": "₹32,50,000",
      "psychology": "psychology",
      "domain_add": "domain_add",
      "NIC Synced": "NIC Synced",
      "visibility": "visibility",
      "Active Now": "सक्रिय Now",
      "CSR Backed": "CSR Backed",
      "Lab Review": "प्रयोगशाळा Review",
      "water_drop": "water_drop",
      "handshake": "handshake",
      "Live Mesh": "Live Mesh",
      "apartment": "apartment",
      "#tracking": "#tracking",
      "dark_mode": "dark_mode",
      "82% Match": "82% Match",
      "Analytics": "Analytics",
      "pie_chart": "pie_chart",
      "₹2,50,000": "₹2,50,000",
      "About C2C": "About C2C",
      "lightbulb": "lightbulb",
      "Ecosystem": "Ecosystem",
      "analytics": "analytics",
      "neurology": "neurology",
      "88% Match": "88% Match",
      "PHASE 08": "PHASE 08",
      "PHASE 01": "PHASE 01",
      "AI Match": "AI Match",
      "payments": "payments",
      "verified": "verified",
      "pin_drop": "pin_drop",
      "About Us": "About Us",
      "C2C Core": "C2C मुख्य",
      "PHASE 09": "PHASE 09",
      "Proposed": "Proposed",
      "PHASE 06": "PHASE 06",
      "timeline": "timeline",
      "PHASE 07": "PHASE 07",
      "PHASE 03": "PHASE 03",
      "PHASE 05": "PHASE 05",
      "task_alt": "task_alt",
      "PHASE 02": "PHASE 02",
      "Timeline": "Timeline",
      "PHASE 04": "PHASE 04",
      "Tracking": "Tracking",
      "Pillars": "स्तंभ",
      "Modules": "Modules",
      "science": "science",
      "factory": "factory",
      "Portals": "Portals",
      "balance": "balance",
      "English": "English",
      "sensors": "sensors",
      "biotech": "biotech",
      "32,480+": "32,480+",
      "₹4.8 Cr": "₹4.8 Cr",
      "Mission": "उद्दिष्ट",
      "Jan 02": "Jan 02",
      "48,240": "48,240",
      "Nov 04": "Nov 04",
      "Oct 12": "Oct 12",
      "shield": "shield",
      "patent": "patent",
      "Dec 15": "Dec 15",
      "school": "school",
      "Oct 24": "Oct 24",
      "policy": "policy",
      "target": "target",
      "हिन्दी": "हिन्दी",
      "search": "search",
      "Nov 18": "Nov 18",
      "About": "About",
      "close": "close",
      "gavel": "gavel",
      "4 New": "4 New",
      "मराठी": "मराठी",
      "group": "group",
      "speed": "speed",
      "badge": "badge",
      "1,284": "1,284",
      "help": "help",
      "Home": "Home",
      "v2.4": "v2.4",
      "info": "info",
      "412+": "412+",
      "Join": "Join",
      "home": "home",
      "menu": "menu",
      "flag": "flag",
      "TEAM": "TEAM",
      "bolt": "bolt",
      "send": "send",
      "map": "map",
      "34%": "34%",
      "FAQ": "FAQ",
      "742": "742",
      "19%": "19%",
      "21%": "21%",
      "184": "184",
      "126": "126",
      "312": "312",
      "Hub": "Hub",
      "C2C": "C2C",
      "ESC": "ESC",
      "hub": "hub",
      "26%": "26%",
      "EN": "EN",
      "🇮🇳": "🇮🇳",
      "HI": "HI",
      "MR": "MR",
      "89": "89",
      "🇬🇧": "🇬🇧",
      "Driven by": "द्वारे संचलित",
      "Orchestrated by": "द्वारे आर्केस्ट्रेट केलेले",
      "Bridging Campus & Community for Real-World Civic Impact.": "प्रत्यक्ष नागरी प्रभावासाठी कॉलेज आणि समुदायाची जोडणी.",
      "Bridging Campus & Community": "कॉलेज आणि समुदायाची जोडणी",
      "for Real-World Civic Impact.": "प्रत्यक्ष नागरी प्रभावासाठी.",
      "Real-World": "प्रत्यक्ष",
      "Real-world": "प्रत्यक्ष",
      "real-world": "प्रत्यक्ष",
      "12-Digit Aadhaar Card Number": "12-अंकी आधार कार्ड क्रमांक",
      "Aadhaar Card Number / Sovereign ID": "आधार कार्ड क्रमांक / संप्रभु आयडी",
      "Student Innovator Mode: Login with 12-digit Aadhaar Card Number linked to DigiLocker / DigiEdu.": "विद्यार्थी नवसंशोधक मोड: डिजीलॉकरशी लिंक केलेल्या 12-अंकी आधार कार्ड क्रमांकासह लॉगिन करा.",
      "Faculty Mentor Mode: Login with 12-digit Aadhaar Card Number linked to AICTE Faculty Registry.": "प्राध्यापक मार्गदर्शक मोड: AICTE रजिस्ट्रीशी जोडलेल्या आधार क्रमांकासह लॉगिन करा.",
      "Government & ULB Mode: Login with Aadhaar Card Number linked to MeriPehchaan National Officer Registry.": "शासकीय व पालिका मोड: मेरीपहचान रजिस्ट्रीशी जोडलेल्या आधार क्रमांकासह लॉगिन करा.",
      "Corporate CSR Partner Mode: Login with Aadhaar Card Number linked to Corporate Section 135 Escrow Signatory.": "कॉर्पोरेट CSR मोड: कलम 135 एस्क्रॉ स्वाक्षरीकर्त्याशी जोडलेल्या आधार क्रमांकासह लॉगिन करा.",
      "Sign In to Sovereign Mesh": "संप्रभु जाळ्यात साइन इन करा",
      "Sovereign Identity Authentication": "संप्रभु ओळख प्रमाणन",
      "Access your personalized dashboard based on your registered ecosystem persona.": "तुमच्या नोंदणीकृत भूमिकेनुसार तुमच्या वैयक्तिक डॅशबोर्डवर प्रवेश करा.",
      "Student Innovator Mode: Login with university email or PRN.": "विद्यार्थी नवसंशोधक मोड: विद्यापीठ ईमेल किंवा PRN सह लॉगिन करा.",
      "Institutional Email / User ID": "संस्थात्मक ईमेल / युझर आयडी",
      "Security Password": "सुरक्षा पासवर्ड",
      "Remember Sovereign Session": "संप्रभु सत्र लक्षात ठेवा",
      "Forgot Key?": "पासवर्ड विसरलात?",
      "Or Sign In With": "किंवा यासह साइन इन करा",
      "Don't have an institutional account?": "संस्थात्मक खाते नाही?"
}
  };

  const c2cWordDictionary = {
    "hi": {
      "AI": "AI",
      "AICTE": "AICTE",
      "API": "API",
      "About": "के बारे में",
      "Academic": "अकादमिक",
      "Access": "पहुंच",
      "Account": "Account",
      "Accredited": "मान्यता प्राप्त",
      "Across": "Across",
      "Act": "Act",
      "Active": "सक्रिय",
      "Activity": "गतिविधि",
      "Actor": "Actor",
      "Admin": "Admin",
      "Administration": "प्रशासन",
      "Admins": "Admins",
      "Advisor": "Advisor",
      "Affected": "Affected",
      "Agritech": "एग्रीटेक",
      "Ahmedabad": "Ahmedabad",
      "Algorithms": "एल्गोरिदम",
      "All": "All",
      "Allocated": "आवंटित",
      "Analytics": "विश्लेषण",
      "Angel": "Angel",
      "App": "App",
      "Apply": "Apply",
      "Approved": "स्वीकृत",
      "Aqsa": "Aqsa",
      "Architecture": "वास्तुकला",
      "Are": "Are",
      "Arsenic": "आर्सेनिक",
      "Ask": "Ask",
      "Asked": "Asked",
      "Assigned": "Assigned",
      "Attach": "Attach",
      "Audio": "Audio",
      "Audit": "लेखा परीक्षा",
      "Audited": "Audited",
      "Auditing": "लेखा परीक्षा",
      "Authored": "Authored",
      "Automated": "स्वचालित",
      "Automatic": "स्वचालित",
      "Autonomous": "स्वायत्त",
      "Average": "Average",
      "Avg": "Avg",
      "BLE": "BLE",
      "BMC": "BMC",
      "Backed": "समर्थित",
      "Benefited": "लाभान्वित",
      "Benefits": "Benefits",
      "Bengaluru": "Bengaluru",
      "Bhiwandi": "भिवंडी",
      "Blocks": "Blocks",
      "Bodies": "Bodies",
      "Bombay": "Bombay",
      "Boost": "Boost",
      "Bridging": "जोड़ना",
      "Budget": "Budget",
      "Built": "Built",
      "Burden": "Burden",
      "Bureaucracy": "Bureaucracy",
      "Burn": "Burn",
      "C": "C",
      "CAMPUS": "CAMPUS",
      "COMMUNITY": "COMMUNITY",
      "CSR": "CSR",
      "Campus": "कैंपस",
      "Campuses": "कैंपस",
      "Capital": "पूंजी",
      "Capstone": "कैपस्टोन",
      "Capstones": "Capstones",
      "Catalogs": "Catalogs",
      "Category": "श्रेणी",
      "Cell": "Cell",
      "Center": "Center",
      "Certificate": "प्रमाणपत्र",
      "Chain": "Chain",
      "Challenge": "चुनौती",
      "Challenges": "चुनौतियां",
      "Chennai": "Chennai",
      "Circularity": "Circularity",
      "Citizen": "नागरिक",
      "Citizens": "नागरिकों",
      "City": "City",
      "Civic": "नागरिक",
      "Civil": "Civil",
      "Classification": "Classification",
      "Clean": "Clean",
      "CleanTech": "CleanTech",
      "Clear": "Clear",
      "Clearance": "मंजूरी",
      "Clearances": "मंजूरी",
      "Click": "Click",
      "Close": "Close",
      "Closed": "Closed",
      "Co": "Co",
      "Coalition": "Coalition",
      "Cockpit": "Cockpit",
      "Coimbatore": "Coimbatore",
      "Cold": "Cold",
      "Collab": "Collab",
      "Collector": "कलेक्टर",
      "Collectorates": "Collectorates",
      "Collectors": "Collectors",
      "College": "कॉलेज",
      "Command": "कमांड",
      "Commissioners": "आयुक्त",
      "Committed": "प्रतिबद्ध",
      "Communities": "समुदायों",
      "Community": "समुदाय",
      "Companies": "Companies",
      "Comparative": "Comparative",
      "Competencies": "Competencies",
      "Complete": "Complete",
      "Completed": "Completed",
      "Compliance": "अनुपालन",
      "Compliant": "Compliant",
      "Conceived": "Conceived",
      "Connect": "Connect",
      "Connected": "Connected",
      "Consent": "Consent",
      "Console": "कंसोल",
      "Contamination": "Contamination",
      "Continuous": "Continuous",
      "Contract": "Contract",
      "Coordinates": "Coordinates",
      "Copilot": "Copilot",
      "Core": "मुख्य",
      "Corporate": "कॉर्पोरेट",
      "Corporation": "Corporation",
      "Corporations": "Corporations",
      "Cosine": "Cosine",
      "Council": "परिषद",
      "Cr": "Cr",
      "Credit": "क्रेडिट",
      "Credits": "क्रेडिट्स",
      "Critical": "गंभीर",
      "Crore": "Crore",
      "Cross": "Cross",
      "Ctrl": "Ctrl",
      "Cycle": "Cycle",
      "D": "D",
      "Daily": "Daily",
      "Data": "डेटा",
      "Days": "दिन",
      "Dec": "Dec",
      "Deduplication": "डुप्लिकेशन हटाना",
      "Department": "विभाग",
      "Deployment": "तैनाती",
      "Desk": "Desk",
      "Details": "विवरण",
      "Development": "विकास",
      "Diagnostics": "Diagnostics",
      "Digital": "डिजिटल",
      "Direct": "प्रत्यक्ष",
      "Disbursed": "Disbursed",
      "Discover": "Discover",
      "Dispatch": "प्रेषण",
      "Dispatches": "Dispatches",
      "Distress": "संकट",
      "Distribution": "Distribution",
      "District": "जिला",
      "Domain": "डोमेन",
      "Domains": "Domains",
      "Download": "Download",
      "Dr": "Dr",
      "Driven": "संचालित",
      "Drones": "Drones",
      "E": "E",
      "EN": "EN",
      "ESC": "ESC",
      "ESG": "ESG",
      "ESP": "ESP",
      "EXIF": "EXIF",
      "Early": "Early",
      "Earn": "Earn",
      "Earned": "Earned",
      "Earns": "Earns",
      "Ecosystem": "पारिस्थितिकी तंत्र",
      "Edge": "Edge",
      "Education": "शिक्षा",
      "Electro": "इलेक्ट्रो",
      "Embedded": "Embedded",
      "Empowering": "सशक्त बनाना",
      "Encrypted": "Encrypted",
      "Energy": "Energy",
      "Engg": "Engg",
      "Engine": "इंजन",
      "Engineered": "Engineered",
      "Engineers": "इंजीनियरों",
      "English": "English",
      "Ensure": "Ensure",
      "Enter": "Enter",
      "Enterprise": "एंटरप्राइज",
      "Environmental": "पर्यावरणीय",
      "Equipment": "Equipment",
      "Escrow": "एस्क्रो",
      "Every": "Every",
      "Everything": "Everything",
      "Evolution": "Evolution",
      "Exceeded": "Exceeded",
      "Expansion": "विस्तार",
      "Experience": "Experience",
      "Explore": "अन्वेषण करें",
      "FAQ": "FAQ",
      "Faculty": "संकाय",
      "Fast": "Fast",
      "Feedback": "प्रतिक्रिया",
      "Field": "क्षेत्र",
      "Filed": "Filed",
      "Filtration": "फिल्टरेशन",
      "Finale": "फिनाले",
      "Firmware": "Firmware",
      "Flow": "प्रवाह",
      "Flutter": "Flutter",
      "Form": "फॉर्म",
      "Formal": "Formal",
      "Formulated": "Formulated",
      "Founding": "Founding",
      "Framework": "ढांचा",
      "Frequently": "Frequently",
      "From": "से",
      "Full": "Full",
      "Fund": "फंड",
      "Funding": "वित्तपोषण",
      "Funds": "फंड",
      "GIS": "जीआईएस",
      "GPS": "GPS",
      "Gadchiroli": "Gadchiroli",
      "Gaikwad": "Gaikwad",
      "Gazetted": "राजपत्रित",
      "Generated": "उत्पन्न",
      "Genesis": "Genesis",
      "Geo": "Geo",
      "Get": "Get",
      "GitHub": "GitHub",
      "Goals": "Goals",
      "Gov": "Gov",
      "Governance": "प्रशासन",
      "Govt": "सरकार",
      "Gram": "Gram",
      "Grand": "ग्रैंड",
      "Grant": "अनुदान",
      "Grants": "अनुदान",
      "Graph": "Graph",
      "Greenlight": "Greenlight",
      "Grid": "ग्रिड",
      "Grievance": "Grievance",
      "Ground": "जमीन",
      "Groundwater": "भूजल",
      "Gujarat": "Gujarat",
      "HI": "HI",
      "Hackathon": "हैकाथॉन",
      "Haji": "Haji",
      "Hardware": "हार्डवेयर",
      "Hazard": "खतरा",
      "Health": "स्वास्थ्य",
      "Healthcare": "स्वास्थ्य सेवा",
      "Healthy": "स्वस्थ",
      "Hi": "Hi",
      "High": "उच्च",
      "Hindi": "Hindi",
      "Home": "Home",
      "Host": "मेजबान",
      "How": "How",
      "Hub": "हब",
      "Hubballi": "Hubballi",
      "Hydrology": "Hydrology",
      "I": "I",
      "ID": "ID",
      "IEEE": "IEEE",
      "IIT": "IIT",
      "IP": "IP",
      "Ideas": "Ideas",
      "Ideation": "Ideation",
      "Identified": "Identified",
      "Identify": "Identify",
      "Immediate": "Immediate",
      "Immutable": "Immutable",
      "Impact": "प्रभाव",
      "Impacted": "प्रभावित",
      "Impacting": "Impacting",
      "In": "में",
      "Inception": "Inception",
      "Incubators": "Incubators",
      "Index": "सूचकांक",
      "India": "India",
      "Industry": "उद्योग",
      "Infestation": "Infestation",
      "Infrastructure": "बुनियादी ढांचा",
      "Initial": "Initial",
      "Initiative": "पहल",
      "Innovation": "इनोवेशन",
      "Innovator": "नवप्रवर्तक",
      "Innovators": "नवप्रवर्तकों",
      "Insights": "Insights",
      "Instant": "Instant",
      "Institute": "संस्थान",
      "Institutional": "संस्थागत",
      "Intake": "इनटेक",
      "Integration": "एकीकरण",
      "Intelligence": "इंटेलिजेंस",
      "Inter": "Inter",
      "Interconnected": "Interconnected",
      "Interdisciplinary": "Interdisciplinary",
      "Into": "Into",
      "Investment": "निवेश",
      "Investors": "Investors",
      "IoT": "आईओटी",
      "Issue": "मुद्दा",
      "Issued": "Issued",
      "Issues": "मुद्दों",
      "Jadhav": "Jadhav",
      "Jal": "Jal",
      "Jan": "Jan",
      "Janbhor": "Janbhor",
      "Jeevan": "Jeevan",
      "Jijabai": "Jijabai",
      "Join": "Join",
      "Journey": "Journey",
      "K": "K",
      "Kanpur": "Kanpur",
      "Karnataka": "Karnataka",
      "Kashaf": "Kashaf",
      "Khan": "Khan",
      "Kulkarni": "Kulkarni",
      "L": "L",
      "Lab": "प्रयोगशाला",
      "Labs": "प्रयोगशालाएं",
      "Lag": "Lag",
      "Lakhs": "Lakhs",
      "Language": "भाषा",
      "Latency": "विलंबता",
      "Launch": "लॉन्च",
      "Lead": "लीड",
      "Leadership": "नेतृत्व",
      "Ledger": "Ledger",
      "Let": "Let",
      "Level": "स्तर",
      "LiDAR": "LiDAR",
      "Literacy": "साक्षरता",
      "Live": "लाइव",
      "Local": "स्थानीय",
      "Location": "स्थान",
      "Locked": "लॉक किया गया",
      "Loop": "Loop",
      "Low": "कम",
      "Lucknow": "Lucknow",
      "M": "M",
      "MIC": "MIC",
      "MPPT": "MPPT",
      "MQTT": "MQTT",
      "MR": "MR",
      "Madurai": "Madurai",
      "Maharashtra": "Maharashtra",
      "Mandate": "जनादेश",
      "Manifest": "Manifest",
      "Manifests": "Manifests",
      "Marathi": "Marathi",
      "Match": "Match",
      "Matched": "Matched",
      "Matching": "मैचिंग",
      "Matchmaker": "Matchmaker",
      "Matchmaking": "Matchmaking",
      "Matrix": "मैट्रिक्स",
      "Measured": "Measured",
      "Measures": "Measures",
      "Memo": "Memo",
      "Mentor": "मार्गदर्शक",
      "Mentors": "मार्गदर्शकों",
      "Mentorship": "मार्गदर्शन",
      "Mesh": "मेश",
      "Metrics": "मेट्रिक्स",
      "Micro": "Micro",
      "Microgrids": "माइक्रोग्रिड्स",
      "Milestone": "मील का पत्थर",
      "Milestones": "मील के पत्थर",
      "Ministry": "मंत्रालय",
      "Mission": "मिशन",
      "Mobile": "Mobile",
      "Mode": "Mode",
      "Model": "मॉडल",
      "Moderate": "मध्यम",
      "Modules": "मॉड्यूल",
      "Monitor": "Monitor",
      "Multi": "Multi",
      "Multiple": "Multiple",
      "Mumbai": "Mumbai",
      "Municipal": "नगर पालिका",
      "Municipalities": "नगर पालिकाओं",
      "My": "My",
      "Mysuru": "Mysuru",
      "N": "N",
      "NGO": "एनजीओ",
      "NIC": "NIC",
      "NIRF": "NIRF",
      "NLP": "NLP",
      "NOC": "एनओसी",
      "NOCs": "NOCs",
      "NTU": "NTU",
      "Nadu": "Nadu",
      "Nagpur": "Nagpur",
      "National": "राष्ट्रीय",
      "Nationwide": "Nationwide",
      "Network": "नेटवर्क",
      "Neural": "न्यूरल",
      "New": "नया",
      "Nodes": "नोड्स",
      "Nov": "Nov",
      "Now": "Now",
      "OTA": "OTA",
      "Observed": "Observed",
      "Oct": "Oct",
      "Onboarded": "शामिल किया गया",
      "Online": "ऑनलाइन",
      "Open": "खुला",
      "Orchestrated": "आर्केस्ट्रेटेड",
      "Orchestrator": "आर्केस्ट्रेटर",
      "Our": "हमारा",
      "Overview": "अवलोकन",
      "PCB": "PCB",
      "PHASE": "PHASE",
      "PHC": "PHC",
      "Panchayat": "पंचायत",
      "Paper": "पेपर",
      "Parnavi": "पर्णवी",
      "Partner": "भागीदार",
      "Partnered": "Partnered",
      "Partnerships": "साझेदारी",
      "Pass": "Pass",
      "Patent": "पेटेंट",
      "Pending": "लंबित",
      "Permits": "Permits",
      "Persona": "Persona",
      "Perspective": "Perspective",
      "Pest": "Pest",
      "Phase": "चरण",
      "Photo": "Photo",
      "Pillar": "स्तंभ",
      "Pillars": "स्तंभों",
      "Pilot": "पायलट",
      "Pilots": "पायलटों",
      "Pioneered": "Pioneered",
      "Pipeline": "पाइपलाइन",
      "Pitch": "Pitch",
      "Platform": "प्लेटफ़ॉर्म",
      "Point": "Point",
      "Points": "Points",
      "Population": "जनसंख्या",
      "Portal": "पोर्टल",
      "Portals": "पोर्टल",
      "Positions": "Positions",
      "Pradesh": "Pradesh",
      "Present": "Present",
      "Priority": "प्राथमिकता",
      "Privacy": "Privacy",
      "Problem": "समस्या",
      "Problems": "समस्याएं",
      "Profile": "प्रोफ़ाइल",
      "Progress": "प्रगति",
      "Projects": "परियोजनाओं",
      "Proof": "प्रमाण",
      "Proposed": "प्रस्तावित",
      "Prototype": "प्रोटोटाइप",
      "Prototypes": "प्रोटोटाइप",
      "Prototyping": "Prototyping",
      "Provisional": "Provisional",
      "Public": "सार्वजनिक",
      "Publication": "प्रकाशन",
      "Pulse": "Pulse",
      "Pune": "Pune",
      "Purity": "शुद्धता",
      "Purpose": "उद्देश्य",
      "Python": "Python",
      "Q": "Q",
      "Queries": "Queries",
      "Questions": "Questions",
      "Quick": "Quick",
      "R": "R",
      "RFPs": "आरएफपी",
      "ROI": "आरओआई",
      "ROS": "ROS",
      "Rails": "Rails",
      "Rate": "दर",
      "Ready": "Ready",
      "Real": "वास्तविक",
      "Realtime": "Realtime",
      "Receive": "Receive",
      "Recommended": "अनुशंसित",
      "Registry": "Registry",
      "Report": "रिपोर्ट",
      "Reported": "Reported",
      "Reporting": "रिपोर्टिंग",
      "Reports": "रिपोर्ट",
      "Requests": "Requests",
      "Research": "अनुसंधान",
      "Reserved": "Reserved",
      "Resolution": "समाधान",
      "Resolutions": "Resolutions",
      "Resolved": "हल किया गया",
      "Resources": "संसाधनों",
      "Retain": "Retain",
      "Return": "Return",
      "Returns": "Returns",
      "Review": "समीक्षा",
      "Rights": "अधिकार",
      "Role": "भूमिका",
      "Rollout": "Rollout",
      "Routing": "Routing",
      "Rural": "ग्रामीण",
      "S": "S",
      "SDG": "SDG",
      "SIH": "SIH",
      "SLA": "एसएलए",
      "SMS": "SMS",
      "SPRINT": "SPRINT",
      "SROI": "एसआरओआई",
      "Safe": "Safe",
      "Safety": "सुरक्षा",
      "Sample": "नमूना",
      "Sandbox": "Sandbox",
      "Sanitation": "स्वच्छता",
      "Scaling": "स्केलिंग",
      "Score": "स्कोर",
      "Scoring": "स्कोरिंग",
      "Search": "खोजें",
      "Searches": "Searches",
      "Sec": "Sec",
      "Section": "धारा",
      "Sector": "Sector",
      "Security": "सुरक्षा",
      "Select": "चुनें",
      "Semantic": "सिमेंटिक",
      "Sensor": "सेंसर",
      "Sensors": "सेंसर",
      "Severe": "Severe",
      "Severity": "गंभीरता",
      "Shared": "Shared",
      "Shirgudi": "Shirgudi",
      "Shlok": "Shlok",
      "Shortcuts": "Shortcuts",
      "Shrutesh": "Shrutesh",
      "Signoff": "हस्ताक्षर",
      "Single": "एकल",
      "Skills": "कौशल्य",
      "Smart": "स्मार्ट",
      "Social": "सामाजिक",
      "Society": "Society",
      "Soil": "मृदा",
      "Solar": "सौर",
      "Solution": "समाधान",
      "Solutions": "समाधान",
      "Solved": "हल किया गया",
      "Solver": "समाधानकर्ता",
      "Solvers": "समाधानकर्ता",
      "Sourcing": "Sourcing",
      "Sovereign": "संप्रभु",
      "Spanning": "Spanning",
      "Spatial": "स्थानिक",
      "Specific": "Specific",
      "Spectrometer": "Spectrometer",
      "Spectrophotometer": "Spectrophotometer",
      "Sponsors": "Sponsors",
      "Sponsorship": "Sponsorship",
      "Sprint": "स्प्रिंट",
      "Squad": "दल",
      "Stage": "चरण",
      "Stakeholder": "हितधारक",
      "Stakeholders": "हितधारकों",
      "State": "राज्य",
      "Statement": "कथन",
      "Status": "स्थिति",
      "Stipend": "वजीफा",
      "Stipends": "वजीफा",
      "Stream": "स्ट्रीम",
      "Student": "छात्र",
      "Students": "छात्रों",
      "Sub": "Sub",
      "Submit": "जमा करें",
      "Submitted": "प्रस्तुत",
      "Suite": "Suite",
      "Summary": "सारांश",
      "Supervised": "Supervised",
      "Supported": "Supported",
      "Surat": "Surat",
      "Sustainable": "Sustainable",
      "Switcher": "Switcher",
      "Sync": "Sync",
      "Synced": "Synced",
      "System": "प्रणाली",
      "Systems": "प्रणालियां",
      "TEAM": "TEAM",
      "Tagged": "Tagged",
      "Tagging": "Tagging",
      "Talent": "Talent",
      "Tamil": "Tamil",
      "Tanmay": "Tanmay",
      "Target": "लक्ष्य",
      "Tata": "Tata",
      "Tax": "Tax",
      "Team": "टीम",
      "Teams": "टीमों",
      "Technological": "Technological",
      "Technology": "प्रौद्योगिकी",
      "Telemetry": "टेलीमेट्री",
      "Test": "परीक्षण",
      "Testbed": "Testbed",
      "Testing": "परीक्षण",
      "The": "The",
      "Theme": "Theme",
      "Ticket": "टिकट",
      "Tier": "Tier",
      "Time": "समय",
      "Timeline": "समयरेखा",
      "Title": "शीर्षक",
      "To": "को",
      "Toggle": "Toggle",
      "Trace": "Trace",
      "Track": "Track",
      "Tracking": "टैकिंग",
      "Tranche": "Tranche",
      "Transform": "बदलना",
      "Transforms": "Transforms",
      "Translate": "Translate",
      "Transmitting": "प्रसारित हो रहा है",
      "Trusts": "Trusts",
      "Turbidity": "गंभीरता",
      "Turn": "Turn",
      "UN": "UN",
      "UV": "यूवी",
      "Unified": "एकीकृत",
      "Unit": "इकाई",
      "Universities": "विश्वविद्यालयों",
      "University": "विश्वविद्यालय",
      "Unlock": "Unlock",
      "Upon": "Upon",
      "Urban": "शहरी",
      "Urgency": "तात्कालिकता",
      "Us": "Us",
      "Uttar": "Uttar",
      "VJTI": "VJTI",
      "VSIT": "VSIT",
      "Vacant": "Vacant",
      "Vaccine": "Vaccine",
      "Vadodara": "Vadodara",
      "Valid": "Valid",
      "Validated": "Validated",
      "Validation": "सत्यापन",
      "Value": "मूल्य",
      "Varanasi": "Varanasi",
      "Vector": "वेक्टर",
      "Veermata": "Veermata",
      "Velocity": "वेग",
      "Verification": "सत्यापन",
      "Verified": "सत्यापित",
      "Verifiers": "सत्यापनकर्ताओं",
      "Verifies": "Verifies",
      "Vernacular": "Vernacular",
      "Vidarbha": "Vidarbha",
      "Vidyalankar": "Vidyalankar",
      "View": "View",
      "Village": "गांव",
      "Vision": "विजन",
      "W": "W",
      "Warning": "Warning",
      "Waste": "Waste",
      "Water": "जल",
      "We": "We",
      "WhatsApp": "WhatsApp",
      "When": "When",
      "Wide": "Wide",
      "Window": "Window",
      "Work": "कार्य",
      "Works": "Works",
      "Workspace": "कार्यक्षेत्र",
      "Workspaces": "Workspaces",
      "World": "विश्व",
      "YOLOv": "YOLOv",
      "Yes": "Yes",
      "Your": "Your",
      "Zero": "शून्य",
      "a": "a",
      "about": "about",
      "academic": "academic",
      "access": "access",
      "account": "account",
      "accounts": "accounts",
      "accredited": "accredited",
      "across": "across",
      "actionable": "actionable",
      "active": "active",
      "activism": "activism",
      "activity": "activity",
      "actual": "actual",
      "add": "add",
      "administration": "administration",
      "affecting": "affecting",
      "after": "after",
      "ago": "ago",
      "ai": "ai",
      "align": "align",
      "alignment": "alignment",
      "allocation": "allocation",
      "allowing": "allowing",
      "alt": "alt",
      "an": "an",
      "analytics": "analytics",
      "and": "और",
      "anything": "anything",
      "apartment": "apartment",
      "approvals": "approvals",
      "approved": "approved",
      "architecture": "architecture",
      "are": "are",
      "arrow": "arrow",
      "arsenic": "arsenic",
      "assemble": "assemble",
      "at": "at",
      "audit": "audit",
      "auditable": "auditable",
      "audits": "audits",
      "authorize": "authorize",
      "automated": "automated",
      "automatically": "automatically",
      "backed": "backed",
      "backing": "backing",
      "badge": "badge",
      "balance": "balance",
      "based": "based",
      "before": "before",
      "belts": "belts",
      "benefits": "benefits",
      "better": "better",
      "between": "between",
      "biotech": "biotech",
      "blocks": "blocks",
      "boards": "boards",
      "bodies": "bodies",
      "body": "body",
      "bollworm": "bollworm",
      "bolt": "bolt",
      "boost": "boost",
      "bots": "bots",
      "bottlenecks": "bottlenecks",
      "bridge": "bridge",
      "built": "built",
      "bureaucratic": "bureaucratic",
      "by": "द्वारा",
      "calculated": "calculated",
      "calibration": "calibration",
      "calls": "calls",
      "can": "can",
      "capital": "capital",
      "capstone": "capstone",
      "cases": "cases",
      "cellular": "cellular",
      "certificates": "certificates",
      "certify": "certify",
      "challenge": "challenge",
      "challenges": "challenges",
      "chapters": "chapters",
      "chart": "chart",
      "check": "check",
      "checked": "checked",
      "chevron": "chevron",
      "circle": "circle",
      "citizen": "citizen",
      "citizens": "citizens",
      "city": "city",
      "civic": "civic",
      "civil": "civil",
      "clearance": "clearance",
      "clearances": "clearances",
      "clearly": "clearly",
      "clock": "clock",
      "close": "close",
      "cloud": "cloud",
      "co": "co",
      "code": "code",
      "cohorts": "cohorts",
      "college": "college",
      "colleges": "colleges",
      "collegiate": "collegiate",
      "community": "community",
      "completed": "completed",
      "completes": "completes",
      "compliance": "compliance",
      "compute": "compute",
      "computer": "computer",
      "concentration": "concentration",
      "connecting": "connecting",
      "contact": "contact",
      "contracts": "contracts",
      "contributions": "contributions",
      "core": "core",
      "corporate": "corporate",
      "cost": "cost",
      "cotton": "cotton",
      "counselors": "counselors",
      "count": "count",
      "credential": "credential",
      "credit": "credit",
      "credits": "credits",
      "cross": "cross",
      "cryptographic": "cryptographic",
      "curriculum": "curriculum",
      "customize": "customize",
      "cutting": "cutting",
      "dark": "dark",
      "dashboard": "dashboard",
      "dashboards": "dashboards",
      "data": "data",
      "database": "database",
      "datasets": "datasets",
      "day": "day",
      "deduplication": "deduplication",
      "degree": "degree",
      "delay": "delay",
      "delivery": "delivery",
      "density": "density",
      "department": "department",
      "departmental": "departmental",
      "departments": "departments",
      "deployed": "deployed",
      "deployment": "deployment",
      "deployments": "deployments",
      "detection": "detection",
      "device": "device",
      "digital": "digital",
      "direct": "direct",
      "directly": "directly",
      "disburse": "disburse",
      "disbursed": "disbursed",
      "disciplinary": "disciplinary",
      "disciplines": "disciplines",
      "dispatch": "dispatch",
      "dispatches": "dispatches",
      "distress": "distress",
      "district": "district",
      "districts": "districts",
      "diversity": "diversity",
      "do": "do",
      "domain": "domain",
      "drafting": "drafting",
      "drainage": "drainage",
      "driven": "driven",
      "driving": "driving",
      "drone": "drone",
      "drop": "drop",
      "duplicate": "duplicate",
      "during": "during",
      "e": "e",
      "earn": "earn",
      "edge": "edge",
      "edu": "edu",
      "efficacy": "efficacy",
      "efficiency": "efficiency",
      "electrical": "electrical",
      "electro": "electro",
      "eliminate": "eliminate",
      "email": "email",
      "encryption": "encryption",
      "endorsed": "endorsed",
      "engine": "engine",
      "engineering": "engineering",
      "engineers": "engineers",
      "enterprise": "enterprise",
      "entries": "entries",
      "escrow": "escrow",
      "establish": "establish",
      "every": "every",
      "exchequer": "exchequer",
      "execution": "execution",
      "executive": "executive",
      "exemption": "exemption",
      "expand": "expand",
      "experience": "experience",
      "expertise": "expertise",
      "extraction": "extraction",
      "fabrication": "fabrication",
      "facilities": "facilities",
      "factory": "factory",
      "faculty": "faculty",
      "fare": "fare",
      "feed": "feed",
      "feedback": "feedback",
      "field": "field",
      "filtration": "filtration",
      "final": "final",
      "finance": "finance",
      "flag": "flag",
      "flash": "flash",
      "flocculation": "flocculation",
      "following": "following",
      "for": "के लिए",
      "formalized": "formalized",
      "formatted": "formatted",
      "forward": "forward",
      "foundation": "foundation",
      "frameworks": "frameworks",
      "frictionless": "frictionless",
      "from": "से",
      "full": "full",
      "funding": "funding",
      "funds": "funds",
      "g": "g",
      "gap": "gap",
      "gastroenteritis": "gastroenteritis",
      "gavel": "gavel",
      "generated": "generated",
      "geofenced": "geofenced",
      "geotags": "geotags",
      "get": "get",
      "go": "go",
      "gov": "gov",
      "governance": "governance",
      "governed": "governed",
      "government": "government",
      "grade": "grade",
      "grading": "grading",
      "graduates": "graduates",
      "graduation": "graduation",
      "grant": "grant",
      "grants": "grants",
      "grew": "grew",
      "grid": "grid",
      "ground": "ground",
      "grounds": "grounds",
      "group": "group",
      "guide": "guide",
      "guidelines": "guidelines",
      "guides": "guides",
      "h": "h",
      "handshake": "handshake",
      "hardware": "hardware",
      "heads": "heads",
      "healthcare": "healthcare",
      "held": "held",
      "help": "help",
      "high": "high",
      "hire": "hire",
      "history": "history",
      "home": "home",
      "hours": "hours",
      "how": "how",
      "hub": "hub",
      "impact": "impact",
      "in": "में",
      "indexing": "indexing",
      "industrial": "industrial",
      "industry": "industry",
      "info": "info",
      "innovation": "innovation",
      "innovators": "innovators",
      "inspector": "inspector",
      "installation": "installation",
      "instant": "instant",
      "institution": "institution",
      "institutional": "institutional",
      "integrated": "integrated",
      "intellectual": "intellectual",
      "intelligence": "intelligence",
      "interdisciplinary": "interdisciplinary",
      "into": "into",
      "inventory": "inventory",
      "investment": "investment",
      "is": "is",
      "issue": "issue",
      "issued": "issued",
      "issues": "issues",
      "join": "join",
      "joining": "joining",
      "kWh": "kWh",
      "km": "km",
      "know": "know",
      "lab": "lab",
      "labs": "labs",
      "launch": "launch",
      "leadership": "leadership",
      "legal": "legal",
      "less": "less",
      "level": "level",
      "library": "library",
      "lightbulb": "lightbulb",
      "linear": "linear",
      "linkage": "linkage",
      "linking": "linking",
      "liters": "liters",
      "live": "live",
      "local": "local",
      "location": "location",
      "lock": "lock",
      "locked": "locked",
      "loops": "loops",
      "m": "m",
      "making": "making",
      "managed": "managed",
      "mandatory": "mandatory",
      "manifests": "manifests",
      "map": "map",
      "mapped": "mapped",
      "mapping": "mapping",
      "matched": "matched",
      "matches": "matches",
      "matching": "matching",
      "measuring": "measuring",
      "mentor": "mentor",
      "mentorship": "mentorship",
      "menu": "menu",
      "mesh": "mesh",
      "metrics": "metrics",
      "micro": "micro",
      "milestone": "milestone",
      "ministerial": "ministerial",
      "mins": "mins",
      "mission": "mission",
      "mode": "mode",
      "models": "models",
      "month": "month",
      "more": "more",
      "ms": "ms",
      "multi": "multi",
      "multidisciplinary": "multidisciplinary",
      "municipal": "municipal",
      "national": "national",
      "nationwide": "nationwide",
      "need": "need",
      "neurology": "neurology",
      "new": "new",
      "no": "no",
      "nodes": "nodes",
      "notes": "notes",
      "notifications": "notifications",
      "observed": "observed",
      "of": "का",
      "officers": "officers",
      "official": "official",
      "on": "on",
      "only": "only",
      "open": "open",
      "or": "or",
      "over": "over",
      "overflow": "overflow",
      "pair": "pair",
      "paired": "paired",
      "panchayats": "panchayats",
      "paper": "paper",
      "papers": "papers",
      "partners": "partners",
      "patent": "patent",
      "patents": "patents",
      "payments": "payments",
      "payout": "payout",
      "pending": "pending",
      "perform": "perform",
      "permit": "permit",
      "permits": "permits",
      "person": "person",
      "photos": "photos",
      "physical": "physical",
      "pie": "pie",
      "pillars": "pillars",
      "pilot": "pilot",
      "pilots": "pilots",
      "pin": "pin",
      "ping": "ping",
      "pink": "pink",
      "plants": "plants",
      "platform": "platform",
      "points": "points",
      "policy": "policy",
      "population": "population",
      "portal": "portal",
      "powered": "powered",
      "ppm": "ppm",
      "premium": "premium",
      "present": "present",
      "primary": "primary",
      "priority": "priority",
      "problem": "problem",
      "problems": "problems",
      "professors": "professors",
      "proficiencies": "proficiencies",
      "progress": "progress",
      "project": "project",
      "projects": "projects",
      "proof": "proof",
      "proofs": "proofs",
      "property": "property",
      "proposals": "proposals",
      "prototype": "prototype",
      "prototypes": "prototypes",
      "prototyping": "prototyping",
      "provisional": "provisional",
      "psychology": "psychology",
      "public": "public",
      "purchasing": "purchasing",
      "purified": "purified",
      "pushed": "pushed",
      "quantified": "quantified",
      "query": "query",
      "radius": "radius",
      "rails": "rails",
      "rankings": "rankings",
      "rapid": "rapid",
      "rating": "rating",
      "ratings": "ratings",
      "ready": "ready",
      "real": "real",
      "receipt": "receipt",
      "receipts": "receipts",
      "receive": "receive",
      "recent": "recent",
      "recognition": "recognition",
      "recovered": "recovered",
      "reference": "reference",
      "regional": "regional",
      "register": "register",
      "regulatory": "regulatory",
      "released": "released",
      "releases": "releases",
      "reliability": "reliability",
      "removes": "removes",
      "report": "report",
      "reported": "reported",
      "reporting": "reporting",
      "reports": "reports",
      "research": "research",
      "reserved": "reserved",
      "residents": "residents",
      "resolution": "resolution",
      "resolved": "resolved",
      "resolving": "resolving",
      "results": "results",
      "return": "return",
      "review": "review",
      "right": "right",
      "rights": "rights",
      "road": "road",
      "roads": "roads",
      "rocket": "rocket",
      "route": "route",
      "routing": "routing",
      "runoff": "runoff",
      "rural": "rural",
      "s": "s",
      "safely": "safely",
      "safety": "safety",
      "sampling": "sampling",
      "sandboxes": "sandboxes",
      "sarpanches": "sarpanches",
      "satisfaction": "satisfaction",
      "saved": "saved",
      "scale": "scale",
      "scans": "scans",
      "school": "school",
      "science": "science",
      "scientist": "scientist",
      "scoring": "scoring",
      "seamlessly": "seamlessly",
      "search": "search",
      "sector": "sector",
      "semantic": "semantic",
      "send": "send",
      "sensor": "sensor",
      "sensors": "sensors",
      "server": "server",
      "shield": "shield",
      "shortlisted": "shortlisted",
      "signoff": "signoff",
      "single": "single",
      "skills": "skills",
      "slots": "slots",
      "smallholder": "smallholder",
      "smart": "smart",
      "social": "social",
      "societal": "societal",
      "society": "society",
      "solar": "solar",
      "solution": "solution",
      "solutions": "solutions",
      "solve": "solve",
      "solving": "solving",
      "sovereign": "sovereign",
      "space": "space",
      "spatial": "spatial",
      "specs": "specs",
      "speed": "speed",
      "sprint": "sprint",
      "squads": "squads",
      "stability": "stability",
      "stakeholder": "stakeholder",
      "state": "state",
      "statement": "statement",
      "statements": "statements",
      "states": "states",
      "stats": "stats",
      "statutory": "statutory",
      "stipends": "stipends",
      "storage": "storage",
      "stream": "stream",
      "structured": "structured",
      "student": "student",
      "sub": "sub",
      "submissions": "submissions",
      "submit": "submit",
      "support": "support",
      "supporting": "supporting",
      "surface": "surface",
      "sustainable": "sustainable",
      "tagged": "tagged",
      "tagging": "tagging",
      "tailored": "tailored",
      "takes": "takes",
      "talent": "talent",
      "target": "target",
      "task": "task",
      "team": "team",
      "teams": "teams",
      "tech": "tech",
      "technologies": "technologies",
      "technology": "technology",
      "tedious": "tedious",
      "telemetry": "telemetry",
      "temperature": "temperature",
      "test": "test",
      "tested": "tested",
      "testing": "testing",
      "text": "text",
      "than": "than",
      "that": "that",
      "the": "the",
      "their": "their",
      "them": "them",
      "theses": "theses",
      "this": "this",
      "tickets": "tickets",
      "tier": "tier",
      "time": "time",
      "timeline": "timeline",
      "to": "को",
      "today": "today",
      "tokens": "tokens",
      "toolkits": "toolkits",
      "top": "top",
      "toward": "toward",
      "tracking": "tracking",
      "tracks": "tracks",
      "traditional": "traditional",
      "transcript": "transcript",
      "transformation": "transformation",
      "translate": "translate",
      "transparent": "transparent",
      "tree": "tree",
      "trending": "trending",
      "tribal": "tribal",
      "turbidity": "turbidity",
      "turn": "turn",
      "turning": "turning",
      "unanswered": "unanswered",
      "under": "under",
      "unify": "unify",
      "unifying": "unifying",
      "unit": "unit",
      "universities": "universities",
      "university": "university",
      "unlock": "unlock",
      "up": "up",
      "updates": "updates",
      "upgrade": "upgrade",
      "upload": "upload",
      "upon": "upon",
      "uptime": "uptime",
      "upward": "upward",
      "urban": "urban",
      "urgency": "urgency",
      "user": "user",
      "using": "using",
      "utilization": "utilization",
      "v": "v",
      "validate": "validate",
      "validated": "validated",
      "vector": "vector",
      "vectors": "vectors",
      "velocities": "velocities",
      "verifiable": "verifiable",
      "verification": "verification",
      "verified": "verified",
      "verifies": "verifies",
      "vernacular": "vernacular",
      "via": "via",
      "view": "view",
      "village": "village",
      "visibility": "visibility",
      "voice": "voice",
      "volume": "volume",
      "volunteer": "volunteer",
      "vs": "vs",
      "ward": "ward",
      "water": "water",
      "web": "web",
      "wells": "wells",
      "where": "where",
      "who": "who",
      "window": "window",
      "wings": "wings",
      "with": "के साथ",
      "within": "within",
      "without": "without",
      "work": "work",
      "workflow": "workflow",
      "workflows": "workflows",
      "working": "working",
      "works": "works",
      "workspace": "workspace",
      "world": "विश्व",
      "x": "x",
      "year": "year",
      "yesterday": "yesterday",
      "you": "you",
      "your": "your",
      "zero": "zero",
      "our": "हमारा",
      "purpose": "उद्देश्य",
      "mandate": "जनादेश",
      "For": "के लिए",
      "By": "द्वारा",
      "And": "और",
      "With": "के साथ",
      "Of": "का"
},
    "mr": {
      "AI": "AI",
      "AICTE": "AICTE",
      "API": "API",
      "About": "बद्दल",
      "Academic": "शैक्षणिक",
      "Access": "प्रवेश",
      "Account": "Account",
      "Accredited": "मान्यताप्राप्त",
      "Across": "Across",
      "Act": "Act",
      "Active": "सक्रिय",
      "Activity": "उपक्रम",
      "Actor": "Actor",
      "Admin": "Admin",
      "Administration": "प्रशासन",
      "Admins": "Admins",
      "Advisor": "Advisor",
      "Affected": "Affected",
      "Agritech": "कृषी तंत्रज्ञान",
      "Ahmedabad": "Ahmedabad",
      "Algorithms": "अल्गोरिदम",
      "All": "All",
      "Allocated": "वाटप केलेले",
      "Analytics": "विश्लेषण",
      "Angel": "Angel",
      "App": "App",
      "Apply": "Apply",
      "Approved": "मंजूर",
      "Aqsa": "Aqsa",
      "Architecture": "संरचना",
      "Are": "Are",
      "Arsenic": "आर्सेनिक",
      "Ask": "Ask",
      "Asked": "Asked",
      "Assigned": "Assigned",
      "Attach": "Attach",
      "Audio": "Audio",
      "Audit": "परीक्षण",
      "Audited": "Audited",
      "Auditing": "परीक्षण",
      "Authored": "Authored",
      "Automated": "स्वयंचलित",
      "Automatic": "स्वयंचलित",
      "Autonomous": "स्वायत्त",
      "Average": "Average",
      "Avg": "Avg",
      "BLE": "BLE",
      "BMC": "BMC",
      "Backed": "समर्थित",
      "Benefited": "लाभान्वित",
      "Benefits": "Benefits",
      "Bengaluru": "Bengaluru",
      "Bhiwandi": "भिवंडी",
      "Blocks": "Blocks",
      "Bodies": "Bodies",
      "Bombay": "Bombay",
      "Boost": "Boost",
      "Bridging": "जोडणी",
      "Budget": "Budget",
      "Built": "Built",
      "Burden": "Burden",
      "Bureaucracy": "Bureaucracy",
      "Burn": "Burn",
      "C": "C",
      "CAMPUS": "CAMPUS",
      "COMMUNITY": "COMMUNITY",
      "CSR": "CSR",
      "Campus": "कॅम्पस",
      "Campuses": "कॅम्पस",
      "Capital": "भांडवल",
      "Capstone": "प्रकल्प",
      "Capstones": "Capstones",
      "Catalogs": "Catalogs",
      "Category": "प्रकार",
      "Cell": "Cell",
      "Center": "Center",
      "Certificate": "प्रमाणपत्र",
      "Chain": "Chain",
      "Challenge": "आव्हान",
      "Challenges": "आव्हाने",
      "Chennai": "Chennai",
      "Circularity": "Circularity",
      "Citizen": "नागरिक",
      "Citizens": "नागरिक",
      "City": "City",
      "Civic": "नागरी",
      "Civil": "Civil",
      "Classification": "Classification",
      "Clean": "Clean",
      "CleanTech": "CleanTech",
      "Clear": "Clear",
      "Clearance": "मंजुरी",
      "Clearances": "मंजुरी",
      "Click": "Click",
      "Close": "Close",
      "Closed": "Closed",
      "Co": "Co",
      "Coalition": "Coalition",
      "Cockpit": "Cockpit",
      "Coimbatore": "Coimbatore",
      "Cold": "Cold",
      "Collab": "Collab",
      "Collector": "जिल्हाधिकारी",
      "Collectorates": "Collectorates",
      "Collectors": "Collectors",
      "College": "कॉलेज",
      "Command": "कमांड",
      "Commissioners": "आयुक्त",
      "Committed": "कटिबद्ध",
      "Communities": "समुदाय",
      "Community": "समुदाय",
      "Companies": "Companies",
      "Comparative": "Comparative",
      "Competencies": "Competencies",
      "Complete": "Complete",
      "Completed": "Completed",
      "Compliance": "पालन",
      "Compliant": "Compliant",
      "Conceived": "Conceived",
      "Connect": "Connect",
      "Connected": "Connected",
      "Consent": "Consent",
      "Console": "कन्सोल",
      "Contamination": "Contamination",
      "Continuous": "Continuous",
      "Contract": "Contract",
      "Coordinates": "Coordinates",
      "Copilot": "Copilot",
      "Core": "मुख्य",
      "Corporate": "कॉर्पोरेट",
      "Corporation": "Corporation",
      "Corporations": "Corporations",
      "Cosine": "Cosine",
      "Council": "परिषद",
      "Cr": "Cr",
      "Credit": "क्रेडिट",
      "Credits": "क्रेडिट्स",
      "Critical": "अत्यंत तातडीचे",
      "Crore": "Crore",
      "Cross": "Cross",
      "Ctrl": "Ctrl",
      "Cycle": "Cycle",
      "D": "D",
      "Daily": "Daily",
      "Data": "डेटा",
      "Days": "दिवस",
      "Dec": "Dec",
      "Deduplication": "डुप्लिकेट काढणे",
      "Department": "विभाग",
      "Deployment": "अंमलबजावणी",
      "Desk": "Desk",
      "Details": "तपशील",
      "Development": "विकास",
      "Diagnostics": "Diagnostics",
      "Digital": "डिजिटल",
      "Direct": "थेट",
      "Disbursed": "Disbursed",
      "Discover": "Discover",
      "Dispatch": "डिस्पॅच",
      "Dispatches": "Dispatches",
      "Distress": "समस्या",
      "Distribution": "Distribution",
      "District": "जिल्हा",
      "Domain": "डोमेन",
      "Domains": "Domains",
      "Download": "Download",
      "Dr": "Dr",
      "Driven": "संचलित",
      "Drones": "Drones",
      "E": "E",
      "EN": "EN",
      "ESC": "ESC",
      "ESG": "ESG",
      "ESP": "ESP",
      "EXIF": "EXIF",
      "Early": "Early",
      "Earn": "Earn",
      "Earned": "Earned",
      "Earns": "Earns",
      "Ecosystem": "परिसंस्था",
      "Edge": "Edge",
      "Education": "शिक्षण",
      "Electro": "इलेक्ट्रो",
      "Embedded": "Embedded",
      "Empowering": "सक्षम करणे",
      "Encrypted": "Encrypted",
      "Energy": "Energy",
      "Engg": "Engg",
      "Engine": "इंजिन",
      "Engineered": "Engineered",
      "Engineers": "अभियंते",
      "English": "English",
      "Ensure": "Ensure",
      "Enter": "Enter",
      "Enterprise": "एंटरप्राइज",
      "Environmental": "पर्यावरणीय",
      "Equipment": "Equipment",
      "Escrow": "एस्क्रॉ",
      "Every": "Every",
      "Everything": "Everything",
      "Evolution": "Evolution",
      "Exceeded": "Exceeded",
      "Expansion": "विस्तार",
      "Experience": "Experience",
      "Explore": "पाहा",
      "FAQ": "FAQ",
      "Faculty": "प्राध्यापक",
      "Fast": "Fast",
      "Feedback": "अभिप्राय",
      "Field": "क्षेत्र",
      "Filed": "Filed",
      "Filtration": "फिल्ट्रेशन",
      "Finale": "फिनाले",
      "Firmware": "Firmware",
      "Flow": "प्रवाह",
      "Flutter": "Flutter",
      "Form": "फॉर्म",
      "Formal": "Formal",
      "Formulated": "Formulated",
      "Founding": "Founding",
      "Framework": "फ्रेमवर्क",
      "Frequently": "Frequently",
      "From": "पासून",
      "Full": "Full",
      "Fund": "निधी",
      "Funding": "निधी",
      "Funds": "निधी",
      "GIS": "GIS",
      "GPS": "GPS",
      "Gadchiroli": "Gadchiroli",
      "Gaikwad": "Gaikwad",
      "Gazetted": "राजपत्रित",
      "Generated": "तयार केलेले",
      "Genesis": "Genesis",
      "Geo": "Geo",
      "Get": "Get",
      "GitHub": "GitHub",
      "Goals": "Goals",
      "Gov": "Gov",
      "Governance": "शासन",
      "Govt": "शासन",
      "Gram": "Gram",
      "Grand": "ग्रँड",
      "Grant": "अनुदान",
      "Grants": "अनुदान",
      "Graph": "Graph",
      "Greenlight": "Greenlight",
      "Grid": "ग्रिड",
      "Grievance": "Grievance",
      "Ground": "जमीन",
      "Groundwater": "भूजल",
      "Gujarat": "Gujarat",
      "HI": "HI",
      "Hackathon": "हॅकाथॉन",
      "Haji": "Haji",
      "Hardware": "हार्डवेअर",
      "Hazard": "धोका",
      "Health": "आरोग्य",
      "Healthcare": "आरोग्य सेवा",
      "Healthy": "निरोगी",
      "Hi": "Hi",
      "High": "उच्च",
      "Hindi": "Hindi",
      "Home": "Home",
      "Host": "होस्ट",
      "How": "How",
      "Hub": "हब",
      "Hubballi": "Hubballi",
      "Hydrology": "Hydrology",
      "I": "I",
      "ID": "ID",
      "IEEE": "IEEE",
      "IIT": "IIT",
      "IP": "IP",
      "Ideas": "Ideas",
      "Ideation": "Ideation",
      "Identified": "Identified",
      "Identify": "Identify",
      "Immediate": "Immediate",
      "Immutable": "Immutable",
      "Impact": "प्रभाव",
      "Impacted": "प्रभावित",
      "Impacting": "Impacting",
      "In": "मध्ये",
      "Inception": "Inception",
      "Incubators": "Incubators",
      "Index": "निर्देशांक",
      "India": "India",
      "Industry": "उद्योग",
      "Infestation": "Infestation",
      "Infrastructure": "पायाभूत सुविधा",
      "Initial": "Initial",
      "Initiative": "उपक्रम",
      "Innovation": "नावीन्यता",
      "Innovator": "नवसंशोधक",
      "Innovators": "नवसंशोधक",
      "Insights": "Insights",
      "Instant": "Instant",
      "Institute": "संस्था",
      "Institutional": "संस्थात्मक",
      "Intake": "इनटेक",
      "Integration": "एकत्रीकरण",
      "Intelligence": "बुद्धिमत्ता",
      "Inter": "Inter",
      "Interconnected": "Interconnected",
      "Interdisciplinary": "Interdisciplinary",
      "Into": "Into",
      "Investment": "गुंतवणूक",
      "Investors": "Investors",
      "IoT": "IoT",
      "Issue": "समस्या",
      "Issued": "Issued",
      "Issues": "समस्या",
      "Jadhav": "Jadhav",
      "Jal": "Jal",
      "Jan": "Jan",
      "Janbhor": "Janbhor",
      "Jeevan": "Jeevan",
      "Jijabai": "Jijabai",
      "Join": "Join",
      "Journey": "Journey",
      "K": "K",
      "Kanpur": "Kanpur",
      "Karnataka": "Karnataka",
      "Kashaf": "Kashaf",
      "Khan": "Khan",
      "Kulkarni": "Kulkarni",
      "L": "L",
      "Lab": "प्रयोगशाळा",
      "Labs": "प्रयोगशाळा",
      "Lag": "Lag",
      "Lakhs": "Lakhs",
      "Language": "भाषा",
      "Latency": "लॅटन्सी",
      "Launch": "लॉन्च",
      "Lead": "प्रमुख",
      "Leadership": "नेतृत्व",
      "Ledger": "Ledger",
      "Let": "Let",
      "Level": "पातळी",
      "LiDAR": "LiDAR",
      "Literacy": "साक्षरता",
      "Live": "थेट",
      "Local": "स्थानिक",
      "Location": "स्थान",
      "Locked": "लॉक केलेले",
      "Loop": "Loop",
      "Low": "कमी",
      "Lucknow": "Lucknow",
      "M": "M",
      "MIC": "MIC",
      "MPPT": "MPPT",
      "MQTT": "MQTT",
      "MR": "MR",
      "Madurai": "Madurai",
      "Maharashtra": "Maharashtra",
      "Mandate": "उद्दिष्ट",
      "Manifest": "Manifest",
      "Manifests": "Manifests",
      "Marathi": "Marathi",
      "Match": "Match",
      "Matched": "Matched",
      "Matching": "मॅचिंग",
      "Matchmaker": "Matchmaker",
      "Matchmaking": "Matchmaking",
      "Matrix": "मॅट्रिक्स",
      "Measured": "Measured",
      "Measures": "Measures",
      "Memo": "Memo",
      "Mentor": "मार्गदर्शक",
      "Mentors": "मार्गदर्शक",
      "Mentorship": "मार्गदर्शन",
      "Mesh": "जाळे",
      "Metrics": "मेट्रिक्स",
      "Micro": "Micro",
      "Microgrids": "मायक्रोग्रिड्स",
      "Milestone": "टप्पा",
      "Milestones": "टप्पे",
      "Ministry": "मंत्रालय",
      "Mission": "उद्दिष्ट",
      "Mobile": "Mobile",
      "Mode": "Mode",
      "Model": "मॉडेल",
      "Moderate": "मध्यम",
      "Modules": "मॉड्यूल्स",
      "Monitor": "Monitor",
      "Multi": "Multi",
      "Multiple": "Multiple",
      "Mumbai": "Mumbai",
      "Municipal": "नगरपालिका",
      "Municipalities": "नगरपालिका",
      "My": "My",
      "Mysuru": "Mysuru",
      "N": "N",
      "NGO": "एनजीओ",
      "NIC": "NIC",
      "NIRF": "NIRF",
      "NLP": "NLP",
      "NOC": "NOC",
      "NOCs": "NOCs",
      "NTU": "NTU",
      "Nadu": "Nadu",
      "Nagpur": "Nagpur",
      "National": "राष्ट्रीय",
      "Nationwide": "Nationwide",
      "Network": "नेटवर्क",
      "Neural": "न्यूरल",
      "New": "नवीन",
      "Nodes": "नोड्स",
      "Nov": "Nov",
      "Now": "Now",
      "OTA": "OTA",
      "Observed": "Observed",
      "Oct": "Oct",
      "Onboarded": "जोडले",
      "Online": "ऑनलाइन",
      "Open": "उघडा",
      "Orchestrated": "आर्केस्ट्रेट",
      "Orchestrator": "आर्केस्ट्रेटर",
      "Our": "आमचे",
      "Overview": "आढावा",
      "PCB": "PCB",
      "PHASE": "PHASE",
      "PHC": "PHC",
      "Panchayat": "पंचायत",
      "Paper": "कागद",
      "Parnavi": "पर्णवी",
      "Partner": "भागीदार",
      "Partnered": "Partnered",
      "Partnerships": "भागीदारी",
      "Pass": "Pass",
      "Patent": "पेटंट",
      "Pending": "प्रलंबित",
      "Permits": "Permits",
      "Persona": "Persona",
      "Perspective": "Perspective",
      "Pest": "Pest",
      "Phase": "टप्पा",
      "Photo": "Photo",
      "Pillar": "स्तंभ",
      "Pillars": "स्तंभ",
      "Pilot": "पायलट",
      "Pilots": "पायलट्स",
      "Pioneered": "Pioneered",
      "Pipeline": "पाइपलाइन",
      "Pitch": "Pitch",
      "Platform": "प्लॅटफॉर्म",
      "Point": "Point",
      "Points": "Points",
      "Population": "लोकसंख्या",
      "Portal": "पोर्टल",
      "Portals": "पोर्टल्स",
      "Positions": "Positions",
      "Pradesh": "Pradesh",
      "Present": "Present",
      "Priority": "प्राधान्य",
      "Privacy": "Privacy",
      "Problem": "समस्या",
      "Problems": "समस्या",
      "Profile": "प्रोफाइल",
      "Progress": "प्रगती",
      "Projects": "प्रकल्प",
      "Proof": "पुरावा",
      "Proposed": "प्रस्तावित",
      "Prototype": "प्रोटोटाइप",
      "Prototypes": "प्रोटोटाइप",
      "Prototyping": "Prototyping",
      "Provisional": "Provisional",
      "Public": "सार्वजनिक",
      "Publication": "प्रकाशन",
      "Pulse": "Pulse",
      "Pune": "Pune",
      "Purity": "शुद्धता",
      "Purpose": "उद्देश",
      "Python": "Python",
      "Q": "Q",
      "Queries": "Queries",
      "Questions": "Questions",
      "Quick": "Quick",
      "R": "R",
      "RFPs": "RFPs",
      "ROI": "ROI",
      "ROS": "ROS",
      "Rails": "Rails",
      "Rate": "दर",
      "Ready": "Ready",
      "Real": "प्रत्यक्ष",
      "Realtime": "Realtime",
      "Receive": "Receive",
      "Recommended": "शिफारस केलेले",
      "Registry": "Registry",
      "Report": "अहवाल",
      "Reported": "Reported",
      "Reporting": "अहवाल",
      "Reports": "अहवाल",
      "Requests": "Requests",
      "Research": "संशोधन",
      "Reserved": "Reserved",
      "Resolution": "निराकरण",
      "Resolutions": "Resolutions",
      "Resolved": "सोडवले",
      "Resources": "संसाधने",
      "Retain": "Retain",
      "Return": "Return",
      "Returns": "Returns",
      "Review": "पुनरावलोकन",
      "Rights": "हक्क",
      "Role": "भूमिका",
      "Rollout": "Rollout",
      "Routing": "Routing",
      "Rural": "ग्रामीण",
      "S": "S",
      "SDG": "SDG",
      "SIH": "SIH",
      "SLA": "SLA",
      "SMS": "SMS",
      "SPRINT": "SPRINT",
      "SROI": "SROI",
      "Safe": "Safe",
      "Safety": "सुरक्षितता",
      "Sample": "नमुना",
      "Sandbox": "Sandbox",
      "Sanitation": "स्वच्छता",
      "Scaling": "विस्तार",
      "Score": "गुण",
      "Scoring": "स्कोअरिंग",
      "Search": "शोधा",
      "Searches": "Searches",
      "Sec": "Sec",
      "Section": "कलम",
      "Sector": "Sector",
      "Security": "सुरक्षा",
      "Select": "निवडा",
      "Semantic": "सिमँटिक",
      "Sensor": "सेन्सर",
      "Sensors": "सेन्सर्स",
      "Severe": "Severe",
      "Severity": "तीव्रता",
      "Shared": "Shared",
      "Shirgudi": "Shirgudi",
      "Shlok": "Shlok",
      "Shortcuts": "Shortcuts",
      "Shrutesh": "Shrutesh",
      "Signoff": "स्वाक्षरी",
      "Single": "एक",
      "Skills": "कौशल्ये",
      "Smart": "स्मार्ट",
      "Social": "सामाजिक",
      "Society": "Society",
      "Soil": "माती",
      "Solar": "सौर",
      "Solution": "उपाय",
      "Solutions": "उपाय",
      "Solved": "सोडवले",
      "Solver": "निवारक",
      "Solvers": "निवारक",
      "Sourcing": "Sourcing",
      "Sovereign": "संप्रभु",
      "Spanning": "Spanning",
      "Spatial": "स्थानिक",
      "Specific": "Specific",
      "Spectrometer": "Spectrometer",
      "Spectrophotometer": "Spectrophotometer",
      "Sponsors": "Sponsors",
      "Sponsorship": "Sponsorship",
      "Sprint": "स्प्रिंट",
      "Squad": "पथक",
      "Stage": "टप्पा",
      "Stakeholder": "भागधारक",
      "Stakeholders": "भागधारक",
      "State": "राज्य",
      "Statement": "विधान",
      "Status": "स्थिती",
      "Stipend": "विद्यावेतन",
      "Stipends": "विद्यावेतन",
      "Stream": "प्रवाह",
      "Student": "विद्यार्थी",
      "Students": "विद्यार्थी",
      "Sub": "Sub",
      "Submit": "सबमिट करा",
      "Submitted": "सादर केलेले",
      "Suite": "Suite",
      "Summary": "सारांश",
      "Supervised": "Supervised",
      "Supported": "Supported",
      "Surat": "Surat",
      "Sustainable": "Sustainable",
      "Switcher": "Switcher",
      "Sync": "Sync",
      "Synced": "Synced",
      "System": "प्रणाली",
      "Systems": "प्रणाली",
      "TEAM": "TEAM",
      "Tagged": "Tagged",
      "Tagging": "Tagging",
      "Talent": "Talent",
      "Tamil": "Tamil",
      "Tanmay": "Tanmay",
      "Target": "उद्दिष्ट",
      "Tata": "Tata",
      "Tax": "Tax",
      "Team": "टीम",
      "Teams": "टीम्स",
      "Technological": "Technological",
      "Technology": "तंत्रज्ञान",
      "Telemetry": "टेलिमेट्री",
      "Test": "चाचणी",
      "Testbed": "Testbed",
      "Testing": "चाचणी",
      "The": "The",
      "Theme": "Theme",
      "Ticket": "तिकीट",
      "Tier": "Tier",
      "Time": "वेळ",
      "Timeline": "वेळापत्रक",
      "Title": "शीर्षक",
      "To": "कडे",
      "Toggle": "Toggle",
      "Trace": "Trace",
      "Track": "Track",
      "Tracking": "ट्रॅकिंग",
      "Tranche": "Tranche",
      "Transform": "रूपांतरित करणे",
      "Transforms": "Transforms",
      "Translate": "Translate",
      "Transmitting": "प्रेषित होत आहे",
      "Trusts": "Trusts",
      "Turbidity": "गढूळपणा",
      "Turn": "Turn",
      "UN": "UN",
      "UV": "UV",
      "Unified": "एकत्रित",
      "Unit": "युनिट",
      "Universities": "विद्यापीठे",
      "University": "विद्यापीठ",
      "Unlock": "Unlock",
      "Upon": "Upon",
      "Urban": "शहरी",
      "Urgency": "तातडी",
      "Us": "Us",
      "Uttar": "Uttar",
      "VJTI": "VJTI",
      "VSIT": "VSIT",
      "Vacant": "Vacant",
      "Vaccine": "Vaccine",
      "Vadodara": "Vadodara",
      "Valid": "Valid",
      "Validated": "Validated",
      "Validation": "पडताळणी",
      "Value": "मूल्य",
      "Varanasi": "Varanasi",
      "Vector": "व्हेक्टर",
      "Veermata": "Veermata",
      "Velocity": "वेग",
      "Verification": "पडताळणी",
      "Verified": "सत्यापित",
      "Verifiers": "पडताळणीकर्ते",
      "Verifies": "Verifies",
      "Vernacular": "Vernacular",
      "Vidarbha": "Vidarbha",
      "Vidyalankar": "Vidyalankar",
      "View": "View",
      "Village": "गाव",
      "Vision": "ध्येय",
      "W": "W",
      "Warning": "Warning",
      "Waste": "Waste",
      "Water": "पाणी",
      "We": "We",
      "WhatsApp": "WhatsApp",
      "When": "When",
      "Wide": "Wide",
      "Window": "Window",
      "Work": "काम",
      "Works": "Works",
      "Workspace": "कार्यक्षेत्र",
      "Workspaces": "Workspaces",
      "World": "जग",
      "YOLOv": "YOLOv",
      "Yes": "Yes",
      "Your": "Your",
      "Zero": "शून्य",
      "a": "a",
      "about": "about",
      "academic": "academic",
      "access": "access",
      "account": "account",
      "accounts": "accounts",
      "accredited": "accredited",
      "across": "across",
      "actionable": "actionable",
      "active": "active",
      "activism": "activism",
      "activity": "activity",
      "actual": "actual",
      "add": "add",
      "administration": "administration",
      "affecting": "affecting",
      "after": "after",
      "ago": "ago",
      "ai": "ai",
      "align": "align",
      "alignment": "alignment",
      "allocation": "allocation",
      "allowing": "allowing",
      "alt": "alt",
      "an": "an",
      "analytics": "analytics",
      "and": "आणि",
      "anything": "anything",
      "apartment": "apartment",
      "approvals": "approvals",
      "approved": "approved",
      "architecture": "architecture",
      "are": "are",
      "arrow": "arrow",
      "arsenic": "arsenic",
      "assemble": "assemble",
      "at": "at",
      "audit": "audit",
      "auditable": "auditable",
      "audits": "audits",
      "authorize": "authorize",
      "automated": "automated",
      "automatically": "automatically",
      "backed": "backed",
      "backing": "backing",
      "badge": "badge",
      "balance": "balance",
      "based": "based",
      "before": "before",
      "belts": "belts",
      "benefits": "benefits",
      "better": "better",
      "between": "between",
      "biotech": "biotech",
      "blocks": "blocks",
      "boards": "boards",
      "bodies": "bodies",
      "body": "body",
      "bollworm": "bollworm",
      "bolt": "bolt",
      "boost": "boost",
      "bots": "bots",
      "bottlenecks": "bottlenecks",
      "bridge": "bridge",
      "built": "built",
      "bureaucratic": "bureaucratic",
      "by": "द्वारे",
      "calculated": "calculated",
      "calibration": "calibration",
      "calls": "calls",
      "can": "can",
      "capital": "capital",
      "capstone": "capstone",
      "cases": "cases",
      "cellular": "cellular",
      "certificates": "certificates",
      "certify": "certify",
      "challenge": "challenge",
      "challenges": "challenges",
      "chapters": "chapters",
      "chart": "chart",
      "check": "check",
      "checked": "checked",
      "chevron": "chevron",
      "circle": "circle",
      "citizen": "citizen",
      "citizens": "citizens",
      "city": "city",
      "civic": "civic",
      "civil": "civil",
      "clearance": "clearance",
      "clearances": "clearances",
      "clearly": "clearly",
      "clock": "clock",
      "close": "close",
      "cloud": "cloud",
      "co": "co",
      "code": "code",
      "cohorts": "cohorts",
      "college": "college",
      "colleges": "colleges",
      "collegiate": "collegiate",
      "community": "community",
      "completed": "completed",
      "completes": "completes",
      "compliance": "compliance",
      "compute": "compute",
      "computer": "computer",
      "concentration": "concentration",
      "connecting": "connecting",
      "contact": "contact",
      "contracts": "contracts",
      "contributions": "contributions",
      "core": "core",
      "corporate": "corporate",
      "cost": "cost",
      "cotton": "cotton",
      "counselors": "counselors",
      "count": "count",
      "credential": "credential",
      "credit": "credit",
      "credits": "credits",
      "cross": "cross",
      "cryptographic": "cryptographic",
      "curriculum": "curriculum",
      "customize": "customize",
      "cutting": "cutting",
      "dark": "dark",
      "dashboard": "dashboard",
      "dashboards": "dashboards",
      "data": "data",
      "database": "database",
      "datasets": "datasets",
      "day": "day",
      "deduplication": "deduplication",
      "degree": "degree",
      "delay": "delay",
      "delivery": "delivery",
      "density": "density",
      "department": "department",
      "departmental": "departmental",
      "departments": "departments",
      "deployed": "deployed",
      "deployment": "deployment",
      "deployments": "deployments",
      "detection": "detection",
      "device": "device",
      "digital": "digital",
      "direct": "direct",
      "directly": "directly",
      "disburse": "disburse",
      "disbursed": "disbursed",
      "disciplinary": "disciplinary",
      "disciplines": "disciplines",
      "dispatch": "dispatch",
      "dispatches": "dispatches",
      "distress": "distress",
      "district": "district",
      "districts": "districts",
      "diversity": "diversity",
      "do": "do",
      "domain": "domain",
      "drafting": "drafting",
      "drainage": "drainage",
      "driven": "driven",
      "driving": "driving",
      "drone": "drone",
      "drop": "drop",
      "duplicate": "duplicate",
      "during": "during",
      "e": "e",
      "earn": "earn",
      "edge": "edge",
      "edu": "edu",
      "efficacy": "efficacy",
      "efficiency": "efficiency",
      "electrical": "electrical",
      "electro": "electro",
      "eliminate": "eliminate",
      "email": "email",
      "encryption": "encryption",
      "endorsed": "endorsed",
      "engine": "engine",
      "engineering": "engineering",
      "engineers": "engineers",
      "enterprise": "enterprise",
      "entries": "entries",
      "escrow": "escrow",
      "establish": "establish",
      "every": "every",
      "exchequer": "exchequer",
      "execution": "execution",
      "executive": "executive",
      "exemption": "exemption",
      "expand": "expand",
      "experience": "experience",
      "expertise": "expertise",
      "extraction": "extraction",
      "fabrication": "fabrication",
      "facilities": "facilities",
      "factory": "factory",
      "faculty": "faculty",
      "fare": "fare",
      "feed": "feed",
      "feedback": "feedback",
      "field": "field",
      "filtration": "filtration",
      "final": "final",
      "finance": "finance",
      "flag": "flag",
      "flash": "flash",
      "flocculation": "flocculation",
      "following": "following",
      "for": "साठी",
      "formalized": "formalized",
      "formatted": "formatted",
      "forward": "forward",
      "foundation": "foundation",
      "frameworks": "frameworks",
      "frictionless": "frictionless",
      "from": "पासून",
      "full": "full",
      "funding": "funding",
      "funds": "funds",
      "g": "g",
      "gap": "gap",
      "gastroenteritis": "gastroenteritis",
      "gavel": "gavel",
      "generated": "generated",
      "geofenced": "geofenced",
      "geotags": "geotags",
      "get": "get",
      "go": "go",
      "gov": "gov",
      "governance": "governance",
      "governed": "governed",
      "government": "government",
      "grade": "grade",
      "grading": "grading",
      "graduates": "graduates",
      "graduation": "graduation",
      "grant": "grant",
      "grants": "grants",
      "grew": "grew",
      "grid": "grid",
      "ground": "ground",
      "grounds": "grounds",
      "group": "group",
      "guide": "guide",
      "guidelines": "guidelines",
      "guides": "guides",
      "h": "h",
      "handshake": "handshake",
      "hardware": "hardware",
      "heads": "heads",
      "healthcare": "healthcare",
      "held": "held",
      "help": "help",
      "high": "high",
      "hire": "hire",
      "history": "history",
      "home": "home",
      "hours": "hours",
      "how": "how",
      "hub": "hub",
      "impact": "impact",
      "in": "मध्ये",
      "indexing": "indexing",
      "industrial": "industrial",
      "industry": "industry",
      "info": "info",
      "innovation": "innovation",
      "innovators": "innovators",
      "inspector": "inspector",
      "installation": "installation",
      "instant": "instant",
      "institution": "institution",
      "institutional": "institutional",
      "integrated": "integrated",
      "intellectual": "intellectual",
      "intelligence": "intelligence",
      "interdisciplinary": "interdisciplinary",
      "into": "into",
      "inventory": "inventory",
      "investment": "investment",
      "is": "is",
      "issue": "issue",
      "issued": "issued",
      "issues": "issues",
      "join": "join",
      "joining": "joining",
      "kWh": "kWh",
      "km": "km",
      "know": "know",
      "lab": "lab",
      "labs": "labs",
      "launch": "launch",
      "leadership": "leadership",
      "legal": "legal",
      "less": "less",
      "level": "level",
      "library": "library",
      "lightbulb": "lightbulb",
      "linear": "linear",
      "linkage": "linkage",
      "linking": "linking",
      "liters": "liters",
      "live": "live",
      "local": "local",
      "location": "location",
      "lock": "lock",
      "locked": "locked",
      "loops": "loops",
      "m": "m",
      "making": "making",
      "managed": "managed",
      "mandatory": "mandatory",
      "manifests": "manifests",
      "map": "map",
      "mapped": "mapped",
      "mapping": "mapping",
      "matched": "matched",
      "matches": "matches",
      "matching": "matching",
      "measuring": "measuring",
      "mentor": "mentor",
      "mentorship": "mentorship",
      "menu": "menu",
      "mesh": "mesh",
      "metrics": "metrics",
      "micro": "micro",
      "milestone": "milestone",
      "ministerial": "ministerial",
      "mins": "mins",
      "mission": "mission",
      "mode": "mode",
      "models": "models",
      "month": "month",
      "more": "more",
      "ms": "ms",
      "multi": "multi",
      "multidisciplinary": "multidisciplinary",
      "municipal": "municipal",
      "national": "national",
      "nationwide": "nationwide",
      "need": "need",
      "neurology": "neurology",
      "new": "new",
      "no": "no",
      "nodes": "nodes",
      "notes": "notes",
      "notifications": "notifications",
      "observed": "observed",
      "of": "चे",
      "officers": "officers",
      "official": "official",
      "on": "on",
      "only": "only",
      "open": "open",
      "or": "or",
      "over": "over",
      "overflow": "overflow",
      "pair": "pair",
      "paired": "paired",
      "panchayats": "panchayats",
      "paper": "paper",
      "papers": "papers",
      "partners": "partners",
      "patent": "patent",
      "patents": "patents",
      "payments": "payments",
      "payout": "payout",
      "pending": "pending",
      "perform": "perform",
      "permit": "permit",
      "permits": "permits",
      "person": "person",
      "photos": "photos",
      "physical": "physical",
      "pie": "pie",
      "pillars": "pillars",
      "pilot": "pilot",
      "pilots": "pilots",
      "pin": "pin",
      "ping": "ping",
      "pink": "pink",
      "plants": "plants",
      "platform": "platform",
      "points": "points",
      "policy": "policy",
      "population": "population",
      "portal": "portal",
      "powered": "powered",
      "ppm": "ppm",
      "premium": "premium",
      "present": "present",
      "primary": "primary",
      "priority": "priority",
      "problem": "problem",
      "problems": "problems",
      "professors": "professors",
      "proficiencies": "proficiencies",
      "progress": "progress",
      "project": "project",
      "projects": "projects",
      "proof": "proof",
      "proofs": "proofs",
      "property": "property",
      "proposals": "proposals",
      "prototype": "prototype",
      "prototypes": "prototypes",
      "prototyping": "prototyping",
      "provisional": "provisional",
      "psychology": "psychology",
      "public": "public",
      "purchasing": "purchasing",
      "purified": "purified",
      "pushed": "pushed",
      "quantified": "quantified",
      "query": "query",
      "radius": "radius",
      "rails": "rails",
      "rankings": "rankings",
      "rapid": "rapid",
      "rating": "rating",
      "ratings": "ratings",
      "ready": "ready",
      "real": "real",
      "receipt": "receipt",
      "receipts": "receipts",
      "receive": "receive",
      "recent": "recent",
      "recognition": "recognition",
      "recovered": "recovered",
      "reference": "reference",
      "regional": "regional",
      "register": "register",
      "regulatory": "regulatory",
      "released": "released",
      "releases": "releases",
      "reliability": "reliability",
      "removes": "removes",
      "report": "report",
      "reported": "reported",
      "reporting": "reporting",
      "reports": "reports",
      "research": "research",
      "reserved": "reserved",
      "residents": "residents",
      "resolution": "resolution",
      "resolved": "resolved",
      "resolving": "resolving",
      "results": "results",
      "return": "return",
      "review": "review",
      "right": "right",
      "rights": "rights",
      "road": "road",
      "roads": "roads",
      "rocket": "rocket",
      "route": "route",
      "routing": "routing",
      "runoff": "runoff",
      "rural": "rural",
      "s": "s",
      "safely": "safely",
      "safety": "safety",
      "sampling": "sampling",
      "sandboxes": "sandboxes",
      "sarpanches": "sarpanches",
      "satisfaction": "satisfaction",
      "saved": "saved",
      "scale": "scale",
      "scans": "scans",
      "school": "school",
      "science": "science",
      "scientist": "scientist",
      "scoring": "scoring",
      "seamlessly": "seamlessly",
      "search": "search",
      "sector": "sector",
      "semantic": "semantic",
      "send": "send",
      "sensor": "sensor",
      "sensors": "sensors",
      "server": "server",
      "shield": "shield",
      "shortlisted": "shortlisted",
      "signoff": "signoff",
      "single": "single",
      "skills": "skills",
      "slots": "slots",
      "smallholder": "smallholder",
      "smart": "smart",
      "social": "social",
      "societal": "societal",
      "society": "society",
      "solar": "solar",
      "solution": "solution",
      "solutions": "solutions",
      "solve": "solve",
      "solving": "solving",
      "sovereign": "sovereign",
      "space": "space",
      "spatial": "spatial",
      "specs": "specs",
      "speed": "speed",
      "sprint": "sprint",
      "squads": "squads",
      "stability": "stability",
      "stakeholder": "stakeholder",
      "state": "state",
      "statement": "statement",
      "statements": "statements",
      "states": "states",
      "stats": "stats",
      "statutory": "statutory",
      "stipends": "stipends",
      "storage": "storage",
      "stream": "stream",
      "structured": "structured",
      "student": "student",
      "sub": "sub",
      "submissions": "submissions",
      "submit": "submit",
      "support": "support",
      "supporting": "supporting",
      "surface": "surface",
      "sustainable": "sustainable",
      "tagged": "tagged",
      "tagging": "tagging",
      "tailored": "tailored",
      "takes": "takes",
      "talent": "talent",
      "target": "target",
      "task": "task",
      "team": "team",
      "teams": "teams",
      "tech": "tech",
      "technologies": "technologies",
      "technology": "technology",
      "tedious": "tedious",
      "telemetry": "telemetry",
      "temperature": "temperature",
      "test": "test",
      "tested": "tested",
      "testing": "testing",
      "text": "text",
      "than": "than",
      "that": "that",
      "the": "the",
      "their": "their",
      "them": "them",
      "theses": "theses",
      "this": "this",
      "tickets": "tickets",
      "tier": "tier",
      "time": "time",
      "timeline": "timeline",
      "to": "कडे",
      "today": "today",
      "tokens": "tokens",
      "toolkits": "toolkits",
      "top": "top",
      "toward": "toward",
      "tracking": "tracking",
      "tracks": "tracks",
      "traditional": "traditional",
      "transcript": "transcript",
      "transformation": "transformation",
      "translate": "translate",
      "transparent": "transparent",
      "tree": "tree",
      "trending": "trending",
      "tribal": "tribal",
      "turbidity": "turbidity",
      "turn": "turn",
      "turning": "turning",
      "unanswered": "unanswered",
      "under": "under",
      "unify": "unify",
      "unifying": "unifying",
      "unit": "unit",
      "universities": "universities",
      "university": "university",
      "unlock": "unlock",
      "up": "up",
      "updates": "updates",
      "upgrade": "upgrade",
      "upload": "upload",
      "upon": "upon",
      "uptime": "uptime",
      "upward": "upward",
      "urban": "urban",
      "urgency": "urgency",
      "user": "user",
      "using": "using",
      "utilization": "utilization",
      "v": "v",
      "validate": "validate",
      "validated": "validated",
      "vector": "vector",
      "vectors": "vectors",
      "velocities": "velocities",
      "verifiable": "verifiable",
      "verification": "verification",
      "verified": "verified",
      "verifies": "verifies",
      "vernacular": "vernacular",
      "via": "via",
      "view": "view",
      "village": "village",
      "visibility": "visibility",
      "voice": "voice",
      "volume": "volume",
      "volunteer": "volunteer",
      "vs": "vs",
      "ward": "ward",
      "water": "water",
      "web": "web",
      "wells": "wells",
      "where": "where",
      "who": "who",
      "window": "window",
      "wings": "wings",
      "with": "सह",
      "within": "within",
      "without": "without",
      "work": "work",
      "workflow": "workflow",
      "workflows": "workflows",
      "working": "working",
      "works": "works",
      "workspace": "workspace",
      "world": "जग",
      "x": "x",
      "year": "year",
      "yesterday": "yesterday",
      "you": "you",
      "your": "your",
      "zero": "zero",
      "our": "आमचे",
      "purpose": "उद्देश",
      "mandate": "उद्दिष्ट",
      "For": "साठी",
      "By": "द्वारे",
      "And": "आणि",
      "With": "सह",
      "Of": "चे"
}
  };

            function toggleLangDropdown() {
      const dropdown = document.getElementById('lang-dropdown');
      if (dropdown) dropdown.classList.toggle('hidden');
    }

                            function setLanguage(langKey) {
      localStorage.setItem('c2c-lang', langKey);
      
      const codeEl = document.getElementById('current-lang-code');
      if (codeEl) codeEl.innerText = langKey.toUpperCase();

      // 1. Translate data-i18n elements if present
      if (typeof c2cTranslations !== 'undefined' && c2cTranslations[langKey]) {
        const langMap = c2cTranslations[langKey];
        document.querySelectorAll('[data-i18n]').forEach(el => {
          const key = el.getAttribute('data-i18n');
          if (langMap[key]) {
            el.innerText = langMap[key];
          }
        });
      }

      // 2. Phrase & Word Replacement Engine using literal replaceAll
      const phraseDict = typeof c2cTextDictionary !== 'undefined' ? (c2cTextDictionary[langKey] || {}) : {};
      const wordDict = typeof c2cWordDictionary !== 'undefined' ? (c2cWordDictionary[langKey] || {}) : {};
      
      const sortedPhraseKeys = Object.keys(phraseDict).sort((a, b) => b.length - a.length);
      const sortedWordKeys = Object.keys(wordDict).sort((a, b) => b.length - a.length);

      const translateString = (orig) => {
        if (!orig) return orig;
        let val = orig;

        // Phase 1: Literal Phrase Replacement
        for (const enKey of sortedPhraseKeys) {
          if (!enKey) continue;
          if (val.includes(enKey)) {
            const transVal = phraseDict[enKey];
            val = val.replaceAll(enKey, transVal);
          }
        }

        // Phase 2: Fallback Word Replacement for any remaining English words
        if (langKey !== 'en' && /[A-Za-z]{2,}/.test(val)) {
          for (const enWord of sortedWordKeys) {
            if (!enWord || enWord.length < 2) continue;
            if (val.includes(enWord)) {
              const transWord = wordDict[enWord];
              val = val.replaceAll(enWord, transWord);
            }
          }
        }

        return val;
      };

      const walkNode = (node) => {
        if (node.nodeType === Node.TEXT_NODE) {
          const trimmed = node.nodeValue.trim();
          if (!trimmed) return;

          // Skip icon text nodes
          if (node.parentNode && node.parentNode.classList && (
            node.parentNode.classList.contains('material-symbols-outlined') ||
            node.parentNode.classList.contains('material-icons') ||
            node.parentNode.classList.contains('material-icons-outlined') ||
            node.parentNode.classList.contains('fa')
          )) {
            return;
          }
          
          if (!node._origText) {
            node._origText = node.nodeValue;
          }
          
          if (langKey === 'en') {
            node.nodeValue = node._origText;
          } else {
            node.nodeValue = translateString(node._origText);
          }
        } else if (node.nodeType === Node.ELEMENT_NODE) {
          if (['SCRIPT', 'STYLE', 'NOSCRIPT', 'SVG', 'CODE'].includes(node.tagName)) return;

          if (node.classList && (
            node.classList.contains('material-symbols-outlined') ||
            node.classList.contains('material-icons') ||
            node.classList.contains('material-icons-outlined') ||
            node.classList.contains('fa')
          )) {
            return;
          }
          
          ['placeholder', 'title', 'alt'].forEach(attr => {
            if (node.hasAttribute(attr)) {
              if (!node['orig_' + attr]) {
                node['orig_' + attr] = node.getAttribute(attr);
              }
              if (langKey === 'en') {
                node.setAttribute(attr, node['orig_' + attr]);
              } else {
                node.setAttribute(attr, translateString(node['orig_' + attr]));
              }
            }
          });

          if (['INPUT', 'BUTTON'].includes(node.tagName) && node.value) {
            if (!node._origValue) {
              node._origValue = node.value;
            }
            if (langKey === 'en') {
              node.value = node._origValue;
            } else {
              node.value = translateString(node._origValue);
            }
          }

          for (let child of node.childNodes) {
            walkNode(child);
          }
        }
      };

      walkNode(document.body);

      const dropdown = document.getElementById('lang-dropdown');
      if (dropdown && !dropdown.classList.contains('hidden')) {
        dropdown.classList.add('hidden');
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      const savedLang = localStorage.getItem('c2c-lang') || 'en';
      setLanguage(savedLang);
      
      document.addEventListener('click', (e) => {
        const btn = document.getElementById('lang-toggle-btn');
        const dropdown = document.getElementById('lang-dropdown');
        if (btn && dropdown && !btn.contains(e.target) && !dropdown.contains(e.target)) {
          dropdown.classList.add('hidden');
        }
      });
    });
  </script>
</body>
</html>