define(['views/admin/event/AdminEventView',
        'text!templates/admin/event/overview.phtml'
], function(AdminEventView,
            Template) {
    "use strict";

    return class OverviewView extends AdminEventView {
    
        initialize(options) {
            super.initialize(options);
            this.render();
        }

        render() {
            this.renderTemplate(Template, {event: this.event});

            this.changePage(this);
            
            return this;                     
        }

    }
} );