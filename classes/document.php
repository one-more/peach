<?php
class document {
    /**
     * @var contains current template name
     */
    private $_template;

    /**
     * @var array of css files to add
     */
    private $_css_files;

    /**
     * @var array of js files to add
     */
    private $_js_files;

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

        $this->_css_files[] = '<link rel="stylesheet" href="/css/bootstrap.min.css" />';
        $this->_css_files[] = '<link rel="stylesheet" href="/css/bootstrap-responsive.min.css" />';

        $this->_js_files[] = '<script src="/js/ajaxupload.js"></script>';
        $this->_js_files[] = '<script src="/js/json2.js"></script>';
        $this->_js_files[] = '<script src="/js/jquery-2.0.3.min.js"></script>';
        $this->_js_files[] = '<script src="/js/underscope.min.js"></script>';
        $this->_js_files[] = '<script src="/js/backbone.js"></script>';
        $this->_js_files[] = '<script src="/js/bootstrap.min..js"></script>';
        $this->_js_files[] = '<script src="/js/App.js"></script>';
        $this->_js_files[] = '<script src="/js/UI.js"></script>';

        $this->_document = phpQuery::newDocumentHTML($this->_template->getTemplate());
    }

    /**
     * @param $css path to css file
     */
    public function addCss($css) {
        $this->_css_files[] = $css;
    }

    /**
     * @param $js path to js file
     */
    public function addJs($js) {
        $this->_js_files[] = $js;
    }

    /**
     * displays html document
     */
    public function display() {
        foreach($this->_css_files as $el) {
            pq('head')->append($el);
        }

        foreach($this->_js_files as $el) {
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
            array_merge($this->_css_files, $css);
        }

        if($js != null) {
            array_merge($this->_js_files, $js);
        }

        foreach($this->_css_files as $el) {
            pq('head')->append($el);
        }

        foreach($this->_js_files as $el) {
            pq('head')->append($el);
        }

        return $html;
    }
}