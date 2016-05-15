SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `comment` (
`id` int(11) NOT NULL,
  `reply_id` int(11) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `comment` text,
  `approved` enum('N','Y') NOT NULL DEFAULT 'N',
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '1',
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `post` (
`id` int(11) NOT NULL,
  `title` varchar(60) NOT NULL,
  `url` varchar(70) NOT NULL,
  `url_locked` enum('N','Y') NOT NULL DEFAULT 'N',
  `page` enum('N','Y') NOT NULL DEFAULT 'N',
  `meta_description` varchar(160) DEFAULT NULL,
  `content` text,
  `content_html` text,
  `content_excerpt` varchar(500) DEFAULT NULL,
  `published_date` date DEFAULT NULL,
  `template` varchar(50) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '1',
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `session` (
  `session_id` char(64) NOT NULL,
  `data` text,
  `user_agent` char(64) DEFAULT NULL,
  `ip_address` varchar(46) DEFAULT NULL,
  `time_updated` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


ALTER TABLE `comment`
 ADD PRIMARY KEY (`id`), ADD KEY `post_approved_idx` (`approved`),
 ADD KEY `post_id_idx` (`post_id`),
 ADD CONSTRAINT `comment_post_id_fk1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE;

ALTER TABLE `post`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `post_url_idx` (`url`), ADD KEY `post_published_idx` (`published_date`), ADD KEY `post_page_idx` (`page`),
 ADD FULLTEXT (`content`);

ALTER TABLE `session`
 ADD PRIMARY KEY (`session_id`);


ALTER TABLE `comment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;
ALTER TABLE `post`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;SET FOREIGN_KEY_CHECKS=1;
