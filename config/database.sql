-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_newrecords`
-- 

CREATE TABLE `tl_newrecords` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tl_sessionId` varchar(128) NOT NULL default '',
  `newrecord_table` varchar(255) NOT NULL default '',
  `newrecord_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `tl_sessionId` (`tl_sessionId`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------