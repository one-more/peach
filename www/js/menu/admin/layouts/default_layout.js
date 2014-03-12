var MenuView = Backbone.View.extend({
    initialize: function() {
        App.elementLoad('#menu-tabs', function(){
            $('#menu-tabs').tabs();
        })
    },

    el : $(document),

    events: {
        'click .delete-layout-icon'     : 'delete_layout',
        'click .edit-layout-icon'       : 'edit_layout',
        'click .create-menu-item'       : 'create_menu',
        'click .edit-menu-icon'         : 'edit_menu',
        'click .delete-menu-icon'       : 'delete_menu',
        'click .create-menu-item-item'  : 'create_menu_item',
        'click .edit-menu-item-icon'    : 'edit_menu_item',
        'click .delete-menu-item-icon'  : 'delete_menu_item',
        'change .menu-item-menu-select' : 'change_menu_items_list',
        'click .menu-items-filter>span' : 'filter_items_table'
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
    },

    create_menu_item: function() {
        App.makeModal('index.php?class=menu&controller=items&task=create')
    },

    update_items_table: function(id) {
        var params = id ? id : null;

        $.post(
            'index.php',
            {
                'class'         : 'menu',
                'controller'    : 'items',
                'params'        : params
            },
            function(data) {

                if(!id) {
                    var obj     = $(data);
                    data    = obj.filter('table');
                }

                $('.menu-items-table').replaceWith(data);
            }
        )
    },

    delete_menu_item: function(e) {
        var el      = $(e.target);
        var params  = el.data('params');
        var msg = LangModel.get('delete_menu_item') || 'delete menu item?';

        App.confirm(msg, function(){
            $.post(
                'index.php',
                {
                    'class'         : 'menu',
                    'controller'    : 'items',
                    'task'          : 'delete',
                    'params'        : params
                },
                function(data) {
                    if(data.trim()) {
                        msg = LangModel.get('error_delete_menu_item') ||
                            'an error occurred during delete menu item';
                        App.showNoty(msg, 'error');
                    }
                    else {
                        msg = LangModel.get('menu_item_deleted') ||
                            'menu item deleted successfully';

                        App.showNoty(msg, 'success');

                        el.parents('tr').remove();
                    }
                }
            )
        })
    },

    change_menu_items_list: function(e) {
        var el      = $(e.target);
        var params  =  el.val()

        $.post(
            'index.php',
            {
                'class'         : 'menu',
                'controller'    : 'items',
                'task'          : 'get_items_list',
                'params'        : params
            },
            function(data) {
                $('.menu-item-parent-select').replaceWith(data);
            }
        )
    },

    edit_menu_item: function(e) {
        var el      = $(e.target);
        var params  = el.data('params');

        App.makeModal('index.php?class=menu&controller=items&task=edit&params='+params)
    },

    filter_items_table: function(e) {
        var el      = $(e.target);
        var params  =  el.data('params');

        if(el.hasClass('label-info')) {
            MenuView.update_items_table();

            el.removeClass('label-info');
        }
        else {
            $('.menu-items-filter span').removeClass('label-info');

            el.addClass('label-info');

            MenuView.update_items_table(params);
        }
    }
})


window.MenuView = new MenuView;