
document.addEventListener('DOMContentLoaded', function () {
    'use strict';
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var sidebar = document.getElementById('sidebar');
    var menuToggle = document.getElementById('menuToggle');
    var sideToggle = document.getElementById('sideToggle');
    if (menuToggle && sidebar) menuToggle.addEventListener('click', function () { sidebar.classList.toggle('open'); });
    if (sideToggle && sidebar) sideToggle.addEventListener('click', function () { sidebar.classList.toggle('expanded'); });
    var tilt = document.getElementById('tiltCard');
    if (tilt && !reduced && window.matchMedia('(pointer: fine)').matches) {
        tilt.addEventListener('mousemove', function (ev) { var r = tilt.getBoundingClientRect(); var x = (ev.clientX - r.left) / r.width - .5, y = (ev.clientY - r.top) / r.height - .5; tilt.style.transform = 'perspective(1000px) rotateY(' + (x * 6).toFixed(2) + 'deg) rotateX(' + (-y * 6).toFixed(2) + 'deg)'; });
        tilt.addEventListener('mouseleave', function () { tilt.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)'; });
    }
    document.querySelectorAll('.typewriter').forEach(function (el) { var text = el.getAttribute('data-text') || ''; if (reduced) { el.textContent = text; return; } var i = 0; el.textContent = ''; var timer = setInterval(function () { el.textContent = text.slice(0, ++i); if (i >= text.length) clearInterval(timer); }, 42); });
    var pwT = document.getElementById('pwToggle'), pwI = document.getElementById('pwInput');
    if (pwT && pwI) { pwT.addEventListener('click', function (ev) { ev.preventDefault(); ev.stopPropagation(); var show = pwI.type === 'password'; pwI.type = show ? 'text' : 'password'; pwT.textContent = show ? '🙈' : '👁'; pwT.setAttribute('aria-pressed', show ? 'true' : 'false'); pwI.focus(); }); }
    var lf = document.getElementById('loginForm'), lb = document.getElementById('loginBtn');
    if (lf && lb) lf.addEventListener('submit', function () { lb.classList.add('loading'); });
    document.addEventListener('click', function (ev) { var btn = ev.target.closest ? ev.target.closest('.btn') : null; if (!btn || reduced) return; var r = btn.getBoundingClientRect(); var sp = document.createElement('span'); sp.className = 'ripple'; var d = Math.max(r.width, r.height); sp.style.width = sp.style.height = d + 'px'; sp.style.left = (ev.clientX - r.left - d / 2) + 'px'; sp.style.top = (ev.clientY - r.top - d / 2) + 'px'; btn.appendChild(sp); setTimeout(function () { sp.remove(); }, 650); });
    document.querySelectorAll('.count').forEach(function (el) { var target = parseInt(el.getAttribute('data-count') || el.textContent || '0', 10); el.textContent = target; if (reduced) return; var start = null; function step(ts) { if (!start) start = ts; var prog = Math.min((ts - start) / 900, 1); el.textContent = Math.floor(prog * target); if (prog < 1) requestAnimationFrame(step); else el.textContent = target; } requestAnimationFrame(step); });
    if (window.Chart && document.getElementById('chartAttendance')) { var data = window.CHART_DATA || []; var labels = [], pr = [], fa = [], at = []; data.forEach(function (r) { labels.push('Mes ' + r.m); pr.push(+r.presentes); fa.push(+r.faltas); at.push(+r.retrasos); }); new Chart(document.getElementById('chartAttendance'), { type: 'bar', data: { labels: labels, datasets: [{ label: 'Presentes', data: pr, backgroundColor: 'rgba(42,212,192,.65)' }, { label: 'Faltas', data: fa, backgroundColor: 'rgba(255,183,3,.65)' }, { label: 'Retrasos', data: at, backgroundColor: 'rgba(25,195,230,.65)' }]}, options: { responsive: true, maintainAspectRatio: false } }); }
    document.querySelectorAll('.alert').forEach(function (a) { setTimeout(function () { a.style.opacity = '0'; a.style.transform = 'translateY(-8px)'; setTimeout(function () { a.remove(); }, 350); }, 5000); });
    if ('IntersectionObserver' in window) { var obs = new IntersectionObserver(function (es) { es.forEach(function (en) { if (en.isIntersecting) en.target.classList.add('visible'); }); }, { threshold: .15 }); document.querySelectorAll('.reveal').forEach(function (el) { obs.observe(el); }); } else { document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('visible'); }); }
    document.querySelectorAll('.table').forEach(function (table) { if (!table.dataset.searchDone && table.rows.length > 4) { var inp = document.createElement('input'); inp.type = 'text'; inp.placeholder = 'Buscar...'; inp.className = 'input'; inp.style.marginBottom = '10px'; table.parentNode.insertBefore(inp, table); inp.addEventListener('keyup', function () { var term = inp.value.toLowerCase(); Array.from(table.rows).forEach(function (row, i) { if (i === 0) return; row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none'; }); }); table.dataset.searchDone = '1'; } });
    function setDevice() { var w = window.innerWidth; document.body.classList.remove('device-mobile', 'device-tablet', 'device-desktop'); document.body.classList.add(w < 700 ? 'device-mobile' : (w < 1000 ? 'device-tablet' : 'device-desktop')); }
    setDevice(); window.addEventListener('resize', setDevice);
});
