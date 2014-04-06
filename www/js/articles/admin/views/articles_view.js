ArticlesPageView = Backbone.View.extend({
    el: $(document),

    events: {
        'click .add-article-btn'        : 'add_article',
        'click .articles-delete-btn'    : 'delete_article',
        'click .articles-publish'       : 'publish_article',
        'click .articles-edit-btn'      : 'edit_article',
        'click .articles-filter>span'   : 'articles_filter',
        'click .articles-create-layout-btn' : 'create_layout'
    },

    add_article: function() {
        App.makeModal(
            'index.php?class=articles&controller=articles&task=create'
        );
    },

    delete_article: function(e) {
        var msg = LangModel.get('delete_article');
        var params = $(e.target).data('params');

        App.confirm(msg, function() {
            $.post(
                'index.php',
                {
                    'class'         : 'articles',
                    'controller'    : 'articles',
                    'task'          : 'delete',
                    'params'        : params
                },
                function(data) {
                    if(data.trim() == '') {
                        msg = LangModel.get('article_deleted');

                        App.showNoty(msg, 'success')

                        ArticlesPageView.update_table();
                    }
                    else {
                        msg = LangModel.get('error_delete_article');

                        App.showNoty(msg, 'error');
                    }
                }
            )
        })
    },

    publish_article: function(e) {
        var params = $(e.target).data('params');

        $.post(
            'index.php',
            {
                'class'         : 'articles',
                'controller'    : 'articles',
                'task'          : 'publish',
                'params'        : params
            }
        )

        src = e.target.src.indexOf('ok.png') == -1? '/media/images/ok.png':
            '/media/images/bullet.png';

        e.target.src = src;
    },

    update_table: function(cat, callb) {
        $.post(
            'index.php',
            {
                'class'         : 'articles',
                'controller'    : 'articles',
                'params'        : cat
            },
            function(data) {
                $('.articles-table').replaceWith(data);

                if(typeof callb == "function") {
                    callb()
                }
            }
        )
    },

    edit_article: function(e) {
        var params = $(e.target).data('params');

        App.makeModal(
            'index.php?class=articles&controller=articles&task=update&params='+params
        )
    },

    articles_filter: function(e) {
        var params = $(e.target).data('params');
        var el = $(e.target);

        if(el.hasClass('label-info')) {
            el.removeClass('label-info');

            ArticlesPageView.update_table();
        }
        else {
            $('.label-info').removeClass('label-info');

            ArticlesPageView.update_table(params, function(){
                $('span[data-params='+params+']').addClass('label-info');
            })
        }
    },

    create_layout: function(e) {
        var params = $(e.target).data('params');

        App.makeModal(
            'index.php?class=articles&controller='+params
        )
    }
})

ArticlesPageView = new ArticlesPageView;
