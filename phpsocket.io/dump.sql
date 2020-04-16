CREATE TABLE 100_chatFiles` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(255) NOT NULL , `mime` VARCHAR(100) NOT NULL , `filename` VARCHAR(255) NOT NULL , `user_id` INT NOT NULL , `created` INT NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = MyISAM;
CREATE TABLE `100_chat` ( `id` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL , `layer_id` INT NULL , `is_payed` TINYINT(1) NULL , `transaction_id` VARCHAR(255) NULL , `created` INT NOT NULL , `is_closed` TINYINT(1) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE 100_chat` ADD INDEX (`user_id`);
ALTER TABLE 100_chat` ADD INDEX (`layer_id`);
ALTER TABLE `100_chat` ADD INDEX (`created`);
ALTER TABLE `100_chat` ADD `chat_id` VARCHAR(255) NULL AFTER `is_closed`;
CREATE TABLE `100_chat_messages` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `100_chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_id` (`chat_id`);
ALTER TABLE `100_chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  ALTER TABLE `100_chat` ADD `is_confirmed` TINYINT NULL DEFAULT NULL AFTER `chat_id`;
