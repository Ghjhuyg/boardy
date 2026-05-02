// Кирпичик 10. Компонент с загрузкой данных
// Паттерн: React-компонент, который загружает данные при старте
//
// useState — хранит данные между рендерами
//   (в vanilla JS их негде хранить — перезагружаем весь DOM)
// useEffect с [] — выполняется один раз при монтировании
//   (аналог loadItems() при загрузке страницы)
// setItems(data) — React автоматически перерисует список
//   (в vanilla JS — innerHTML заново)

const { useState, useEffect } = React;
const API = 'https://api.bagaev.ai-info.ru';   // ваш домен
const PARENT_ID = 2;                           // ID поста

function CommentsApp() {
    const [items, setItems] = useState([]);
    const [text, setText] = useState('');
    const [editId, setEditId] = useState(null);
    const [editText, setEditText] = useState('');

    const load = async () => {
        const res = await fetch(`${API}/api/posts/${PARENT_ID}/comments`);
        const data = await res.json();
        setItems(data.items);
    };

    // Паттерн: useEffect → fetch за токеном → сохранить в state
    const [jwt, setJwt] = useState(null);
 
    useEffect(() => {
        fetch('/api/me.php', { credentials: 'include' })
            .then(r => {
                if (!r.ok) return null;    // не залогинен
                return r.json();
            })
            .then(data => {
                if (data && data.token) 
		{
			setJwt(data.token);
			console.log('JWT:', data.token);
		}
            })
            .catch(() => setJwt(null));
    }, []);

    const getAuthHeaders = () => {
        const headers = { 'Content-Type': 'application/json' };
        if (jwt) {
            headers['Authorization'] = `Bearer ${jwt}`;
        }
        return headers;
    };

    // Кирпичик 11. Управляемая форма + отправка
    // Паттерн: форма в React
    //
    // value={text} + onChange — «управляемый компонент»
    //   React владеет значением поля, не DOM
    //   (в vanilla JS: getElementById().value)
    // JSON.stringify — то же что в vanilla JS
    // После успеха: setText('') очищает, load() обновляет

    const add = async () => {
        if (!text.trim()) return;
        await fetch(`${API}/api/posts/${PARENT_ID}/comments`, {
            method: 'POST',
            headers: getAuthHeaders(),
            body: JSON.stringify({ body: text })
        });
        setText('');
        load();
    };

    // Кирпичик 12. Условный рендер для редактирования
    // Паттерн: редактирование «на месте»
    //
    // editId — какой элемент сейчас редактируется (null = никакой)
    // Если editId === item.id → показываем input
    // Иначе → показываем текст и кнопку ✏️
    //
    // В vanilla JS: руками подменяем DOM-элементы (createElement, appendChild)
    // В React: одно условие в JSX, React сам перерисует

    const save = async (id) => {
        await fetch(`${API}/api/comments/${id}`, {
            method: 'PUT',
            headers: getAuthHeaders(),
            body: JSON.stringify({ body: editText })
        });
        setEditId(null);
        load();
    };

    // Кирпичик 13. Удаление с подтверждением
    // Паттерн: удалить и обновить список

    const del = async (id) => {
        if (!confirm('Удалить?')) return;
        await fetch(`${API}/api/comments/${id}`, {
            method: 'DELETE',
            headers: getAuthHeaders()
        });
        load();
    };

    return (
        <div>
            {items.map(item => (
                <div key={item.id} className="card mb-2">
                    <div className="card-body">
                        <strong>{item.author_name}</strong>
                        {editId === item.id ? (
                            <div className="input-group">
                                <input className="form-control" value={editText}
                                    onChange={e => setEditText(e.target.value)} />
                                <button className="btn btn-success" onClick={() => save(item.id)}>
                                    Сохранить</button>
                                <button className="btn btn-secondary" onClick={() => setEditId(null)}>
                                    Отмена</button>
                            </div>
                        ) : (
                            <div>
                                <p>{item.body}</p>
                                <button className="btn btn-sm btn-outline-secondary"
                                    onClick={() => { setEditId(item.id); setEditText(item.body); }}>
                                    ✏️</button>
                                <button className="btn btn-sm btn-outline-danger"
                                    onClick={() => del(item.id)}>🗑️</button>
                            </div>
                        )}
                    </div>
                </div>
            ))}
            <div className="input-group mt-3">
                <input className="form-control" placeholder="Комментарий"
                    value={text} onChange={e => setText(e.target.value)} />
                <button className="btn btn-primary" onClick={add}>Отправить</button>
            </div>
        </div>
    );
}

// React vs vanilla JS — итог
// useState — состояние управляется, не теряется между рендерами.
// XSS — React экранирует {item.body} автоматически. esc() не нужен.
// Условный рендер — {condition ? <A/> : <B/>} вместо подмены DOM.
// Bootstrap — className="card", className="btn btn-primary". Красиво сразу.

ReactDOM.createRoot(document.getElementById('app')).render(<CommentsApp />);
