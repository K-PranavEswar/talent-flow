<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <div class="card shadow-sm p-3">
  <h4 class="mb-3">HR AI Assistant 🤖</h4>
  <p class="text-muted small">Ask anything about HR policies, leave, onboarding, benefits, or salary.</p>

  <form id="faqForm" class="d-flex gap-2 mt-3">
    <input class="form-control" name="q" placeholder="Type your question..." required>
    <button class="btn btn-primary">Ask</button>
  </form>

  <div id="faqChat" class="mt-4" style="max-height: 400px; overflow-y: auto;"></div>
</div>
<script>
document.getElementById('faqForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = this;
    const chat = document.getElementById('faqChat');
    const question = form.q.value.trim();

    if (!question) return;

    // Show user question
    chat.innerHTML += `
        <div class="border rounded p-2 mb-2 bg-dark text-light">
            <strong>You:</strong> ${question}
        </div>
    `;

    // AJAX request
    const res = await fetch("<?= APP_URL ?>/faq/ask", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "q=" + encodeURIComponent(question)
    });

    const data = await res.json();

    // Show AI answer
    chat.innerHTML += `
        <div class="border rounded p-2 mb-2 bg-secondary text-light">
            <strong>AI:</strong> ${data.answer}<br>
            <span class="small text-warning">Confidence: ${(data.confidence * 100).toFixed(1)}%</span>
        </div>
    `;

    chat.scrollTop = chat.scrollHeight;
    form.reset();
});
</script>
</body>
</html>
