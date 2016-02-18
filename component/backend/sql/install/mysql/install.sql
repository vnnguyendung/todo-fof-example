CREATE TABLE IF NOT EXISTS `#__todo_items` (
  `todo_item_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `description` mediumtext,
  `due` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `enabled` tinyint(3) NOT NULL DEFAULT '1',
  `ordering` int(10) NOT NULL DEFAULT '0',
  `asset_id` int(10) NOT NULL DEFAULT '0',
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` bigint(20) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`todo_item_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__todo_categories` (
  `todo_category_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `enabled` tinyint(3) NOT NULL DEFAULT '1',
  `lft` int NOT NULL,
  `rgt` int NOT NULL,
  `hash` char(40) NOT NULL,
  `ordering` int(10) NOT NULL DEFAULT '0',
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `locked_by` bigint(20) NOT NULL DEFAULT '0',
  `locked_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`todo_category_id`)
) DEFAULT CHARSET=utf8;

CREATE INDEX `#__todo_categories_lft_index` ON `#__todo_categories` (`lft`);
CREATE INDEX `#__todo_categories_rgt_index` ON `#__todo_categories` (`rgt`);
CREATE INDEX `#__todo_categories_lft_rgt_index` ON `#__todo_categories` (`lft`, `rgt`);
CREATE INDEX `#__todo_categories_hash_index` ON `#__todo_categories` (`hash`);
