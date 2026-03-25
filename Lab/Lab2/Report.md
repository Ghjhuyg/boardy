Задание 1. SSH-ключ
Сгенерируйте SSH-ключ и покажите результат:

Скриншоты:

![вывод ssh-keygen и ls](screenshots/01-ssh-keygen.png )
---
Задание 2. VPS и файрвол
Создайте VPS в VK Cloud (Ubuntu 22.04, SSH-ключ). Настройте файрвол: TCP 22, 80, 443 + ICMP в обоих направлениях.

Я пользовался Yandex Cloud, на скришотах 2 и 3 представленны соответсвующие аналоги панелей VK Cloud vps и firewall на Yandex Cloud. (На втором скриншоте не видно ip, он становится виден только при запуске ВМ)

Скриншоты:

![панель Yandex Cloud с VPS (IP не виден)](screenshots/02-vps.png)
![правила файрвола](screenshots/03-firewall.png)
---
Задание 3. Подключение через PuTTY
Подключитесь к VPS через PuTTY с SSH-ключом (.ppk).

Скриншоты:

![терминал PuTTY после подключения](screenshots/04-putty.png)
---
Задание 4. Настройка сервера
Изменили временную зону и имя хоста

Скриншоты:

![hostname (фамилия) + timedatectl (Moscow)](screenshots/05-hostname.png)
---
Задание 5. Пользователь student
Создайте пользователя student, скопируйте SSH-ключ, переподключитесь

Скриншоты:

![приглашение student@фамилия:~$](screenshots/06-student.png)
---
Задание 6. Git и SSH-ключ → GitHub
Установите Git, настройте имя/email. Добавьте SSH-ключ VPS в GitHub. Проверьте:

Скриншоты:

![конфиг git](screenshots/07-git-config.png)
![подключение к git по ssh](screenshots/08-ssh-github.png)
---
Задание 7. Репозиторий и структура
Клонируйте (или создайте) репозиторий boardy. Создайте структуру Lab/Lab1/, Lab/Lab2/.

Скриншоты:

![репозиторий на GitHub с папками Lab/](screenshots/09-github-repo.png)
---
Задание 8. Ветка и Pull Request
Создайте ветку lab2, добавьте отчёт и скриншоты, отправьте ветку на GitHub, создайте Pull Request:

Скриншоты:

![созданный PR на GitHub (видно diff)](screenshots/10-pull-request.png)