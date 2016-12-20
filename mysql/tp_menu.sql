/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50712
 Source Host           : localhost
 Source Database       : rbac

 Target Server Type    : MySQL
 Target Server Version : 50712
 File Encoding         : utf-8

 Date: 11/12/2016 23:25:08 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `tp_menu`
-- ----------------------------
DROP TABLE IF EXISTS `tp_menu`;
CREATE TABLE `tp_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `parent_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `app` char(20) NOT NULL COMMENT '应用名称app',
  `model` char(20) NOT NULL COMMENT '控制器',
  `action` char(20) NOT NULL COMMENT '操作名称',
  `url_param` char(50) NOT NULL COMMENT 'url参数',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '菜单类型  1：权限认证+菜单；0：只作为菜单',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态，1显示，0不显示',
  `name` varchar(50) NOT NULL COMMENT '菜单名称',
  `icon` varchar(50) NOT NULL COMMENT '菜单图标',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `list_order` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序ID',
  `rule_param` varchar(255) NOT NULL COMMENT '验证规则',
  `nav_id` int(11) DEFAULT '0' COMMENT 'nav ID ',
  `request` varchar(255) NOT NULL COMMENT '请求方式（日志生成）',
  `log_rule` varchar(255) NOT NULL COMMENT '日志规则',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `model` (`model`),
  KEY `parent_id` (`parent_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
--  Records of `tp_menu`
-- ----------------------------
BEGIN;
INSERT INTO `tp_menu` VALUES ('1', '0', 'index', 'auth', 'default', '', '0', '1', '系统管理', '', '', '10', '', '0', '', ''), ('2', '1', 'index', 'auth', 'default', '', '0', '1', '权限管理', '', '', '0', '', '0', '', ''), ('3', '2', 'index', 'auth', 'role', '', '1', '1', '角色管理', '', '', '0', '', '0', '', ''), ('4', '3', 'index', 'auth', 'roleAdd', '', '1', '0', '角色增加', '', '', '0', '', '0', '', ''), ('5', '3', 'index', 'auth', 'roleEdit', '', '1', '0', '角色编辑', '', '', '0', '', '0', '', ''), ('6', '3', 'index', 'auth', 'roleDelete', '', '1', '0', '角色删除', '', '', '0', '', '0', '', ''), ('7', '3', 'index', 'auth', 'authorize', '', '1', '0', '角色授权', '', '', '0', '', '0', '', ''), ('8', '1', 'index', 'auth', 'default', '', '0', '1', '菜单管理', '', '', '0', '', '0', '', ''), ('9', '8', 'index', 'auth', 'menu', '', '1', '1', '菜单列表', '', '', '0', '', '0', '', ''), ('10', '9', 'index', 'auth', 'menuAdd', '', '1', '0', '菜单增加', '', '', '0', '', '0', '', ''), ('11', '9', 'index', 'auth', 'menuEdit', '', '1', '0', '菜单修改', '', '', '0', '', '0', '', ''), ('12', '9', 'index', 'auth', 'menuDelete', '', '1', '0', '菜单删除', '', '', '0', '', '0', '', ''), ('13', '9', 'index', 'auth', 'menuOrder', '', '1', '0', '菜单排序', '', '', '0', '', '0', '', ''), ('14', '2', 'index', 'admin', 'index', '', '1', '1', '用户管理', '', '', '0', '', '0', '', '');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
