var DocumentationView = Backbone.View.extend({

    initialize: function(){
        App.elementLoad('.ac-small', function(){
            $(document).on('click', '.ac-container label', function(){

                var art = $(this).siblings('article');


                art.mCustomScrollbar("destroy");

                setTimeout(function(){
                    if(art.height()) {
                        art.mCustomScrollbar();
                    }
                }, 500)
            })
        }, 150)
    }
})

window.DocumentationView = new DocumentationView;
