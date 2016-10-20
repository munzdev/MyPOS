// Login View
// =============

// Includes file dependencies
define([ "app"],
 function( app ) {
    "use strict";
        
    return class AbstractView extends Backbone.View {
        el() {return 'body';}
        id(){ return this.constructor.name; }
        
        renderTemplate(Template, Datas) {
            var template = _.template(Template);
            
            var div = $("<div/>").attr(_.extend({id: this.id()}, this.jqmAttributes()));
            div.html(template(Datas));
                        
            //append the new page onto the end of the body
            this.$el.append(div);

            //initialize the new page
            $.mobile.initializePage();
        }
        
        jqmAttributes() {
            return {};
        }                
    }
} );