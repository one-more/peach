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
     * @return mixed
     */
    public static function get_template()
    {
        return static::read_params('options')['template'];
    }
}