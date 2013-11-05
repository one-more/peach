window.InstallSiteView = Backbone.View.extend({
    el : $('#all'),

    events: {
        "click .nav-panel a": "showTab",
        "change .lang-select" : "changeLang"
    },

    current_lang : 'en-EN',

    showTab: function(e) {
        e.preventDefault();

        var tab = $(e.target).data('target');

        $(this.el).find('form').find('div').hide();

        $(tab).show();
    },

    changeLang : function(e) {
        this.current_lang = $('.lang-select').val();

        $this = this;

        $.post('index.php', {lang : this.current_lang}, function(data) {
           $this.render(data);
        });
    },

    initialize: function() {
        App.elementLoad('#header', function(){
            $('#header').height($(window).height()*0.25);
        });

        $('select').val(this.current_lang);

        if(location.pathname != '/install') {
            location = '/install';
        }
    },

    render : function(html) {
        alert(html);
    }
});

window.InstallSiteView = new InstallSiteView;
