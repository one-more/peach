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
     * @param $template - current template of the site
     */
    public function __construct($template) {
        $this->_template = $template;

        require_once 'classes/phpQuery/phpQuery.php';

        $this->_document = phpQuery::newDocumentFile("/templates/$this->_template/views/index.html");
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
}