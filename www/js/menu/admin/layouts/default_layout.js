var MenuView = Backbone.View.extend({
    initialize: function() {
        App.elementLoad('#menu-tabs', function(){
            $('#menu-tabs').tabs();
        })
    },

    el : $(document),

    events: {
        'click .delete-layout-icon' : 'delete_layout',
        'click .edit-layout-icon'   : 'edit_layout',
        'click .create-menu-item'   : 'create_menu',
        'click .edit-menu-icon'     : 'edit_menu',
        'click .delete-menu-icon'   : 'delete_menu'
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
    },

    create_menu: function() {
        App.makeModal('index.php?class=menu&controller=menus&task=create')
    },

    update_menus_table: function()
    {
        $.post(
            'index.php',
            {
                'class'         : 'menu',
                'controller'    : 'menus'
            },
            function(data) {
                $('.menus-table').replaceWith(data);
            }
        )
    },

    edit_menu: function(e) {
        var el      = $(e.target);
        var params  = el.data('params');

        App.makeModal(
            'index.php?class=menu&controller=menus&task=edit&params='+params
        )
    },

    delete_menu: function(e) {
        var el      = $(e.target);
        var params  = el.data('params');

        var msg = LangModel.get('delete_menu') || 'delete menu?';

        App.confirm(msg, function(){
            $.post(
                'index.php',
                {
                    'class'         : 'menu',
                    'controller'    : 'menus',
                    'task'          : 'delete',
                    'params'        : params
                },
                function(data) {
                    if(!data.trim()) {
                        var msg = LangModel.get('menu_deleted') ||
                            'menu deleted';

                        App.showNoty(msg, 'success');

                        MenuView.update_menus_table();
                    }
                    else {
                        var msg = LangModel.get('error_delete_menu') ||
                            'an error occurred during delete menu'
                        App.showNoty(msg, 'error');
                    }
                }
            )
        })
    }
})


window.MenuView = new MenuView;