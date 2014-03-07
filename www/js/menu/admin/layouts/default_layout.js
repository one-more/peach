var MenuView = Backbone.View.extend({
    initialize: function() {
        App.elementLoad('#menu-tabs', function(){
            $('#menu-tabs').tabs();
        })
    },

    el : $(document),

    events: {
        'click .delete-layout-icon' : 'delete_layout',
        'click .edit-layout-icon'   : 'edit_layout'
    },

    delete_layout: function(e) {

        var msg = LangModel.get('delete_layout') || 'delete layout?';

        App.confirm(msg, function(){
            var el = $(e.target);
            var params = el.data('params');

            $.post(
                'index.php',
                {
                    'class'         : 'menu',
                    'controller'    : 'layouts',
                    'task'          : 'delete',
                    'params'        : params
                },
                function(data) {
                    if(!data.trim()) {
                        el.parents('tr').remove();

                        var msg = LangModel.get('layout_deleted') || 'layout deleted';
                        App.showNoty(msg, 'success');
                    }
                    else {
                        var msg = LangModel.get('error_delete_layout') ||
                            'request error';
                        App.showNoty(msg, 'error');
                        console.log(data);
                    }
                }
            )
        })
    },

    edit_layout: function(e) {
        var el = $(e.target);
        var params = el.data('params');

        App.makeModal(
            'index.php?class=menu&controller=layouts&task=edit&params='+params
        );
    },

    update_layouts_table: function() {
        $.post(
            'index.php',
            {
                'class'         : 'menu',
                'controller'    : 'layouts'
            },
            function(data) {
                $('.layouts-table').replaceWith(data);
            }
        )
    }
})


window.MenuView = new MenuView;