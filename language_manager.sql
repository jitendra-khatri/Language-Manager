SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Table structure for table `dictionary`

CREATE TABLE IF NOT EXISTS `#__dictionary` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identification for the language key-value Pair',
  `key` mediumtext NOT NULL COMMENT 'Key of language String, can be repeated',
  `value` mediumtext NOT NULL COMMENT 'Translation of the kay',
  `language_code` varchar(200) NOT NULL COMMENT 'Code For the Translation From which the translation belongs',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='This Table contains translations of all languages.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE IF NOT EXISTS `#__history` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identification for the language key-value Pair of Deleted String',
  `key` mediumtext NOT NULL COMMENT 'Key of language String, can be repeated',
  `value` mediumtext NOT NULL COMMENT 'Translation of the kay',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='This Table contains translations of all languages which are Deleted by you.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `resource`
--

CREATE TABLE IF NOT EXISTS `#__resource` (
  `file_id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identification for the language File and this will be used as foriegn key in Source Table',
  `file_name` mediumtext NOT NULL COMMENT 'Name of the',
  `version` mediumtext NOT NULL COMMENT 'version of your component, module,etc.',
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='This Table contains File name and their version.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `source`
--

CREATE TABLE IF NOT EXISTS `#__source` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identification for the language key-value Pair',
  `key` text CHARACTER SET utf8 NOT NULL COMMENT 'Key of language String, can be repeated',
  `value` text CHARACTER SET utf8 NOT NULL COMMENT 'Translation of the kay',
  `resource_id` int(255) NOT NULL COMMENT 'Id of File, This can be found in Resource table.',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=1 ;

