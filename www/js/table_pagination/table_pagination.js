$(function(){

    $.getScript('/js/table_pagination/jquery.tablePagination.0.5.min.js', function(){
        var href = "/css/table_pagination/style.css"

        if($('link[href="'+href+'"]').length == 0) {
            var link = $('<link>', {
                'rel'  : 'stylesheet',
                'href'  : href
            })

            $('head').append(link);
        }

        App.elementLoad('table', function(){
            $('table:not(.not-pagination)').each(function(){

                if($(this).find('td').length) {
                    var rpp = parseInt(App.getCookie('table_pagination_rows_per_page'))

                    if(!rpp) {
                        rpp = 5;
                    }

                    $(this).tablePagination(
                        {
                            firstArrow      : '/media/table_pagination/first.gif',
                            nextArrow       : '/media/table_pagination/next.gif',
                            prevArrow       : '/media/table_pagination/prev.gif',
                            lastArrow       : '/media/table_pagination/last.gif',
                            optionsForRows  : [5, 20, 50],
                            rowsPerPage     : rpp
                        }
                    );
                }
            })
        }, 300)

        $(document).on('change', '#tablePagination_rowsPerPage', function(e){

            var val = $(e.target).val();

            var d = new Date();
            var y = d.getFullYear();
            d.setFullYear(y+100);

            App.setCookie('table_pagination_rows_per_page', val, {expires : d});
        })
    })
})

