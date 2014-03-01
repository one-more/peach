App.module('LinkCorrector', function(LinkCorrector){

    LinkCorrector.initialize = function() {
        $('a[href]:not(.external)').each(function(){
            var pos = $(this).attr('href').indexOf('admin');
            var href = $(this).attr('href');

            switch (pos) {
                case -1:
                    href = href.charAt(0) == '/' ? '/admin'+href : '/admin/'+href;
                    $(this).attr('href', href);
                    break;
                case 0:
                    href = '/'+href;
                    $(this).attr('href', href);
                    break;
            }
        })
    }

    window.LinkCorrector = LinkCorrector;
})
