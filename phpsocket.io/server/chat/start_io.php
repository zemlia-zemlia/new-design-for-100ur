<?php

use Workerman\Worker;
use Workerman\WebServer;
use PHPSocketIO\SocketIO;

require_once join(DIRECTORY_SEPARATOR, array(__DIR__, "..", "..", "..", "protected", "vendor", "autoload.php"));

require_once './Connector.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../protected/config/');
$dotenv->load();

$io = new SocketIO($_ENV['CHAT_PORT']);

$io->on('connection', function ($socket) use ($io) {


    $socket->on('join room', function ($data) use ($socket, $io) {
        $socket->join($data['room']);
        $room = $data['room'];

        $connector = new Connector();
        $userData = explode('_', $data['room']);
        $layerId = $userData[1];
        try {
            $check = $connector->checkForChat($data);
            switch ($check) {
                case 'newChat':
                    $io->sockets->to($room)->emit('newChat', []);
                    break;
                case 'confirmed':
                    $price = $connector->getLayerPrice($layerId);
                    $chatId = $connector->getChatId($room);
                    $io->sockets->to($room)->emit('openPayForm', ['lawyer_id' => $layerId, 'chatId' => $chatId, 'price' => $price, 'message' => 'Необходимо оплатить услуги юриста', 'username' => '']);
                    break;
                case 'decline':
                    $io->sockets->to($room)->emit('decline', []);
                    break;
                case 'closed':
                    $io->sockets->to($room)->emit('chatClosed', []);
                    break;

                default:
                    $io->sockets->to($room)->emit('openChat', []);
            }
            unset($connector);
        } catch (\Exception $exception) {
            $io->sockets->to($room)->emit('error', ['message' => $exception->getMessage(), 'username' => '']);
        }
    });

    $socket->addedUser = false;
    // Новое сообщение в чате
    $socket->on('new message', function ($data) use ($socket, $io) {
        if (isset($data['room']) and $data['room']) {
            $connector = new Connector();
            $connector->saveMessage($data);
            $io->sockets->to($data['room'])->emit('new message', array(
                'username' => $data['username'],
                'avatar' => $connector->getAvatar($data['token']),
                'message' => strip_tags($data['message'], '<a>'),
                'token' => $data['token'],
                'date' => date('H:i'),
            ));
            unset($connector);
        } else {
            $socket->broadcast->emit('new message', array(
                'username' => $socket->username,
                'message' => $data
            ));

        }
    });
    // Одобрить чат
    $socket->on('accept chat', function ($data) use ($socket, $io) {
        $userData = explode('_', $data['room']);
        $layerId = $userData[1];
        $connector = new Connector();
        $price = $connector->getLayerPrice($layerId);
        $chatId = $connector->getChatId($data['room']);
        $connector->accept($chatId);
        unset($connector);
        $io->sockets->to($data['room'])->emit('openPayForm', ['lawyer_id' => $layerId, 'chatId' => $chatId, 'price' => $price, 'message' => 'Необходимо оплатить услуги юриста', 'username' => '']);

    });

    // Отклонить чат
    $socket->on('decline chat', function ($data) use ($socket, $io) {
        $userData = explode('_', $data['room']);
        $connector = new Connector();
        $chatId = $connector->getChatId($data['room']);
        $connector->declineChat($chatId);
        unset($connector);
        $io->sockets->to($data['room'])->emit('chatClosed', []);
        $io->sockets->to($data['room'])->emit('decline', []);

    });
    // Закрыть чат
    $socket->on('close chat', function ($room) use ($socket, $io) {
        $connector = new Connector();
        $connector->closeChat($room);
        unset($connector);
        $io->sockets->to($room)->emit('chatClosed', []);
    });
    // Принять файл
    $socket->on('new file', function ($data) use ($socket, $io) {
        $connector = new Connector();
        $fileData = $connector->saveFile($data['files'], $data['token']);
        $message = '<a target="_blank" href="' . $fileData['link'] . '">' . $fileData['name'] . '</a>';
        $connector->saveMessage(['room' => $data['room'], 'token' => $data['token'], 'message' => $message]);

        if ($fileData == null) {
            return;
        }
        if (isset($data['room']) and $data['room']) {
            $io->sockets->to($data['room'])->emit('new message', array(
                'username' => $data['username'],
                'avatar' => $connector->getAvatar($data['token']),
                'message' => $message,
                'token' => $data['token'],
                'date' => date('H:i'),
            ));

            unset($connector);
        } else {


        }
    });
    $socket->on('add user', function ($data) use ($socket, $io) {
        if ($socket->addedUser)
            return;
        global $usernames, $numUsers;

        $socket->username = '';
        ++$numUsers;
        $socket->addedUser = true;
        if (isset($data['room']) and $data['room']) {
            $io->sockets->to($data['room'])->emit('user joined', array(
                'username' => $data['name'],
                'numUsers' => $numUsers
            ));
        }
    });

    // when the client emits 'typing', we broadcast it to others
    $socket->on('typing', function ($data) use ($socket, $io) {
        if (isset($data['room']) and $data['room']) {
            $io->sockets->to($data['room'])->emit('typing', array(
                'username' => $data['username'],
                'token' => $data['token']
            ));
        }
    });
    $socket->on('stop typing', function ($data) use ($socket, $io) {
        if (isset($data['room']) and $data['room']) {
            $io->sockets->to($data['room'])->emit('stop typing', array(
                'username' => $data['username'],
                'token' => $data['token']
            ));
        }
    });
    // when the user disconnects.. perform this
    $socket->on('disconnect', function () use ($socket) {
        global $usernames, $numUsers;
        if ($socket->addedUser) {
            --$numUsers;

            // echo globally that this client has left
            $socket->broadcast->emit('user left', array(
                'username' => $socket->username,
                'numUsers' => $numUsers
            ));
        }
    });

});

if (!defined('GLOBAL_START')) {
    Worker::runAll();
}
