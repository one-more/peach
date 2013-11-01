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

		$params = ['css'=>$css, 'js'=>$js];

		if(!empty($_REQUEST['lang'])) {
			$ini = factory::getIniServer('../lang/install/admin/'.$_REQUEST['lang'].'.ini');

			$lang = $ini->readSection('install_site', '');

			$params = array_merge($params, $lang);
		}

        $html = templator::getTemplate('index', $params , installer::$path.'admin/views/site');

        echo $html;
    }
}