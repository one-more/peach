$(function(){
    var src = '/css/articles/anysearch/anysearch.css'

    var link = $('link[href="'+src+'"]')

    if(link.length == 0) {
        link = $('<link>', {
            'rel'   : 'stylesheet',
            'href'  : src
        })

        $('head').append(link)
    }

    if($('.articles-article').is('*')) {

        $.getScript('/js/articles/anysearch/anysearch.min.js', function(){
            if(!$('#anysearch-slidebox').is('div')) {
                $(document).anysearch({
                    searchFunc: function(word) {

                        $.post(
                            'index.php',
                            {
                                'class'         : 'articles',
                                'controller'    : 'search',
                                'params'        : word
                            },
                            function(data) {
                                $('.articles-article')
                                    .parent()
                                    .html(data)
                                App.goto('/search/'+encodeURI(word));
                            }
                        )
                    },
                    secondsBetweenKeypress  : 5,
                    reactOnKeycodes         : 'all'
                })
            }
        })

        if($('#anysearch-slidebox').css('display') == 'none') {

            $('#anysearch-slidebox').show()
        }
    } else {

        $('#anysearch-slidebox').hide();
    }
})
