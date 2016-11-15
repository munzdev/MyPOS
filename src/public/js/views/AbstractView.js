// Login View
// =============

// Includes file dependencies
define(function() {
    "use strict";
        
    return class AbstractView extends Backbone.View {
        constructor(options)
        {
            super(options);
            
            this.subViews = new Map();
        }
        
        id(){ return this.constructor.name; }
        
        renderTemplate(Template, Datas) {
            this.renderTemplateToEl(Template, Datas);
                        
            //append the new page onto the end of the body                        
            $('body').append(this.$el);
                 
            //initialize the new page
            $.mobile.initializePage();
            
            return this;
        }
        
        registerSubview(target, view)
        {
            this.subViews.set(target, view);                   
        }

        renderTemplateToEl(Template, Datas)
        {
            var template = _.template(Template);

            var i18n = {};
            var i18nNamespace = this.id();

            if(i18nNamespace in app.i18n.template)
                i18n = app.i18n.template[i18nNamespace];

            Datas = _.extend({}, Datas, {t: i18n,
                                         i18n: app.i18n.template});

            this.$el.attr(this.jqmAttributes());
            this.$el.html(template(Datas));

            if(this.subViews) {
                for (let [ target, view ] of this.subViews.entries()) {
                    let targetContent = view.render().el.outerHTML;
                    let targetObject = this.$(target);
                    targetObject.html(targetContent);
                    view.setElement(targetObject);
                }
            }

            return this;
        }
        
        jqmAttributes() {
            return {};
        }                
        
        static reload()
        {
            Backbone.history.loadUrl();
        }
        
        reload()
        {
            AbstractView.reload();
        }
        
        static changePage(Page, options) {
            if(Page instanceof AbstractView)
                Page = Page.id();
            
            $.mobile.changePage( "#" + Page, options);
        }
        
        changePage(Page, options) {
            AbstractView.changePage(Page, options);
        }
        
        static changeHash(Hash, options) {
            options = _.extend({trigger: true}, options);
            Backbone.history.navigate("#" + Hash, options);
        }
        
        changeHash(Hash, options) {
            AbstractView.changeHash(Hash, options);
        }
        
        close() {
            if(this.keepMeInDom)
                return;
            
            this.remove();
            this.unbind();
            if (this.onClose) {
                this.onClose();
            }
        };
    }
} );