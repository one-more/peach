ArticlesBlogView = Backbone.View.extend({
    el: $(document),

    events: {
        'scroll'                            : 'scroll',
        'click .articles-show-more-div'     : 'load_more'
    },

    initialize: function() {
        App.elementLoad('.art-show-more-btn', function(){
            Ladda.bind('.art-show-more-btn',
                {
                    callback: function(instance, target) {
                        var params = $(target).data('params');
                        var last =
                            $(target).parents('.articles-article')
                                .is(':last-of-type');

                        $.post(
                            'index.php',
                            {
                                'class'         : 'articles',
                                'controller'    : 'blog',
                                'task'          : 'get_full',
                                'params'        : params
                            },
                            function(data) {
                                var well =
                                    $(target)
                                        .parents('.articles-article')
                                        .find('.articles-show-more-div');

                                var dat = $(data);
                                $(target).parents('.articles-article').
                                    replaceWith(dat);

                                $('.articles-article:last-of-type')
                                    .find('.articles-article-text')
                                    .append(well);

                                if(!last) {
                                    dat.append('<hr />');
                                }
                            }
                        )
                    }
                }
            )
        })
    },

    scroll: function() {
        $('.articles-show-more-div:in-viewport').trigger('click');
    },

    load_more: function(e) {
        var el = $(e.target);

        var arr = {
            'id'        : el.data('params'),
            'limit'     : el.data('offset'),
            'category'  : el.data('category')
        }

        el.remove();

        if(!$('.articles-spinner').is('*')) {

            var radius = el.height()*0.2
            var width = radius < 7 ? 2 : 3;

            var text = el.text();

            span = document.createElement('span');

            span.innerText = text;
            $(span).css('margin-right', '20px');

            el.text('').html(span);

            var span = document.createElement('span');
            span.className = 'articles-spinner';
            e.target.appendChild(span)

            var spinner = new Spinner({
                'color'     : '#000',
                'lines'     : 12,
                'radius'    : radius,
                'length'    : radius * 0.6,
                'width'     : width,
                'zIndex'    : 'auto',
                'top'       : 'auto',
                'left'      : 'auto',
                'className' : 'auto'
            })

            spinner.spin(span)
        }


        $.post(
            'index.php',
            {
                'class'         : 'articles',
                'controller'    : 'blog',
                'task'          : 'show_more',
                'params'        : arr
            },
            function(data) {
                if(data.trim() != '') {
                    $('.articles-article:last-of-type')
                        .append('<hr />')
                        .after(data)

                    ArticlesBlogView.initialize()
                }
            }
        )
    }
})

ArticlesBlogView = new ArticlesBlogView;
