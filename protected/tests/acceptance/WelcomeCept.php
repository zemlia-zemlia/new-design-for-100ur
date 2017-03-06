<?php
/**
 * Тестирование главной страницы, входа и выхода
 */
$I = new AcceptanceTester($scenario);
$I->amOnPage('/');
$I->see('ПОСЛЕДНИЕ ВОПРОСЫ ЮРИСТАМ И АДВОКАТАМ ПОРТАЛА');
// есть форма входа?
$I->see('ВХОД НА САЙТ');
$I->seeElement('form');
$I->fillField('#LoginForm_email', 'risamabel@mail.ru');
$I->fillField('#LoginForm_password', '12345');
$I->click('Войти');
$I->wait(3);
// удалось войти под юристом?
$I->see('Марина Сергеева');
$I->see('СЧЕТЧИКИ ВАШИХ ОТВЕТОВ');
// есть ссылка для выхода?
$I->seeElement('.glyphicon-log-out');
$I->click('.glyphicon-log-out');
$I->wait(3);
// удалось выйти?
$I->see('ВХОД НА САЙТ');
