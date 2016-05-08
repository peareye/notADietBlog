-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 04, 2016 at 08:00 PM
-- Server version: 5.6.21
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE IF NOT EXISTS `post` (
`id` int(11) NOT NULL,
  `title` varchar(60) NOT NULL,
  `url` varchar(70) NOT NULL,
  `url_locked` enum('N','Y') NOT NULL DEFAULT 'N',
  `meta_description` varchar(160) DEFAULT NULL,
  `content` text,
  `content_html` text,
  `content_excerpt` varchar(500) DEFAULT NULL,
  `published_date` date DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '1',
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
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

--
-- Table structure for table `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `session_id` char(64) NOT NULL,
  `data` text,
  `user_agent` char(64) DEFAULT NULL,
  `ip_address` varchar(46) DEFAULT NULL,
  `time_updated` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `post`
--
ALTER TABLE `post`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `post_url_idx` (`url`), ADD KEY `post_published_idx` (`published_date`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
 ADD PRIMARY KEY (`session_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;
