/**
        {
   routes: {
         'index': 'index'
   },

   index: {
         before: function(){},
         route: function(){}, // Main route function
         after: function(){},
         leave: function(){}
   }
}
 */
(function(Backbone, _) {
  var leave;

  _.extend(Backbone.Router.prototype, Backbone.Events, {
    route : function(route, name, callback) {

          callback = callback || this[name];

      var before, after;
          var fn = callback;


      Backbone.history || (Backbone.history = new Backbone.History);
      if (!_.isRegExp(route)) route = this._routeToRegExp(route);
      if (!fn) fn = this[name];


      if(typeof callback == 'object'){

        before = callback.before;
        fn = callback.route;
        after = callback.after;
      }

      Backbone.history.route(route, _.bind(function(fragment) {

        var args = this._extractParameters(route, fragment);

        if(leave){
          if(leave.apply(this, args) === false)
            return;
          else
            leave = false;
        }

        if(before && before.apply(this, args) === false) return;
        fn.apply(this, args);
        if(after && after.apply(this, args) === false) return;

        if(typeof callback == 'object')
          leave = callback.leave;

        this.trigger.apply(this, ['route:' + name].concat(args));
                this.trigger('route', name, args);

        Backbone.history.trigger('route', this, name, args);
      }, this));
      return this;
    }
  });
}).call(this, Backbone, _);