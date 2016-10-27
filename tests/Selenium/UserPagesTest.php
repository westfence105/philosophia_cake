<?php
use PHPUnit\Framework\TestCase;

use Facebook\WebDriver;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;

class UserPagesTest extends TestCase
{
	public $driver;

	public $host;

	public function setUp(){
		parent::setUp();

		$this->host = getenv('CAKE_SERVER_ROOT');
		$this->assertNotEmpty( $this->host, 'CAKE_SERVER_ROOT is not set' );

		$selenium_host = getenv('SELENIUM_HOST');
		$selenium_driver = getenv('SELENIUM_DRIVER');
		if( empty($selenium_host) ){
			$selenium_host = 'http://localhost:4444/wd/hub';
		}
		debug('selenium host: '.$selenium_host);
		$capability = DesiredCapabilities::chrome();
		$capability->setCapability(WebDriverCapabilityType::ACCEPT_SSL_CERTS, false );
		$this->driver = RemoteWebDriver::create( $selenium_host, $capability );

		//login
		$this->driver->get( $this->host.'login' );
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
		$this->driver->get( $this->host.'settings' );

	//	debug( $this->driver->getPageSource(), "\n" );

		//-- test names form --
		$name_inputs = $this->driver->findElements(WebDriverBy::cssSelector('.name_input'));
		$base_full = [];
		$base_short = [];
		foreach ( $name_inputs as $i => $name_input ) {
			$display = $name_input->findElement(WebDriverBy::cssSelector('.input_name_display select'))
								  ->getAttribute('value')
								;
			if( $display != 'private' ){
				$name = $name_input->findElement(WebDriverBy::cssSelector('.input_name input'))
								   ->getAttribute('value');
				if( !empty($name) ){
					$base_full[] = $name;
				}

				if( $display == 'display' && (!empty($name))  ){
					$base_short[] = $name;
				}
				else if( $display == 'short' ){
					$short = $name_input->findElement(WebDriverBy::cssSelector('.input_name_short input'))
										->getAttribute('value');
					if( !empty(($short)) ){
						$base_short[] = $short;
					}
					unset($short);
				}
				unset($name);
			}
			unset($display);
			unset($name_input);
		}
		$gen_preview = function( $addition = [], $addition_short = [] )
								use ( $base_full, $base_short ) 
						{
							foreach ( [ &$addition, &$addition_short ] as $i => &$value) {
								if( is_string($value) ){
									$value = [ $value ];
								}
								if( !is_array($value) ){
									throw \TypeError();
								}
							}
							$full  = array_merge( $base_full, $addition );
							$short = array_merge( $base_short, $addition_short );
							if( !empty( $full ) ){
								return implode( ' ', $full ). 
									   ' => '.
									   ( empty( $short ) ? '""' : implode( ' ', $short ) );
							}
							else if( !empty($short) ) {
								return implode( ' ', $short );
							}
							else return '';
					  	};
		//assert gen_preview works
		$preview = $this->driver->findElement(WebDriverBy::id('name_preview'));
		$this->assertEquals( $gen_preview(), $preview->getText(), 'gen_preview works' );

		//assert adding name element
		$count_old = count($name_inputs);
		$this->driver->findElement(WebDriverBy::cssSelector('.add_name'))->click();
		$name_inputs = $this->driver->findElements(WebDriverBy::cssSelector('.name_input'));
		$this->assertEquals( $count_old + 1, count($name_inputs), 'adding name element' );

		$name_input = end($name_inputs);
		$input_name = $name_input->findElement(WebDriverBy::cssSelector('.input_name'))
				   				 ->findElement(WebDriverBy::cssSelector('input'));
		$input_name_display = $name_input->findElement(WebDriverBy::cssSelector('.input_name_display'))
				   						 ->findElement(WebDriverBy::cssSelector('select'));
		$input_name_short = $name_input->findElement(WebDriverBy::cssSelector('.input_name_short'))
									   ->findElement(WebDriverBy::tagName('input'));
		
		//test input name
		$input_name->click();
		$this->driver->getKeyboard()->sendKeys('Test');
		$input_name_display->findElement(WebDriverBy::cssSelector('option[value="display"]'))->click();
		$this->assertEquals( $gen_preview('Test','Test'), $preview->getText() );

		//assert short input is disabled when 'display' != 'short'
		$this->assertFalse( $input_name_short->isEnabled() );

		$input_name_display->findElement(WebDriverBy::cssSelector('option[value="short"]'))->click();
		$this->assertTrue( $input_name_short->isEnabled() );
		$input_name_short->click();
		$this->driver->getKeyboard()->sendKeys('T');

		//test change display
		$input_name_display->findElement(WebDriverBy::cssSelector('option[value="private"]'))->click();
		$this->assertEquals( $gen_preview(), $preview->getText() );
		$input_name_display->findElement(WebDriverBy::cssSelector('option[value="omit"]'))->click();
		$this->assertEquals( $gen_preview(['Test']), $preview->getText() );
		$input_name_display->findElement(WebDriverBy::cssSelector('option[value="short"]'))->click();
		$this->assertEquals( $gen_preview(['Test'],['T']), $preview->getText() );

		sleep(1);
	}
}