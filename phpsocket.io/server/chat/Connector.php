<?php

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class Connector
{
    private $db;
    private $filePath = '';
    private $dbSeting = [];

    /** @var Monolog\Logger */
    private $logger;

    public function __construct()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../../protected/config");
        $dotenv->load();
        $this->dbSeting = [
            'dbName' => $_ENV['DB_NAME'],
            'login' => $_ENV['DB_USER'],
            'pass' => $_ENV['DB_PASSWORD'],
            'host' => $_ENV['DB_HOST']
        ];
        $this->filePath = __DIR__ . '/../../../' . $_ENV['CHAT_UPLOUD_FOLDER'] . '/';
        try {
            $this->db = new PDO('mysql:dbname=' . $this->dbSeting['dbName'] . ';charset=UTF8' . ';host=' . $this->dbSeting['host'], $this->dbSeting['login'], $this->dbSeting['pass']);
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        $this->logger = new Logger('chat');
        $this->logger->pushHandler(new RotatingFileHandler(__DIR__ . '/../../../protected/runtime/chat_logs/chat.log', 30, Logger::DEBUG));
    }

    /**
     * Одобрить чат
     * @param $id
     */
    public function accept($id)
    {
        $sth = $this->db->prepare('update 100_chat set is_confirmed = :type where id = :chat_id');
        $sth->execute(['chat_id' => $id, 'type' => 1]);
    }

    /**
     * закрыть чат
     * @param $room
     */
    public function closeChat($room)
    {
        $sth = $this->db->prepare('update 100_chat set is_closed = 1 where chat_id = :chat_id');
        $sth->execute(['chat_id' => $room]);
        $sth = $this->db->prepare("SELECT transaction_id, lawyer_id FROM `100_chat` WHERE `chat_id` = :id");
        $sth->execute(['id' => $room]);
        $chatData = $sth->fetch(PDO::FETCH_ASSOC);

        $sth = $this->db->prepare("SELECT * FROM `100_transactionCampaign` WHERE `id` = :id and status = 3"); // TransactionCampaign::STATUS_HOLD = 3
        $sth->execute(['id' => $chatData['transaction_id']]);
        $trans = $sth->fetch(PDO::FETCH_ASSOC);
        $sth = $this->db->prepare("SELECT * FROM `100_user` WHERE `id` = :id");
        $sth->execute(array('id' => $chatData['lawyer_id']));
        $layer = $sth->fetch(PDO::FETCH_ASSOC);
        $layer['balance'] = $layer['balance'] + $trans['sum'];
        $sth = $this->db->prepare('update 100_user set balance = :bal where id = :id');
        $sth->execute(['id' => $layer['id'], 'bal' => $layer['balance']]);
        $sth = $this->db->prepare('update 100_transactionCampaign set status = 1 where id = :id');
        $sth->execute(['id' => $chatData['transaction_id']]);
    }

    /**
     * Отклонить чат
     * @param $chatId
     */
    public function declineChat($chatId)
    {
        $sth = $this->db->prepare('update 100_chat set is_closed = 1 where id = :id');
        $sth->execute(['id' => $chatId]);
        $sth = $this->db->prepare('delete from 100_chat_messages where chat_id = :chat_id');
        $sth->execute(['chat_id' => $chatId]);
    }

    /**
     * Сохранить сообщение
     */
    public function saveMessage($data)
    {
        $id = $this->getChatId($data['room']);
        $time = time();
        $sth = $this->db->prepare('INSERT INTO `100_chat_messages`( `chat_id`, `user_id`, `message`, `created`) VALUES (:chat_id, :user_id,:message,:created)');
        $sth->execute([
            ':chat_id' => $id,
            ':user_id' => $this->getUserId($data['token']),
            ':message' => strip_tags($data['message'], '<a>'),
            ':created' => $time,
        ]);
        $sth = $this->db->prepare('update 100_chat set created = :created where id = :id');
        $sth->execute(['id' => $id, ':created' => $time,]);
    }

    /**
     * Проверим на наличие чата
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function checkForChat($data)
    {
        $this->logger->addDebug('check for chat', ['data' => $data]);

        $roomName = $data['room'];
        $sth = $this->db->prepare("SELECT * FROM `100_chat` WHERE `chat_id` = :id");
        $sth->execute(['id' => $roomName]);
        $chatData = $sth->fetch(PDO::FETCH_ASSOC);
        $this->logger->addDebug('chat data', ['data' => $chatData]);
        if (!$chatData and $data['role'] == 3) { // пользователь
            $userData = explode('_', $roomName);
            $userId = $this->getUserId($data['token']);
            $layer = $this->getUserById($userData[1]);

            $this->logger->addDebug('Yurist', ['yurist' => $layer]);

            if (!$userId || !$layer) {
                throw new Exception('Пользователь или юрист не найден');
            }
            $sth = $this->db->prepare('INSERT INTO `100_chat`(`user_id`, `lawyer_id`, `created`, `chat_id`) 
            VALUES (:user_id, :layer_id, :created, :chat_id)'
            );

            $sth->execute([
                ':user_id' => $userId,
                ':layer_id' => $layer['id'],
                ':chat_id' => $roomName,
                ':created' => time(),
            ]);

            return 'newChat';
        } else {
            $this->logger->addDebug('Else case');

            $result = 'newChat';
            if ($chatData['is_payed'] and !$chatData['is_closed']) {
                $result = 'ok';

            } elseif ($chatData['is_closed']) {
                $result = 'closed';

            } elseif ($chatData['is_confirmed'] == null) {
                $result = 'newChat';
            } elseif ($chatData['is_confirmed'] == 1) {
                $result = 'confirmed';
            } elseif ($chatData['is_confirmed'] == -1) {
                $result = 'decline';
                $this->closeChat($roomName);
            }
            return $result;
        }

    }

    /**
     * Получить цену чата
     * @param $id
     * @return mixed
     */
    public function getLayerPrice($id)
    {
        $sth = $this->db->prepare("SELECT priceConsult FROM `100_yuristSettings` WHERE `yuristId` = :id");
        $sth->execute(array('id' => $id));
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        if ($data and $data['priceConsult']) {
            return floor($data['priceConsult'] / 100);
        } else {
            return 0;
        }

    }

    /**
     * Получить id тользователя
     * @param $token
     * @return mixed
     */
    private function getUserId($token)
    {
        $sth = $this->db->prepare("SELECT id FROM `100_user` WHERE `chatToken` = :id");
        $sth->execute(array('id' => $token));
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        return $data['id'];
    }

    /**
     * Получить аватар
     * @param $token
     * @return string
     */
    public function getAvatar($token)
    {
        $sth = $this->db->prepare("SELECT avatar FROM `100_user` WHERE `chatToken` = :id");
        $sth->execute(array('id' => $token));
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        if ($data['avatar']) {
            return '/upload/userphoto/thumbs/' . $data['avatar'];
        } else {
            return '/pics/yurist.png';
        }
    }

    /**
     * @param $token
     * @return mixed
     */
    public function getChatId($token)
    {
        $sth = $this->db->prepare("SELECT id FROM `100_chat` WHERE `chat_id` = :id");
        $sth->execute(array('id' => $token));
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        return $data['id'];
    }

    /**
     * @param $id
     * @return mixed
     */
    private function getUserById($id)
    {
        $sth = $this->db->prepare("SELECT * FROM `100_user` WHERE `id` = :id");
        $sth->execute(['id' => $id]);

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param $fileData
     * @param $token
     * @return array|null
     */
    public function saveFile($fileData, $token)
    {
        $userId = $this->getUserId($token);
        if (!$userId) {
            echo 'Нет пользователя';
            return null;
        }
        foreach ($fileData as $file) {
            $filename = uniqid('', true) . '_' . $file['origin_name'];
            file_put_contents($this->filePath . $filename, base64_decode($file['base64']));
            $sth = $this->db->prepare("INSERT INTO `100_chat_files` (`id`, `name`, `mime`, `filename`, `user_id`, `created`) VALUES (NULL, :name, :mime, :filename, :userId, :created)");
            $sth->execute([
                ':name' => $file['origin_name'],
                ':mime' => $file['type'],
                ':filename' => $filename,
                ':userId' => $userId,
                ':created' => time(),
            ]);
            return [
                'name' => $file['origin_name'],
                'mime' => $file['type'],
                'link' => '/site/getfile?fileName=' . $filename,
                'userId' => $userId
            ];
        }

    }
}
