<?php
namespace menu_admin;

/**
 * Class linkscontroller
 *
 * @package menu_admin
 *
 * @author Nikolaev D.
 */
class linkscontroller extends \supercontroller {
    use \trait_extension_controller;

    /**
     *
     */
    private $_extension;

    /**
     * @var
     */
    private $_cache_path;

    public function __construct()
    {
        $this->_cache_path = \menu::$path.'admin'.DS.'cache'.DS;

        $this->_extension = 'menu';
    }

    /**
     * @return string
     */
    public function display()
    {
        $params = \menu::read_lang('links_page');

        $arr = $this->get_urls();

        if(count($arr)) {
            $urls = '';

            foreach($arr as $el) {
                $span = \dom::create_element(
                    'span',
                    [
                        'class'     => 'label float-left cursor-pointer margin-2px',
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
            'index',
            $params,
            \menu::$path.'admin'.DS.'views'.DS.'links'
        );
    }

    /**
     * @return bool|mixed|string
     */
    public function get_urls()
    {
        if($cache = $this->get_cache_view('urls')) {
            return json_decode($this->get_cache_view('urls'), true);
        }
        else {
            $model = \menu::get_admin_model('links');

            $cache = $model->get_all('url');

            $this->set_cache_view('urls', json_encode($cache));

            return $cache;
        }
    }

    /**
     * @param $link
     * @return string
     */
    public function add($link) {
        if($link) {
            $model = \menu::get_admin_model('links');

            $answer = $model->add($link);

            if(!$answer) {
                $this->delete_cache_view('urls');
            }
            else {
                return $answer;
            }
        }
        else {
            return 'empty link';
        }
    }

    /**
     * @param $arr
     * @return mixed
     */
    public function delete($arr) {

        $model = \menu::get_admin_model('links');

        foreach($arr as $el) {
            $model->remove($el);
        }

        $this->delete_cache_view('urls');

        $arr = $this->get_urls();

        if(count($arr) == 0) {
            return \menu::read_lang('links_page')['NO_URLS'];
        }
    }
}