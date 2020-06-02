/*
Source Server         : DANONEK
Source Host           : localhost:1337
Source Database       : pastebin

Target Server Type    : MYSQL
File Encoding         : 65001

Date: 2020-06-02 21:20:57
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `bans`
-- ----------------------------
DROP TABLE IF EXISTS `bans`;
CREATE TABLE `bans` (
  `ip` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ban_period` int(11) DEFAULT NULL,
  `degree` int(11) DEFAULT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bans
-- ----------------------------

-- ----------------------------
-- Table structure for `codes`
-- ----------------------------
DROP TABLE IF EXISTS `codes`;
CREATE TABLE `codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of codes
-- ----------------------------

-- ----------------------------
-- Table structure for `logins`
-- ----------------------------
DROP TABLE IF EXISTS `logins`;
CREATE TABLE `logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `login_date` varchar(64) DEFAULT NULL,
  `logout_date` varchar(64) DEFAULT 'Didn''t logout yet.',
  `ip_address` varchar(255) DEFAULT NULL,
  `browser_agent` varchar(255) DEFAULT NULL,
  `session_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of logins
-- ----------------------------

-- ----------------------------
-- Table structure for `paste_visitors`
-- ----------------------------
DROP TABLE IF EXISTS `paste_visitors`;
CREATE TABLE `paste_visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitor_ip` varchar(255) DEFAULT NULL,
  `paste_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of paste_visitors
-- ----------------------------

-- ----------------------------
-- Table structure for `pastes`
-- ----------------------------
DROP TABLE IF EXISTS `pastes`;
CREATE TABLE `pastes` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `paste_id` text CHARACTER SET utf8 NOT NULL,
  `paste_title` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `deletion_date` int(11) DEFAULT '-1',
  `highlighting` int(11) DEFAULT '0',
  `wrap` int(11) DEFAULT '0',
  `paste` mediumtext CHARACTER SET utf8,
  `added` varchar(11) CHARACTER SET utf8 DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'Unknown',
  `is_private` int(11) NOT NULL DEFAULT '0',
  `views` bigint(20) DEFAULT '0',
  `guest` int(11) DEFAULT '0',
  PRIMARY KEY (`entry_id`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of pastes
-- ----------------------------

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_username` varchar(64) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_email` varchar(64) NOT NULL,
  `user_title` varchar(11) NOT NULL DEFAULT 'Free',
  `user_priv_limit` int(11) NOT NULL DEFAULT '10',
  `user_join_date` varchar(11) NOT NULL DEFAULT 'Unknown',
  PRIMARY KEY (`user_id`,`user_username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
