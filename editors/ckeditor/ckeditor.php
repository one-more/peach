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

    /**
     * @return mixed|void
     */
    public static function delete()
    {

    }

    /**
     *
     */
    public static function browse()
    {
        $funcNum    = $_GET['CKEditorFuncNum'];

        $noop   = new noop;
        $jq     = dom::create_element(
            'script',
            [
                'src'   => '/js/jquery-2.0.3.min.js'
            ]
        );
        $script = dom::create_element(
            'script',
            [
                'src'   => '/js/ckeditor/file_browser.js'
            ]
        );
        $hidden = dom::create_element(
            'input',
            [
                'type'  => 'hidden',
                'name'  => 'funcNum',
                'value' => $funcNum
            ]
        );

        echo $noop->get_file_selection('www/media/').$jq.$script.$hidden;
    }

    /**
     *
     */
    public static function upload_image()
    {
        list($type, $ext) = preg_split('/\//', $_FILES['upload']['type']);

        if($type != 'image') {
            echo 'selected file is not an image';
            return;
        }

        $exts   = ['jpeg', 'jpg', 'png', 'bmp', 'ico', 'gif'];
        if(!in_array(strtolower($ext), $exts)) {
            echo 'selected file is not an image (wrong extension)';
            return;
        }

        $name   = file_get_contents($_FILES['upload']['tmp_name']);
        $name   = sha1($name.time().getenv('REMOTE_ADDR'));

        move_uploaded_file($_FILES['upload']['tmp_name'],
            SITE_PATH.'www'.DS.'media'.DS.'images'.DS."{$name}.{$ext}");

        $funcNum    = $_GET['CKEditorFuncNum'] ;
        $url        = "/media/images/{$name}.{$ext}";

        $message    = '';
        $text       = "window.parent.CKEDITOR.tools
                    .callFunction($funcNum, '$url', '$message');";
        $script     = dom::create_element(
            'script',
            [
                'text'  => $text
            ]
        );

        echo $script;
    }
}