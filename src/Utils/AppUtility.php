<?php
	namespace App\Utils;

    use Locale;

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

        public static function calculateSimilarity( array $a, array $b ) : int {
            if( count( $a ) > count( $b ) ){
                list( $a, $b ) = [ $b, $a ]; //swap
            }

            $ret = 0;
            foreach( $a as $key => $value ){
                if( array_key_exists( $key, $b ) && $value === $b[$key] ){
                    ++$ret;
                }
            }
            return $ret;
        }

    	public static function selectPreset( $langs, array $presets ){
    		if( is_string( $langs ) ){
    			$langs = [ $langs ];
    		}
    		else if( (!is_array( $langs )) || empty( $langs ) || empty( $presets ) ){
    			return "";
    		}

    		$preset_data = [];

    		foreach( $langs as $i => $lang ){
    			if( ( $i = array_search( $lang, $presets ) ) !== false ){
    				return $presets[$i];
    			}
    			else {
    				$lang_data = locale_parse($lang);
    				if( empty($preset_data) ){
    					foreach( $presets as $i => $preset ) {
    						$preset_data[$i] = locale_parse($preset);
    					}
    				}
    				$match = false;
    				$match_rate = 0;
    				foreach ( $preset_data as $i => $preset ) {
    					if( $lang_data['language'] == $preset['language'] ){
    						$c = 0;
    						foreach( $lang_data as $key => $value) {
    							if( array_key_exists( $key, $preset ) && $value == $preset[$key] ){
    								++$c;
    							}
    						}
    						if( $c > $match_rate ){
    							$match = $presets[$i];
    							$match_rate = $c;
    						}
    					}
    				}
    				if( $match ){
    					return $match;
    				}
    			}
    		}

    		return array_values($presets)[0];
    	}

        public static function sortPresets( array $presets, $langs ){
            if( is_string($langs) ){
                $langs = self::parseAcceptLanguage( $langs );
            }

            if( (!is_array($langs)) || empty($presets) || empty($langs) ){
                return $presets;
            }

            $locale_pool = [];
            $parseLocale = function( $lang ) use( $locale_pool ){
                if( array_key_exists( $lang, $locale_pool ) ){
                    return $locale_pool[$lang];
                }
                $locale = Locale::parseLocale( $lang );
                $locale_pool[$lang] = $locale;
                return $locale;
            };

            $locales = [];
            foreach( $langs as $i => $lang ){
                if( array_key_exists( $lang, $locale_pool ) ){
                    continue;
                }
                $locales[] = $parseLocale( $lang );
            };

            $match_pool = [];
            $matchPreset = function( $preset ) use( $locales, $parseLocale, $match_pool ){
                if( array_key_exists( $preset, $match_pool ) ){
                    return $match_pool[$preset];
                }
                $p_locale = $parseLocale( $preset );
                $v_count = count( $p_locale );
                $match_count = 0;
                $ret = count( $locales );
                foreach ( $locales as $i => $locale ) {
                    $mc = self::calculateSimilarity( $p_locale, $locale );
                    if( $match_count < $mc ){
                        $ret = $i;
                        $match_count = $mc;
                    }
                }
                $match_pool[$preset] = $ret;
                return $ret;
            };

            usort( $presets, 
                function( $first, $second ) use( $matchPreset ) : int {
                    return $matchPreset( $first ) - $matchPreset( $second );
                }
            );

            return $presets;
        }

        public static function parseAcceptLanguage( string $str ){
            $langs = [];
            foreach( explode( ',', $str ) as $i => $value ){
                $m = [];
                if( preg_match('/([a-zA-Z-_]*)(;q=([0-9.]*)){0,1}/', $value, $m ) ){
                    $q = ( array_key_exists( 3, $m ) ? $m[3] : 1 ) * 100;
                    $langs[$q][] = $m[1];
                }
            }
            krsort($langs);
            $ret = [];
            foreach( $langs as $i => $list ){
                $ret = array_merge($ret,$list);
            }
            return $ret;
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
				if( array_key_exists('text', $options ) ){
					$ret .= 'value="'.$options['text'].'"';
				}
				$ret .= ' />';
			}
			else {
				$ret .= '>';
				if( array_key_exists('html', $options ) ){
					$ret .= $options['html'];
				}
				else if( array_key_exists('text', $options ) ){
					$ret .= h($options['text']);
				}
				if( array_key_exists('close', $options) && $options['close'] ){
					$ret .= '</'.$tag_name.'>';
				}
			}
			return $ret;
		}
	}
?>