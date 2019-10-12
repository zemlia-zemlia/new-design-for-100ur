-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 25, 2019 at 12:05 PM
-- Server version: 5.7.27-0ubuntu0.18.04.1
-- PHP Version: 7.2.19-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `100yuristov_test`
--

-- --------------------------------------------------------
DROP DATABASE `100yuristov_test`;
CREATE DATABASE `100yuristov_test` DEFAULT CHARACTER SET utf8;
USE `100yuristov_test`;

--
-- Table structure for table `100_answer`
--

CREATE TABLE `100_answer` (
  `id` int(11) NOT NULL,
  `questionId` int(11) NOT NULL,
  `answerText` text NOT NULL,
  `videoLink` varchar(255) NOT NULL,
  `authorId` int(11) NOT NULL DEFAULT '0',
  `datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '0',
  `karma` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_apilog`
--

CREATE TABLE `100_apilog` (
  `id` int(11) NOT NULL,
  `dateTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `route` varchar(255) NOT NULL,
  `requestData` text NOT NULL,
  `duration` float NOT NULL,
  `ip` varchar(255) NOT NULL,
  `response` text NOT NULL,
  `responseCode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_campaign`
--

CREATE TABLE `100_campaign` (
  `id` int(11) NOT NULL,
  `regionId` int(11) NOT NULL,
  `townId` int(11) NOT NULL,
  `timeFrom` int(11) NOT NULL DEFAULT '0',
  `timeTo` int(11) NOT NULL DEFAULT '24',
  `price` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  `leadsDayLimit` int(11) NOT NULL,
  `realLimit` int(11) NOT NULL DEFAULT '0',
  `brakPercent` int(11) NOT NULL,
  `buyerId` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `lastLeadTime` datetime DEFAULT NULL,
  `sendEmail` tinyint(4) NOT NULL DEFAULT '1',
  `days` varchar(255) NOT NULL DEFAULT '1,2,3,4,5,6,7'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_cat2follower`
--

CREATE TABLE `100_cat2follower` (
  `catId` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_comment`
--

CREATE TABLE `100_comment` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL,
  `authorId` int(11) NOT NULL,
  `objectId` int(11) NOT NULL,
  `text` text NOT NULL,
  `dateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rating` smallint(6) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `authorName` varchar(255) NOT NULL,
  `root` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `seen` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_country`
--

CREATE TABLE `100_country` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_doctype`
--

CREATE TABLE `100_doctype` (
  `id` int(11) NOT NULL,
  `class` tinyint(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  `minPrice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `100_expence`
--

CREATE TABLE `100_expence` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `expences` decimal(6,2) NOT NULL COMMENT 'суммарный расход на контекст',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `comment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_karmaChange`
--

CREATE TABLE `100_karmaChange` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `authorId` int(11) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `answerId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_lead2category`
--

CREATE TABLE `100_lead2category` (
  `id` int(11) NOT NULL,
  `leadId` int(11) NOT NULL,
  `cId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_lead100`
--

CREATE TABLE `100_lead100` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sourceId` int(11) NOT NULL,
  `question` text NOT NULL,
  `question_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `townId` int(11) NOT NULL,
  `leadStatus` tinyint(4) NOT NULL,
  `questionId` int(11) NOT NULL,
  `contactId` int(11) NOT NULL,
  `addedById` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `campaignId` int(11) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  `deliveryTime` datetime DEFAULT NULL,
  `brakReason` int(11) DEFAULT NULL,
  `brakComment` varchar(255) NOT NULL,
  `secretCode` varchar(255) NOT NULL,
  `buyPrice` int(11) NOT NULL,
  `buyerId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_leadsource100`
--

CREATE TABLE `100_leadsource100` (
  `id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Тип источника',
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `officeId` int(11) NOT NULL,
  `noLead` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `appId` varchar(255) NOT NULL,
  `secretKey` varchar(255) NOT NULL,
  `userId` int(11) NOT NULL COMMENT 'id пользователя',
  `moderation` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'требуется премодерация лидов'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_money`
--

CREATE TABLE `100_money` (
  `id` int(11) NOT NULL,
  `accountId` tinyint(4) NOT NULL,
  `datetime` date NOT NULL,
  `type` tinyint(1) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `direction` int(11) NOT NULL,
  `isInternal` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_order`
--

CREATE TABLE `100_order` (
  `id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `itemType` tinyint(3) UNSIGNED NOT NULL,
  `price` int(11) NOT NULL,
  `description` text NOT NULL,
  `userId` int(11) NOT NULL,
  `juristId` int(11) NOT NULL DEFAULT '0',
  `term` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_orderresponse`
--

CREATE TABLE `100_orderresponse` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL,
  `authorId` int(11) NOT NULL,
  `objectId` int(11) NOT NULL,
  `text` text NOT NULL,
  `dateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rating` smallint(6) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `authorName` varchar(255) NOT NULL,
  `root` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `price` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_partnerTransaction`
--

CREATE TABLE `100_partnerTransaction` (
  `id` int(11) NOT NULL,
  `partnerId` int(11) NOT NULL,
  `sourceId` int(11) NOT NULL,
  `sum` decimal(9,2) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `leadId` int(11) NOT NULL,
  `questionId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_post`
--

CREATE TABLE `100_post` (
  `id` int(11) NOT NULL,
  `authorId` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `preview` text NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rating` int(11) NOT NULL DEFAULT '0',
  `update_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `datePublication` date NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_post2cat`
--

CREATE TABLE `100_post2cat` (
  `postId` int(11) NOT NULL,
  `catId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_postcategory`
--

CREATE TABLE `100_postcategory` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `alias` varchar(256) NOT NULL,
  `avatar` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_postComment`
--

CREATE TABLE `100_postComment` (
  `id` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `authorId` int(11) NOT NULL,
  `text` text NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_postRatingHistory`
--

CREATE TABLE `100_postRatingHistory` (
  `id` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `delta` smallint(6) NOT NULL DEFAULT '0',
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_postviews`
--

CREATE TABLE `100_postviews` (
  `postId` int(11) UNSIGNED NOT NULL,
  `views` int(11) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_question`
--

CREATE TABLE `100_question` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `questionText` text NOT NULL,
  `title` varchar(255) NOT NULL,
  `authorName` varchar(255) NOT NULL,
  `townId` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `phone` varchar(255) NOT NULL,
  `createDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) NOT NULL,
  `publishDate` datetime DEFAULT NULL,
  `publishedBy` int(11) NOT NULL,
  `leadStatus` tinyint(4) NOT NULL DEFAULT '1',
  `authorId` int(11) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  `payed` tinyint(1) NOT NULL DEFAULT '0',
  `sessionId` varchar(255) NOT NULL,
  `isModerated` tinyint(1) NOT NULL DEFAULT '0',
  `moderatedBy` int(11) NOT NULL,
  `moderatedTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(255) NOT NULL,
  `townIdByIP` smallint(6) NOT NULL DEFAULT '0',
  `sourceId` int(11) NOT NULL COMMENT 'id источника лидов',
  `buyPrice` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT 'Цена покупки вопроса'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_question2category`
--

CREATE TABLE `100_question2category` (
  `qId` int(11) NOT NULL,
  `cId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_questionCategory`
--

CREATE TABLE `100_questionCategory` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parentId` int(11) NOT NULL,
  `parentDirectionId` int(11) DEFAULT '0',
  `alias` varchar(255) NOT NULL,
  `description1` text NOT NULL,
  `description2` text NOT NULL,
  `seoTitle` varchar(255) NOT NULL,
  `seoDescription` text NOT NULL,
  `seoKeywords` text NOT NULL,
  `seoH1` varchar(255) NOT NULL,
  `isDirection` tinyint(1) NOT NULL DEFAULT '0',
  `root` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_region`
--

CREATE TABLE `100_region` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `countryId` int(11) NOT NULL,
  `buyPrice` int(11) NOT NULL COMMENT 'цена покупки лида у партнеров',
  `sellPrice` int(11) NOT NULL COMMENT 'цена продажи лида покупателям'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_sourceCost`
--

CREATE TABLE `100_sourceCost` (
  `id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `sourceId` int(11) NOT NULL,
  `operatorBonus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_town`
--

CREATE TABLE `100_town` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `regionId` smallint(5) UNSIGNED NOT NULL,
  `countryId` smallint(5) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `alias` varchar(64) NOT NULL,
  `size` int(11) NOT NULL DEFAULT '0',
  `description1` text NOT NULL,
  `description2` text NOT NULL,
  `seoTitle` varchar(255) NOT NULL,
  `seoDescription` varchar(255) NOT NULL,
  `seoKeywords` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `lat` decimal(8,6) DEFAULT NULL,
  `lng` decimal(9,6) DEFAULT NULL,
  `isCapital` tinyint(1) NOT NULL,
  `buyPrice` int(11) NOT NULL COMMENT 'цена покупки лида у партнеров',
  `sellPrice` int(11) NOT NULL COMMENT 'цена продажи лида покупателям'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_transaction`
--

CREATE TABLE `100_transaction` (
  `id` int(10) UNSIGNED NOT NULL,
  `contactId` int(11) NOT NULL,
  `agreementId` int(11) NOT NULL,
  `datePlan` date NOT NULL,
  `datePayment` date DEFAULT NULL,
  `value` decimal(10,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL,
  `form` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_transactionCampaign`
--

CREATE TABLE `100_transactionCampaign` (
  `id` int(11) NOT NULL,
  `buyerId` int(11) NOT NULL,
  `campaignId` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sum` decimal(10,2) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_user`
--

CREATE TABLE `100_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name2` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active100` tinyint(1) NOT NULL DEFAULT '0',
  `confirm_code` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `birthday` date NOT NULL,
  `townId` int(11) NOT NULL DEFAULT '0',
  `townName` varchar(255) NOT NULL,
  `registerDate` date DEFAULT NULL,
  `isSubscribed` tinyint(1) NOT NULL DEFAULT '1',
  `karma` int(11) NOT NULL DEFAULT '0',
  `autologin` varchar(32) NOT NULL,
  `lastActivity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `balance` decimal(8,2) NOT NULL DEFAULT '0.00',
  `lastTransactionTime` datetime NOT NULL,
  `priceCoeff` float NOT NULL DEFAULT '1' COMMENT 'Коэффициент цены для партнера',
  `lastAnswer` timestamp NULL DEFAULT NULL,
  `refId` int(11) NOT NULL COMMENT 'id пользователя, пригласившего текущего',
  `rating` float NOT NULL COMMENT 'рейтинг юриста'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_user2category`
--

CREATE TABLE `100_user2category` (
  `uId` int(11) NOT NULL,
  `cId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_userFile`
--

CREATE TABLE `100_userFile` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(255) NOT NULL,
  `isVerified` tinyint(1) NOT NULL,
  `comment` text NOT NULL,
  `type` tinyint(4) NOT NULL,
  `reason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_userStatusRequest`
--

CREATE TABLE `100_userStatusRequest` (
  `id` int(11) NOT NULL,
  `yuristId` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `isVerified` tinyint(2) NOT NULL DEFAULT '0',
  `vuz` varchar(255) NOT NULL,
  `facultet` varchar(255) NOT NULL,
  `education` varchar(255) NOT NULL,
  `vuzTownId` int(11) NOT NULL,
  `educationYear` int(11) NOT NULL,
  `advOrganisation` varchar(255) NOT NULL,
  `advNumber` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `fileId` int(11) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_yurCompany`
--

CREATE TABLE `100_yurCompany` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `townId` int(11) NOT NULL,
  `metro` varchar(255) NOT NULL,
  `yurName` varchar(255) NOT NULL,
  `phone1` varchar(255) NOT NULL,
  `phone2` varchar(255) NOT NULL,
  `phone3` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `yurAddress` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `yearFound` int(11) NOT NULL DEFAULT '1990',
  `website` varchar(255) NOT NULL,
  `authorId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `100_yuristSettings`
--

CREATE TABLE `100_yuristSettings` (
  `yuristId` int(11) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `startYear` int(11) NOT NULL,
  `description` text NOT NULL,
  `hello` varchar(255) NOT NULL,
  `townId` int(11) NOT NULL DEFAULT '598',
  `status` int(11) NOT NULL DEFAULT '0',
  `isVerified` tinyint(1) NOT NULL DEFAULT '0',
  `vuz` varchar(255) NOT NULL,
  `facultet` varchar(255) NOT NULL,
  `education` varchar(255) NOT NULL,
  `vuzTownId` int(11) NOT NULL,
  `educationYear` int(11) NOT NULL,
  `advOrganisation` varchar(255) NOT NULL,
  `advNumber` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `site` varchar(255) NOT NULL,
  `priceConsult` int(11) NOT NULL,
  `priceDoc` int(11) NOT NULL,
  `phoneVisible` varchar(255) NOT NULL,
  `emailVisible` varchar(255) NOT NULL,
  `subscribeQuestions` tinyint(4) NOT NULL DEFAULT '2'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `phinxlog`
--

CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `100_answer`
--
ALTER TABLE `100_answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `datetime` (`datetime`),
  ADD KEY `status` (`status`),
  ADD KEY `karma` (`karma`),
  ADD KEY `questionId` (`questionId`),
  ADD KEY `authorId` (`authorId`),
  ADD KEY `authorId_2` (`authorId`,`status`);

--
-- Indexes for table `100_campaign`
--
ALTER TABLE `100_campaign`
  ADD PRIMARY KEY (`id`),
  ADD KEY `regionId` (`regionId`),
  ADD KEY `buyerId` (`buyerId`),
  ADD KEY `active` (`active`),
  ADD KEY `timeFrom` (`timeFrom`),
  ADD KEY `timeTo` (`timeTo`),
  ADD KEY `townId` (`townId`);

--
-- Indexes for table `100_cat2follower`
--
ALTER TABLE `100_cat2follower`
  ADD KEY `userId` (`userId`),
  ADD KEY `catId` (`catId`);

--
-- Indexes for table `100_comment`
--
ALTER TABLE `100_comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `authorId` (`authorId`),
  ADD KEY `objectId` (`objectId`),
  ADD KEY `status` (`status`),
  ADD KEY `root` (`root`),
  ADD KEY `lft` (`lft`),
  ADD KEY `rgt` (`rgt`),
  ADD KEY `level` (`level`),
  ADD KEY `seen` (`seen`);

--
-- Indexes for table `100_country`
--
ALTER TABLE `100_country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `100_doctype`
--
ALTER TABLE `100_doctype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `100_expence`
--
ALTER TABLE `100_expence`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date` (`date`,`type`,`comment`) USING BTREE;

--
-- Indexes for table `100_karmaChange`
--
ALTER TABLE `100_karmaChange`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `authorId` (`authorId`,`answerId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `100_lead2category`
--
ALTER TABLE `100_lead2category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leadId` (`leadId`,`cId`);

--
-- Indexes for table `100_lead100`
--
ALTER TABLE `100_lead100`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `townId` (`townId`),
  ADD KEY `leadStatus` (`leadStatus`),
  ADD KEY `price` (`price`),
  ADD KEY `question_date` (`question_date`),
  ADD KEY `sourceId` (`sourceId`),
  ADD KEY `campaignId` (`campaignId`),
  ADD KEY `secretCode` (`secretCode`(5)),
  ADD KEY `deliveryTime` (`deliveryTime`),
  ADD KEY `buyerId` (`buyerId`);

--
-- Indexes for table `100_leadsource100`
--
ALTER TABLE `100_leadsource100`
  ADD PRIMARY KEY (`id`),
  ADD KEY `officeId` (`officeId`),
  ADD KEY `active` (`active`),
  ADD KEY `appId` (`appId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `100_money`
--
ALTER TABLE `100_money`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `100_order`
--
ALTER TABLE `100_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `juristId` (`juristId`);

--
-- Indexes for table `100_orderresponse`
--
ALTER TABLE `100_orderresponse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `authorId` (`authorId`),
  ADD KEY `objectId` (`objectId`),
  ADD KEY `status` (`status`),
  ADD KEY `root` (`root`),
  ADD KEY `lft` (`lft`),
  ADD KEY `rgt` (`rgt`),
  ADD KEY `level` (`level`);

--
-- Indexes for table `100_partnerTransaction`
--
ALTER TABLE `100_partnerTransaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `partnerId` (`partnerId`),
  ADD KEY `leadId` (`leadId`,`questionId`),
  ADD KEY `status` (`status`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `100_post`
--
ALTER TABLE `100_post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `authorId` (`authorId`),
  ADD KEY `datetime` (`datetime`),
  ADD KEY `update_timestamp` (`update_timestamp`),
  ADD KEY `datePublication` (`datePublication`),
  ADD KEY `alias` (`alias`);

--
-- Indexes for table `100_post2cat`
--
ALTER TABLE `100_post2cat`
  ADD KEY `postId` (`postId`),
  ADD KEY `catId` (`catId`);

--
-- Indexes for table `100_postcategory`
--
ALTER TABLE `100_postcategory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alias` (`alias`(10)) USING BTREE;

--
-- Indexes for table `100_postComment`
--
ALTER TABLE `100_postComment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `postId` (`postId`),
  ADD KEY `authorId` (`authorId`);

--
-- Indexes for table `100_postRatingHistory`
--
ALTER TABLE `100_postRatingHistory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `postId` (`postId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `100_postviews`
--
ALTER TABLE `100_postviews`
  ADD UNIQUE KEY `postId` (`postId`);

--
-- Indexes for table `100_question`
--
ALTER TABLE `100_question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `createDate` (`createDate`),
  ADD KEY `publishedBy` (`publishedBy`),
  ADD KEY `leadStatus` (`leadStatus`),
  ADD KEY `townId` (`townId`),
  ADD KEY `authorId` (`authorId`),
  ADD KEY `sessionId` (`sessionId`),
  ADD KEY `isModerated` (`isModerated`),
  ADD KEY `moderatedBy` (`moderatedBy`),
  ADD KEY `sourceId` (`sourceId`),
  ADD KEY `publishDate` (`status`,`publishDate`) USING BTREE,
  ADD KEY `publishDate_2` (`publishDate`);

--
-- Indexes for table `100_question2category`
--
ALTER TABLE `100_question2category`
  ADD UNIQUE KEY `qId_2` (`qId`,`cId`),
  ADD KEY `qId` (`qId`),
  ADD KEY `cId` (`cId`);

--
-- Indexes for table `100_questionCategory`
--
ALTER TABLE `100_questionCategory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alias_2` (`alias`),
  ADD KEY `parentId` (`parentId`),
  ADD KEY `alias` (`alias`),
  ADD KEY `isDirection` (`isDirection`),
  ADD KEY `parentId_2` (`parentId`,`name`),
  ADD KEY `lft` (`lft`,`rgt`),
  ADD KEY `root` (`root`),
  ADD KEY `parentDirectionId` (`parentDirectionId`);

--
-- Indexes for table `100_region`
--
ALTER TABLE `100_region`
  ADD PRIMARY KEY (`id`),
  ADD KEY `countryId` (`countryId`);

--
-- Indexes for table `100_sourceCost`
--
ALTER TABLE `100_sourceCost`
  ADD PRIMARY KEY (`id`),
  ADD KEY `month` (`month`,`year`),
  ADD KEY `sourceId` (`sourceId`);

--
-- Indexes for table `100_town`
--
ALTER TABLE `100_town`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coord` (`lat`,`lng`),
  ADD KEY `alias` (`alias`),
  ADD KEY `size` (`size`),
  ADD KEY `regionId` (`regionId`),
  ADD KEY `countryId` (`countryId`),
  ADD KEY `name` (`name`(4)) USING BTREE;

--
-- Indexes for table `100_transaction`
--
ALTER TABLE `100_transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form` (`form`),
  ADD KEY `agreementId` (`agreementId`),
  ADD KEY `datePlan` (`datePlan`);

--
-- Indexes for table `100_transactionCampaign`
--
ALTER TABLE `100_transactionCampaign`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaignId` (`campaignId`),
  ADD KEY `buyerId` (`buyerId`);

--
-- Indexes for table `100_user`
--
ALTER TABLE `100_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `townId` (`townId`),
  ADD KEY `role` (`role`),
  ADD KEY `isSubscribed` (`isSubscribed`),
  ADD KEY `karma` (`karma`),
  ADD KEY `active100` (`active100`),
  ADD KEY `autologin` (`autologin`),
  ADD KEY `email` (`email`),
  ADD KEY `lastAnswer` (`lastAnswer`),
  ADD KEY `refId` (`refId`),
  ADD KEY `rating` (`rating`);

--
-- Indexes for table `100_userFile`
--
ALTER TABLE `100_userFile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `100_userStatusRequest`
--
ALTER TABLE `100_userStatusRequest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `yuristId` (`yuristId`);

--
-- Indexes for table `100_yurCompany`
--
ALTER TABLE `100_yurCompany`
  ADD PRIMARY KEY (`id`),
  ADD KEY `authorId` (`authorId`),
  ADD KEY `townId` (`townId`);

--
-- Indexes for table `100_yuristSettings`
--
ALTER TABLE `100_yuristSettings`
  ADD PRIMARY KEY (`yuristId`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `phinxlog`
--
ALTER TABLE `phinxlog`
  ADD PRIMARY KEY (`version`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `100_answer`
--
ALTER TABLE `100_answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=501;
--
-- AUTO_INCREMENT for table `100_campaign`
--
ALTER TABLE `100_campaign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=353;
--
-- AUTO_INCREMENT for table `100_comment`
--
ALTER TABLE `100_comment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57625;
--
-- AUTO_INCREMENT for table `100_country`
--
ALTER TABLE `100_country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `100_doctype`
--
ALTER TABLE `100_doctype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `100_expence`
--
ALTER TABLE `100_expence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4574;
--
-- AUTO_INCREMENT for table `100_karmaChange`
--
ALTER TABLE `100_karmaChange`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1592;
--
-- AUTO_INCREMENT for table `100_lead2category`
--
ALTER TABLE `100_lead2category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2965;
--
-- AUTO_INCREMENT for table `100_lead100`
--
ALTER TABLE `100_lead100`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55240;
--
-- AUTO_INCREMENT for table `100_leadsource100`
--
ALTER TABLE `100_leadsource100`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
--
-- AUTO_INCREMENT for table `100_money`
--
ALTER TABLE `100_money`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1490;
--
-- AUTO_INCREMENT for table `100_order`
--
ALTER TABLE `100_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
--
-- AUTO_INCREMENT for table `100_orderresponse`
--
ALTER TABLE `100_orderresponse`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;
--
-- AUTO_INCREMENT for table `100_partnerTransaction`
--
ALTER TABLE `100_partnerTransaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `100_post`
--
ALTER TABLE `100_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;
--
-- AUTO_INCREMENT for table `100_postcategory`
--
ALTER TABLE `100_postcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `100_postComment`
--
ALTER TABLE `100_postComment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `100_postRatingHistory`
--
ALTER TABLE `100_postRatingHistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `100_question`
--
ALTER TABLE `100_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46952;
--
-- AUTO_INCREMENT for table `100_questionCategory`
--
ALTER TABLE `100_questionCategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2707;
--
-- AUTO_INCREMENT for table `100_region`
--
ALTER TABLE `100_region`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;
--
-- AUTO_INCREMENT for table `100_sourceCost`
--
ALTER TABLE `100_sourceCost`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;
--
-- AUTO_INCREMENT for table `100_town`
--
ALTER TABLE `100_town`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1132;
--
-- AUTO_INCREMENT for table `100_transaction`
--
ALTER TABLE `100_transaction`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2477;
--
-- AUTO_INCREMENT for table `100_transactionCampaign`
--
ALTER TABLE `100_transactionCampaign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22161;
--
-- AUTO_INCREMENT for table `100_user`
--
ALTER TABLE `100_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8636;
--
-- AUTO_INCREMENT for table `100_userFile`
--
ALTER TABLE `100_userFile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `100_userStatusRequest`
--
ALTER TABLE `100_userStatusRequest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;
--
-- AUTO_INCREMENT for table `100_yurCompany`
--
ALTER TABLE `100_yurCompany`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
