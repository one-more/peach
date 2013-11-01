<?php
/**
 * Class sitecontroller
 *
 * @author - Dmitriy Nikolaev
 */
class sitecontroller extends supercontroller{
    /**
     * displays entry point of install site
     */
    public function display() {
        $css = document::$css_files;

        $js = document::$js_files;

        $html = templator::getTemplate('index', ['css'=>$css, 'js'=>$js], installer::$path.'admin/views/site');

        echo $html;
    }
}