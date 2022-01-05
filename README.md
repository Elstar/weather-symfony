# weather-symfony
- Создана консольная команда app:GetTemp которая по Api забирает текущую температуру в кельвинах у города указанного в env файле в константе **CITY**.
- Эту команду можно использовать для создания cronjob и запуска его раз в час.
- Данные по температуре фиксируются в БД
### Получение температуры за день
- Чтобы получить температуру за день нужно в хедерах запроса передать X-AUTH-TOKEN (сравнивается с токеном который хранится в env файле в константе **TOKEN**)
- Ссылка для получения статистики /api/weather?day=2022-01-05
- Все проверки осуществляются в контроллере **ApiWeatherController.php**
- Формат возвращение данных **JSON**
