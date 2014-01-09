<?php
/**
 * implements factory pattern
 *
 * Class factory
 *
 * @author Nikolaev D.
 */
class factory {
    /**
     * @var $_iniserver - class for work with ini files
     */
    private static $_iniserver = null;

    /**
     * @param null $path
     * @return iniServer|null
     */
    public static function getIniServer($path = null) {
        if($path != null) {
			return new iniServer($path);
		}

		if(static::$_iniserver == null) {
            static::$_iniserver = new iniServer('../configuration.ini');
        }

        return static::$_iniserver;
    }

    /**
     * @param null $section
     * @param string $lang
     * @return bool|iniServer|null
     */
    public static function get_reference($section = null, $lang = 'en-EN')
    {
        $path1 = '..'.DS.'lang'.DS.'references'.DS.system::get_current_lang().'.ini';
        $path2 = '..'.DS.'lang'.DS.'references'.DS.$lang.'.ini';

        if(file_exists($path1)) {
            if($section) {
                return factory::getIniServer($path1)->readSection($section);
            }
            else {
                return false;
            }
        }
        elseif(file_exists($path2)) {
            if($section) {
                return factory::getIniServer($path2)->readSection($section);
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }
}