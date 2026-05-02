# routers/comments.py
from fastapi import APIRouter, Depends, HTTPException
from pydantic import BaseModel
from database import get_db
from auth import get_current_user
import aiomysql

router = APIRouter()

# ---------- Модели ----------
class CommentCreate(BaseModel):
    body: str

class CommentUpdate(BaseModel):
    body: str

# ---------- GET (публичный) ----------
@router.get('/posts/{post_id}/comments')
async def get_comments(post_id: int):
    conn = await get_db()
    async with conn.cursor(aiomysql.DictCursor) as cur:
        await cur.execute(
            'SELECT c.id, c.body, c.created_at, '
            'u.name AS author_name '
            'FROM comments c '
            'JOIN users u ON c.author_id = u.id '
            'WHERE c.post_id = %s '
            'ORDER BY c.created_at',
            (post_id,)
        )
        items = await cur.fetchall()
    conn.close()
    for item in items:
        item['created_at'] = str(item['created_at'])
    return {'items': items, 'count': len(items)}

# ---------- POST (требует JWT) ----------
@router.post('/posts/{post_id}/comments', status_code=201)
async def create_comment(
    post_id: int,
    data: CommentCreate,
    user = Depends(get_current_user)          # JWT → user_id
):
    if not data.body.strip():
        raise HTTPException(status_code=422, detail='Текст пустой')
    author_id = user['user_id']               # ← из токена, не хардкод
    conn = await get_db()
    async with conn.cursor() as cur:
        # Проверяем, существует ли пост
        await cur.execute('SELECT id FROM posts WHERE id=%s', (post_id,))
        if not await cur.fetchone():
            conn.close()
            raise HTTPException(status_code=404, detail='Пост не найден')
        # Вставляем комментарий
        await cur.execute(
            'INSERT INTO comments (body, post_id, author_id) VALUES (%s, %s, %s)',
            (data.body, post_id, author_id)
        )
        await conn.commit()
        new_id = cur.lastrowid
    conn.close()
    return {'id': new_id, 'body': data.body, 'status': 'created'}

# ---------- PUT (требует JWT + проверка авторства) ----------
@router.put('/comments/{comment_id}')
async def update_comment(
    comment_id: int,
    data: CommentUpdate,
    user = Depends(get_current_user)
):
    if not data.body.strip():
        raise HTTPException(status_code=422, detail='Текст пустой')
    current_user_id = user['user_id']
    conn = await get_db()
    async with conn.cursor() as cur:
        # Получаем автора комментария
        await cur.execute('SELECT author_id FROM comments WHERE id=%s', (comment_id,))
        row = await cur.fetchone()
        if not row:
            conn.close()
            raise HTTPException(status_code=404, detail='Комментарий не найден')
        if row[0] != current_user_id:
            conn.close()
            raise HTTPException(status_code=403, detail='Нельзя редактировать чужой комментарий')
        # Обновляем
        await cur.execute(
            'UPDATE comments SET body=%s WHERE id=%s',
            (data.body, comment_id)
        )
        await conn.commit()
    conn.close()
    return {'id': comment_id, 'body': data.body, 'status': 'updated'}

# ---------- DELETE (требует JWT + проверка авторства) ----------
@router.delete('/comments/{comment_id}', status_code=204)
async def delete_comment(
    comment_id: int,
    user = Depends(get_current_user)
):
    current_user_id = user['user_id']
    conn = await get_db()
    async with conn.cursor() as cur:
        await cur.execute('SELECT author_id FROM comments WHERE id=%s', (comment_id,))
        row = await cur.fetchone()
        if not row:
            conn.close()
            raise HTTPException(status_code=404, detail='Комментарий не найден')
        if row[0] != current_user_id:
            conn.close()
            raise HTTPException(status_code=403, detail='Нельзя удалять чужой комментарий')
        await cur.execute('DELETE FROM comments WHERE id=%s', (comment_id,))
        await conn.commit()
    conn.close()
    # 204 — тело пустое, return не требуется