<?php
/**
 * @package    Joomla.Installation
 *
 * @copyright  Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @since      3.0.x
 */

defined('_JEXEC') or die;

jimport('joomla.updater.update');

/**
 * Language Installer model for the Joomla Core Installer.
 *
 * @package  Joomla.Installation
 * @since    3.0
 */
class InstallationModelLanguages extends JModelLegacy
{

	/**
	 * @var object client object
	 */
	protected $client = null;

	/**
	 * @var array languages description
	 */
	protected $data = null;

	/**
	 * @var string language path
	 */
	protected $path = null;

	/**
	 * @var int total number of languages installed
	 */
	protected $langlist = null;

	/**
 	 * Constructor, deletes the default installation config file and recreates it with the good config file.
	 */
	public function __construct()
	{

		// Overrides application config and set the configuration.php file so tokens and database works
		JFactory::$config = null;
		JFactory::getConfig(JPATH_SITE . '/configuration.php');
		JFactory::$session = null;

		parent::__construct();
	}

	/**
	 * Generate a list of language choices to install in the Joomla CMS
	 *
	 * @return	boolean True if successful
	 */
	public function getItems()
	{
		$updater = JUpdater::getInstance();

		/*
		 * The following function uses extension_id 600, that is the English language extension id.
		 * In #__update_sites_extensions you should have 600 linked to the Accredited Translations Repo
		 */
		$updater->findUpdates(array(600), 0);

		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the updates table
		$query->select('update_id, name, version')
			->from('#__updates')
			->order('name');

		$db->setQuery($query);
		$list = $db->loadObjectList();

		if (!$list || $list instanceof Exception)
		{
			$list = array();
		}

		return $list;
	}

