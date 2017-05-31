define(['views/helpers/HeaderView',
        'views/helpers/AdminFooterView'
], function(HeaderView,
            AdminFooterView) {
    "use strict";

    return class AdminEventView extends app.PageView {
        initialize(options) {
            super.initialize(options);
            this.eventid = options.eventid;
        }

        i18n() {
            return super.i18n('Admin.Event');
        }

        renderTemplate(Template, Datas) {
            let header = new HeaderView();
            let footer = new AdminFooterView(this.eventid);
            this.registerSubview(".nav-header", header);
            this.registerSubview(".nav-footer", footer);

            super.renderTemplate(Template, Datas);
        }

        getMenuLink() {
            return 'admin/event/modify/' + this.eventid;
        }
    }

} );