<?php
/**
 * Class site
 */
class site {
    use trait_extension;

    /**
     * @var array
     */
    public static $js_files = [
        '<script src="/js/site/site/modules/router.js"></script>',
        '<script src="/js/site/site/models/site_model.js"></script>'
    ];

    /**
     * @return string
     */
    public static  function getLang() {
		$ini = factory::getIniServer('../configuration.ini');

        return $ini->read('language', 'current', false);
	}
}