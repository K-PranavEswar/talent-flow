document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('faqForm');
  const chat = document.getElementById('faqChat');

  if (form && chat) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const fd = new FormData(form);
      const question = fd.get('q');

      // Append user question
      chat.innerHTML += `
        <div class="p-2 bg-light rounded mb-2">
          <strong>You:</strong> ${question}
        </div>
      `;

      form.reset();

      // Send to backend
      const res = await fetch('./faq/ask', { method: 'POST', body: fd });
      const data = await res.json();

      chat.innerHTML += `
        <div class="p-2 bg-white border rounded mb-2">
          <strong>AI:</strong> ${data.answer}
          <div class="small text-muted">Confidence: ${(data.confidence * 100).toFixed(0)}%</div>
        </div>
      `;

      chat.scrollTop = chat.scrollHeight;
    });
  }
});
