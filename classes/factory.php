<?php
/**
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
}