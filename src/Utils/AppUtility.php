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
                    $ret = $matchPreset( $first ) - $matchPreset( $second );
                    if( $ret == 0 ){
                        return strnatcmp( $first, $second ); //sort by alpha if same priority
                    }
                    else {
                        return $ret;
                    }
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