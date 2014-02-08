window.AdminView = Backbone.View.extend({
    el : $(document),

    initialize: function() {
        $('#admin-tabs').tabs();
    },

    events: {
        'change .admin-editor-select'   : 'select_editor',
        'click  .admin-choose-template' : 'choose_template'
    },

    select_editor: function(e) {
        var params = $(e.target).val();

        $.post('index.php?class=admin&controller=options&task=select_editor&params='+params);
    },

    choose_template: function(e) {
        var params = $(e.target).data('params');

        var arr = $(e.target).attr('src').split('/');

        if(arr[arr.length-1] == 'ok.png') {
            return;
        }
        else {
            $('.admin-choose-template.selected').attr('src', '/media/images/bullet.png').
            removeClass('selected');
            $(e.target).attr('src', '/media/images/ok.png').addClass('selected');

            $.post('index.php?class=admin&controller=templates&task=select_template&params='+params);
        }
    }
})

window.AdminView = new AdminView;
