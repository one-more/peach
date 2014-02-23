<?php

/**
 * Class removecontroller
 *
 * @author Nikolaev D.
 */
class removecontroller extends supercontroller {

    /**
     * @var
     */
    public $extension;

    use trait_extension_controller;

    public function __construct()
    {
        $this->extension = 'installer';
    }

    /**
     *
     */
    public function display()
    {
        $params = $this->getLang('remove_page');

        $trs = '';

        $arr = admin::get_daemons();

        $j = 1;

        foreach($arr as $el) {

            $tr = '';

            $td = dom::create_element(
                'td',
                [
                    'text'  => $j
                ]
            );
            $tr .= $td;

            $td = dom::create_element(
                'td',
                [
                    'text'  => $el['name']
                ]
            );
            $tr .= $td;

            $td = dom::create_element(
                'td',
                [
                    'text'  => $params['daemon']
                ]
            );
            $tr .= $td;

            $i = dom::create_element(
                'i',
                [
                    'class'     => 'icon-trash installer-remove-icon cursor-pointer',
                    'data-type' => 'daemon',
                    'data-name' => $el['name']
                ]
            );
            $td = dom::create_element(
                'td',
                [
                    'text'  => $i
                ]
            );
            $tr .= $td;

            $trs .= dom::create_element(
                'tr',
                [
                    'text' => $tr
                ]
            );
            $j++;
        }

        $arr = admin::get_editors();

        foreach($arr as $el) {
            if(!in_array($el['name'], ['ckeditor', 'tinymce'])) {
                $tr = '';

                $td = dom::create_element(
                    'td',
                    [
                        'text'  => $j
                    ]
                );
                $tr .= $td;

                $td = dom::create_element(
                    'td',
                    [
                        'text'  => $el['name']
                    ]
                );
                $tr .= $td;

                $td = dom::create_element(
                    'td',
                    [
                        'text'  => $params['editor']
                    ]
                );
                $tr .= $td;

                $i = dom::create_element(
                    'i',
                    [
                        'class'     => 'icon-trash installer-remove-icon cursor-pointer',
                        'data-type' => 'editor',
                        'data-name' => $el['name']
                    ]
                );
                $td = dom::create_element(
                    'td',
                    [
                        'text'  => $i
                    ]
                );
                $tr .= $td;

                $trs .= dom::create_element(
                    'tr',
                    [
                        'text' => $tr
                    ]
                );
                $j++;
            }
        }

        $arr = array_merge(
            admin::get_menu_extensions(),
            admin::get_user_extensions()
        );
        foreach($arr as $el) {
            $tr = '';
            $td = dom::create_element(
                'td',
                [
                    'text'  => $j
                ]
            );
            $tr .= $td;

            $alias = $el['name']::get_info()['alias'];
            $td = dom::create_element(
                'td',
                [
                    'text'  => $alias
                ]
            );
            $tr .= $td;

            $td = dom::create_element(
                'td',
                [
                    'text'  => $params['extension']
                ]
            );
            $tr .= $td;

            $i = dom::create_element(
                'i',
                [
                    'class'     => 'icon-trash installer-remove-icon cursor-pointer',
                    'data-type' => 'extension',
                    'data-name' => $el['name']
                ]
            );
            $td = dom::create_element(
                'td',
                [
                    'text'  => $i
                ]
            );
            $tr .= $td;

            $trs .= dom::create_element(
                'tr',
                [
                    'text' => $tr
                ]
            );
            $j++;
        }

        $at = admin::get_admin_templates();
        $st = admin::get_site_templates();
        $arr = array_merge($at, $st);
        $ct = [
            admin::get_template(), site::get_template()
        ];

        foreach($arr as $el) {
            if(!in_array($el['name'], $ct)) {
                $tr = '';
                $td = dom::create_element(
                    'td',
                    [
                        'text'  => $j
                    ]
                );
                $tr .= $td;

                $alias = $el['name']::get_info()['alias'];
                $td = dom::create_element(
                    'td',
                    [
                        'text'  => $alias
                    ]
                );
                $tr .= $td;

                $td = dom::create_element(
                    'td',
                    [
                        'text'  => in_array($el, $st) ? $params['site_template']
                                : $params['admin_template']
                    ]
                );
                $tr .= $td;

                $i = dom::create_element(
                    'i',
                    [
                        'class'     => 'icon-trash installer-remove-icon cursor-pointer',
                        'data-type' => 'template',
                        'data-name' => $el['name']
                    ]
                );
                $td = dom::create_element(
                    'td',
                    [
                        'text'  => $i
                    ]
                );
                $tr .= $td;

                $trs .= dom::create_element(
                    'tr',
                    [
                        'text' => $tr
                    ]
                );
                $j++;
            }
        }

        $params['trs'] = $trs;

        if($trs) {
            return templator::getTemplate(
                'index',
                $params,
                installer::$path.'admin'.DS.'views'.DS.'remove'
            );
        }
        else {
            return templator::get_warning($params['no_extensions']);
        }
    }

    /**
     * @param $arr
     * @throws Exception
     */
    public function remove($arr)
    {
        if(is_array($arr)) {
            $model = installer::getAdminModel('remove');

            $model->remove($arr);
            installer::clear_cache(true);
        }
        else {
            throw new Exception('wrong params');
        }
    }
}