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


class addResource extends JApplicationCli
{
	//Function contains script for adding Resource Entry
	public function doExecute()
	{
		$this->out('Do you want to add Resource(Name of Main files.) : (y/n)');
       	$add = $this->in();
        ($add == 'y') ? $this->out('Enter Name of your Resource File :') : die('Bye');
        $fileName = $this->in();
        
       	if($fileName)
       	{
       		for(; ; )
        	{
        	  	$this->out('Enter version of your Resource File :');
        	  	$version = $this->in();
        	  	break;
        	}

        }
       	
       	$db 	= JFactory::getDbo();
		$query  = $db->getQuery(true);
		$query->insert('#__resource');
		$query->set("`file_name`='$fileName', `version`='$version'");
		$db->setQuery($query);
		$db->query();		
	}
}

JApplicationCli::getInstance('addResource')->execute();