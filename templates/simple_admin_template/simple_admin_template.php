<?php
/**
 * Class simple_admin_template
 *
 * @author Nikolaev D.
 */
class simple_admin_template implements template_interface {
    use trait_template;

    /**
     * @return mixed|void
     */
    public static function get_info()
    {
        $lang = static::get_lang('alias');

        return [
            'author'    => 'Nikolaev D.',
            'alias'     => $lang['alias'],
            'preview'   => ''
        ];
    }
}