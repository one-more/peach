<?php
namespace admin_admin;

/**
 * Class optionscontroller
 *
 * @package admin_admin
 *
 * @author Nikolaev D.
 */
class optionscontroller extends \supercontroller {

    /**
     * @return string
     */
    public function display()
    {
        $params = \admin::read_lang('options_page');
        $params = array_merge($params, \admin::read_params('options'));
        $params['js'] = array_merge(
            \helper::get_jquery_tabs_js(),
            [\dom::create_element(
                'script',
                [
                    'src'   => '/js/admin/admin/views/admin_view.js'
                ]
            )]);
        $params['css'] = \dom::create_element(
          'link',
            [
                'rel'   => 'stylesheet',
                'href'  => '/js/ui/themes/base/jquery.ui.all.css'
            ]
        );
        $params['js'] = \builder::build('default_admin.js', $params['js']);

        $params['opts'] = $this->get_editors();

        return \templator::getTemplate(
            'index',
            $params,
            \admin::$path.'admin'.DS.'views'.DS.'options'
        );
    }

    /**
     * @return string
     */
    public function get_editors()
    {
        $params = \admin::read_params('options');
        $arr = \admin::get_editors();

        $opts = '';

        foreach($arr as $el) {
            if($el['name'] == $params['editor']) {
                $opt = \dom::create_element(
                    'option',
                    [
                        'selected'  => '',
                        'value'     => $el['name'],
                        'text'      => $el['name']
                    ]
                );
            }
            else {
                $opt = \dom::create_element(
                    'option',
                    [
                        'value' => $el['name'],
                        'text'  => $el['name']
                    ]
                );
            }
            $opts .= $opt;
        }

        return $opts;
    }

    /**
     * @param $name
     */
    public function select_editor($name)
    {
        if(strval($name)) {
            \admin::write_params('options', 'editor', $name);
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