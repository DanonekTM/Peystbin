/*
 Peystbin
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for last_logins
-- ----------------------------
DROP TABLE IF EXISTS `last_logins`;
CREATE TABLE `last_logins`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `browser_agent` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `operating_system` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `timestamp` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 178 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for paste_visitors
-- ----------------------------
DROP TABLE IF EXISTS `paste_visitors`;
CREATE TABLE `paste_visitors`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitor_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `paste_uid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 107 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for pastes
-- ----------------------------
DROP TABLE IF EXISTS `pastes`;
CREATE TABLE `pastes`  (
  `paste_id` int(11) NOT NULL AUTO_INCREMENT,
  `paste_uid` varchar(13) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `paste_owner_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `paste_owner_nickname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `paste_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `paste_created` int(11) NOT NULL DEFAULT 0,
  `paste_expiry` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '-1',
  `paste_exposure` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'PUBLIC',
  `paste_password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `paste_views` bigint(20) NULL DEFAULT 0,
  `paste_syntax_highlighting` tinyint(1) NULL DEFAULT 0,
  `paste_burn_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`paste_id`, `paste_uid`) USING BTREE,
  UNIQUE INDEX `paste_uid`(`paste_uid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 202 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `token_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `user_email` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `user_nickname` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `refresh_token` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`token_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_nickname` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_email` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_membership` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Free',
  `user_priv_limit` int(11) NOT NULL DEFAULT 10,
  `user_join_date` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Unknown',
  `user_status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'BAN',
  `user_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_api_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`, `user_nickname`, `user_email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
