<?php /** @var string $view */ ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>TalentFlow | HR Automation Suite</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --primary: #7209b7;
      --secondary: #560bad;
      --accent: #ff6ec7;
      --dark-bg: radial-gradient(circle at top, #0b0824 0%, #06041c 80%);
      
      /* --- ✨ NEW: Glass Variables (Dark) --- */
      --sidebar-width-collapsed: 90px;
      --sidebar-width-expanded: 260px;
      --sidebar-bg-glass: rgba(20, 15, 40, 0.6);
      --sidebar-border: rgba(255, 255, 255, 0.1);
      --chat-bg-glass: rgba(20, 20, 35, 0.85);
      --chat-answer-bg: rgba(255, 255, 255, 0.08);
      --chat-input-bg: rgba(255, 255, 255, 0.12);
    }

    body {
      font-family: 'Inter', system-ui, sans-serif;
      min-height: 100vh;
      overflow-x: hidden;
      transition: all 0.5s ease;
    }

    /* --- ✨ NEW: Light Theme Variable Overrides --- */
    body[data-bs-theme="light"] { 
      background: #f8f9fc; 
      color: #1a1a1a;
      --sidebar-bg-glass: rgba(255, 255, 255, 0.7);
      --sidebar-border: rgba(0, 0, 0, 0.1);
      --chat-bg-glass: rgba(255, 255, 255, 0.85);
      --chat-answer-bg: rgba(0, 0, 0, 0.06);
      --chat-input-bg: rgba(0, 0, 0, 0.06);
    }
    
    body[data-bs-theme="dark"] { 
      background: var(--dark-bg); 
      color: #eaeaff; 
    }

    /* --- ✨ MODIFIED: Glass Sidebar --- */
    .sidebar {
      position: fixed; left: 0; top: 0; height: 100vh;
      width: var(--sidebar-width-collapsed);
      
      /* ✨ Glass Effect */
      background: var(--sidebar-bg-glass);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border-right: 1px solid var(--sidebar-border);
      
      box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
      display: flex; flex-direction: column; overflow: hidden;
      transition: width 0.3s ease, background 0.5s ease, border 0.5s ease;
      z-index: 1030;
    }
    .sidebar:hover { width: var(--sidebar-width-expanded); }

    .sidebar-brand {
      color: #fff; font-weight: 700; font-size: 1.4rem;
      padding: 1.5rem 0; text-align: center;
      display: flex; align-items: center; justify-content: center;
      text-decoration: none;
      /* ✨ Add gradient bg to brand icon container */
      background: linear-gradient(180deg, var(--primary), var(--secondary));
      text-shadow: 0 0 10px rgba(255,255,255,0.3);
    }
    /* ✨ Hide text, show icon */
    .sidebar-brand .link-text { display: none; margin-left: 0.5rem; }
    .sidebar-brand i { min-width: var(--sidebar-width-collapsed); }
    /* ✨ On hover, show text and shrink icon padding */
    .sidebar:hover .sidebar-brand .link-text { display: inline; }
    .sidebar:hover .sidebar-brand i { min-width: auto; margin-left: 1.75rem; } /* Adjust as needed */

    .sidebar-nav {
        flex-grow: 1; /* Pushes footer down */
        list-style: none; padding: 1rem 0 0 0; margin: 0;
    }
    .sidebar-nav a {
      color: var(--bs-body-color); /* Theme-aware text */
      text-decoration: none;
      display: flex; align-items: center;
      padding: 1rem 0; font-weight: 500;
      transition: all 0.3s ease;
    }

    .sidebar-nav a:hover {
      color: var(--primary); 
      background: rgba(114, 9, 183, 0.1); /* Accent hover */
    }

    .sidebar-nav a.active {
      color: var(--primary);
      background: rgba(114, 9, 183, 0.15);
      box-shadow: inset 4px 0 0 var(--accent);
      font-weight: 600;
    }
    body[data-bs-theme="light"] .sidebar-nav a.active {
        color: var(--primary);
    }

    .sidebar-nav a i {
      font-size: 1.5rem;
      min-width: var(--sidebar-width-collapsed);
      text-align: center;
    }

    .sidebar:hover .link-text { opacity: 1; transition-delay: 0.1s; }
    .link-text { opacity: 0; transition: opacity 0.2s ease; white-space: nowrap; }
    
    /* --- ✨ MODIFIED: Sidebar Footer --- */
    .sidebar-footer {
        padding: 0.5rem 0;
        border-top: 1px solid var(--sidebar-border);
        transition: border 0.5s ease;
    }
    .sidebar-footer-item {
        display: flex; align-items: center;
        padding: 1rem 0; width: 100%;
        background: none; border: none;
        color: var(--bs-body-color); /* Theme-aware text */
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .sidebar-footer-item:hover {
        color: var(--primary);
        background: rgba(114, 9, 183, 0.1);
    }
    .sidebar-footer-item i {
      font-size: 1.5rem;
      min-width: var(--sidebar-width-collapsed);
      text-align: center;
    }
    .sidebar-footer-item.logout-btn:hover {
        color: #d90429;
        background: rgba(217, 4, 41, 0.1);
    }

    /* --- Main Content --- */
    .main-content {
      margin-left: var(--sidebar-width-collapsed);
      transition: margin-left 0.3s ease;
      min-height: 100vh; /* ✨ Fill viewport */
      display: flex; flex-direction: column;
      padding-top: 2rem;
    }
    .main-content .container {
        flex-grow: 1; /* ✨ Push footer down */
    }
    .sidebar:hover ~ .main-content { margin-left: var(--sidebar-width-expanded); }

    /* --- ✨ MODIFIED: Modern Footer --- */
    footer.footer {
      background: transparent; /* Remove heavy bg */
      color: var(--bs-secondary-text-emphasis); /* Theme-aware text */
      text-align: center;
      padding: 1.5rem 0;
      font-size: 0.9rem;
      box-shadow: none; /* Remove shadow */
      border-top: 1px solid var(--sidebar-border); /* Use glass border */
      transition: all 0.5s ease;
    }
    footer.footer a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }
    footer.footer a:hover {
        color: var(--accent);
    }

    /* --- Chatbot --- */
    .chatbot-btn {
      position: fixed; bottom: 25px; right: 25px;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: #fff; border: none; border-radius: 50%;
      width: 65px; height: 65px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.8rem; cursor: pointer;
      box-shadow: 0 6px 25px rgba(114,9,183,0.4);
      transition: all 0.3s ease;
      z-index: 1050;
    }
    .chatbot-btn:hover { transform: scale(1.08); box-shadow: 0 8px 30px rgba(255,110,199,0.5); }

    .chatbot-container {
      position: fixed; bottom: 100px; right: 25px;
      width: 340px; max-height: 480px;
      /* ✨ Use CSS Vars */
      background: var(--chat-bg-glass);
      backdrop-filter: blur(18px);
      border-radius: 16px;
      border: 1px solid var(--sidebar-border);
      box-shadow: 0 10px 40px rgba(0,0,0,0.4);
      display: none; flex-direction: column;
      overflow: hidden; z-index: 1049;
      animation: slideUp 0.3s ease forwards;
      transition: background 0.5s ease, border 0.5s ease;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .chatbot-header {
      background: linear-gradient(90deg, var(--primary), var(--secondary));
      color: #fff; padding: 0.8rem 1rem;
      display: flex; align-items: center; justify-content: space-between;
    }

    .chatbot-body {
      flex-grow: 1;
      overflow-y: auto;
      padding: 1rem;
      display: flex; flex-direction: column;
      gap: 0.8rem;
      scrollbar-width: thin;
    }

    .chatbot-body .question,
    .chatbot-body .answer {
      padding: 0.7rem 1rem;
      border-radius: 16px;
      max-width: 80%;
      line-height: 1.4;
      animation: fadeIn 0.3s ease;
      transition: background 0.5s ease, color 0.5s ease;
    }

    .chatbot-body .question {
      align-self: flex-end;
      background: var(--accent);
      color: #fff;
      border-bottom-right-radius: 0;
    }
    /* ✨ Use CSS Var */
    .chatbot-body .answer {
      align-self: flex-start;
      background: var(--chat-answer-bg);
      color: var(--bs-body-color);
      border-bottom-left-radius: 0;
    }

    .typing {
      width: 40px; display: flex; gap: 4px;
      align-self: flex-start; margin-left: 6px;
    }

    .typing span {
      width: 6px; height: 6px;
      background: var(--bs-body-color); /* Theme-aware */
      border-radius: 50%;
      opacity: 0.6;
      animation: blink 1.3s infinite;
    }
    .typing span:nth-child(2){ animation-delay:0.2s; }
    .typing span:nth-child(3){ animation-delay:0.4s; }

    @keyframes blink {
      0%, 80%, 100% { opacity: 0.2; transform: translateY(0); }
      40% { opacity: 1; transform: translateY(-4px); }
    }

    .chatbot-footer {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.7rem 1rem;
      background: rgba(0,0,0,0.1); /* Darker footer */
      border-top: 1px solid var(--sidebar-border);
      transition: all 0.5s ease;
    }
    /* ✨ Use CSS Var */
    .chatbot-footer input {
      flex: 1;
      background: var(--chat-input-bg);
      color: var(--bs-body-color);
      border: 1px solid transparent;
      border-radius: 10px;
      padding: 0.6rem 0.9rem;
      outline: none;
      transition: all 0.5s ease;
    }
    .chatbot-footer input:focus {
        border-color: var(--accent);
    }
    .chatbot-footer input::placeholder {
        color: var(--bs-secondary-text-emphasis);
    }

    .chatbot-footer button {
      background: var(--accent);
      border: none;
      border-radius: 10px;
      color: #fff;
      padding: 0.6rem 0.9rem;
      transition: all 0.2s ease;
    }
    .chatbot-footer button:hover { opacity: 0.85; }

    /* --- ✨ NEW: Light Theme Chatbot Overrides --- */
    body[data-bs-theme="light"] .chatbot-body .question {
        background: var(--primary); /* Accent is too light */
    }
    body[data-bs-theme="light"] .chatbot-footer {
        background: rgba(0,0,0,0.03);
    }
    /* --- End Light Theme --- */

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(5px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 480px) {
      .chatbot-container { width: 95%; right: 2.5%; bottom: 80px; }
      .chatbot-btn { width: 55px; height: 55px; font-size: 1.4rem; }
    }
  </style>
</head>

<body data-bs-theme="dark">

  <!-- Sidebar -->
  <nav class="sidebar">
    <!-- ✨ MODIFIED: Brand logo/text split for hover -->
    <a class="sidebar-brand" href="<?= APP_URL ?>">
        <i class="bi bi-stars"></i>
        <span class="link-text">TalentFlow</span>
    </a>

    <ul class="sidebar-nav">
      <li><a href="<?= APP_URL ?>" class="<?= ($view == 'dashboard') ? 'active' : '' ?>"><i class="bi bi-grid-1x2-fill"></i><span class="link-text">Dashboard</span></a></li>
      <li><a href="<?= APP_URL ?>/onboarding" class="<?= ($view == 'onboarding') ? 'active' : '' ?>"><i class="bi bi-rocket-takeoff"></i><span class="link-text">Onboarding</span></a></li>
      <li><a href="<?= APP_URL ?>/interview" class="<?= ($view == 'interview') ? 'active' : '' ?>"><i class="bi bi-person-badge"></i><span class="link-text">Interview</span></a></li>
      <li><a href="<?= APP_URL ?>/offer" class="<?= ($view == 'offer') ? 'active' : '' ?>"><i class="bi bi-card-checklist"></i><span class="link-text">Offer</span></a></li>
      <li><a href="<?= APP_URL ?>/leave" class="<?= ($view == 'leave') ? 'active' : '' ?>"><i class="bi bi-calendar2-x"></i><span class="link-text">Leave</span></a></li>
      <li><a href="<?= APP_URL ?>/faq" class="<?= ($view == 'faq') ? 'active' : '' ?>"><i class="bi bi-question-circle"></i><span class="link-text">HR FAQ</span></a></li>
    </ul>

    <!-- ✨ MODIFIED: Sidebar footer with new styles -->
    <div class="sidebar-footer">
      <button id="themeToggle" class="sidebar-footer-item" title="Toggle Theme">
          <i class="bi bi-moon-stars-fill"></i>
          <span class="link-text">Toggle Theme</span>
      </button>
      <a href="<?= APP_URL ?>/login.php" class="sidebar-footer-item logout-btn">
          <i class="bi bi-box-arrow-left"></i>
          <span class="link-text">Logout</span>
      </a>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="main-content">
    <!-- ✨ MODIFIED: Container is now inside main-content -->
    <div class="container py-4">
      <?php include __DIR__ . "/$view.php"; ?>
    </div>
    
    <!-- ✨ MODIFIED: Footer is now inside main-content -->
    <footer class="footer">
      <div class="container">
        <small>© 2025 <strong>MACSEEDS</strong> | Hackathon Series — <a href="https://lablab.ai" target="_blank">lablab.ai</a></small>
      </div>
    </footer>
  </div>

  <!-- 💬 Floating Chatbot -->
  <button class="chatbot-btn" id="chatbotToggle" title="Ask HR Bot">
    <i class="bi bi-chat-dots"></i>
  </button>

  <div class="chatbot-container" id="chatbotContainer">
    <div class="chatbot-header">
      <span><i class="bi bi-robot me-2"></i> HR Assistant</span>
      <button class="btn btn-sm btn-light text-dark" id="closeChat"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="chatbot-body" id="chatMessages">
      <div class="answer">👋 Hi! I'm your HR Assistant.<br><small>Try asking: “What’s the leave policy?”</small></div>
    </div>
    <div class="chatbot-footer">
      <input type="text" id="chatInput" placeholder="Type your question..." autocomplete="off">
      <button id="sendChat"><i class="bi bi-send-fill"></i></button>
    </div>
  </div>

  <script>
  // --- ✨ NEW: Theme Switcher Logic ---
  (function() {
    const toggleBtn = document.getElementById('themeToggle');
    if (!toggleBtn) return;
    
    const toggleIcon = toggleBtn.querySelector('i');
    const currentTheme = localStorage.getItem('theme') || 'dark';
    
    document.body.dataset.bsTheme = currentTheme;
    updateIcon(currentTheme);

    toggleBtn.addEventListener('click', () => {
      const newTheme = document.body.dataset.bsTheme === 'light' ? 'dark' : 'light';
      document.body.dataset.bsTheme = newTheme;
      localStorage.setItem('theme', newTheme);
      updateIcon(newTheme);
    });

    function updateIcon(theme) {
      if (toggleIcon) {
          toggleIcon.className = theme === 'light' 
              ? 'bi bi-moon-stars-fill' 
              : 'bi bi-sun-fill text-warning';
      }
    }
  })();

  // --- Chatbot Logic ---
  (function() {
    const toggleBtn = document.getElementById('chatbotToggle');
    const chatbot = document.getElementById('chatbotContainer');
    const closeBtn = document.getElementById('closeChat');
    
    if (!toggleBtn || !chatbot || !closeBtn) return;

    toggleBtn.onclick = () => chatbot.style.display = (chatbot.style.display === 'flex' ? 'none' : 'flex');
    closeBtn.onclick = () => chatbot.style.display = 'none';

    const chatInput = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendChat');
    const chatMessages = document.getElementById('chatMessages');

    function appendMessage(content, type) {
      const div = document.createElement('div');
      div.className = type;
      // ✨ Sanitize text content
      div.textContent = content; 
      
      // ✨ Render line breaks
      if (type === 'answer') {
          div.innerHTML = div.innerHTML.replace(/\n/g, '<br>');
      }
      
      chatMessages.appendChild(div);
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function showTyping() {
      const typing = document.createElement('div');
      typing.className = 'typing';
      typing.innerHTML = '<span></span><span></span><span></span>';
      chatMessages.appendChild(typing);
      chatMessages.scrollTop = chatMessages.scrollHeight;
      return typing;
    }

    async function sendMessage() {
      const question = chatInput.value.trim();
      if (!question) return;
      appendMessage(question, 'question');
      chatInput.value = '';

      const typing = showTyping();

      try {
        const res = await fetch('<?= APP_URL ?>/chatbot_response.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({ question })
        });
        if (!res.ok) throw new Error('Network response was not ok');
        const data = await res.json();
        typing.remove();
        appendMessage(data.answer || "I'm not sure, please rephrase.", 'answer');
      } catch (err) {
        console.error('Chatbot Error:', err);
        typing.remove();
        appendMessage('⚠️ Error connecting to the HR bot. Please check your connection.', 'answer');
      }
    }

    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', (e) => e.key === 'Enter' && sendMessage());
  })();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>