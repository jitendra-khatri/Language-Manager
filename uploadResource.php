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


class uploadResource extends JApplicationCli
{
	//Function contains script for adding Resource Entry
	public function doExecute()
	{
		$this->out('For List of Available Resources Type "list" and For Uploading New Resource Type "upload" : ');
       	$list = $this->in();
//			$list = 'upload';
        switch(strtolower($list))
        {
        	case 'list':
        		$result = $this->_getResources();
        		if($result)
        		{
					foreach($result as $resource){
						$this->out("\nFile Id   : $resource->file_id\n"."File Name : $resource->file_name\n"."Version   : $resource->version\n");
					}
					$this->out('For Uploading Resource Use "upload" :');
        		}
				break;
				
        	case 'upload':
        		//For Taking File Name
        		$this->out("File Path : ");
        		$filePath = $this->in();
				//For Taking Resource Id.
        		$this->out("Resource File Id : ");
        		$resourceId = $this->in();
        		$this->_uploadResource($filePath, $resourceId);
        }
	}
	
	protected function _getResources()
	{
		$db 	= JFactory::getDbo();
		$query  = $db->getQuery(true);
		
		$query->select('*')
	          ->from('#__resource');
	          
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
	}
	
	protected function _uploadResource($filePath, $resourceId)
	{
		if(file_exists($filePath))
        {
        	$fileContent = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        	
        	$db 	= JFactory::getDbo();
			$query  = $db->getQuery(true);
        	
			//For Cross checking of key and reource id exists or not.
        	$query->select('*')
	        	  ->from('`#__source`');
	        	       	  
	        $db->setQuery($query);
			$db->query();
			$record = $db->loadObjectList('key');
			$query->clear();
			
			
        	foreach($fileContent as $arrKey => $string)
        	{      		
        		$string = trim($string, " ");
        		//If Comment then nothing to dump in DataBase
        		
        		if( empty($string) || ($string[0] == ';'))
        			continue;
        		
        		//For Separating key and value from the string
        		$string = explode('="', $string);
        		if (empty($string[0]) || empty($string[1]))
        			continue;
        			
        		$key	= $string[0];
       			$value	= rtrim($string[1], '"');
       			
				if(!array_key_exists("$key", $record))
				{
					$value = $db->quote($value);
					//Dumps key value and resource id into database
					$query->insert('`#__source`');
					$query->set("`key`='$key', `value`=$value, `resource_id`='$resourceId'");
					$db->setQuery((string)$query);
					$db->query();
					$query->clear();
				}
				unset($key);
				unset($value);
				unset($string);
        	}
        }
	}
}

JApplicationCli::getInstance('uploadResource')->execute();