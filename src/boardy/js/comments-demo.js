// Кирпичик 7. fetch GET + отрисовка списка
const API = 'https://api.bagaev.ai-info.ru';   // ваш домен
const PARENT_ID = 2;                           // ID поста, к которому привязаны комментарии

async function loadItems() {
    const listDiv = document.getElementById('list');
    listDiv.innerHTML = 'Загрузка...';
    try {
        const res = await fetch(`${API}/api/posts/${PARENT_ID}/comments`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();
        if (!data.items || data.items.length === 0) {
            listDiv.innerHTML = '<em>Нет комментариев. Будьте первым!</em>';
            return;
        }
        listDiv.innerHTML = data.items.map(item => `
            <div style="border-bottom:1px solid #ccc; margin-bottom:10px; padding-bottom:5px;">
                <strong>${esc(item.author_name)}</strong>
                <p>${esc(item.body)}</p>
                <small>${esc(item.created_at)}</small>
            </div>
        `).join('');
    } catch (err) {
        console.error(err);
        listDiv.innerHTML = '<span style="color:red;">Ошибка загрузки комментариев</span>';
    }
}

// Кирпичик 8. fetch POST + форма
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('btn');
    const input = document.getElementById('body');

    if (btn) {
        btn.addEventListener('click', async () => {
            const bodyText = input.value.trim();
            if (!bodyText) return;

            try {
                const res = await fetch(`${API}/api/posts/${PARENT_ID}/comments`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ body: bodyText })
                });
                if (!res.ok) {
                    const errData = await res.json().catch(() => ({}));
                    throw new Error(errData.detail || `HTTP ${res.status}`);
                }
                input.value = '';          // очистить поле
                await loadItems();          // обновить список
            } catch (err) {
                console.error(err);
                alert(`Не удалось отправить комментарий: ${err.message}`);
            }
        });
    }

    // начальная загрузка при загрузке страницы
    loadItems();
});

// Кирпичик 9. Защита от XSS
function esc(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}
