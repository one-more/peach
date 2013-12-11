<?php
namespace simple_template_site;
use document, templator, supercontroller, site, simple_template;

/**
 * Class default_controller
 *
 * @author Nikolaev D.
 */
class defaultcontroller extends supercontroller {
    /**
     * display template
     */
    public function display()
    {
        $params['js'] = array_merge(document::$js_files, site::$js_files);
        $params['js'] = array_merge($params['js'], [
            '<script src="/js/simple_template/site/views/layout.js"></script>'
        ]);
        $params['css'] = array_merge(document::$css_files, [
            '<link rel="stylesheet" href="/css/simple_template/site/default.css" />'
        ]);

        $params['css'] = \builder::build('simple_template.css', $params['css']);
        $params['js']  = \builder::build('simple_template.js', $params['js']);

        $params = array_merge($params, simple_template::read_params('options'));

        return templator::getTemplate('index', $params, simple_template::$path.'site'.DS.'views'.DS.'default');
    }
}