<?php
/**
 * WYSIWYG editor
 *
 * Class ckeditor
 *
 * @author Nikolaev D.
 */
class ckeditor implements editor_interface {
    use trait_editor;

    /**
     * @return array|mixed
     */
    public static function get_js()
    {
        return [
            //'<script src="/js/ckeditor/ckeditor.js"></script>',
            //'<script src="/js/ckeditor/adapters/jquery.js"></script>',
            '<script src="/js/ckeditor/start.js"></script>'
        ];
    }

    /**
     * @return array|mixed
     */
    public static function get_css()
    {
        return [];
    }

    public static function delete()
    {

    }
}