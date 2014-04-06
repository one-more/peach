var HtmlView = Backbone.View.extend({

    initialize: function() {
        App.elementLoad('#html-tabs', function(){
            $('#html-tabs').tabs()
        })
    },

    el: $(document),

    events: {
        'click .create-html-record-icon'    : 'create_record',
        'click .edit-html-record-icon'      : 'edit_record',
        'click .delete-html-record-icon'    : 'delete_record',
        'click .html-create-layout-btn'     : 'create_layout'
    },

    create_record: function(e) {
        var el      = $(e.target);
        var params  = el.data('params');

        App.makeModal(
            'index.php?class=html&controller=records&task=create'
        )
    },

    edit_record: function(e) {
        var el      = $(e.target);
        var params  = el.data('params');

        App.makeModal(
            'index.php?class=html&controller=records&task=edit&params='+params
        )
    },

    update_records_table: function() {
        $.post(
            'index.php',
            {
                'class'         : 'html',
                'controller'    : 'records'
            },
            function(data) {
                $('.html-records-table').replaceWith(data);
            }
        )
    },

    delete_record: function(e) {
        var params = $(e.target).data('params');

        var msg = LangModel.get('delete_html_record') ||
            'delete record?';

        App.confirm(msg, function() {
            $.post(
                'index.php',
                {
                    'class'         : 'html',
                    'controller'    : 'records',
                    'task'          : 'delete',
                    'params'        : params
                },
                function(data) {
                    if(data.trim()) {
                        msg = LangModel.get('error_delete_html_record') ||
                            'an error occurred during delete record'

                        App.showNoty(msg, 'error');
                    }
                    else {
                        msg = LangModel.get('html_record_deleted') ||
                            'record deleted successfully';

                        App.showNoty(msg, 'success');

                        HtmlView.update_records_table();
                    }
                }
            )
        })
    },

    create_layout: function(e) {
        var params = $(e.target).data('params');

        App.makeModal(
            'index.php?class=html&controller='+params
        )
    }
})

window.HtmlView = new HtmlView;
