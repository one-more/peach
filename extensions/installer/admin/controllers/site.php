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
        $css = [
            '<link rel="stylesheet" href="/css/bootstrap.min.css" />',
            '<link rel="stylesheet" href="/css/bootstrap-responsive.min.css" />'
        ];

        $js = [
            '<script src="/js/ajaxupload.3.5.js"></script>',
            '<script src="/js/json2.js"></script>',
            '<script src="/js/jquery-2.0.3.min.js"></script>',
            '<script src="/js/underscore-min.js"></script>',
            '<script src="/js/backbone-min.js"></script>',
            '<script src="/js/bootstrap.min.js"></script>',
            '<script src="/js/App.js"></script>',
            '<script src="/js/UI.js"></script>'
        ];

        $html = templator::getTemplate('index', ['css'=>$css, 'js'=>$js], installer::$path.'admin/views/site');

        echo $html;
    }
}