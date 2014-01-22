<?php
/**
 * simple site template
 *
 * Class simple_template
 *
 * @author Nikolaev D.
 */
class simple_template implements template_interface{

    /**
     * @var string
     */
    public static  $path = '../templates/simple_template/';

    use trait_template;

    /**
     * @return array|mixed
     */
    public static function get_info()
    {
        $alias = static::get_lang('info')['alias'];

        return [
            'alias'     => $alias,
            'author'    => 'Nikolaev D.',
            'preview'   => ''
        ];
    }
}