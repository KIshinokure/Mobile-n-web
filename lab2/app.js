// ЛР 2. AJAX-счётчик «Like»: запросы уходят на сервер в фоне,
// на странице меняется только кнопка и число лайков.

function showToast(text) {
    const t = document.getElementById('toast');
    t.textContent = text;
    t.hidden = false;
    clearTimeout(showToast.timer);
    showToast.timer = setTimeout(() => { t.hidden = true; }, 2500);
}

function render(article, likes, liked) {
    const btn = article.querySelector('.like-btn');
    article.querySelector('.count').textContent = likes;
    btn.classList.toggle('liked', liked);
    btn.setAttribute('aria-pressed', liked ? 'true' : 'false');
    btn.querySelector('.label').textContent = liked ? 'Вам нравится' : 'Нравится';
}

async function toggleLike(article) {
    const btn = article.querySelector('.like-btn');
    const action = btn.classList.contains('liked') ? 'unlike' : 'like';
    btn.disabled = true;
    try {
        // AJAX-запрос методом POST (тело — как у обычной HTML-формы)
        const resp = await fetch('like.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ news_id: article.dataset.id, action })
        });
        const data = await resp.json();
        if (!data.ok) throw new Error(data.error);
        render(article, data.likes, data.liked);
        showToast(action === 'like' ? '+1 к счётчику' : '−1 от счётчика');
    } catch (err) {
        showToast('Ошибка: ' + err.message);
    } finally {
        btn.disabled = false;
    }
}

document.querySelectorAll('.news').forEach(article => {
    article.querySelector('.like-btn').addEventListener('click', () => toggleLike(article));
});

// Раз в 5 секунд подтягиваем счётчики, чтобы видеть лайки других посетителей
async function refresh() {
    try {
        const data = await (await fetch('news.php')).json();
        data.news.forEach(n => {
            const a = document.querySelector(`.news[data-id="${n.id}"]`);
            if (a) render(a, n.likes, n.liked);
        });
    } catch (_) { /* сеть недоступна — попробуем позже */ }
}
setInterval(refresh, 5000);
