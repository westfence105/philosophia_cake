<?php
namespace App\Test\TestCase\Util;

use App\Utils\AppUtility;
use Cake\TestSuite\TestCase;

class AppUtilityTest extends TestCase
{
	public function testSelectPreset(){
		$cases = [
			[
				'langs' => 'en',
				'presets' => ['en'],
				'expected' => 'en',
			],
			[
				'langs' => 'ja',
				'presets' => ['en','ru'],
				'expected' => 'en',
			],
			[
				'langs' => 'en',
				'presets' => ['ru', 'en_US'],
				'expected' => 'en_US',
			],
			[
				'langs' => 'en_US',
				'presets' => ['en','ru'],
				'expected' => 'en',
			],
			[
				'langs' => 'en_US',
				'presets' => ['en','en_US'],
				'expected' => 'en_US',
			],
			[
				'langs' => ['ja','en','ru'],
				'presets' => ['ru','en'],
				'expected' => 'en',
			],
			[
				'langs' => 'fr_CA',
				'presets' => ['en_CA','fr'],
				'expected' => 'fr',
			],
		];

		foreach ( $cases as $i => $case ) {
			$ret = AppUtility::selectPreset( $case['langs'], $case['presets'] );
			$this->assertEquals( $case['expected'], $ret, "failed at case $i" );
		}
	}
}