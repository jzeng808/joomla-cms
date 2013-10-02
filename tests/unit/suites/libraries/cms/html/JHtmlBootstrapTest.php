<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once __DIR__ . '/stubs/JHtmlBootstrapInspector.php';
require_once __DIR__ . '/stubs/JHtmlJqueryInspector.php';

/**
 * Test class for JHtmlBootstrap.
 * Generated by PHPUnit on 2012-08-16 at 17:39:35.
 */
class JHtmlBootstrapTest extends TestCase
{
	/**
	 * Backup of the SERVER superglobal
	 *
	 * @var    array
	 * @since  3.1
	 */
	protected $backupServer;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	protected function setUp()
	{
		// Ensure the loaded states are reset
		JHtmlBootstrapInspector::resetLoaded();
		JHtmlJqueryInspector::resetLoaded();

		parent::setUp();

		$this->saveFactoryState();

		JFactory::$application = $this->getMockApplication();
		JFactory::$document = $this->getMockDocument();

		$this->backupServer = $_SERVER;

		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['SCRIPT_NAME'] = '';
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	protected function tearDown()
	{
		$_SERVER = $this->backupServer;

		$this->restoreFactoryState();

		parent::tearDown();
	}

	/**
	 * @todo   Implement testAffix().
	 */
	public function testAffix()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.'
		);
	}

	/**
	 * Tests the alert method.
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	public function testAlert()
	{
		// Initialise the alert script
		JHtmlBootstrap::alert();

		// Get the document instance
		$document = JFactory::getDocument();

		$this->assertArrayHasKey(
			'/media/jui/js/bootstrap.min.js',
			$document->_scripts,
			'Verify that the alert method initialises Bootstrap as well'
		);

		$this->assertEquals(
			$document->_script['text/javascript'],
			"(function($){\n\t\t\t\t$('.alert').alert();\n\t\t\t\t})(jQuery);",
			'Verify that the alert script is initialised'
		);
	}

	/**
	 * Tests the button method.
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	public function testButton()
	{
		// Initialise the alert script
		JHtmlBootstrap::button();

		// Get the document instance
		$document = JFactory::getDocument();

		$this->assertArrayHasKey(
			'/media/jui/js/bootstrap.min.js',
			$document->_scripts,
			'Verify that the button method initialises Bootstrap as well'
		);

		$this->assertEquals(
			$document->_script['text/javascript'],
			"(function($){\n\t\t\t\t$('.button').button();\n\t\t\t\t})(jQuery);",
			'Verify that the button script is initialised'
		);
	}

	/**
	 * @todo   Implement testCarousel().
	 */
	public function testCarousel()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.'
		);
	}

	/**
	 * Tests the dropdown method.
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	public function testDropdown()
	{
		// Initialise the dropdown script
		JHtmlBootstrap::dropdown();

		// Get the document instance
		$document = JFactory::getDocument();

		$this->assertArrayHasKey(
			'/media/jui/js/bootstrap.min.js',
			$document->_scripts,
			'Verify that the dropdown method initialises Bootstrap as well'
		);

		$this->assertEquals(
			$document->_script['text/javascript'],
			"(function($){\n\t\t\t\t$('.dropdown-toggle').dropdown();\n\t\t\t\t})(jQuery);",
			'Verify that the dropdown script is initialised'
		);
	}

	/**
	 * Tests the framework method.
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	public function testFramework()
	{
		// Initialise the Bootstrap JS framework
		JHtmlBootstrap::framework();

		// Get the document instance
		$document = JFactory::getDocument();

		$this->assertArrayHasKey(
			'/media/jui/js/jquery.min.js',
			$document->_scripts,
			'Verify that Bootstrap initializes jQuery as well'
		);

		$this->assertArrayHasKey(
			'/media/jui/js/bootstrap.min.js',
			$document->_scripts,
			'Verify that Bootstrap initializes Bootstrap'
		);
	}

	/**
	 * @todo   Implement testModal().
	 */
	public function testModal()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.'
		);
	}

	/**
	 * @todo   Implement testRenderModal().
	 */
	public function testRenderModal()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.'
		);
	}

	/**
	 * @todo   Implement testPopover().
	 */
	public function testPopover()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.'
		);
	}

	/**
	 * @todo   Implement testScrollspy().
	 */
	public function testScrollspy()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.'
		);
	}

	/**
	 * @todo   Implement testTooltip().
	 */
	public function testTooltip()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.'
		);
	}

	/**
	 * @todo   Implement testTypeahead().
	 */
	public function testTypeahead()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.'
		);
	}

	/**
	 * @todo   Implement testStartAccordion().
	 */
	public function testStartAccordion()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.'
		);
	}

	/**
	 * Tests the endAccordion method
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function testEndAccordion()
	{
		$this->assertThat(
			JHtml::_('bootstrap.endAccordion'),
			$this->equalTo('</div>')
		);
	}

	/**
	 * @todo   Implement testAddSlide().
	 */
	public function testAddSlide()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.'
		);
	}

	/**
	 * Tests the endSlide method
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function testEndSlide()
	{
		$this->assertThat(
			JHtml::_('bootstrap.endSlide'),
			$this->equalTo('</div></div></div>')
		);
	}

	/**
	 * @todo   Implement testStartTabSet().
	 */
	public function testStartTabSet()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.'
		);
	}

	/**
	 * Tests the endTabSet method
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	public function testEndTabSet()
	{
		$this->assertThat(
			JHtml::_('bootstrap.endTabSet'),
			$this->equalTo("\n</div>")
		);
	}

	/**
	 * @todo   Implement testAddTab().
	 */
	public function testAddTab()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.'
		);
	}

	/**
	 * Tests the endTab method
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	public function testEndTab()
	{
		$this->assertThat(
			JHtml::_('bootstrap.endTab'),
			$this->equalTo("\n</div>")
		);
	}

	/**
	 * @todo   Implement testStartPane().
	 */
	public function testStartPane()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.'
		);
	}

	/**
	 * Tests the endPane method
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function testEndPane()
	{
		$this->assertThat(
			JHtml::_('bootstrap.endTabSet'),
			$this->equalTo("\n</div>")
		);
	}

	/**
	 * @todo   Implement testAddPanel().
	 */
	public function testAddPanel()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.'
		);
	}

	/**
	 * Tests the endPanel method
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function testEndPanel()
	{
		$this->assertThat(
			JHtml::_('bootstrap.endTab'),
			$this->equalTo("\n</div>")
		);
	}

	/**
	 * Tests the loadCss method.
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	public function testLoadCss()
	{
		// Initialise the Bootstrap JS framework
		JHtmlBootstrap::loadCss(true, 'rtl');

		// Get the document instance
		$document = JFactory::getDocument();

		$this->assertArrayHasKey(
			'/media/jui/css/bootstrap.min.css',
			$document->_styleSheets,
			'Verify that the base Bootstrap CSS is loaded'
		);

		$this->assertArrayHasKey(
			'/media/jui/css/bootstrap-rtl.css',
			$document->_styleSheets,
			'Verify that the RTL Bootstrap CSS is loaded'
		);
	}
}
