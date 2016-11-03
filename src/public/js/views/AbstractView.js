// Login View
// =============

// Includes file dependencies
define(function() {
    "use strict";
        
    return class AbstractView extends Backbone.View {
        el() {return 'body';}
        id(){ return this.constructor.name; }
        
        renderTemplate(Template, Datas) {
            var template = _.template(Template);
            
            var i18n = {};
            var i18nNamespace = this.id();
            
            if(i18nNamespace in app.i18n.template)
                i18n = app.i18n.template[i18nNamespace];
            
            Datas = _.extend({}, Datas, {t: i18n,
                                         i18n: app.i18n.template});
            
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
        
        ChangePage(View) {
            Backbone.history.navigate(View, true);
        }
    }
} );