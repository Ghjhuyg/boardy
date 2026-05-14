## Часть A. Установка и переключение домена

### 1. Composer и PHP-расширения
Установлен Composer глобально, расширения `mbstring, xml, bcmath, curl, mysql, zip` активны.

**Скриншот:**  
![composer --version и php -m](screenshots/01-composer-php.png)

### 2. Переезд папок
`/var/www/boardy-legacy` — старый проект, `/var/www/boardy` — новый Laravel 11.

**Скриншоты:**  
![ls /var/www](screenshots/02-folders.png)  
![php artisan --version](screenshots/03-laravel-version.png)

### 3. Структура Laravel
- **app/** – ядро приложения (модели, контроллеры, policies)
- **routes/** – файлы маршрутизации (web.php, api.php)
- **resources/views/** – Blade-шаблоны
- **database/** – миграции, фабрики, сидеры
- **public/** – точка входа (index.php) и публичные ресурсы (CSS, JS)

**Защитный вопрос:** *Почему document_root nginx должен указывать на `public/`, а не на корень Laravel?*  
Чтобы не было доступа к файлам типа .env с важной информацией, чтобы нельзя было получить к ним доступ.

### 4. Nginx-конфиг
Обновлён `root /var/www/boardy/public`, добавлен `try_files`.

**Скриншоты:**  
![nginx config boardy](screenshots/04-nginx-config.png)  
![Laravel welcome](screenshots/05-laravel-welcome.png)

**Защитный вопрос:** *Что делает `try_files $uri $uri/ /index.php?$query_string`? Что без неё при заходе на `/posts/3`?*  
Проверяет, существует ли файл $uri или папка $uri/, и если нет отправляет в index.php. Без этой строки Nginx попытается найти файл /posts/3 и выдаст ошибку 404, и до роутера Laravel не дойдёт, поэтому красивые URL перестанут работать.

---

## Часть B. БД, миграции, сидер

### 5. Создание БД `boardy_main`
Создана БД с `utf8mb4`, пользователь `boardy` получил права.

**Скриншот:**  
![SHOW DATABASES](screenshots/06-databases.png)

**Защитный вопрос:** *Зачем новая БД, а не подгонка старой?*
Легче новую сделать, чем старую под laravel подгонять

### 6. Подключение Laravel к БД
Настроен `.env`, `tinker` показывает PDO.

**Скриншот:**  
![tinker getPdo()](screenshots/07-tinker-pdo.png)

### 7. Миграции `posts` и `comments`
Созданы миграции, накатаны.

**Скриншоты:**  
![migrate:status](screenshots/08-migrate-status.png)  
![SHOW TABLES](screenshots/09-show-tables.png)

### 8. Модели со связями
У `User` добавлены `hasMany(Post::class)`, `hasMany(Comment::class)`. У `Post` – `belongsTo(User::class, 'author_id')` и `hasMany(Comment::class)`. У `Comment` – `belongsTo(User::class)` и `belongsTo(Post::class)`.

**Скриншот:**  
![tinker проверка связей](screenshots/10-model-relations.png)

### 9. Сидер
Фабрики `PostFactory` (100 записей), `CommentFactory` (200), сидер создаёт 5 пользователей, каждому посты, к каждому посту комментарии.

**Скриншот:**  
![количество записей](screenshots/11-seed-counts.png)

---

## Часть C. CRUD постов и комментариев

### 10. Маршруты
`Route::resource('posts', PostController::class)` и `Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->middleware('auth')`.

**Скриншот:**  
![php artisan route:list](screenshots/12-route-list.png)

### 11. Лента постов
`PostController@index` пагинирует 10 постов, каждый с автором и датой.

**Скриншот:**  
![лента /posts](screenshots/13-posts-index.png)

### 12. Страница поста с комментариями
`PostController@show` передаёт пост, комментарии (с авторами) и форму нового комментария для авторизованных.

**Скриншот:**  
![/posts/3](screenshots/14-post-show.png)

### 13. Создание поста
Форма `create`, валидация `title|required|min:5`, `body|required|min:10`. После сохранения – редирект на страницу поста.

**Скриншоты:**  
![форма создания](screenshots/15-post-create.png)  
![пост создан](screenshots/16-post-after-create.png)

### 14. Policy и редактирование
`PostPolicy` с методом `update`. В контроллере `$this->authorize('update', $post)`. В Blade `@can('update', $post)` показывает кнопки.

**Скриншоты:**  
![кнопки edit/delete у своего поста](screenshots/17-edit-own.png)  
![403 при попытке edit чужого](screenshots/18-edit-foreign-403.png)

**Защитный вопрос:** *Сравните Policy с ручной авторизацией в Lab10–11.*  
В Policy логика вынесена и в одном месте, а раньше сами ручками через if-ы прописывали проверки, да везде повторяли, если надо - сейчас короче и удобнее

### 15. Удаление поста
`destroy` с Policy, после удаления редирект на `/posts`.

**Скриншот:**  
![пост удалён](screenshots/19-post-deleted.png)

### 16. Комментарий через Blade
`CommentController@store` сохраняет `body`, привязывает к `user_id` (auth) и `post_id`. После сохранения – редирект обратно на страницу поста.

**Скриншот:**  
![комментарий создан](screenshots/20-comment-created.png)

---

## Часть D. Breeze + Socialite

### 17. Установка Breeze
`composer require laravel/breeze --dev`, `php artisan breeze:install blade`, `npm install && npm run build`, миграция.

**Скриншоты:**  
![страница регистрации](screenshots/21-register.png)  
![страница входа](screenshots/22-login.png)

### 18. Регистрация и вход
Пользователь создан через `/register`, после входа имя отображается в navbar.

**Скриншот:**  
![после регистрации](screenshots/23-after-register.png)

### 19. GitHub OAuth-приложение
Создано приложение, callback: `https://фамилия.ai-info.ru/auth/github/callback`.

**Скриншот:**  
![OAuth App на GitHub](screenshots/24-github-app.png)

### 20. Socialite
Установлен `laravel/socialite`, добавлена миграция `github_id`, настройки `config/services.php`, `.env` (GITHUB_CLIENT_ID, GITHUB_CLIENT_SECRET, GITHUB_REDIRECT). Создан `GitHubController`, маршруты `auth/github/redirect` и `auth/github/callback`. Кнопка «Войти через GitHub» добавлена в форму `/login`.

**Скриншот:**  
![кнопка GitHub на странице входа](screenshots/25-login-with-github.png)

### 21. Полный OAuth flow
Нажатие на кнопку → редирект на GitHub → авторизация → callback → поиск/создание пользователя по `github_id` → вход.

**Скриншоты:**  
![авторизация на GitHub](screenshots/26-github-authorize.png)  
![после входа — имя GitHub в navbar](screenshots/27-after-github-login.png)  
![запись с github_id в БД](screenshots/28-mysql-github-id.png)

**Защитный вопрос:** *Сравните количество строк кода ручного OAuth (Lab11) и Socialite.*
Строк кода меньше в десятки раз - пропала чихарда с токенами при передаче на стронний сервис, генерацией сессий, поиском\созданием пользователей (понимаю, не пропала, а скрылась за пакетами ларавеля, но мы её уже не прописываем, не видим, не трогаем, лишь используем)

---

## Часть E. Архитектурные вопросы

### 22. Что осталось от прошлых практик
У вас на VPS лежат /var/www/boardy-legacy/ (старый PHP) и БД boardy. Зачем мы их не удалили? Что произойдёт, если попробовать открыть https://фамилия.ai-info.ru/login.php (старый PHP-логин)?

Они не мешают работе Laravel (разные корни, разные БД), при откате к можно быстро переключить document_root, если попробовать открыть https://фамилия.ai-info.ru/login.php (старый PHP-логин) - этот файл просто не найдётся

### 23. FastAPI и React
FastAPI продолжает работать на api.фамилия.ai-info.ru, а React-файлы лежат в Lab9–11. Но в Laravel-проекте мы их не используем. Почему сейчас не используем — что мешает интегрировать? Где они нам пригодятся в Lab13?

Мешает файт того, что FastApi и Laravel не общаются и FastApi не умеет проверять пользователей Laravel. Пригодится при внедрении redis и когда будем ставить passport
### 24. Реалтайм
Сейчас комментарии появляются только после F5. Какое архитектурное решение нам нужно, чтобы один пользователь видел новый комментарий другого без перезагрузки? Какие два сервера-кандидата для этого решения и почему именно они?

- **Laravel Reverb** (WebSocket-сервер) – нативный для Laravel, прост в настройке, хорошо работает с Redis.
- **Node.js + Socket.io** – более гибкий, но требует отдельного сервера.

Reverb оставляет всё в экосистеме PHP/Laravel, Node.js даёт больше контроля и производительности для высоких нагрузок.