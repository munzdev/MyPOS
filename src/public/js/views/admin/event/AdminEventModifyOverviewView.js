define(['views/helpers/HeaderView',
        'views/helpers/AdminFooterView',
        'text!templates/admin/event/admin-event-modify-overview.phtml'
], function(HeaderView,
            AdminFooterView,
            Template) {
    "use strict";

    return class AdminEventModifyOverviewView extends app.PageView {
    
        initialize(options) {
            this.eventid = options.eventid;
            this.render();
        }

        render() {
            var header = new HeaderView();
            var footer = new AdminFooterView(this.eventid);
            this.registerSubview(".nav-header", header);
            this.registerSubview(".nav-footer", footer);

            this.renderTemplate(Template, {event: this.event});            

            this.changePage(this);
            
            return this;                     
        }

    }
} );