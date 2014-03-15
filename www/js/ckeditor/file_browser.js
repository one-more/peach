$(function(){
    var css = $('<link>', {
        'href'  : '/css/peach.css',
        'rel'   : 'stylesheet'
    });

    var css2 = $('<link>', {
        'href'  : '/css/bootstrap.min.css',
        'rel'   : 'stylesheet'
    })

    $('head').append(css)
    $('head').append(css2)

    var level       = 1;
    var arr         = [];
    var funcNum     = $('input[name=funcNum]').val();

    //open dir
    $(document).on('click', '.custom-file-select-open-dir', function(e) {
        var el      = e.target == 'FIGURE' ? $(e.target) :
            $(e.target).parent('figure');

        var params  = el.data('path');

        $.post(
            'index.php',
            {
                'class'         : 'noop',
                'controller'    : 'fileselect',
                'base_dir'      : params,
                'ajax'          : 1,
                'old_url'       : '/admin',
                'level'         : 2
            },
            function(data) {
                $('.custom-file-select-ul').replaceWith(data);

                level++;

                arr.unshift(params);

                if(level > 1 && $('.custom-file-select-back').hasClass('disabled')) {
                    $('.custom-file-select-back').removeClass('disabled');
                }
            }
        )
    })

    //back button
    $(document).on('click', '.custom-file-select-back:not(:disabled)', function() {

        level--;
        var params  = level > 1 ? arr[arr.length-1] : 'www/media';

        if(level == 1) {
            $('.custom-file-select-back').addClass('disabled');
        }

        $.post(
            'index.php',
            {
                'class'         : 'noop',
                'controller'    : 'fileselect',
                'base_dir'      : params,
                'ajax'          : 1,
                'old_url'       : '/admin',
                'level'         : level == 1 ? 3 : 2
            },
            function(data) {
                $('.custom-file-select-ul').replaceWith(data);
            }
        )
    })

    //close button
    $(document).on('click', '.custom-file-select-close', function(){
        window.close();
    })

    //select file
    $(document).on('click', '.custom-file-select-select-file', function(e) {
        var el      = e.target == 'FIGURE' ? $(e.target) :
            $(e.target).parent('figure');
        var params  = el.data('path');


        window.opener.CKEDITOR.tools.callFunction(funcNum, params, '');
        window.close();
    })
})