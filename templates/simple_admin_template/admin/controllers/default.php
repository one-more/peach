<?php
namespace simple_admin_template_admin;
use supercontroller, document, templator, simple_admin_template, dom;
/**
 * Class defaultcontroller
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends supercontroller {
    /**
     * @return mixed|string
     */
    public function display()
    {
        $params = simple_admin_template::get_lang('main_page');

        $params['css'] = array_merge(document::$css_files, [
            '<link rel="stylesheet" href="/js/gridster/dist/jquery.gridster.min.css" />',
            '<link rel="stylesheet" href="/css/simple_admin_template/admin/default.css" />'
        ]);

        $params['js'] = array_merge(document::$js_files, [
            '<script src="/js/simple_admin_template/admin/views/layout.js"></script>',
            '<script src="/js/simple_admin_template/admin/modules/router.js"></script>',
            '<script src="/js/gridster/dist/jquery.gridster.min.js"></script>'
        ]);

        $params['js'] = array_merge($params['js'], \admin::$js_files);

        $params['grid'] = $this->get_grid();

        return templator::getTemplate(
            'index',
            $params,
            '..'.DS.'templates'.DS.'simple_admin_template'.DS.'admin'.DS.'views'.DS.'default'
        );
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
}