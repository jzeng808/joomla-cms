<?php
/**
 * @package	    Joomla.UnitTest
 * @subpackage  Toolbar
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Test class for JToolbarButtonLink.
 * Generated by PHPUnit on 2012-08-10 at 22:19:08.
 */
class JToolbarButtonLinkTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var JToolbar
	 */
	protected $toolbar;

	/**
	 * @var JToolbarButtonLink
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->toolbar = JToolbar::getInstance();
		$this->object  = $this->toolbar->loadButtonType('link');
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

	/**
	 * Tests the fetchButton method
	 *
	 * @return  void
	 *
	 * @since   3.0
	 *
	 * @covers  JToolbarButtonLink::fetchButton
	 */
	public function testFetchButton()
	{
		$this->assertThat(
			$this->object->fetchButton('Link', 'jdotorg', 'Joomla.org', 'http://www.joomla.org'),
			$this->equalTo("<button class=\"btn btn-small\" onclick=\"location.href='http://www.joomla.org';\">\n<span class=\"icon-jdotorg\">\n</span>\nJoomla.org\n</button>\n")
		);
	}

	/**
	 * Tests the fetchId method
	 *
	 * @return  void
	 *
	 * @since   3.0
	 *
	 * @covers  JToolbarButtonLink::fetchId
	 */
	public function testFetchId()
	{
		$this->assertThat(
			$this->object->fetchId('link', 'test'),
			$this->equalTo('toolbar-test')
		);
	}
}
