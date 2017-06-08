define(["views/AbstractView",
        "views/helpers/HeaderView"
], function(AbstractView,
            HeaderView) {
    "use strict";

    return class PageView extends AbstractView {
        jqmAttributes() {
            return {'data-role': 'page'};
        }

        renderTemplate(Template, Datas) {
            // add default page header is shown on each page
            let header = new HeaderView();
            this.registerSubview("#nav-header", header);
            
            Template = "<div id=\"nav-header\"></div>" + Template;
            super.renderTemplate(Template, Datas);

            // Verify global menu swipe is available on page
            this.$el.on("swiperight", app.sideMenu.open);
            this.$('.side-menu-open').click(app.sideMenu.open);
        }

        fetchData(hxr, loadingText, callback) {
            if (!loadingText) {
                loadingText = app.i18n.template.loading;
            }
            
            $.mobile.loading("show", {
                text: loadingText,
                textVisible: true,
                theme: 'b'
            });
            
            this.fetching = hxr.done((data) => {
                                        this.fetching = null;
                                        $.mobile.loading("hide");

                                        if (!callback) {
                                            callback = this.onDataFetched.bind(this);
                                        }

                                        callback(data);
                                    })
                                .fail((jqXHR, textStatus, errorThrown) => {
                                        if (textStatus === 'abort') {
                                            return;
                                        }                                                                                
                                        
                                        let button = $("<button class='ui-btn'>" + app.i18n.template.errorReloadPageText + "</button>");                                        
                                        button.click(() => {
                                            this.reload();
                                        });
                                        
                                        let content = this.$("div[data-role='content']");
                                        content.empty();
                                        content.append(button);
                                                                                
                                        app.error.showAlert(app.i18n.template.errorLoadingApp, app.i18n.template.errorLoadingDataText);
                                    });
        }
        
        onDataFetched() {
            this.render();
        }
        
        onClose() {
            this.$el.off();
            
            if (this.fetching) {
                this.fetching.abort();
            }
        }
    }

} );