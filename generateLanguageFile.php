<?php
/**
 * @package     Language Manager By Team PayPlans
 *
 * @copyright   Copyright (C) 2009 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later;
 * @Author 		Jitendra Khatri
 * @contact		payplans@readybytes.in
 *  
 */

const _JEXEC = 1;

error_reporting(E_ALL | E_NOTICE);
ini_set('display_errors', 1);

// Load system defines
if (file_exists(dirname(__DIR__) . '/defines.php'))
{
	require_once dirname(__DIR__) . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', dirname(__DIR__));
	require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_LIBRARIES . '/import.legacy.php';
require_once JPATH_LIBRARIES . '/cms.php';

// Load the configuration
require_once JPATH_CONFIGURATION . '/configuration.php';

jimport('joomla.filesystem.folder');


class generateLanguageFile extends JApplicationCli
{
	//Function contains script for adding Resource Entry
	public function doExecute()
	{
		$this->out('Resource Id : ');
       	$resId  = $this->in();
       	$resources = $this->_checkInResources($resId);
       	if(!array_key_exists($resId, $resources))
       	{
       		$this->out("Resource Id not exists.");
       	}
       	
       	$this->out('Language Code : ');
       	$langCode = $this->in();

       	$this->out('Path For Dumping New File : ');
       	$filePath = $this->in();
       	
       	$masterRecords = $this->_collectMasterRecordKey($resId);
       	
       	$keyString="";
       	foreach($masterRecords as $record)
       	{
       		$keyString = $keyString."'".$record->key."', ";
       	}
       	
       	$keyString = rtrim($keyString,", ");
       	      	
       	$data = $this->_getTranslation($keyString, $langCode);
		
       	$dataToDump ='';
       	foreach ($data as $languageString)
       	{
       		$dataToDump = $dataToDump.$languageString->key.'="'.$languageString->value.'"'."\n";
       	}
       	
       	$resourceName = explode(".", $resources[$resId]->file_name);
       	$fileName = $langCode.'.'.$resourceName[1].'.'.$resourceName[2];
       	$filePath = $filePath."/".$fileName;
       	if(!file_exists($filePath))
       	{
       		$file = fopen($fileName, 'x+');
       		file_put_contents($filePath, $dataToDump);
       	}
       	else
       	{
       		$file = fopen($fileName, 'w');
       		file_put_contents($filePath, $dataToDump);
       	}
       	
	}
	
	protected function _checkInResources($resId)
	{
		$db 	= JFactory::getDbo();
		$query  = $db->getQuery(true);
		//For Cross checking of key and reource id exists or not.
		$query->select('*')
		 	  ->from('`#__resource`')
		 	  ->where("`file_id` = $resId");
		
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList('file_id');
	}
	
	protected function _collectMasterRecordKey($resId)
	{
		$db 	= JFactory::getDbo();
		$query  = $db->getQuery(true);
		//For Cross checking of key and reource id exists or not.
		$query->select('`key`')
		 	  ->from('`#__source`')
		 	  ->where("`resource_id` = $resId");
		
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
	}
	
	protected function _getTranslation($keyString, $langCode)
	{
		$db 		= JFactory::getDbo();
		$query		= $db->getQuery(true);
		//For Cross checking of key and reource id exists or not.
		$query = "SELECT `key`,`value` FROM `#__dictionary` WHERE `key` IN ($keyString) AND `language_code` = '$langCode'";
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
		
		
	}
}


JApplicationCli::getInstance('generateLanguageFile')->execute();