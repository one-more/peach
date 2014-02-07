<?php
namespace noop_admin;

/**
 * Class slidercontroller
 *
 * @package noop_admin
 *
 * @author Nikolaev D.
 */
class slidercontroller extends \supercontroller {

    /**
     * @param $path
     * @return string
     */
    public function display($path)
    {
        $imgpath = $path;
        $path = '.'.$path;

        if(is_file($path)) {
            $params = [
                \dom::create_element('script', ['src'  => '/js/peach-slider/peach-slider.js']),
                \dom::create_element('script', ['src'  => '/js/peach-slider/start.js'])
            ];

            $params['js']   = \builder::build('peach_slider.js', $params, false);

            $params['css']  = \dom::create_element('link', [
                'rel'   => 'stylesheet',
                'href'  => '/js/peach-slider/peach-slider.css'
            ]);
            $params['css'] = \builder::build('peach_slider.css', [$params['css']], false);

            $img = \dom::create_element(
                'img',
                [
                    'src'   => $imgpath
                ]
            );

            $li = \dom::create_element(
                'li',
                [
                    'text'  => $img
                ]
            );

            $params['lis'] = $li;

            return \templator::getTemplate(
                'index',
                $params,
                \noop::$path.'admin'.DS.'views'.DS.'slider'
            );
        }
        elseif(is_dir($path)) {
            return 'dir';
        }
        else {
            $ref = \factory::get_reference('errors')['no_file'];

            return \templator::get_warning($ref);
        }
    }
}