<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.redirect
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * Plugin class for redirect handling.
 *
 * @package     Joomla.Plugin
 * @subpackage  System.redirect
 * @since       1.6
 */
class PlgSystemRedirect extends JPlugin
{
	/**
	 * Object Constructor.
	 *
	 * @access    public
	 * @param   object    The object to observe -- event dispatcher.
	 * @param   object    The configuration object for the plugin.
	 * @return  void
	 * @since   1.6
	 */
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);

		// Set the error handler for E_ERROR to be the class handleError method.
		JError::setErrorHandling(E_ERROR, 'callback', array('PlgSystemRedirect', 'handleError'));
		set_exception_handler(array('PlgSystemRedirect', 'handleError'));
	}

	public static function handleError(&$error)
	{
		// Get the application object.
		$app = JFactory::getApplication();

		// Make sure the error is a 404 and we are not in the administrator.
		if (!$app->isAdmin() and ($error->getCode() == 404))
		{
			// Get the full current URI.
			$uri = JURI::getInstance();
			$current = $uri->toString(array('scheme', 'host', 'port', 'path', 'query', 'fragment'));

			// Attempt to ignore idiots.
			if ((strpos($current, 'mosConfig_') !== false) || (strpos($current, '=http://') !== false))
			{
				// Render the error page.
				JErrorPage::render($error);
			}

			// See if the current url exists in the database as a redirect.
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('new_url'))
				->select($db->quoteName('published'))
				->from($db->quoteName('#__redirect_links'))
				->where($db->quoteName('old_url') . ' = ' . $db->quote($current));
			$db->setQuery($query, 0, 1);
			$link = $db->loadObject();

			// If a redirect exists and is published, permanently redirect.
			if ($link and ($link->published == 1))
			{
				$app->redirect($link->new_url, null, null, true, false);
			}
			else
			{
				$referer = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];

				$db->setQuery('SELECT id FROM ' . $db->quoteName('#__redirect_links') . '  WHERE old_url= ' . $db->quote($current));
				$res = $db->loadResult();
				if (!$res)
				{

					// If not, add the new url to the database.
					$columns = array(
						$db->quoteName('old_url'),
						$db->quoteName('new_url'),
						$db->quoteName('referer'),
						$db->quoteName('comment'),
						$db->quoteName('hits'),
						$db->quoteName('published'),
						$db->quoteName('created_date')
					);
					$query = $db->getQuery(true)
						->insert($db->quoteName('#__redirect_links'), false)
						->columns($columns)
						->values(
							$db->quote($current) . ', ' . $db->quote('') .
								' ,' . $db->quote($referer) . ', ' . $db->quote('') . ',1,0, ' .
								$db->quote(JFactory::getDate()->toSql())
						);

					$db->setQuery($query);
					$db->execute();
				}
				else
				{
					// Existing error url, increase hit counter
					$query = $db->getQuery(true)
						->update($db->quoteName('#__redirect_links'))
						->set($db->quoteName('hits') . ' = ' . $db->quote('hits') . ' + 1')
						->where('id = ' . (int) $res);
					$db->setQuery($query);
					$db->execute();
				}
				// Render the error page.
				JErrorPage::render($error);
			}
		}
		else
		{
			// Render the error page.
			JErrorPage::render($error);
		}
	}
}
