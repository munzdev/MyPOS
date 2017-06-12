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

        i18n(subNamespace) {
            var i18n = app.i18n.template;
            var i18nNamespace = this.id();

            if (subNamespace) {
                let namespaces = subNamespace.split('.');

                for (let i = 0, length = namespaces.length; i < length; i++) {
                    if (namespaces[i] in i18n) {
                        i18n = i18n[namespaces[i]];
                    }
                }
            }

            if (i18nNamespace in i18n) {
                i18n = i18n[i18nNamespace];
            }

            if (i18n === app.i18n.template) {
                i18n = null;
            }

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

            if (this.subViews) {
                for (let [ target, view ] of this.subViews.entries()) {
                    this.renderSubview(target, view);
                }
            }

            if (this.appendViews) {
                for (let [ view, type ] of this.appendViews.entries()) {
                    this.renderAppendview(view, type);
                }
            }

            return this;
        }

        renderSubview(target, view) {
            let targetContent = view.render().el.outerHTML;
            let targetObject = this.$(target);
            targetObject.html(targetContent);
            view.setElement(targetObject);
            view.$el.trigger("create");
        }

        renderAppendview(view, type) {
            let targetContent = view.render().$el.html();

            if (type == "end")
                this.$el.append(targetContent)
            else
                this.$el.prepend(targetContent)

            view.setElement(this.$el);
            view.$el.trigger("create");
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