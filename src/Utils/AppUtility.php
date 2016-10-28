<?php
	namespace App\Utils;

	class AppUtility {

    	public static function array_subset( array $exp, array $values ){
    	    foreach ( $values as $key => $value) {
    	        foreach ( $exp as $e_key => $e_value) {
    	            if( array_key_exists($e_key,$value) && $value[$e_key] == $e_value ){
    	                continue;
    	            }
    	            else {
    	                return false;
    	            }
    	        }
    	    }
    	    return true;
    	}

		public static function genHtmlTag( $tag_name, $options ){
			$ret = '<'.$tag_name;
			if( array_key_exists('attrs', $options) ){
				foreach ( $options['attrs'] as $key => $value) {
					if( !is_string($value) ){
						$value = json_encode($value);
					}
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