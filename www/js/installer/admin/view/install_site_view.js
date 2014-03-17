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
                $('#db').removeClass('hide');
                break;
            case 2:
                $('#admin').removeClass('hide');
                $('.complete-button').removeClass('hide');
                break;
        }
    },

    changeLang : function() {
        $this = this;

        this.current_lang = $('select').val();

        $.post(
            'index.php',
            {
                lang            : this.current_lang,
                'class'         : 'installer',
                'controller'    : 'site'
            },
            function(data) {
            $this.render(data);

            switch($this.current_page) {
                case 1:
                    $('#db').removeClass('hide');
                    break;
                case 2:
                    $('#db').removeClass('hide');
                    $('#admin').removeClass('hide');
                    $('.complete-button').removeClass('hide');
                    $('.next-button').addClass('disabled');
                    break;
            }

            $('body').find('.error-span').remove();
        });
    },

    initialize: function() {
        App.elementLoad('#header', function(){
            $('#header').height($(window).height()*0.25);
        });

        $('select').val(this.current_lang);

        $('#lang-tab').addClass('active');

        Form.add_success_handler('install-site-form', function(data){

            console.log(data);

            if(typeof data != 'object') {
                var msg = LangModel.get('error_occurred') ||
                    'an error occurred';
                App.showNoty(msg, 'error');
            }
            else {
                App.goto('install/done');
            }
        })

        App.deleteCookie('admin_lang');
        App.deleteCookie('site_lang');
    },

    render : function(html) {
        $(this.el).html(html);

        $('#'+this.tabs[this.current_page]+'-tab').addClass('active');

        $('select').val(this.current_lang);

        $('#header').height($(window).height()*0.25);
    }
});

window.InstallSiteView = new InstallSiteView;
