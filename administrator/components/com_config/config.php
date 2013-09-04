<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_config
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

// Sessions
jimport('joomla.session.session');

// Load classes
JLoader::registerPrefix('Config', JPATH_COMPONENT);

// Tell the browser not to cache this page.
JResponse::setHeader('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT', true);

// Application
$app = JFactory::getApplication();

if ($controllerTask = $app->input->get('controller'))
	// Checking for new MVC controller
	$tasks = explode('.', $controllerTask);
else
{
	// Checking for old MVC task
	$task = $app->input->get('task');
	$tasks = explode('.', $task);
}

if (empty($tasks[1]))
	$activity = 'display';
elseif ($tasks[1] == 'apply')
	$activity = 'save';
else $activity = $tasks[1];

// Create the controller

// For Application
$classname  = 'ConfigControllerApplication' . ucfirst($activity);// only for applications

// Check if component mentioned
$componentRequired = $app->input->get('component');
if(!empty($componentRequired))
{
	// For Component
	$classname  = 'ConfigControllerComponent' . ucfirst($activity);
}

$controller = new $classname;

// Perform the Request task
$controller->execute();
