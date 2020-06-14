/*
Navicat MySQL Data Transfer

Source Server         : testedu
Source Server Version : 50729
Source Host           : 140.143.158.102:3306
Source Database       : test_lixuqi_com

Target Server Type    : MYSQL
Target Server Version : 50729
File Encoding         : 65001

Date: 2020-05-10 13:36:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for edu_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `edu_admin_menu`;
CREATE TABLE `edu_admin_menu` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父菜单id',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '菜单类型;1:有界面可访问菜单,2:无界面可访问菜单,0:只作为菜单',
  `delete_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态;1:已删除,0:未删除',
  `show_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '状态;1:显示,0:不显示',
  `sort` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `url` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '应用路径:app/controller/action',
  `title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '菜单图标',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `show_status` (`show_status`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8mb4 COMMENT='后台菜单表';

-- ----------------------------
-- Records of edu_admin_menu
-- ----------------------------
INSERT INTO `edu_admin_menu` VALUES ('1', '0', '1', '0', '1', '20', 'admin/menu/default', '权限管理', 'cogs', '模块管理', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('2', '1', '2', '0', '1', '21', 'admin/menu/index', '模块列表', 'medium', '模块列表', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('3', '1', '2', '0', '1', '0', 'admin/administrator/index', '管理员列表', 'user-plus', '11', '1581490040', '0');
INSERT INTO `edu_admin_menu` VALUES ('4', '1', '2', '1', '1', '0', '123123', '模块管理12111', 'user-cog', '角色列表', '1581491348', '0');
INSERT INTO `edu_admin_menu` VALUES ('5', '1', '2', '0', '1', '0', 'admin/role/index', '角色组', 'users', '', '1581493975', '0');
INSERT INTO `edu_admin_menu` VALUES ('6', '0', '1', '0', '1', '0', 'admin/setting/default', '网站管理', 'laptop', '', '1581834849', '0');
INSERT INTO `edu_admin_menu` VALUES ('7', '6', '2', '0', '1', '0', 'admin/setting/website', '基础配置', 'cog', '', '1581834952', '0');
INSERT INTO `edu_admin_menu` VALUES ('8', '6', '2', '0', '1', '0', 'admin/banner/index', 'Banner管理', 'picture-o', '轮播图/友情链接', '1581999892', '0');
INSERT INTO `edu_admin_menu` VALUES ('9', '6', '2', '1', '0', '0', 'admin/file/filelist', '附件管理', 'file', '', '1582515985', '0');
INSERT INTO `edu_admin_menu` VALUES ('10', '0', '1', '0', '1', '0', 'admin/course/default', '课程管理', 'graduation-cap', '', '1582517857', '0');
INSERT INTO `edu_admin_menu` VALUES ('11', '10', '2', '0', '1', '0', 'vod/AdminCourse/index', '课程列表', 'television', '', '1582518197', '0');
INSERT INTO `edu_admin_menu` VALUES ('12', '10', '2', '0', '1', '3', 'vod/AdminCourseCategory/index', '课程分类', 'sliders', '', '1582518489', '0');
INSERT INTO `edu_admin_menu` VALUES ('13', '10', '2', '0', '1', '2', 'vod/AdminCourseVideo/index', '视频添加', 'file-video-o', '', '1582518574', '0');
INSERT INTO `edu_admin_menu` VALUES ('22', '10', '2', '0', '1', '1', 'vod/AdminChapter/index', '章节管理', 'file-text', '', '1585128259', '0');
INSERT INTO `edu_admin_menu` VALUES ('35', '6', '2', '0', '1', '0', 'admin/nav/index', '导航管理', 'bars', '', '1586419329', '0');
INSERT INTO `edu_admin_menu` VALUES ('40', '6', '2', '1', '1', '0', 'admin/banner/index', 'Banner管理', 'circle', '', '1586427699', '0');
INSERT INTO `edu_admin_menu` VALUES ('45', '0', '1', '0', '1', '0', 'admin/user/default', '用户管理', 'users', '', '1587021481', '0');
INSERT INTO `edu_admin_menu` VALUES ('46', '45', '2', '0', '1', '0', 'user/AdminUser/index', '用户列表', 'user-circle-o', '', '1587021561', '0');
INSERT INTO `edu_admin_menu` VALUES ('47', '0', '1', '0', '1', '0', 'admin/order/default', '财务管理', 'credit-card-alt', '', '1587099913', '0');
INSERT INTO `edu_admin_menu` VALUES ('48', '47', '2', '0', '1', '0', 'vod/AdminOrder/index', '订单列表', 'first-order', '', '1587099976', '0');
INSERT INTO `edu_admin_menu` VALUES ('88', '0', '3', '0', '1', '0', 'admin/index/index', '后台首页', '', '', '1587384227', '0');
INSERT INTO `edu_admin_menu` VALUES ('98', '13', '3', '0', '1', '0', 'vod/AdminCoursevideo/getChapterList', '获取课程', '', '', '1587385185', '0');
INSERT INTO `edu_admin_menu` VALUES ('102', '0', '1', '0', '1', '0', 'admin/dict/default', '数据字典', 'database', '', '1587899968', '0');
INSERT INTO `edu_admin_menu` VALUES ('103', '102', '2', '0', '1', '0', 'admin/dict/index', '字典列表', 'circle', '', '1587900001', '0');
INSERT INTO `edu_admin_menu` VALUES ('107', '102', '2', '1', '1', '0', 'admin/dictCategory/index', '字典分类', '', '', '1587900086', '0');
INSERT INTO `edu_admin_menu` VALUES ('111', '0', '1', '0', '1', '0', 'vod/AdminComment/default', '评论管理', 'commenting-o', '', '1587965750', '0');
INSERT INTO `edu_admin_menu` VALUES ('112', '111', '2', '0', '1', '0', 'vod/AdminComment/index', '评论列表', 'comments-o', '', '1587965801', '0');
INSERT INTO `edu_admin_menu` VALUES ('113', '0', '3', '0', '1', '0', 'admin/administrator/editInfo', '后台用户修改资料', '', '', '1587384227', '0');
INSERT INTO `edu_admin_menu` VALUES ('119', '8', '3', '0', '1', '0', 'admin/banner/add', '添加', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('120', '8', '3', '0', '1', '0', 'admin/banner/del', '删除', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('121', '8', '3', '0', '1', '0', 'admin/banner/edit', '编辑', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('122', '7', '3', '0', '1', '0', 'admin/setting/add', '添加', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('123', '7', '3', '0', '1', '0', 'admin/setting/del', '删除', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('124', '7', '3', '0', '1', '0', 'admin/setting/edit', '编辑', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('125', '35', '3', '0', '1', '0', 'admin/nav/add', '添加', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('126', '35', '3', '0', '1', '0', 'admin/nav/del', '删除', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('127', '35', '3', '0', '1', '0', 'admin/nav/edit', '编辑', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('128', '11', '3', '0', '1', '0', 'vod/AdminCourse/add', '添加', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('129', '11', '3', '0', '1', '0', 'vod/AdminCourse/del', '删除', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('130', '11', '3', '0', '1', '0', 'vod/AdminCourse/edit', '编辑', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('131', '22', '3', '0', '1', '0', 'vod/AdminChapter/add', '添加', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('132', '22', '3', '0', '1', '0', 'vod/AdminChapter/del', '删除', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('133', '22', '3', '0', '1', '0', 'vod/AdminChapter/edit', '编辑', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('134', '13', '3', '0', '1', '0', 'vod/AdminCourseVideo/add', '添加', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('135', '13', '3', '0', '1', '0', 'vod/AdminCourseVideo/del', '删除', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('136', '13', '3', '0', '1', '0', 'vod/AdminCourseVideo/edit', '编辑', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('137', '12', '3', '0', '1', '0', 'vod/AdminCourseCategory/add', '添加', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('138', '12', '3', '0', '1', '0', 'vod/AdminCourseCategory/del', '删除', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('139', '12', '3', '0', '1', '0', 'vod/AdminCourseCategory/edit', '编辑', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('140', '2', '3', '0', '1', '0', 'admin/menu/add', '添加', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('141', '2', '3', '0', '1', '0', 'admin/menu/del', '删除', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('142', '2', '3', '0', '1', '0', 'admin/menu/edit', '编辑', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('143', '5', '3', '0', '1', '0', 'admin/role/add', '添加', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('144', '5', '3', '0', '1', '0', 'admin/role/del', '删除', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('145', '5', '3', '0', '1', '0', 'admin/role/edit', '编辑', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('146', '3', '3', '0', '1', '0', 'admin/administrator/add', '添加', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('147', '3', '3', '0', '1', '0', 'admin/administrator/del', '删除', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('148', '3', '3', '0', '1', '0', 'admin/administrator/edit', '编辑', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('149', '112', '3', '0', '1', '0', 'vod/AdminComment/add', '添加', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('150', '112', '3', '0', '1', '0', 'vod/AdminComment/del', '删除', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('151', '112', '3', '0', '1', '0', 'vod/AdminComment/edit', '编辑', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('152', '103', '3', '0', '1', '0', 'admin/dict/add', '添加', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('153', '103', '3', '0', '1', '0', 'admin/dict/del', '删除', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('154', '103', '3', '0', '1', '0', 'admin/dict/edit', '编辑', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('155', '46', '3', '0', '1', '0', 'user/AdminUser/add', '添加', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('156', '46', '3', '0', '1', '0', 'user/AdminUser/del', '删除', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('157', '46', '3', '0', '1', '0', 'user/AdminUser/edit', '编辑', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('158', '48', '3', '0', '1', '0', 'vod/AdminOrder/add', '添加', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('159', '48', '3', '0', '1', '0', 'vod/AdminOrder/del', '删除', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('160', '48', '3', '0', '1', '0', 'vod/AdminOrder/edit', '编辑', 'circle', '', '0', '0');
INSERT INTO `edu_admin_menu` VALUES ('161', '11', '3', '0', '1', '0', 'vod/AdminCourse/operation', '热门/推荐', '', '', '1588837165', '0');
INSERT INTO `edu_admin_menu` VALUES ('162', '5', '3', '0', '1', '0', 'admin/Role/tree', '权限分配', '', '', '1588992297', '0');
INSERT INTO `edu_admin_menu` VALUES ('163', '5', '3', '0', '1', '0', 'admin/role/getAuthTree', '获取用户组权限', '', '', '1588992350', '0');
INSERT INTO `edu_admin_menu` VALUES ('164', '46', '3', '0', '1', '0', 'user/AdminUser/block', '拉黑', '', '', '1589088951', '0');

-- ----------------------------
-- Table structure for edu_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `edu_admin_role`;
CREATE TABLE `edu_admin_role` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `role_auth` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限字符串',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `show_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常',
  `delete_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态;1:已删除,0:未删除',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='管理员角色组';

-- ----------------------------
-- Records of edu_admin_role
-- ----------------------------
INSERT INTO `edu_admin_role` VALUES ('1', '超级管理员', '', '拥有后台所有权限', '1', '0', '1581830431', '0');

-- ----------------------------
-- Table structure for edu_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `edu_admin_user`;
CREATE TABLE `edu_admin_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联角色id',
  `mobile` varchar(32) NOT NULL DEFAULT '' COMMENT '中国手机不带国家代码，国际手机号格式为：国家代码-手机号',
  `avatar_url` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `nickname` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码',
  `show_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常',
  `delete_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态;1:已删除,0:未删除',
  `last_login_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `nickname` (`nickname`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='管理员用户表';

-- ----------------------------
-- Records of edu_admin_user
-- ----------------------------
INSERT INTO `edu_admin_user` VALUES ('9', '1', '', '', 'eduYouke', 'd93a5def7511da3d0f2d171d9c344e91', '1', '0', '', '0', '0', '0');

-- ----------------------------
-- Table structure for edu_banner
-- ----------------------------
DROP TABLE IF EXISTS `edu_banner`;
CREATE TABLE `edu_banner` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(64) NOT NULL DEFAULT '' COMMENT '描述',
  `link_url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接地址',
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片地址',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;1:轮播图,1:友情链接',
  `show_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常',
  `delete_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态;1:已删除,0:未删除',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='轮播图/友情链接表';

-- ----------------------------
-- Records of edu_banner
-- ----------------------------

-- ----------------------------
-- Table structure for edu_chapter
-- ----------------------------
DROP TABLE IF EXISTS `edu_chapter`;
CREATE TABLE `edu_chapter` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联的课程id',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '章节名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '章节简介',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `show_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常',
  `delete_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态;1:已删除,0:未删除',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='课程章节表';

-- ----------------------------
-- Records of edu_chapter
-- ----------------------------

-- ----------------------------
-- Table structure for edu_comment
-- ----------------------------
DROP TABLE IF EXISTS `edu_comment`;
CREATE TABLE `edu_comment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '被回复的评论id',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发表评论的用户id',
  `to_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '被评论的用户id',
  `source_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论内容 id',
  `like_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `dislike_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '不喜欢数',
  `floor` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '楼层数',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论时间',
  `show_status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态,1:已审核,0:未审核',
  `delete_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态,1:已删除,0:未删除',
  `table_name` varchar(64) NOT NULL DEFAULT '' COMMENT '评论内容所在表，不带表前缀',
  `full_name` varchar(64) NOT NULL DEFAULT '' COMMENT '评论者昵称',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '层级关系',
  `url` text COMMENT '原文地址',
  `content` text CHARACTER SET utf8mb4 COMMENT '评论内容',
  `more` text CHARACTER SET utf8mb4 COMMENT '扩展属性',
  PRIMARY KEY (`id`),
  KEY `source_id` (`source_id`) USING BTREE,
  KEY `parent_id` (`parent_id`) USING BTREE,
  KEY `create_time` (`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='评论表';

-- ----------------------------
-- Records of edu_comment
-- ----------------------------

-- ----------------------------
-- Table structure for edu_course
-- ----------------------------
DROP TABLE IF EXISTS `edu_course`;
CREATE TABLE `edu_course` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '课程名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '课程简介',
  `cource_image_url` varchar(255) NOT NULL DEFAULT '' COMMENT '课程封面图片',
  `sell_price` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '售卖价格',
  `content` text COMMENT '课程简介内容',
  `cource_tag` varchar(64) NOT NULL DEFAULT '' COMMENT '课程tag',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `sell_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '收费状态;0:免费,1:收费',
  `level_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '难度级别;1:初级,2:中级,3:高级,4:炼狱',
  `recommend_status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否推荐 0:不推荐 1:推荐',
  `hot_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否热门 0:热门未推荐 1:热门',
  `study_num` int(10) unsigned DEFAULT '0' COMMENT '学习人数',
  `views` int(10) unsigned DEFAULT '0',
  `show_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常',
  `delete_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态;1:已删除,0:未删除',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COMMENT='课程列表';

-- ----------------------------
-- Records of edu_course
-- ----------------------------

-- ----------------------------
-- Table structure for edu_course_category
-- ----------------------------
DROP TABLE IF EXISTS `edu_course_category`;
CREATE TABLE `edu_course_category` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父菜单id',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '分类名称',
  `seoTitle` varchar(255) DEFAULT NULL COMMENT 'SEO标题',
  `seoKeywords` varchar(255) DEFAULT NULL COMMENT 'SEO关键字',
  `seoDescription` varchar(255) DEFAULT NULL COMMENT 'SEO描述',
  `sort` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `show_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常',
  `delete_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态;1:已删除,0:未删除',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb4 COMMENT='课程分类';

-- ----------------------------
-- Records of edu_course_category
-- ----------------------------

-- ----------------------------
-- Table structure for edu_course_video
-- ----------------------------
DROP TABLE IF EXISTS `edu_course_video`;
CREATE TABLE `edu_course_video` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chapter_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联的章节id',
  `course_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联的课程id',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '视频名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '视频简介',
  `seoTitle` varchar(255) DEFAULT NULL COMMENT 'SEO标题',
  `seoKeywords` varchar(255) DEFAULT NULL COMMENT 'SEO关键字',
  `seoDescription` varchar(255) DEFAULT NULL COMMENT 'SEO描述',
  `video_url` varchar(255) NOT NULL DEFAULT '' COMMENT '视频地址',
  `image_url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `remark` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `channel` varchar(64) NOT NULL DEFAULT '' COMMENT '视频类型;local:本地',
  `show_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常',
  `delete_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态;1:已删除,0:未删除',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `chapter_id` (`chapter_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='课程视频表';

-- ----------------------------
-- Records of edu_course_video
-- ----------------------------

-- ----------------------------
-- Table structure for edu_dict
-- ----------------------------
DROP TABLE IF EXISTS `edu_dict`;
CREATE TABLE `edu_dict` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(64) NOT NULL DEFAULT '' COMMENT '菜单标识',
  `key` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `value` varchar(64) NOT NULL DEFAULT '' COMMENT '内容',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:数据,1:分类',
  `show_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常',
  `delete_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态;1:已删除,0:未删除',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COMMENT='字典表';

-- ----------------------------
-- Records of edu_dict
-- ----------------------------

-- ----------------------------
-- Table structure for edu_nav
-- ----------------------------
DROP TABLE IF EXISTS `edu_nav`;
CREATE TABLE `edu_nav` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父导航id',
  `category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '关联的菜单id',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '分类名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '自定义链接',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `sort` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `nav_type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '关联分类;1:关联分类,2:自定义地址',
  `show_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常',
  `delete_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态;1:已删除,0:未删除',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4 COMMENT='导航表';

-- ----------------------------
-- Records of edu_nav
-- ----------------------------

-- ----------------------------
-- Table structure for edu_order
-- ----------------------------
DROP TABLE IF EXISTS `edu_order`;
CREATE TABLE `edu_order` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT '0' COMMENT '用户id',
  `commodity_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `order_no` varchar(64) NOT NULL DEFAULT '0' COMMENT '订单号',
  `order_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态;0:未支付,1:已支付',
  `amount_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品单价',
  `order_type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;1:课程,2:会员',
  `show_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常',
  `delete_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态;1:已删除,0:未删除',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4452 DEFAULT CHARSET=utf8mb4 COMMENT='订单表';

-- ----------------------------
-- Records of edu_order
-- ----------------------------

-- ----------------------------
-- Table structure for edu_record_log
-- ----------------------------
DROP TABLE IF EXISTS `edu_record_log`;
CREATE TABLE `edu_record_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `category` varchar(255) NOT NULL DEFAULT '' COMMENT '分类',
  `value` varchar(255) DEFAULT NULL COMMENT '输入内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `more` text,
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=275 DEFAULT CHARSET=utf8mb4 COMMENT='系统Log流水表';

-- ----------------------------
-- Records of edu_record_log
-- ----------------------------

-- ----------------------------
-- Table structure for edu_setting
-- ----------------------------
DROP TABLE IF EXISTS `edu_setting`;
CREATE TABLE `edu_setting` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '分类名称',
  `category` varchar(64) NOT NULL DEFAULT '' COMMENT '分类',
  `category_name` varchar(64) NOT NULL DEFAULT '' COMMENT '分类别名',
  `content` text COMMENT '输入内容',
  `default_content` varchar(255) DEFAULT NULL COMMENT '默认展示内容',
  `small_help` varchar(255) DEFAULT NULL COMMENT '提示文字',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `show_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常',
  `delete_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态;1:已删除,0:未删除',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COMMENT='系统配置表';

-- ----------------------------
-- Records of edu_setting
-- ----------------------------
INSERT INTO `edu_setting` VALUES ('31', '基础配置', 'base', 'baseConfig', '', null, null, 'nav导航', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('32', '阿里云配置', 'base', 'aliConfig', '', null, '', 'nav导航', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('39', '网站名称', 'baseConfig', 'webSiteName', '', null, null, '网站名称', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('40', 'SEO标题', 'baseConfig', 'seoTitle', '', null, null, 'SEO标题', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('41', 'SEO关键字', 'baseConfig', 'seoKeywords', '', null, null, 'SEO关键字', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('42', 'SEO描述', 'baseConfig', 'seoDescription', '', null, null, 'SEO描述', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('43', 'ICP备案', 'baseConfig', 'icpString', '', null, null, 'ICP备案', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('44', '统计代码', 'baseConfig', 'countCode', '', '', '', '统计代码', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('45', '播放器Key', 'aliConfig', 'aliPlayerKey', '', null, null, '播放器Key', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('46', '播放器Secret', 'aliConfig', 'aliPlayerSecret', '', null, null, '播放器Secret', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('47', 'ossKey', 'aliConfig', 'aliossKey', '', null, null, 'ossKey', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('48', 'ossSecret', 'aliConfig', 'aliossSecret', '', null, null, 'ossSecret', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('59', '阿里短信Key', 'aliConfig', 'aliSmsKey', '', null, null, '阿里短信Key', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('60', '阿里短信Secret', 'aliConfig', 'aliSmsSecret', '', null, null, '阿里短信Secret', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('68', '网站logo', 'baseConfig', 'logoImage', '', null, null, '网站logo', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('69', '上传配置', 'base', 'uploaderConfig', '', null, null, '上传配置', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('70', '图片配置', 'uploaderConfig', 'imageUploader', '', null, null, '图片配置', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('71', '视频配置', 'uploaderConfig', 'videoUploader', '', null, null, '视频配置', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('72', 'ossEndpoint', 'aliConfig', 'ossEndpoint', '', null, null, 'ossEndpoint', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('73', 'ossBucket', 'aliConfig', 'ossBucket', '', null, null, 'ossBucket', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('74', 'smsKey', 'aliConfig', 'smsKey', '', null, null, '阿里短信Key', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('75', 'smsSecret', 'aliConfig', 'smsSecret', '', null, null, '阿里短信Secret', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('76', 'smsSign', 'aliConfig', 'smsSign', '', null, null, '签名名称', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('77', 'smsLoginTemplateCode', 'aliConfig', 'smsLoginTemplateCode', '', null, null, '登录模板', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('78', 'smsForgetTemplateCode', 'aliConfig', 'smsForgetTemplateCode', '', null, null, '忘记密码', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('79', '会员配置', 'base', 'vipConfig', '', null, null, '会员配置', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('80', '月度会员', 'vipConfig', 'vipMonth', '', null, null, '月度会员', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('81', '季度会员', 'vipConfig', 'vipQuarter', '', null, null, '季度会员', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('82', '年度会员', 'vipConfig', 'vipYear', '', null, null, '年度会员', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('83', '前端模板选择', 'baseConfig', 'template', '', null, null, '', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('84', '后台模板选择', 'baseConfig', 'adminTemplate', '', null, null, '', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('85', '登录页默认图', 'baseConfig', 'loginImage', '', null, null, '登录页默认图', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('86', '支付宝配置', 'base', 'AliPay', null, null, null, '', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('87', '沙箱模式', 'AliPay', 'alipaySandbox', '', null, null, '沙箱模式', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('88', 'appId', 'AliPay', 'alipayAppId', '', null, null, '支付宝app_id', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('89', '支付加密模式', 'AliPay', 'alipaySignType', '', null, null, '支付加密模式', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('90', '支付宝公钥', 'AliPay', 'alipayPublicKey', '', null, null, '支付宝公钥', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('91', '应用秘钥', 'AliPay', 'alipayRsaPrivateKey', '', null, null, '应用秘钥', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('92', '异步通知地址', 'AliPay', 'alipayNotifyUrl', '', null, null, '异步通知地址', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('93', '同步通知地址', 'AliPay', 'alipayReturnUrl', '', null, null, '同步通知地址', '1', '0', '0', '0');
INSERT INTO `edu_setting` VALUES ('95', '阿里VodRegionId', 'aliConfig', 'aliPlayerRegionId', null, null, null, '', '1', '0', '0', '0');

-- ----------------------------
-- Table structure for edu_user
-- ----------------------------
DROP TABLE IF EXISTS `edu_user`;
CREATE TABLE `edu_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(32) NOT NULL DEFAULT '' COMMENT '中国手机不带国家代码，国际手机号格式为：国家代码-手机号',
  `avatar_url` varchar(255) NOT NULL DEFAULT '' COMMENT '用户头像',
  `sex` tinyint(2) unsigned DEFAULT '0' COMMENT '0:保密1男:2女',
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码',
  `introduce` varchar(255) DEFAULT NULL COMMENT '简介',
  `empirical` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值',
  `user_status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常',
  `delete_status` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态;1:已删除,0:未删除',
  `last_login_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `user_type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '会员状态;1:普通会员,2:会员',
  `vip_expiration_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'vip到期时间',
  PRIMARY KEY (`id`),
  KEY `nickname` (`nickname`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

-- ----------------------------
-- Records of edu_user
-- ----------------------------
