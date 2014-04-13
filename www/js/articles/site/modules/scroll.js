$(function(){

    href = '/css/articles/scrollup/themes/tab.css';

    link = $('link[href="'+href+'"]');

    if(link.length == 0) {
        link = $('<link>', {
            'rel'   : 'stylesheet',
            'href'  : href
        })

        $('head').append(link)
    }

    $.getScript('/js/articles/scrollup/jquery.easing.min.js', function(){

        $.getScript(
            '/js/articles/scrollup/jquery.scrollUp.js',
            function() {
                if(!$('#scrollUp').is('*')) {
                    $.scrollUp({
                        scrollText  : 'UP'
                    })
                }
            }
        )
    })
})
