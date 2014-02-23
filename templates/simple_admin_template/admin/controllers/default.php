<?php
namespace simple_admin_template_admin;
use supercontroller, document, templator, simple_admin_template, dom;
/**
 * Class defaultcontroller
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends supercontroller {

    use \trait_extension_controller;

    /**
     * @var
     */
    public $extension;

    public function __construct()
    {
        $this->extension = 'simple_admin_template';
    }

    /**
     * @return mixed|string
     */
    public function display()
    {
        $params = simple_admin_template::get_lang('main_page');

        $params['css'] = array_merge(document::$css_files, [
            '<link rel="stylesheet" href="/js/gridster/dist/jquery.gridster.min.css" />',
            '<link rel="stylesheet" href="/css/simple_admin_template/admin/default.css" />',
            '<link rel="stylesheet" href="/js/jtoolbar/jquery.toolbars.css" />'
        ]);

        $params['js'] = array_merge(document::$js_files, [
            '<script src="/js/simple_admin_template/admin/views/layout.js"></script>',
            '<script src="/js/simple_admin_template/admin/modules/router.js"></script>',
            '<script src="/js/gridster/dist/jquery.gridster.min.js"></script>',
            '<script src="/js/jtoolbar/jquery.toolbar.js"></script>',
            '<script src="/js/simple_admin_template/admin/views/widget.js"></script>',
            '<script src="/js/simple_admin_template/admin/models/widget.js"></script>',
            '<script src="/js/simple_admin_template/admin/models/template_model.js"></script>'
        ]);

        $params['js'] = array_merge($params['js'], \system::$system_js);

        $params['css'] = \builder::build('simple_admin_template.css', $params['css']);

        $params['js'] = \builder::build('simple_admin_template.js', $params['js']);

        $params['grid'] = $this->get_grid();

        $params['user_extensions_list'] = $this->get_menu();

        return templator::getTemplate(
            'index',
            $params,
            '..'.DS.'templates'.DS.'simple_admin_template'.DS.'admin'.DS.'views'.DS.'default'
        );
    }

    /**
     * @return string
     */
    public function get_menu()
    {
        $params = simple_admin_template::get_lang('main_page');

        $user_extensions = array_merge(
            \admin::get_user_extensions(),
            \admin::get_daemons()
        );

        if(count($user_extensions) > 0) {

            $li = '';

            foreach($user_extensions as $el) {
                $a = dom::create_element('a', [
                    'href'  => '/admin/'.$el['name'],
                    'text'  => $el['name']::get_info()['alias']
                ]);

                $li .= dom::create_element('li', [
                    'text'  => $a
                ]);
            }

            return $li;
        }
        else {
            return dom::create_element('li', [
                'text'=> $params['NO_EXTENSIONS']
            ]);
        }
    }

    /**
     * @return string
     */
    public function get_grid()
    {
        $arr = json_decode(
            file_get_contents('..'.DS.'templates'.DS.'simple_admin_template'.DS.'admin'.DS.'resources'.DS.'grid.txt'),
            true
        );

        $str = '';

        foreach($arr as $num=>$el) {
            $params = [
                'data-col'      =>   $el['col'],
                'data-row'      =>   $el['row'],
                'data-sizex'    =>   $el['size_x'],
                'data-sizey'    =>   $el['size_y'],
                'data-widget'   =>   $num
            ];

            $str .= dom::create_element('<li>', $params);
        }

        return $str;
    }

    /**
     * @param $arr
     */
    public function update_grid($arr)
    {
        $arr = json_encode($arr);

        file_put_contents(
            '..'.DS.'templates'.DS.'simple_admin_template'.DS.'admin'.DS.'resources'.DS.'grid.txt',
            $arr
        );
    }

    /**
     * @return array
     */
    public function get_widgets()
    {
        return simple_admin_template::read_params('widgets');
    }

    /**
     * @param $arr
     * @return string
     */
    public function update_widgets($arr)
    {
        if(is_array($arr)) {
            simple_admin_template::write_params('widgets', $arr);
        }
        else {
            return 'update failed';
        }
    }

    /**
     * @return array|bool|string
     */
    public function get_options()
    {
        return simple_admin_template::read_params('options');
    }

    /**
     * @param null $class
     * @return array
     */
    public function get_widget_list($class = null)
    {
        if(!$class) {
            $arr =  \admin::get_widgets();

            $li = '';

            foreach($arr as $el) {
                $alias = $el::get_info()['alias'];

                $a = dom::create_element('<a>',
                    ['data-extension'=>$el, 'text'=>$alias, 'class'=>'cursor-pointer']);

                $li .= dom::create_element('<li>', ['text'=>$a]);
            }

            $h3 = $this->getLang('widget_modal')['header'];

            $h3 = dom::create_element('<h3>', ['text'=>$h3]);

            return $h3.dom::create_element('<ul>', ['text'=>$li, 'class'=>'widget-extensions']);
        }
        else {
            $arr = $class::get_widgets();

            $li = '';

            foreach($arr as $el) {

                $a = dom::create_element('<a>', [
                    'data-widget'   => $el['name'],
                    'text'          => $el['alias'],
                    'class'         => 'cursor-pointer',
                    'data-class'    => $class
                ]);

                $li .= dom::create_element('<li>', ['text'=>$a]);
            }

            return dom::create_element('ul', [
                'class' => 'widget-list',
                'text'  => $li
            ]);
        }
    }
}