<?php
require_once __DIR__ . '/db.php';

// Handle Logout Action
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logoutUser();
}

$error_msg = null;
$success_msg = null;

// Handle Form Submission (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aadhaar = trim($_POST['aadhaar_number'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = trim($_POST['persona_role'] ?? 'student');

    if (empty($aadhaar)) {
        $error_msg = "Please enter a valid 12-digit Aadhaar Card Number.";
    } else {
        try {
            $user = authenticateUser($aadhaar, $password);
            if ($user) {
                $_SESSION['user'] = $user;
                header("Location: index.php?status=logged_in");
                exit();
            } else {
                $error_msg = "Authentication failed. Please verify credentials.";
            }
        } catch (Exception $e) {
            $error_msg = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Sign In & Portal Access | CAMPUS2COMMUNITY (C2C)</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            brand: {
              pastel: "#CAE7F7",
              tint: "#EBF5FC",
              ice: "#F0F7FD",
              accent: "#38BDF8",
              primary: "#0284C7",
              deep: "#0369A1"
            }
          },
          fontFamily: {
            headline: ["Plus Jakarta Sans", "sans-serif"],
            body: ["Inter", "sans-serif"]
          }
        }
      }
    };
  </script>

  <style>
    .glass-sky {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.85) 0%, rgba(240, 247, 253, 0.7) 100%) !important;
      backdrop-filter: blur(28px) saturate(210%);
      -webkit-backdrop-filter: blur(28px) saturate(210%);
      border: 1.5px solid rgba(255, 255, 255, 0.95) !important;
      box-shadow: 0 20px 50px 0 rgba(2, 132, 199, 0.14);
    }
    .dark .glass-sky {
      background: linear-gradient(135deg, rgba(15, 28, 48, 0.9) 0%, rgba(7, 17, 31, 0.8) 100%) !important;
      border: 1.5px solid rgba(56, 189, 248, 0.45) !important;
      box-shadow: 0 20px 50px 0 rgba(0, 0, 0, 0.7);
    }
    .persona-tab.active {
      background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);
      color: #ffffff !important;
      box-shadow: 0 4px 14px 0 rgba(2, 132, 199, 0.35);
    }
  </style>
