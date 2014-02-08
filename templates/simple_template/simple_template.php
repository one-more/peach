<?php
/**
 * simple site template
 *
 * Class simple_template
 *
 * @author Nikolaev D.
 */
class simple_template implements site_template_interface{
    use trait_template;
    use trait_site_template;

    /**
     * @return array|mixed
     */
    public static function get_info()
    {
        $alias = static::get_lang('info')['alias'];

        return [
            'alias'     => $alias,
            'author'    => 'Nikolaev D.',
            'preview'   => '/media/simple_template/preview.png'
        ];
    }
}