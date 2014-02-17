<?php
/**
 * simple template for administrative panel
 *
 * Class simple_admin_template
 *
 * @author Nikolaev D.
 */
class simple_admin_template implements admin_template_interface {
    use trait_template;

    /**
     * @return mixed|void
     */
    public static function get_info()
    {
        $alias = static::get_lang('info')['alias'];

        return [
            'author'    => 'Nikolaev D.',
            'alias'     => $alias,
            'preview'   => DS.'media'.DS.'simple_admin_template'.DS.'preview.png'
        ];
    }

    public static function delete()
    {

    }
}