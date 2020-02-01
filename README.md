## Установка проекта

После клонирования проекта из репозитория для создания необходимых папок запустить скрипт /protected/tools/init_project.sh

Пропишите в файле protected/config/.env данные для доступа к БД для разработки и тестирования. В данные для тестирование впишите значения из файла protected/tests/integration.suite.yml 

## Настройки веб-сервера
* PHP 7.2 (с модулями: curl, mbstring)
* MySQL
* Composer
* Yii framework 1.1 (в папке "framework")
* В настройках PHP (php.ini) post_max_size = 10M; и upload_max_filesize = 10M;

## Запуск тестов
**Внимание:** перед запуском тестов установите константе YII_ENV в файле protected/config/settings.php значение test.

Тесты запускаются из директории protected
```
Unit:
vendor/bin/codecept run tests/unit

Unit с отчетом по покрытию:
vendor/bin/codecept run tests/unit --coverage --coverage-html 

Интеграционные:
vendor/bin/codecept run tests/unit
```
