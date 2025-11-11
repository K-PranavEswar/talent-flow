<?php /** @var string $view */ ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TalentFlow | HR Automation Suite</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom App Styles -->
  <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/app.css">

  <style>
    /* 🌌 Smooth transition for theme */
    body {
      transition: background-color 0.5s, color 0.5s;
      font-family: 'Inter', system-ui, sans-serif;
    }

    /* 🌞 Light Mode */
    body[data-bs-theme="light"] {
      background: #f8f9fc;
      color: #212529;
    }

    /* 🌙 Galaxy Dark Mode */
    body[data-bs-theme="dark"] {
      background: radial-gradient(circle at top, #0b0824 0%, #06041c 80%);
      color: #e4e4f7;
    }

    /* Navbar - Galaxy Gradient */
    .navbar {
      background: linear-gradient(90deg, #3a0ca3, #7209b7, #560bad);
      box-shadow: 0 2px 15px rgba(130, 48, 255, 0.3);
    }

    .navbar-brand {
      font-weight: 700;
      color: #fff !important;
      letter-spacing: 0.5px;
      text-shadow: 0 0 10px rgba(255,255,255,0.3);
    }

    .navbar .nav-link {
      color: #d6c9ff !important;
      margin-right: 15px;
      font-weight: 500;
    }

    .navbar .nav-link:hover {
      color: #fff !important;
      text-shadow: 0 0 8px rgba(255,255,255,0.4);
    }

    /* Buttons */
    .btn-primary {
      background: linear-gradient(90deg, #7209b7, #560bad);
      border: none;
      box-shadow: 0 0 10px rgba(137, 70, 255, 0.4);
    }
    .btn-primary:hover {
      background: linear-gradient(90deg, #8338ec, #3a0ca3);
      box-shadow: 0 0 14px rgba(167, 96, 255, 0.6);
    }

    .btn-danger {
      background: linear-gradient(90deg, #d00000, #9d0208);
      border: none;
      box-shadow: 0 0 10px rgba(255, 50, 50, 0.3);
    }

    /* Theme Toggle Button */
    #themeToggle {
      border: none;
      background: transparent;
      color: #fff;
      font-size: 1.2rem;
    }
    #themeToggle:hover {
      text-shadow: 0 0 10px rgba(255,255,255,0.7);
    }

    /* Footer */
    footer.footer {
      background: linear-gradient(90deg, #10072e, #1b0a46);
      color: #b9a8ff;
      border-top: 1px solid rgba(255,255,255,0.1);
      text-align: center;
      padding: 1rem 0;
      font-size: 0.9rem;
      margin-top: 3rem;
      box-shadow: 0 -2px 20px rgba(95, 60, 255, 0.2);
    }
    footer.footer strong {
      color: #d0b8ff;
    }
    footer.footer a {
      color: #a06bff;
      text-decoration: none;
    }
    footer.footer a:hover {
      color: #d6b6ff;
    }

    /* Scrollbar (dark mode aesthetic) */
    body[data-bs-theme="dark"]::-webkit-scrollbar {
      width: 8px;
    }
    body[data-bs-theme="dark"]::-webkit-scrollbar-thumb {
      background: linear-gradient(180deg, #8338ec, #3a0ca3);
      border-radius: 5px;
    }
  </style>
</head>

<body data-bs-theme="light">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="<?= APP_URL ?>">TalentFlow</a>

      <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
        <div class="navbar-nav">
          <a class="nav-link" href="<?= APP_URL ?>/onboarding">Onboarding</a>
          <a class="nav-link" href="<?= APP_URL ?>/interview">Interview</a>
          <a class="nav-link" href="<?= APP_URL ?>/offer">Offer</a>
          <a class="nav-link" href="<?= APP_URL ?>/leave">Leave</a>
          <a class="nav-link" href="<?= APP_URL ?>/faq">HR FAQ</a>
        </div>

        <div class="d-flex align-items-center gap-3">
          <!-- 🌗 Theme Toggle -->
          <button id="themeToggle" class="btn btn-outline-light btn-sm" title="Toggle Theme">
            <i class="bi bi-moon-stars-fill"></i>
          </button>

          <!-- 🔒 Logout -->
          <a href="<?= APP_URL ?>/login.php" class="btn btn-danger btn-sm">
            <i class="bi bi-box-arrow-right"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container py-4">
    <?php include __DIR__ . "/$view.php"; ?>
  </div>

  <!-- 🌗 Theme Switcher Script -->
  <script>
    const toggleBtn = document.getElementById('themeToggle');
    const currentTheme = localStorage.getItem('theme') || 'dark';
    document.body.dataset.bsTheme = currentTheme;
    updateIcon(currentTheme);

    toggleBtn.addEventListener('click', () => {
      const newTheme = (document.body.dataset.bsTheme === 'light') ? 'dark' : 'light';
      document.body.dataset.bsTheme = newTheme;
      localStorage.setItem('theme', newTheme);
      updateIcon(newTheme);
    });

    function updateIcon(theme) {
      toggleBtn.innerHTML = theme === 'light'
        ? '<i class="bi bi-moon-stars-fill"></i>'
        : '<i class="bi bi-sun-fill text-warning"></i>';
    }
  </script>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <small>
        © 2025 <strong>MACSEEDS</strong> | Hackathon Series — 
        <a href="https://lablab.ai" target="_blank"><strong>lablab.ai</strong></a>
      </small>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
