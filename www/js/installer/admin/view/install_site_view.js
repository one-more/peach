window.InstallSiteView = Backbone.View.extend({
    el : $('#all'),

    events: {
        "click .next-button:not(.disabled)": "showTab",
        "change .lang-select" : "changeLang"
    },

    tabs: [
        'lang', 'db', 'admin'
    ],

    current_lang : 'en-EN',

    current_page : 0,

    showTab: function(e) {
        e.preventDefault();

        $('#'+this.tabs[this.current_page]+'-tab').removeClass('active');

        $('#'+this.tabs[this.current_page+1]+'-tab').addClass('active');

        this.current_page++;

        if(this.current_page == 2) {
            $('.next-button').addClass('disabled');
        }

        switch(this.current_page) {
            case 1:
                $('#db').show();
                break;
            case 2:
                $('#admin').show();
                $('.complete-button').show();
                break;
        }
    },

    changeLang : function() {
        $this = this;

        this.current_lang = $('select').val();

        $.post('index.php', {lang : this.current_lang}, function(data) {
            $this.render(data);

            $('#'+$this.tabs[$this.current_page]+'-tab').addClass('active');

            switch($this.current_page) {
                case 1:
                    $('#db').show();
                    break;
                case 2:
                    $('#db').show();
                    $('#admin').show();
                    $('.complete-button').show();
                    break;
            }
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

        $('#lang-tab').addClass('active');
    },

    render : function(html) {
        $(this.el).html(html);

        $('select').val(this.current_lang);

        $('#header').height($(window).height()*0.25);
    }
});

window.InstallSiteView = new InstallSiteView;
