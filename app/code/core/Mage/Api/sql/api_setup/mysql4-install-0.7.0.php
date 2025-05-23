<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Api
 */

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;
$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('api_assert')};
CREATE TABLE {$this->getTable('api_assert')} (
  `assert_id` int(10) unsigned NOT NULL auto_increment,
  `assert_type` varchar(20) character set utf8 NOT NULL default '',
  `assert_data` text character set utf8,
  PRIMARY KEY  (`assert_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Api ACL Asserts';

-- DROP TABLE IF EXISTS {$this->getTable('api_role')};
CREATE TABLE {$this->getTable('api_role')} (
  `role_id` int(10) unsigned NOT NULL auto_increment,
  `parent_id` int(10) unsigned NOT NULL default '0',
  `tree_level` tinyint(3) unsigned NOT NULL default '0',
  `sort_order` tinyint(3) unsigned NOT NULL default '0',
  `role_type` char(1) character set utf8 NOT NULL default '0',
  `user_id` int(11) unsigned NOT NULL default '0',
  `role_name` varchar(50) character set utf8 NOT NULL default '',
  PRIMARY KEY  (`role_id`),
  KEY `parent_id` (`parent_id`,`sort_order`),
  KEY `tree_level` (`tree_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Api ACL Roles';

-- DROP TABLE IF EXISTS {$this->getTable('api_rule')};
CREATE TABLE {$this->getTable('api_rule')} (
  `rule_id` int(10) unsigned NOT NULL auto_increment,
  `role_id` int(10) unsigned NOT NULL default '0',
  `resource_id` varchar(255) character set utf8 NOT NULL default '',
  `privileges` varchar(20) character set utf8 NOT NULL default '',
  `assert_id` int(10) unsigned NOT NULL default '0',
  `role_type` char(1) default NULL,
  `permission` varchar(10) default NULL,
  PRIMARY KEY  (`rule_id`),
  KEY `resource` (`resource_id`,`role_id`),
  KEY `role_id` (`role_id`,`resource_id`),
  CONSTRAINT `FK_api_rule` FOREIGN KEY (`role_id`) REFERENCES {$this->getTable('api_role')} (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Api ACL Rules';

-- DROP TABLE IF EXISTS {$this->getTable('api_user')};
CREATE TABLE {$this->getTable('api_user')} (
  `user_id` mediumint(9) unsigned NOT NULL auto_increment,
  `firstname` varchar(32) character set utf8 NOT NULL default '',
  `lastname` varchar(32) character set utf8 NOT NULL default '',
  `email` varchar(128) character set utf8 NOT NULL default '',
  `username` varchar(40) character set utf8 NOT NULL default '',
  `api_key` varchar(40) character set utf8 NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime default NULL,
  `logdate` datetime default NULL,
  `lognum` smallint(5) unsigned NOT NULL default '0',
  `reload_acl_flag` tinyint(1) NOT NULL default '0',
  `is_active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Api Users';

");

$installer->endSetup();
