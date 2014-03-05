<?php
namespace simple_admin_template_admin;

/**
 * Class optionscontroller
 *
 * @package simple_admin_template_admin
 *
 * @author Nikolaev D.
 */
class optionscontroller extends \supercontroller {

    /**
     * @return mixed|string
     */
    public function display()
    {
        $params = \simple_admin_template::get_lang('options_page');
        $params = array_merge($params, \simple_admin_template::read_params('options'));

        $arr = array_merge(\admin::get_user_extensions(), \admin::get_menu_extensions());
        $tmp = [];
        foreach($arr as $el) {
            $tmp[] = $el['name'];
        }

        $arr = array_merge($tmp, ['user', 'system', 'documentation', 'site']);
        $opts = '';

        foreach($arr as $el) {

            if($el == $params['start_extension']) {
                $opt = \dom::create_element(
                    'option',
                    [
                        'selected'  => '',
                        'value'     => $el,
                        'text'      => $el
                    ]
                );
            }
            else {
                $opt = \dom::create_element(
                    'option',
                    [
                        'value'     => $el,
                        'text'      => $el
                    ]
                );
            }

            $opts .= $opt;
        }

        $params['opts'] = $opts;

        $controller = \simple_admin_template::get_admin_controller('default');
        $params['widgets_count'] = count($controller->get_widgets());


        return \templator::getTemplate(
            'index',
            $params,
            \simple_admin_template::$path.'admin'.DS.'views'.DS.'options'
        );
    }

    /**
     * @param $arr
     */
    public function save($arr) {
        if(is_array($arr)) {
            $opts = \simple_admin_template::read_params('options');

            $opts = array_merge($opts, $arr);

            \simple_admin_template::write_params('options', $opts);

            \comet::add_message([
                'task'      => 'delegate',
                'object'    => 'TemplateModel',
                'method'    => 'update',
                'params'    => []
            ]);
        }
        else {
            $ref = \factory::get_reference('errors')['invalid_param'];

            \comet::add_message([
                'task'      => 'delegate',
                'object'    => 'App',
                'method'    => 'showNoty',
                'params'    => [$ref, 'error']
            ]);
        }
    }
}