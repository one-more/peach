var App = {};

_.extend(App, Backbone.Events, {

    //fires callback when element is loaded
    elementLoad : function(el, callback) {
        setTimeout(function timer1(){
            if(!$('div').is(el)) {
                timer1();
            }
            else {
                callback();
            }
        }, 20);
    }
})
