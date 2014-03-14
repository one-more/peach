<?php
/**
 * WYSIWYG editor
 *
 * Class tinymce
 *
 * @author Nikolaev D.
 */
class tinymce implements editor_interface {
    use trait_editor;

    /**
     * @return array|mixed
     */
    public static function get_js()
    {
        return [
            '<script src="/js/tinymce/tinymce.min.js"></script>',
            '<script src="/js/tinymce/start.js"></script>'
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