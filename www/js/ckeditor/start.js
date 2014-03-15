$(function(){
    window.CKEDITOR_BASEPATH = '/js/ckeditor/';

    $.getScript('/js/ckeditor/ckeditor.js', function(){

        $.getScript('/js/ckeditor/adapters/jquery.js', function(){


            CKEDITOR.config.toolbar = [
                {
                    name: 'document',
                    items:
                        [
                            'Source',
                            '-',
                            'NewPage',
                            '-',
                            'Templates'
                        ]
                },
                {
                    name: 'clipboard',
                    items:
                        [
                            'Cut',
                            'Copy',
                            'Paste',
                            'PasteText',
                            'PasteFromWord',
                            '-',
                            'Undo',
                            'Redo'
                        ]
                },
                {
                    name    : 'search',
                    items   :
                        [
                            'Find',
                            'Replace',
                            '-',
                            'SelectAll',
                            '-',
                            'Scayt'
                        ]
                },
                '/',
                {
                    name: 'basicstyles',
                    items:
                        [
                            'Bold',
                            'Italic',
                            'Underline',
                            'Strike',
                            'SubScript',
                            'SuperScript',
                            '-',
                            'RemoveFormat'
                        ]
                },
                {
                    name:   'paragraph',
                    items:
                        [
                            'NumberedList',
                            'BulletedList',
                            '-',
                            'Outdent',
                            'Indent',
                            '-',
                            'Blockquote',
                            '-',
                            'CreateDiv',
                            '-',
                            'JustifyLeft',
                            'JustifyCenter',
                            'JustifyRight',
                            'JustifyBlock',
                            '-',
                            'BidiLtr',
                            'BidiRtl',
                            'Language'
                        ]
                },
                {
                    name:   'links',
                    items:
                        [
                            'Link',
                            'Unlink',
                            'Anchor'
                        ]
                },
                {
                    name:   'insert',
                    items:
                        [
                            'Image',
                            'Table',
                            'HorizontalRule',
                            'Smiley',
                            'SpecialChar',
                            'Iframe'
                        ]
                },
                {
                    name:   'style',
                    items:
                        [
                            'Styles',
                            'Format',
                            'Font',
                            'FontSize'
                        ]
                },
                {
                    name:   'colors',
                    items:
                        [
                            'TextColor',
                            'BGColor'
                        ]
                },
                {
                    name:   'tools',
                    items:
                        [
                            'Maximize',
                            'ShowBlocks'
                        ]
                }
            ];

            CKEDITOR.config.filebrowserBrowseUrl =
                'index.php?class=ckeditor&task=browse';
            CKEDITOR.config.filebrowserImageUploadUrl =
                'index.php?class=ckeditor&task=upload_image';

            CKEDITOR.config.filebrowserWindowWidth = '640';
            CKEDITOR.config.filebrowserWindowHeight = '480';

            $('.peach-editor').ckeditor();
        });
    })
})
