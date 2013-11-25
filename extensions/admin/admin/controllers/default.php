<?php
/**
 * Class defaultcontroller
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends supercontroller {
    use trait_extension_controller;

    /**
     * @var
     */
    public $extension;

    public function __construct()
    {
        $this->extension = 'admin';
    }

    public function display()
    {
        $params = $this->getLang('main_page');

        $params['css'] = array_merge(document::$css_files, [
            '<link rel="stylesheet" href="/js/gridster/dist/jquery.gridster.min.css" />',
            '<link rel="stylesheet" href="/css/admin/admin/default.css" />'
        ]);

        $params['js'] = array_merge(document::$js_files, [
            '<script src="/js/admin/admin/views/layout.js"></script>',
            '<script src="js/admin/admin/modules/router.js"></script>',
            '<script src="/js/gridster/dist/jquery.gridster.min.js"></script>'
        ]);

        $params['grid'] = $this->get_grid();

        echo templator::getTemplate('index', $params, admin::$path.'admin'.DS.'views'.DS.'default');
    }

    /**
     * @return string
     */
    public function get_grid() {
        $arr = json_decode(file_get_contents('..'.DS.'extensions'.DS.'admin'.DS.'admin'.DS.'resources'.DS.'grid.txt'), true);

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
     * @param $params
     */
    public function update_grid($params)
    {
        $params = json_encode($params);

        file_put_contents('..'.DS.'extensions'.DS.'admin'.DS.'admin'.DS.'resources'.DS.'grid.txt', $params);
    }
}