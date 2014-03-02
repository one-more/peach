<?php
namespace menu_admin;

/**
 * Class layoutscontroller
 *
 * @package menu_admin
 *
 * @author Nikolaev D.
 */
class layoutscontroller extends \supercontroller {

    /**
     * @return string
     */
    public function display()
    {
        return 'layouts';
    }

    /**
     * @return string
     */
    public function get_create_layout_html()
    {
        $params = \menu::read_lang('create_layout_page');

        $template = \site::get_template();

        $arr = $template::get_positions();

        $opts = '';

        foreach($arr as $el) {
            $opt = \dom::create_element(
                'option',
                [
                    'value'     => $el,
                    'text'      => $el
                ]
            );

            $opts .= $opt;
        }

        $params['positions'] = $opts;

        $arr = \menu::get_urls();

        if(count($arr)) {
            $urls = '';

            foreach($arr as $el) {
                $span = \dom::create_element(
                    'span',
                    [
                        'class'     => 'label float-left cursor-pointer margin-1',
                        'text'      => $el['url']
                    ]
                );

                $urls .= $span;
            }

            $params['urls'] = $urls;
        }
        else {
            $params['urls'] = $params['NO_URLS'];
        }

        return \templator::getTemplate(
            'create_layout',
            $params,
            \menu::$path.'admin'.DS.'views'.DS.'layouts'
        );
    }
}