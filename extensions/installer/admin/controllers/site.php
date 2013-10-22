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
        $doc = factory::getDocument();


        $html = $doc->createDocument(installer::$path.'views/site/index.html');

        echo $html;
    }
}