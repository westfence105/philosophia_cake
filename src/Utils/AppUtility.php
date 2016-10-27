<?php
	namespace App\Utils;

	class AppUtility {
		public static function genHtmlTag( $tag_name, $options ){
			$ret = '<'.$tag_name;
			if( array_key_exists('attrs', $options) ){
				foreach ( $options['attrs'] as $key => $value) {
					$ret .= ' '.$key.'="'.h($value).'"';
				}
			}
			if( array_key_exists('single', $options) && $options['single'] ){
				$ret .= ' />';
			}
			else if( array_key_exists('close', $options) && $options['close'] ){
				$ret .= '></'.$tag_name.'>';
			}
			else {
				$ret .= '>';
			}
			return $ret;
		}
	}
?>