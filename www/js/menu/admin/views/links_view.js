var LinksView = Backbone.View.extend({
    el : $(document),

    initialize: function() {

    },

    events: {
        'click .add-url-btn'                        : 'add_url',
        'keydown input[name=add-url-input]'         : 'handle_kd',
        'click .url-box span'                       : 'select_url',
        'click input[name=select-all-url-input]'    : 'select_all',
        'click .delete-url-btn'                     : 'delete_url'
    },

    add_url: function() {
        var link = $('input[name=add-url-input]').val();

        var input = $('input[name=add-url-input]');

        if(link.trim() == '') {
            var msg = LangModel.get('not_empty') || 'field cannot be empty';

            $('input[name=add-url-input]').addClass('error').attr('placeholder', msg);
        }
        else {
            $.post(
                'index.php',
                {
                    'class'         : 'menu',
                    'controller'    : 'links',
                    'task'          : 'add',
                    'params'        : link
                },
                function(data) {
                    if(data.trim() == '') {

                        if(link.charAt(0) != '/') {
                            link = '/'+link;
                        }

                        var span = $('<span>', {
                            'class' : 'label float-left cursor-pointer margin-2px',
                            'text'  : link
                        })

                        if($('.url-box span').length == 0) {

                            var chbx = $('<input>', {
                                'name'  : 'select-all-url-input',
                                'type'  : 'checkbox'
                            })

                            $('.url-box').text('').append(chbx).append('<br>')
                        }

                        $('.url-box').append(span);

                        input.val('');
                    }
                    else {

                        input.addClass('error').val('').attr('placeholder', data);
                    }
                }
            );
        }
    },

    handle_kd: function(e) {
        var el = $(e.target);

        if(el.hasClass('error')) {
            el.removeClass('error');
            el.attr('placeholder', '');
        }
    },

    select_url: function(e) {
        var el = $(e.target);

        if(el.hasClass('label-info')) {
            el.removeClass('label-info');

            $('input[value="'+el.text()+'"]').remove();
        }
        else {
            el.addClass('label-info');

            var chbx = $('<input>', {
                'type'      : 'checkbox',
                'name'      : 'url[]',
                'value'     : el.text(),
                'class'     : 'hide',
                'checked'   : 'true'
            })

            $('.url-box').append(chbx);
        }
    },

    select_all: function() {
        $('.url-box span').each(function(){
            $(this).trigger('click');
        })
    },

    delete_url: function() {
        var arr = new Array();

        $('.url-box span.label-info').each(function(){
            arr.push($(this).text())

            $('input[value="'+$(this).text()+'"').remove();
            $(this).remove();
        })

        if(arr.length > 0) {
            $.post(
                'index.php',
                {
                    'class'         : 'menu',
                    'controller'    : 'links',
                    'task'          : 'delete',
                    'params'        : arr
                },
                function(data) {
                    if(data.trim() != '') {

                        $('.url-box').append(data);
                    }

                    $('input[name=select-all-url-input]').removeAttr('checked');
                }
            )
        }
    }
})

window.LinksView = new LinksView;
