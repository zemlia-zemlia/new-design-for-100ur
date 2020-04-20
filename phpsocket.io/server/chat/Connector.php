<?php


class Connector
{
    private $db;
    private $filePath = '';
    private $dbSeting = [];

    function __construct()
    {
        $dotenv = new Symfony\Component\Dotenv\Dotenv();
        $dotenv->load(__DIR__ . '/../../../protected/config/.env');
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
        $sth = $this->db->prepare("SELECT * FROM `100_transactionCampaign` WHERE `id` = :id and status = 3");
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
        $sth = $this->db->prepare('delete from 100_chat where id = :chat_id');
        $sth->execute(['chat_id' => $chatId]);
        $sth = $this->db->prepare('delete from 100_chat_messages where chat_id = :chat_id');
        $sth->execute(['chat_id' => $chatId]);
    }

    /**
     * Сохранить сообщение
     */
    public function saveMessage($data)
    {
        $sth = $this->db->prepare('INSERT INTO `100_chat_messages`( `chat_id`, `user_id`, `message`, `created`) VALUES (:chat_id, :user_id,:message,:created)');
        $sth->execute([
            ':chat_id' => $this->getChatId($data['room']),
            ':user_id' => $this->getUserId($data['token']),
            ':message' => strip_tags($data['message'], '<a>'),
            ':created' => time(),
        ]);
    }

    /**
     * Проверим на наличие чата
     * @param $room
     * @return string
     * @throws Exception
     */
    public function checkForChat($data)
    {
        $roomName = $data['room'];
        $sth = $this->db->prepare("SELECT * FROM `100_chat` WHERE `chat_id` = :id");
        $sth->execute(['id' => $roomName]);
        $chatData = $sth->fetch(PDO::FETCH_ASSOC);
        if (!$chatData and $data['role'] == 3) {
            $userData = explode('_', $roomName);
            $userId = $this->getUserId($data['token']);
            $layer = $this->getUserById($userData[1]);
            if (!$userId || !$layer) {
                throw new Exception('Пользователь или юрист не найден');
            }
            $sth = $this->db->prepare('INSERT INTO `100_chat`(`user_id`, `lawyer_id`, `created`, `chat_id`) 
            VALUES (:user_id, :layer_id, :created, :chat_id)'
            );
            $message = '<html><body>Поступил запрос на новый чат <a href="https://100yuristov.com/user/chats/chatId/' . $roomName . '"> Смотреть </a></body></html>';
            $headers = "Content-type: text/html; charset=UTF-8 \r\n";
            $headers .= "From: admin@100yuristov.com\r\n";
            $headers .= "Reply-To: admin@100yuristov.com\r\n";
            mail($layer['email'], 'Запрос на новый чат', $message, $headers);
            $sth->execute([
                ':user_id' => $userId,
                ':layer_id' => $layer['id'],
                ':chat_id' => $roomName,
                ':created' => time(),
            ]);

            return 'newChat';
        } else {
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
            var_dump($result);
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
            return $data['priceConsult'];
        }
    }

    /**
     * Получить id тользователя
     * @param $token
     * @return mixed
     */
    private function getUserId($token)
    {
        $sth = $this->db->prepare("SELECT id FROM `100_user` WHERE `confirm_code` = :id");
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
        $sth = $this->db->prepare("SELECT avatar FROM `100_user` WHERE `confirm_code` = :id");
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
        $sth->execute(array('id' => $id));
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
            $sth = $this->db->prepare("INSERT INTO `100_chatFiles` (`id`, `name`, `mime`, `filename`, `user_id`, `created`) VALUES (NULL, :name, :mime, :filename, :userId, :created)");
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
                'link' => '/' . $_ENV['CHAT_UPLOUD_FOLDER'] . '/' . $filename,
                'userId' => $userId
            ];
        }

    }
}
