## Events RESTful API

Управление сущностью событий (events) через стандартизированные HTTP-запросы с защитой по API-ключу. 

### Получение API-ключа

- [Сгенерировать API-ключ](generate_key.php)
- Ключ необходим для всех запросов к API:  
  `X-API-Key: ваш_ключ` (указывается в заголовках)

### Эндпоинты для работы с событиями

| Метод и путь         | Описание                       | Данные запроса                                                  |
|----------------------|--------------------------------|-----------------------------------------------------------------|
| `GET /events`        | Получить все события           | —                                                               |
| `GET /events/{id}`   | Получить событие по id         | —                                                               |
| `POST /events`       | Создать новое событие          | JSON:<br>`{"title": "...", "date": "YYYY-MM-DD HH:MM:SS", "location": "...", "description": "..."}` |
| `PUT /events/{id}`   | Обновить событие по id         | JSON:<br>`{"title": "...", "date": "YYYY-MM-DD HH:MM:SS", "location": "...", "description": "..."}` |
| `DELETE /events/{id}`| Удалить событие по id          | —                                                               |

### Пример запроса в curl
bash
curl -H "Content-Type: application/json" \
     -H "X-API-Key: ваш_ключ" \
     http://localhost/api_db/events
   
### Коды ответов
| |
|----------------------|
|200 OK — успешные запросы|
|201 Created — создание новой записи|
|400 Bad Request — ошибка в параметрах запроса|
|401 Unauthorized — нет или неверный API-ключ|
|404 Not Found — запись не найдена|
|500 Internal Server Error — ошибка на сервере|

### Протестируйте через Postman/Insomnia
Обязательный заголовок: X-API-Key
Content-Type: application/json для POST/PUT
