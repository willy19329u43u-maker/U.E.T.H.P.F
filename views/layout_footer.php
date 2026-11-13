</main><footer class="footer">
<span><?= e(APP_NAME) ?> &copy; <?= date('Y') ?> · v<?= e(APP_VERSION) ?></span></footer></div></div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
<script src="assets/js/app.js" defer></script>
<!-- Chatbot Widget -->
<div id="chatbot-widget" style="position:fixed;bottom:20px;right:20px;z-index:1000;">
    <button id="chatbot-toggle" class="btn btn-metal" style="width:60px;height:60px;border-radius:50%;font-size:24px;box-shadow:0 4px 12px rgba(0,0,0,0.3);">
        💬
    </button>
    
    <div id="chatbot-window" style="display:none;position:absolute;bottom:70px;right:0;width:350px;height:500px;background:var(--surface);border:1px solid var(--line);border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.4);overflow:hidden;">
        <div style="background:var(--primary-2);color:white;padding:12px;font-weight:600;">
            🤖 Asistente Virtual U.E. Paulo Freire
        </div>
        
        <div id="chatbot-messages" style="height:380px;overflow-y:auto;padding:12px;">
            <div style="background:var(--surface-2);padding:8px 12px;border-radius:8px;margin-bottom:8px;font-size:.9rem;">
                ¡Hola! Soy tu asistente virtual. ¿En qué puedo ayudarte hoy?
            </div>
        </div>
        
        <form id="chatbot-form" style="display:flex;padding:12px;gap:8px;border-top:1px solid var(--line);">
            <input type="text" id="chatbot-input" class="input" placeholder="Escribe tu pregunta..." style="flex:1;padding:8px;">
            <button type="submit" class="btn btn-metal" style="padding:8px 16px;">Enviar</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('chatbot-toggle');
    const window = document.getElementById('chatbot-window');
    const form = document.getElementById('chatbot-form');
    const input = document.getElementById('chatbot-input');
    const messages = document.getElementById('chatbot-messages');
    
    toggle.addEventListener('click', function() {
        window.style.display = window.style.display === 'none' ? 'block' : 'none';
    });
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const question = input.value.trim();
        if (!question) return;
        
        messages.innerHTML += `<div style="background:var(--primary-2);color:white;padding:8px 12px;border-radius:8px;margin-bottom:8px;font-size:.9rem;text-align:right;">${question}</div>`;
        input.value = '';
        
        fetch('chatbot.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({question: question})
        })
        .then(res => res.json())
        .then(data => {
            if (data.response) {
                messages.innerHTML += `<div style="background:var(--surface-2);padding:8px 12px;border-radius:8px;margin-bottom:8px;font-size:.9rem;">${data.response}</div>`;
                messages.scrollTop = messages.scrollHeight;
            }
        })
        .catch(err => {
            messages.innerHTML += `<div style="background:#d62828;color:white;padding:8px 12px;border-radius:8px;margin-bottom:8px;font-size:.9rem;">Error al conectar</div>`;
        });
    });
});
</script>
</body></html>