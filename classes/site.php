<?php
/**
 * Class site
 */
class site {
    use trait_extension;

    /**
     * @return string
     */
    public static  function getLang() {
		$ini = factory::getIniServer('../configuration.ini');

        return $ini->read('language', 'current', false);
	}
}