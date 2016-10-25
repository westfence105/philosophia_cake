<?php
use PHPUnit\Framework\TestCase;

use Facebook\WebDriver;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

class UserPagesTest extends TestCase
{
	public $server = 'http://localhost:4444/wd/hub';

	public $driver;

	public function setUp(){
		parent::setUp();

		$this->driver = RemoteWebDriver::create( $this->server, DesiredCapabilities::chrome() );

		//login
		$this->driver->get('http://localhost:8765/login');
		$this->driver->findElement(WebDriverBy::id('username'))->click();
		$this->driver->getKeyboard()->sendKeys('test');
		$this->driver->findElement(WebDriverBy::id('password'))->click();
		$this->driver->getKeyboard()->sendKeys('password');
		$this->driver->findElement(WebDriverBy::cssSelector('input[type="submit"]'))->click();

		$this->driver->wait()->until(
				WebDriverExpectedCondition::titleContains('philosophia')
			);
	}

	public function tearDown(){
		$this->driver->close();

		parent::tearDown();
	}

	public function testConfig(){
		$this->driver->get('http://localhost:8765/config');

	//	debug( $this->driver->getPageSource(), "\n" );

		$name_inputs = $this->driver->findElements(WebDriverBy::cssSelector('.name_input'));

		$name_input = end($name_inputs);
		
		$name_input->findElement(WebDriverBy::cssSelector('.input_name'))
				   ->findElement(WebDriverBy::cssSelector('input'))
				   ->click()
				;
		$this->driver->getKeyboard()->sendKeys('Test');
		
		$name_input->findElement(WebDriverBy::cssSelector('.input_name_display'))
				   ->findElement(WebDriverBy::cssSelector('select'))
				   ->click();
		$this->driver->getKeyboard()->sendKeys('display');

		$preview = $this->driver->findElement(WebDriverBy::id('name_preview'));

		$this->assertRegExp('/Test => .*Test/', $preview->getText() );
	}
}