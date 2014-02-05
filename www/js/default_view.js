window.DefaultView = Backbone.View.extend({
    el : $(document),

    file_select_el : '',

    $this: '',

    file_select_dirs : [],

    file_select_container : undefined,

    events: {
        'click .custom-file-select'                     : 'open_file_select',
        'click .custom-file-select-open-dir'            : 'file_select_open_dir',
        'click .custom-file-select-back:not(.disabled)' : 'file_select_back',
        'click .custom-file-select-select-file'         : 'file_select_select_file',
        'click .custom-file-select-submenu-dir'         : 'file_select_submenu_dir',
        'click .custom-file-select-close'               : 'file_select_close',
        'click .modal-slider'                           : 'make_slider'
    },

    initialize: function() {
        $this = this;
    },

    open_file_select: function(e) {
        var params = [];

        var el = e.target.tagName == 'A' ? $(e.target) : $(e.target).parent('a');

        $this.file_select_el = el;

        el.addClass('current');

        params['base_dir']      = el.data('root');
        params['accept_type']   = el.data('accept');
        params['file_filter']   = el.data('filter');

        if($('*').is('.modal')) {
            var html = $('.modal').html();
            var width = $('.modal').width();
            var cont = $('<div>', {
               'class'  : 'inline-block position-relative',
                'width' : width*2
            });
            var div1 = $('<div>', {
                'class' : 'inline-block float-left',
                'html'  : html,
                'width' : width
            })
            var div2 = $('<div>', {
                'class' : 'inline-block position-relative',
                'text'  : '...',
                'width' : width
            })
            cont.append(div1).append(div2);

            $('.modal').html(cont).addClass('overflow-hidden');

            cont.animate({'left':-width});

            $this.file_select_container = cont;

            div2.load(
                'index.php?class=noop&controller=fileselect',
                {
                    'base_dir'      : params['base_dir'],
                    'accept_type'   : params['accept_type'],
                    'file_filter'   : params['file_filter'],
                    'level'         : '1'
                }
            );
        }
        else {
            var url = 'index.php?class=noop&controller=fileselect&base_dir='
                +params['base_dir']
                +'&accept_type='
                +params['accept_type']
                +'&file_filter='
                +params['file_filter']
                +'&level=1';
            App.makeModal(url);
        }
    },

    file_select_open_dir: function(e) {
        var el = e.target.tagName == 'FIGURE'? $(e.target) : $(e.target).parents('figure');

        var backel = ($this.file_select_dirs.length == 0) ? $this.file_select_el.data('root')
            : el.data('path');

        $this.file_select_dirs.unshift(backel);

        if($('.custom-file-select-back').hasClass('disabled')) {
            $('.custom-file-select-back').removeClass('disabled');
        }

        var params = [];

        params['base_dir']      = el.data('path');
        params['accept_type']   = $this.file_select_el.data('accept');
        params['file_filter']   = $this.file_select_el.data('filter');

        $.post(
            'index.php?class=noop&controller=fileselect',
            {
                'base_dir'      : params['base_dir'],
                'accept_type'   : params['accept_type'],
                'file_filter'   : params['file_filter'],
                'level'         : '2'
            },
            function(data) {
                $('.custom-file-select-ul').replaceWith(data);
            }
        );
    },

    file_select_back: function() {
        var params = [];

        params['base_dir']  = $this.file_select_dirs.shift();
        params['accept_type']   = $this.file_select_el.data('accept');
        params['file_filter']   = $this.file_select_el.data('filter');

        if($this.file_select_dirs.length == 0) {
            $('.custom-file-select-back').addClass('disabled');
            var level = 3;
        }
        else {
            var level = 2;
        }

        $.post(
            'index.php?class=noop&controller=fileselect',
            {
                'base_dir'      : params['base_dir'],
                'accept_type'   : params['accept_type'],
                'file_filter'   : params['file_filter'],
                'level'         : level
            },
            function(data) {
                $('.custom-file-select-ul').replaceWith(data);
            }
        );
    },

    file_select_select_file: function(e) {
        var el = e.target.tagName == 'FIGURE' ? $(e.target) : $(e.target).parents('figure');

        var path = el.data('path');

        if($this.file_select_container) {
            $this.file_select_container.animate({'left' : '0'})
        }

        $('a.custom-file-select.current').children('input').val(path);

        $('a.custom-file-select.current').children('i').attr('class', 'icon-ok');

        $('body').find('a.custom-file-select').removeClass('current');
    },

    file_select_submenu_dir: function(e) {
        var el = e.target.tagName == 'FIGURE'? $(e.target) : $(e.target).parent('figure');

        var btn1 = $('<a>', {
            'class' : 'btn btn-mini external custom-file-select-open-dir',
            'text'  : ' open '
        })

        var btn2 = $('<a>', {
            'class' : 'btn btn-mini external custom-file-select-select-file',
            'text'  : 'select'
        })

        el.children('figcaption').html('').append(btn1).append(btn2);
    },

    file_select_close: function() {
         $this.file_select_container.animate({'left' : '0'})
    },

    make_slider: function(e) {
        var params = $(e.target).data('params');

        App.makeModal('index.php?class=noop&controller=slider&params='+params);
    }
})

window.DefaultView = new DefaultView;