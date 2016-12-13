<?php
namespace App\Test\TestCase\Util;

use App\Utils\AppUtility;
use Cake\TestSuite\TestCase;

class AppUtilityTest extends TestCase
{

	public function testCalculateSimilarity(){
		$cases = [
			[
				'a' => ['a','b'],
				'b' => ['a','c'],
				'expected' => 1
			],
			[
				'a' => ['a','b','c'],
				'b' => ['a','c'],
				'expected' => 1
			],
		];

		foreach( $cases as $i => $case ){
			$ret = AppUtility::calculateSimilarity( $case['a'], $case['b'] );
			$this->assertEquals( $case['expected'], $ret,
					"Failed asserting case ${i}.\n".json_encode( $case, JSON_PRETTY_PRINT )
				);
		}
	}

	public function testSortPresets(){
		$cases = [
			[
				'presets' 	=> ['en','ja'],
				'langs' 	=> ['ru'],
				'expected' 	=> ['en','ja']
			],
			[
				'presets' 	=> ['en','ja'],
				'langs' 	=> ['ru','en'],
				'expected' 	=> ['en','ja']
			],
			[
				'presets' 	=> ['en','ja','ru'],
				'langs' 	=> 'ja,ru,en',
				'expected' 	=> ['ja','ru','en']
			],
			[
				'presets' 	=> ['en','ja','ru'],
				'langs' 	=> 'en;q=0.8,ru',
				'expected' 	=> ['ru','en','ja']
			],
		];

		foreach( $cases as $i => $case ){
			$ret = AppUtility::sortPresets( $case['presets'], $case['langs'] );
			$this->assertEquals( $case['expected'], $ret,
					"Failed asserting case ${i}.\n".json_encode( $case, JSON_PRETTY_PRINT )
				);
		}
	}

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

	public function testParseAcceptLanguage(){
		$cases = [
			[
				'str' => 'ja,en',
				'expected' => ['ja','en'],
			],
			[
				'str' => 'ja;q=0.8,en',
				'expected' => ['en','ja'],
			],
			[
				'str' => 'en,ru;q=0.6,ja;q=0.8',
				'expected' => ['en','ja','ru'],
			],
		];

		foreach( $cases as $i => $case ){
			$ret = AppUtility::parseAcceptLanguage( $case['str'] );
			$this->assertEquals( $case['expected'], $ret, "failed at case $i" );
		}
	}
}