var SiteView = Backbone.View.extend({

    el : $(document),

    initialize: function() {
        App.elementLoad('#site-tabs', function(){
            $('#site-tabs').tabs({});
        })
    },

    events: {
        'click .site_choose_template' : 'choose_template'
    },

    choose_template: function(e) {
        var _el = $(e.target);

        var img = _el.attr('src').split('/');

        if(img[img.length-1] == 'ok.png') {
            return;
        }
        else {
            $('.site_choose_template.selected').attr('src', '/media/images/bullet.png').
            removeClass('selected');
            _el.attr('src', '/media/images/ok.png').addClass('selected')

            var params = _el.data('params');

            $.post('index.php?class=site&controller=templates&task=select&params='+params);
        }
    }
});

window.SiteView = new SiteView;