	/**
	 * Method that installs in Joomla! the selected languages in the Languages View of the installer
	 *
	 * @param   array  $lids  list of the update_id value of the languages to install
	 *
	 * @return  boolean True if successful
	 */
	public function install($lids)
	{

		$app			= JFactory::getApplication();
		$installer		= JInstaller::getInstance();

		// Loop through every selected language
		foreach ($lids as $id)
		{

			// Loads the update database object that represents the language
			$language = JTable::getInstance('update');
			$language->load($id);

			// Get the url to the XML manifest file of the selected language
			$remote_manifest 	= $this->_getLanguageManifest($id);
			if (!$remote_manifest)
			{

				// Could not find the url, the information in the update server may be corrupt
				$message 	= JText::sprintf('INSTL_DEFAULTLANGUAGE_COULD_NOT_INSTALL_LANGUAGE', $language->name);
				$message 	.= ' ' . JText::_('INSTL_DEFAULTLANGUAGE_TRY_LATER');
				$app->enqueueMessage($message);
				continue;
			}

			// Based on the language XML manifest get the url of the package to download
			$package_url 		= $this->_getPackageUrl($remote_manifest);
			if (!$package_url)
			{

				// Could not find the url , maybe the url is wrong in the update server, or there is not internet access
				$message 	= JText::sprintf('INSTL_DEFAULTLANGUAGE_COULD_NOT_INSTALL_LANGUAGE', $language->name);
				$message 	.= ' ' . JText::_('INSTL_DEFAULTLANGUAGE_TRY_LATER');
				$app->enqueueMessage($message);
				continue;
			}

			// Download the package to the tmp folder
			$package 			= $this->_downloadPackage($package_url);

			// Install the package
			if (!$installer->install($package['dir']))
			{

				// There was an error installing the package
				$message 	= JText::sprintf('INSTL_DEFAULTLANGUAGE_COULD_NOT_INSTALL_LANGUAGE', $language->name);
				$message 	.= ' ' . JText::_('INSTL_DEFAULTLANGUAGE_TRY_LATER');
				$app->enqueueMessage($message);
				continue;
			}

			// Cleanup the install files in tmp folder
			if (!is_file($package['packagefile']))
			{
				$config = JFactory::getConfig();
				$package['packagefile'] = $config->get('tmp_path') . '/' . $package['packagefile'];
			}
			JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);

			// Delete the installed language from the list
			$language->delete($id);
		}
		return true;

	}

	/**
	 * Gets the manifest file of a selected language from a the language list in a update server.
	 *
	 * @param   int  $uid  the id of the language in the #__updates table
	 *
	 * @return string
	 */
	protected function _getLanguageManifest($uid)
	{
		$instance	= JTable::getInstance('update');
		$instance->load($uid);
		$detailurl	= trim($instance->detailsurl);

		return $detailurl;
	}

	/**
	 * Finds the url of the package to download.
	 *
	 * @param   string  $remote_manifest  url to the manifest XML file of the remote package
	 *
	 * @return string|bool
	 */
	protected function _getPackageUrl($remote_manifest)
	{
		$update = new JUpdate;
		$update->loadFromXML($remote_manifest);
		$package_url = trim($update->get('downloadurl', false)->_data);

		return $package_url;
	}

	/**
	 * Download a language package from a URL and unpack it in the tmp folder.
	 *
	 * @param   string  $url  url of the package
	 *
	 * @return array|bool Package details or false on failure
	 */
	protected function _downloadPackage($url)
	{

		// Download the package from the given URL
		$p_file = JInstallerHelper::downloadPackage($url);

		// Was the package downloaded?
		if (!$p_file)
		{
			JError::raiseWarning('', JText::_('COM_INSTALLER_MSG_INSTALL_INVALID_URL'));
			return false;
		}

		$config		= JFactory::getConfig();
		$tmp_dest	= $config->get('tmp_path');

		// Unpack the downloaded package file
		$package = JInstallerHelper::unpack($tmp_dest . '/' . $p_file);

		return $package;
	}

	/**
	 * Method to get Languages item data for the Administrator
	 *
	 * @return	array
	 */
	public function getInstalledlangsAdministrator()
	{
		return $this->getInstalledlangs('administrator');
	}

	/**
	 * Method to get Languages item data for the Frontend
	 *
	 * @return	array
	 */
	public function getInstalledlangsFrontend()
	{
		return $this->getInstalledlangs('site');
	}

	/**
	 * Method to get Languages item data
	 *
	 * @param   string  $cms_client  name of the cms client
	 *
	 * @return	array
	 */
	private function getInstalledlangs($cms_client = 'administrator')
	{

		// Get information
		$path		= $this->_getPath();
		$client		= $this->_getClient($cms_client);
		$langlist   = $this->_getLanguageList($client->id);

		// Compute all the languages
		$data	= array ();

		foreach ($langlist as $lang)
		{
			$file = $path . '/' . $lang . '/' . $lang . '.xml';
			$info = JApplicationHelper::parseXMLLangMetaFile($file);
			$row = new stdClass;
			$row->language = $lang;

			if (!is_array($info)) {
				continue;
			}

			foreach ($info as $key => $value)
			{
				$row->$key = $value;
			}

			// If current then set published
			$params = JComponentHelper::getParams('com_languages');
			if ($params->get($client->name, 'en-GB') == $row->language)
			{
				$row->published	= 1;
			}
			else {
				$row->published = 0;
			}

			$row->checked_out = 0;
			$data[] = $row;
		}

		usort($data, array($this, '_compareLanguages'));

		return $data;
	}

	/**
	 * Method to get installed languages data.
	 *
	 * @return	string	An SQL query
	 */
	protected function _getLanguageList($client_id = 1)
	{

		// Create a new db object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select field element from the extensions table.
		$query->select('a.element, a.name');
		$query->from('#__extensions AS a');

		$query->where('a.type = ' . $db->quote('language'));
		$query->where('state = 0');
		$query->where('enabled = 1');
		$query->where('client_id=' . (int) $client_id);

		$db->setQuery($query);

		$this->langlist = $db->loadColumn();

		return $this->langlist;
	}

	/**
	 * Method to compare two languages in order to sort them
	 *
	 * @param   object  $lang1  the first language
	 * @param   object  $lang2  the second language
	 *
	 * @return	integer
	 */
	protected function _compareLanguages($lang1, $lang2)
	{
		return strcmp($lang1->name, $lang2->name);
	}


	/**
	 * Method to get the path
	 *
	 * @return	string	The path to the languages folders
	 */
	protected function _getPath()
	{
		if (is_null($this->path))
		{
			$client = $this->_getClient();
			$this->path = JLanguage::getLanguagePath($client->path);
		}

		return $this->path;
	}

	/**
	 * Method to get the client object of Administrator or FrontEnd
	 *
	 * @param   string  $client  name of the client object
	 *
	 * @return	object
	 */
	protected function _getClient( $client = null )
	{
		if (null == $this->client)
		{
			$this->client = JApplicationHelper::getClientInfo('administrator', true);
		}
		if ($client)
		{
			$this->client = JApplicationHelper::getClientInfo($client, true);
		}

		return $this->client;
	}

	/**
	 * Method to set the default language.
	 *
	 * @param   int     $language_id  The Id of the language to be set as default
	 * @param   string  $cms_client   The name of the CMS client
	 *
	 * @return	bool
	 */
	public function setDefault($language_id = null, $cms_client = "administrator")
	{
		if (!$language_id)
		{
			return false;
		}

		$client	= $this->_getClient($cms_client);

		$params = JComponentHelper::getParams('com_languages');
		$params->set($client->name, $language_id);

		$table = JTable::getInstance('extension');
		$id = $table->find(array('element' => 'com_languages'));

		// Load
		if (!$table->load($id))
		{
			$this->setError($table->getError());
			return false;
		}

		$table->params = (string) $params;

		// Pre-save checks
		if (!$table->check())
		{
			$this->setError($table->getError());
			return false;
		}

		// Save the changes
		if (!$table->store())
		{
			$this->setError($table->getError());
			return false;
		}

		return true;
	}

	/**
	 * Get the current setup options from the session.
	 *
	 * @return	array
	 */
	public function getOptions()
	{
		$session = JFactory::getSession();
		$options = $session->get('setup.options', array());

		return $options;
	}
}
