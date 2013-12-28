<?php
/**
 * extension implements site work
 *
 * Class site
 *
 * @author Nikolaev D
 */
class site {
    use trait_extension;

    /**
     * @var array
     */
    public static $js_files = [
        '<script src="/js/site/site/modules/router.js"></script>',
    ];

    /**
     * @return string
     */
    public static  function getLang() {
		$ini = factory::getIniServer('../configuration.ini');

        return $ini->read('language', 'current', false);
	}
}