</head>
<body class="bg-brand-ice dark:bg-[#070D18] text-slate-800 dark:text-slate-100 min-h-screen flex flex-col justify-between relative selection:bg-brand-pastel">

  <canvas id="login-bg-canvas" class="fixed inset-0 w-full h-full pointer-events-none z-0 opacity-60 dark:opacity-40"></canvas>

  <?php include __DIR__ . '/includes/header.php'; ?>

  <!-- Main Content: Login Form Card -->
  <main class="relative z-10 my-auto py-12 px-4 flex items-center justify-center">
    <div class="w-full max-w-xl glass-sky rounded-3xl p-6 sm:p-10 shadow-2xl relative overflow-hidden">
      
      <div class="text-center mb-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-pastel/80 dark:bg-sky-900/60 text-brand-deep dark:text-sky-300 text-[11px] font-bold uppercase tracking-wider mb-3">
          <span class="material-symbols-outlined text-[15px]">verified_user</span>
          <span>Sovereign Identity Authentication</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
          Sign In
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 mt-2">
          Access your personalized dashboard based on your registered ecosystem persona.
        </p>
      </div>

      <?php if ($error_msg): ?>
        <div class="mb-5 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-xs font-semibold text-rose-700 dark:text-rose-300 flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">error</span>
          <span><?= htmlspecialchars($error_msg) ?></span>
        </div>
      <?php endif; ?>

      <!-- Persona Selector Tabs (3 Persona Modes) -->
      <div class="grid grid-cols-3 gap-1.5 p-1.5 rounded-2xl bg-white/70 dark:bg-slate-900/70 border border-[#D5E6F5] dark:border-slate-800 mb-8">
        <button type="button" onclick="selectPersona('student')" id="tab-student" class="persona-tab active py-2.5 px-2 rounded-xl text-center transition-all flex flex-col items-center gap-1">
          <span class="material-symbols-outlined text-[20px]">school</span>
          <span class="text-[11px] font-bold">Student</span>
        </button>
        <button type="button" onclick="selectPersona('govt')" id="tab-govt" class="persona-tab py-2.5 px-2 rounded-xl text-center text-slate-600 dark:text-slate-300 hover:text-brand-deep transition-all flex flex-col items-center gap-1">
          <span class="material-symbols-outlined text-[20px]">account_balance</span>
          <span class="text-[11px] font-bold">Govt / ULB</span>
        </button>
        <button type="button" onclick="selectPersona('csr')" id="tab-csr" class="persona-tab py-2.5 px-2 rounded-xl text-center text-slate-600 dark:text-slate-300 hover:text-brand-deep transition-all flex flex-col items-center gap-1">
          <span class="material-symbols-outlined text-[20px]">corporate_fare</span>
          <span class="text-[11px] font-bold">CSR Partner</span>
        </button>
      </div>

      <!-- Login Form (PHP POST Action) -->
      <form action="login.php" method="POST" class="space-y-5">
        <input type="hidden" name="persona_role" id="persona-role-input" value="student"/>

        <!-- Persona Label Helper -->
        <div id="persona-badge-info" class="p-3 rounded-xl bg-brand-tint/80 dark:bg-slate-800/80 border border-brand-pastel dark:border-slate-700 flex items-center gap-2.5 text-xs text-brand-deep dark:text-sky-300 font-semibold">
          <span class="material-symbols-outlined text-[18px]">fingerprint</span>
          <span id="persona-description">Student Innovator Mode: Login with 12-digit Aadhaar Card Number linked to DigiLocker / DigiEdu.</span>
        </div>

        <!-- Aadhaar Number Field -->
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5" id="label-username">
            12-Digit Aadhaar Card Number
          </label>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">badge</span>
            <input type="text" name="aadhaar_number" id="login-aadhaar" maxlength="14" inputmode="numeric" required placeholder="9022 6343 3612" class="w-full pl-11 pr-4 py-3 rounded-xl bg-white/90 dark:bg-slate-900/90 border border-[#CBD5E1] dark:border-slate-700 text-sm font-medium text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-primary transition-all"/>
          </div>
        </div>

        <!-- Password Field -->
        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
              Security Password
            </label>
            <a href="#" onclick="alert('Password reset link dispatched to your registered Aadhaar phone number.')" class="text-xs font-semibold text-brand-primary hover:underline">
              Forgot Key?
            </a>
          </div>
          <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">lock</span>
            <input type="password" name="password" id="login-password" required placeholder="••••••••••••" class="w-full pl-11 pr-11 py-3 rounded-xl bg-white/90 dark:bg-slate-900/90 border border-[#CBD5E1] dark:border-slate-700 text-sm font-medium text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-primary transition-all"/>
            <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
              <span class="material-symbols-outlined text-[20px]" id="pwd-toggle-icon">visibility</span>
            </button>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" id="submit-btn" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-sky-600 to-brand-primary text-white font-headline text-sm font-bold shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 hover:scale-[1.01] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
          <span class="material-symbols-outlined text-[20px]">vpn_key</span>
          <span id="btn-login-text">Sign In</span>
        </button>

      </form>

      <div class="mt-6 text-center text-[11px] text-slate-400">
        Don't have an account? <a href="register.php" class="font-bold text-brand-primary hover:underline">Register New Sovereign Account</a>
      </div>

    </div>
  </main>

  <?php include __DIR__ . '/includes/footer.php'; ?>

  <script>
    // 1. Ambient Particle Canvas Animation
    const canvas = document.getElementById('login-bg-canvas');
    const ctx = canvas.getContext('2d');
    let width, height, particles = [];

    function resize() {
      width = canvas.width = window.innerWidth;
      height = canvas.height = window.innerHeight;
    }
    window.addEventListener('resize', resize);
    resize();

    for (let i = 0; i < 35; i++) {
      particles.push({
        x: Math.random() * width,
        y: Math.random() * height,
        vx: (Math.random() - 0.5) * 0.6,
        vy: (Math.random() - 0.5) * 0.6,
        radius: Math.random() * 2 + 1.5,
        color: i % 2 === 0 ? 'rgba(2, 132, 199, 0.4)' : 'rgba(56, 189, 248, 0.4)'
      });
    }

    function animate() {
      ctx.clearRect(0, 0, width, height);
      particles.forEach(p => {
        p.x += p.vx;
        p.y += p.vy;
        if (p.x < 0 || p.x > width) p.vx *= -1;
        if (p.y < 0 || p.y > height) p.vy *= -1;

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
        ctx.fillStyle = p.color;
        ctx.fill();
      });

      for (let i = 0; i < particles.length; i++) {
        for (let j = i + 1; j < particles.length; j++) {
          const dx = particles[i].x - particles[j].x;
          const dy = particles[i].y - particles[j].y;
          const dist = Math.sqrt(dx * dx + dy * dy);
          if (dist < 120) {
            ctx.beginPath();
            ctx.moveTo(particles[i].x, particles[i].y);
            ctx.lineTo(particles[j].x, particles[j].y);
            ctx.strokeStyle = `rgba(2, 132, 199, ${0.15 * (1 - dist / 120)})`;
            ctx.lineWidth = 0.8;
            ctx.stroke();
          }
        }
      }
      requestAnimationFrame(animate);
    }
    animate();

    // 2. Persona Switcher
    const personaData = {
      student: {
        label: "12-Digit Aadhaar Card Number",
        placeholder: "9022 6343 3612",
        desc: "Student Innovator Mode: Login with 12-digit Aadhaar Card Number linked to DigiLocker / DigiEdu."
      },
      govt: {
        label: "Aadhaar / Govt Officer ID",
        placeholder: "9137 0258 0712",
        desc: "Government & ULB Mode: Login with Aadhaar Card Number linked to MeriPehchaan National Officer Registry."
      },
      csr: {
        label: "Aadhaar / Escrow Signatory ID",
        placeholder: "8828 3594 0412",
        desc: "Corporate CSR Partner Mode: Login with Aadhaar Card Number linked to Corporate Section 135 Escrow Signatory."
      }
    };

    function selectPersona(role) {
      document.getElementById('persona-role-input').value = role;
      document.querySelectorAll('.persona-tab').forEach(tab => tab.classList.remove('active'));
      const activeTab = document.getElementById('tab-' + role);
      if (activeTab) activeTab.classList.add('active');

      const data = personaData[role];
      if (data) {
        document.getElementById('label-username').innerText = data.label;
        const inputEl = document.getElementById('login-aadhaar');
        if (inputEl) inputEl.placeholder = data.placeholder;
        document.getElementById('persona-description').innerText = data.desc;
      }
    }

    function togglePasswordVisibility() {
      const pwd = document.getElementById('login-password');
      const icon = document.getElementById('pwd-toggle-icon');
      if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.innerText = 'visibility_off';
      } else {
        pwd.type = 'password';
        icon.innerText = 'visibility';
      }
    }

    function toggleTheme() {
      const html = document.documentElement;
      const isDark = html.classList.contains('dark');
      if (isDark) {
        html.classList.remove('dark');
        html.classList.add('light');
        localStorage.setItem('c2c-theme', 'light');
      } else {
        html.classList.remove('light');
        html.classList.add('dark');
        localStorage.setItem('c2c-theme', 'dark');
      }
    }

    function toggleLangDropdown() {
      const dropdown = document.getElementById('lang-dropdown');
      if (dropdown) dropdown.classList.toggle('hidden');
    }
  </script>
</body>
</html>
