// Login View
// =============

// Includes file dependencies
define(function() {
    "use strict";

    return class AbstractView extends Backbone.View {
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
            if(!this.subViews)
                this.subViews = new Map();

            this.subViews.set(target, view);
        }

        registerAppendview(view, type = "begin")
        {
            if(!this.appendViews)
                this.appendViews = new Map();

            this.appendViews.set(view, type);
        }

        i18n()
        {
            var i18n = {};
            var i18nNamespace = this.id();

            if(i18nNamespace in app.i18n.template)
                i18n = app.i18n.template[i18nNamespace];

            return i18n;
        }

        renderTemplateToEl(Template, Datas)
        {
            var template = _.template(Template);

            Datas = _.extend({}, Datas, {t: this.i18n(),
                                         i18n: app.i18n.template,
                                         f: {toCurrency: app.i18n.toCurrency,
                                             toDecimal: app.i18n.toDecimal,
                                             toDateTime: app.i18n.toDateTime,
                                             toDate: app.i18n.toDate,
                                             toTime: app.i18n.toTime}});

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

            if(this.appendViews) {
                for (let [ view, type ] of this.appendViews.entries()) {
                    let targetContent = view.render().$el.html();

                    if(type == "end")
                        this.$el.append(targetContent)
                    else
                        this.$el.prepend(targetContent)

                    view.setElement(this.$el);
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
            if(Hash[0] != '#') Hash = '#' + Hash;
            Backbone.history.navigate(Hash, options);
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