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


class uploadTranslation extends JApplicationCli
{
	//Function contains script for adding Resource Entry
	public function doExecute()
	{
		$this->out('For Uploading Translations Type "upload" : ');
       	$input = $this->in();

       	if($input == 'upload')
       	{
       		//For Taking File Name
        	$this->out("File Path : ");
        	$folderPath = $this->in();
//        	
//			//For Taking Resource Id.
//        	$this->out("Language Code : ");
//        	$languageCode = $this->in();
//        	
        	$folders = JFolder::folders($folderPath);
        	
        	foreach($folders as $folder){
        		$subFolderPath = $folderPath."/".$folder;
        		$files 		   = JFolder::files($subFolderPath);
        		foreach($files as $file){
        			$filePath = $subFolderPath."/".$file;
	        		if(file_exists($filePath))
	        		{
		        		$fileContent = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		        		
		        		        	
			        	$db 	= JFactory::getDbo();
						$query  = $db->getQuery(true);
			        	
						//For Cross checking of key and reource id exists or not.
			        	$query->select('*')
				        	  ->from('`#__dictionary`');
				        	       	  
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
			        		$value	= rtrim($string[1], '"\n');
			        				
			        		if(!array_key_exists("$key", $record) || (  array_key_exists("$key", $record)
			        													&& !($record[$key]->value == $value)
			        													&& !($record[$key]->language_code == $folder)  ) )
							{
								$value = $db->quote($value);
								//Dumps key value and resource id into database
								$query->insert('#__dictionary');
								$query->set("`key`='$key', `value`=$value, `language_code`='$folder'");
								$db->setQuery($query);
								$db->query();							
							}
		
							unset($key);
							unset($value);
							unset($string);
							$query->clear();
							
						}
						
	        		}
        			
        		}
        	}
        	
        	}
    	}
}

JApplicationCli::getInstance('uploadTranslation')->execute();