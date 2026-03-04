Задание 1. Установка Nginx
Установите Nginx. Убедитесь, что он работает

Скриншоты:

![01-nginx-status.png](screenshots/01-nginx-status.png)
---
Задание 2. Страница по IP
Откройте IP вашего VPS в браузере. Должна быть дефолтная страница Nginx.

Скриншоты:

![«Welcome to nginx!» в браузере (IP виден в адресной строке)](screenshots/02-browser-ip.png)
---
Задание 3. curl
Проверьте сайт через curl

Вывод:
> GET / HTTP/1.1 - Строка запроса
< HTTP/1.1 200 OK - Код ответа
< Content-Type: text/html - Тип контента

Скриншоты:

![вывод curl -v](screenshots/03-curl.png)
---
Задание 4. Директория и права
Найдите файл, который Nginx отдаёт. Смените владельца на student

Скриншоты:

![вывод ls -la /var/www/ ДО и ПОСЛЕ chown](screenshots/04-permissions.png)
---
Задание 5. Конфигурация Nginx
Посмотрите дефолтный конфиг.

listen 80 default_server;
listen [::]:80 default_server;
Указывает какой порт прослушивает сервер

root /var/www/html;
Путь к корневой папке сайта с html-документами

index index.html index.htm index.nginx-debian.html;
Названия файлов, которые Nginx будет искать при обращении к директории

server_name _;
Доменное имя сервера (_ - любое)
---
Задание 6. DNS-зона
Создайте DNS-зону в VK Cloud: bagaev.ai-info.ru.

Скриншоты:

![панель VK Cloud с созданной зоной](screenshots/05-dns-zone.png)
---
Задание 7. A-запись
Создайте A-запись: фамилия.ai-info.ru → IP вашего VPS. TTL = 300

Скриншоты:

![A-запись в панели VK Cloud (домен, IP, TTL видны)](screenshots/06-a-record.png)
---
Задание 8. ping
Скриншоты:

![вывод ping (домен резолвится в IP VPS)](screenshots/07-ping.png)
---
Задание 9. dig
;; QUESTION SECTION:
;bagaev.ai-info.ru.        IN    A

;; ANSWER SECTION:
bagaev.ai-info.ru.    60    IN    A    10.128.0.6

;; SERVER: 127.0.0.53#53(127.0.0.53) (UDP)
Скриншоты:

![вывод dig с подписями](screenshots/08-dig.png)
---
Задание 10. dig +trace

.                       4898    IN      NS      a.root-servers.net.
.                       4898    IN      NS      h.root-servers.net.
.                       4898    IN      NS      g.root-servers.net.
.                       4898    IN      NS      e.root-servers.net.
.                       4898    IN      NS      k.root-servers.net.
.                       4898    IN      NS      i.root-servers.net.
.                       4898    IN      NS      l.root-servers.net.
.                       4898    IN      NS      j.root-servers.net.
.                       4898    IN      NS      c.root-servers.net.
.                       4898    IN      NS      b.root-servers.net.
.                       4898    IN      NS      d.root-servers.net.
.                       4898    IN      NS      f.root-servers.net.
.                       4898    IN      NS      m.root-servers.net.
;; Received 503 bytes from 127.0.0.53#53(127.0.0.53) in 0 ms

ru.                     172800  IN      NS      e.dns.ripn.net.
ru.                     172800  IN      NS      b.dns.ripn.net.
ru.                     172800  IN      NS      d.dns.ripn.net.
ru.                     172800  IN      NS      a.dns.ripn.net.
ru.                     172800  IN      NS      f.dns.ripn.net.
;; Received 695 bytes from 202.12.27.33#53(m.root-servers.net) in 65 ms

ai-info.ru.             345600  IN      NS      ns1.netangels.ru.
ai-info.ru.             345600  IN      NS      ns2.netangels.ru.
ai-info.ru.             345600  IN      NS      ns3.netangels.ru.
ai-info.ru.             345600  IN      NS      ns4.netangels.ru.
;; Received 675 bytes from 194.190.124.17#53(d.dns.ripn.net) in 36 ms

ai-info.ru.             3600    IN      SOA     ns1.netangels.ru. hostmaster.netangels.ru. 1772650794 3600 1800 1209600 3600
;; Received 143 bytes from 91.201.54.2#53(ns1.netangels.ru) in 28 ms

Слишком поздно отправил доменное имя, поэтому нет A-запроса

Скриншоты:

![вывод dig +trace](screenshots/09-dig-trace.png)

Задание 11. Сайт по домену
Откройте домен в браузере. Должна быть та же дефолтная страница Nginx

Слишком поздно отправил доменное имя, поэтому не отображается в браузере

Скриншоты:

![страница Nginx в браузере (домен виден в адресной строке)](screenshots/10-browser-domain.png)