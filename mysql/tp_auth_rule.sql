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

 Date: 11/12/2016 23:25:13 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `tp_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `tp_auth_rule`;
CREATE TABLE `tp_auth_rule` (
  `menu_id` int(11) NOT NULL COMMENT '后台菜单 ID',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` varchar(30) NOT NULL DEFAULT '1' COMMENT '权限规则分类，请加应用前缀,如admin_',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识,全小写',
  `url_param` varchar(255) DEFAULT NULL COMMENT '额外url参数',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `rule_param` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  `nav_id` int(11) DEFAULT '0' COMMENT 'nav id',
  PRIMARY KEY (`menu_id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限规则表';

-- ----------------------------
--  Records of `tp_auth_rule`
-- ----------------------------
BEGIN;
INSERT INTO `tp_auth_rule` VALUES ('2', 'index', 'admin_url', 'index/auth/default', '', '权限管理', '1', '', '0'), ('3', 'index', 'admin_url', 'index/auth/role', '', '角色管理', '1', '', '0'), ('4', 'index', 'admin_url', 'index/auth/roleadd', '', '角色增加', '1', '', '0'), ('5', 'index', 'admin_url', 'index/auth/roleedit', '', '角色编辑', '1', '', '0'), ('6', 'index', 'admin_url', 'index/auth/roledelete', '', '角色删除', '1', '', '0'), ('7', 'index', 'admin_url', 'index/auth/authorize', '', '角色授权', '1', '', '0'), ('8', 'index', 'admin_url', 'index/auth/menu', '', '菜单管理', '1', '', '0'), ('9', 'index', 'admin_url', 'index/auth/menu', '', '菜单列表', '1', '', '0'), ('10', 'index', 'admin_url', 'index/auth/menuadd', '', '菜单增加', '1', '', '0'), ('11', 'index', 'admin_url', 'index/auth/menuedit', '', '菜单修改', '1', '', '0'), ('12', 'index', 'admin_url', 'index/auth/menudelete', '', '菜单删除', '1', '', '0'), ('13', 'index', 'admin_url', 'index/auth/menuorder', '', '菜单排序', '1', '', '0'), ('14', 'index', 'admin_url', 'index/admin/index', '', '用户管理', '1', '', '0');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
