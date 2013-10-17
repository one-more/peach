<?php
class factory {
    private static  $_document = null;

    private static  $_site = null;

    public static  function getDocument() {
        if(!self::$_document) {
            self::$_document = new document(self::getSite()->getTemplate());
        }

        return self::$_document;
    }

    public static  function getSite() {
        if(self::$_site) {
            self::$_site = new site();
        }

        return self::$_site;
    }
}