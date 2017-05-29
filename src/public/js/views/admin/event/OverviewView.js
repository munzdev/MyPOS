define(['views/admin/event/AdminEventView',
        'views/helpers/HeaderView',
        'views/helpers/AdminFooterView',
        'text!templates/admin/event/overview.phtml'
], function(AdminEventView,
            HeaderView,
            AdminFooterView,
            Template) {
    "use strict";

    return class OverviewView extends AdminEventView {
    
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