-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 26, 2016 at 03:23 PM
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
  `content_excerpt` varchar(500) DEFAULT NULL,
  `published_date` date DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '1',
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `post`
--
ALTER TABLE `post`
 ADD PRIMARY KEY (`id`), ADD KEY `post_created_idx` (`created_date`), ADD KEY `post_published_idx` (`published_date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;