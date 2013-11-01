<?php
class factory {
    /**
     * @var $_document - contains current templates html
     */
    private static  $_document = null;

    /**
     * @var $_site - contains site class
     */
    private static  $_site = null;

    /**
     * @var $_template - contains current templates class
     */
    private static $_template = null;

    /**
     * @var $_iniserver - class for work with ini files
     */
    private static $_iniserver = null;

    /**
     * @return document
     */
    public static  function getDocument() {
        if(self::$_document == null) {
            self::$_document = new document();
        }

        return self::$_document;
    }

    /**
     * @return site
     */
    public static  function getSite() {
        if(self::$_site == null) {
            self::$_site = new site();
        }

        return self::$_site;
    }

    /**
     * @return current templates class
     */
    public static function getTemplate()
    {
        if(self::$_template == null) {
            $site = self::getSite();
            $tmpName = $site->getTemplate();
            require_once("../templates/$tmpName/$tmpName.php");

            self::$_template = new $tmpName;
        }

        return self::$_template;
    }

    /**
     * @return ini observer class
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