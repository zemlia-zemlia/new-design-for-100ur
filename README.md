## Установка проекта

См. https://gitlab.com/mkrutikov/100yuristov/-/wikis/%D0%A0%D0%B0%D0%B7%D0%B2%D0%B5%D1%80%D1%82%D1%8B%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5-%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%82%D0%B0-%D0%BD%D0%B0-%D0%BD%D0%BE%D0%B2%D0%BE%D0%BC-%D0%BA%D0%BE%D0%BC%D0%BF%D1%8C%D1%8E%D1%82%D0%B5%D1%80%D0%B5

Скопируйте файл protected/config/.env.example в protected/config/.env\
Пропишите в файле protected/config/.env данные для доступа к БД для разработки и тестирования. В данные для тестирование впишите значения из файла protected/tests/integration.suite.yml 

## Настройки веб-сервера
* В настройках PHP (php.ini) post_max_size = 10M; и upload_max_filesize = 10M;

## Запуск тестов
**Внимание:** перед запуском тестов установите переменной ENV в файле protected/config/.env значение test.

Тесты запускаются из директории protected
```
Unit:
vendor/bin/codecept run tests/unit

Unit с отчетом по покрытию:
vendor/bin/codecept run tests/unit --coverage --coverage-html 

Интеграционные:
vendor/bin/codecept run tests/unit
```
