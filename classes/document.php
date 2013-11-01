<?php
class document {
    /**
     * @var contains current template name
     */
    private $_template;

    /**
     * @var array of css files to add
     */
    public static  $css_files = [
		'<link rel="stylesheet" href="/css/bootstrap.min.css" />',
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
		'<script src="/js/bootstrap.min.js"></script>',
		'<script src="/js/App.js"></script>',
		'<script src="/js/UI.js"></script>'
	];

    /**
     * @var contains html code of currents template
     */
    private $_document;

    /**
     * constructor of document class
     */
    public function __construct() {
        $this->_template = factory::getTemplate();

        require_once 'classes/phpQuery/phpQuery.php';

        $this->_document = phpQuery::newDocumentHTML($this->_template->getTemplate());
    }

    /**
     * @param $css path to css file
     */
    public function addCss($css) {
        static::$css_files[] = $css;
    }

    /**
     * @param $js path to js file
     */
    public function addJs($js) {
        static::$js_files[] = $js;
    }

    /**
     * displays html document
     */
    public function display() {
        foreach(static::$css_files as $el) {
            pq('head')->append($el);
        }

        foreach(static::$js_files as $el) {
            pq('head')->append($el);
        }

        echo $this->_document;
    }

    /**
     * @param $file - file or string to create document
     * @param null $css - optionally, array of css files
     * @param null $js - optionally, array of js files
     * @return phpQueryObject
     */
    public function createDocument($file, $css = null, $js = null) {
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