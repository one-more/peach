<?php
/**
 * class allows you to create html document from a file or string
 *
 * Class document
 *
 * @author Nikolaev D.
 */
class document {
    /**
     * @var array of css files to add
     */
    public static  $css_files = [
		'<link rel="stylesheet" href="/css/bootstrap.min.css" />',
        '<link rel="stylesheet" href="/css/peach.css" />',
		'<link rel="stylesheet" href="/css/bootstrap-responsive.min.css" />'
	];

    /**
     * @var array of js files to add
     */
    public static  $js_files = [
		'<script src="/js/ajaxupload.3.5.js"></script>',
		'<script src="/js/json2.js"></script>',
		'<script src="/js/jquery-2.0.3.min.js"></script>',
		'<script src="/js/underscore-min.js"></script>',
		'<script src="/js/backbone-min.js"></script>',
        '<script src="/js/backbone.module.js"></script>',
        '<script src="/js/backbone.router.js"></script>',
		'<script src="/js/bootstrap.min.js"></script>',
		'<script src="/js/App.js"></script>',
		'<script src="/js/default_view.js"></script>',
        '<script src="/js/Form.js"></script>',
        '<script src="/js/noty/jquery.noty.js"></script>',
        '<script src="/js/noty/layouts/top.js"></script>',
        '<script src="/js/noty/layouts/topCenter.js"></script>',
        '<script src="/js/noty/layouts/center.js"></script>',
        '<script src="/js/noty/themes/default.js"></script>',
        '<script src="/js/Comet.js"></script>'
	];

    /**
     * @param $file - file or string to create document
     * @param null $css - optionally, array of css files
     * @param null $js - optionally, array of js files
     * @return phpQueryObject
     */
    public static function createDocument($file, $css = null, $js = null) {
        require_once 'classes/phpQuery/phpQuery.php';

        $html = null;

        //create document from file
        if(is_file($file)) {
            $html = phpQuery::newDocumentFile($file);
        }
        //create document from string
        else {
            $html = phpQuery::newDocumentHTML($file);
        }

        if($css != null) {
            array_merge(static::$css_files, $css);
        }

        if($js != null) {
            array_merge(static::$js_files, $js);
        }

        foreach(static::$css_files as $el) {
            pq('head')->append($el);
        }

        foreach(static::$js_files as $el) {
            pq('head')->append($el);
        }

        return $html;
    }
}