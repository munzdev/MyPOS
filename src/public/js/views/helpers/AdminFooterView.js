define(['text!templates/helpers/admin-footer.phtml'],
 function(Template ) {
    "use strict";

    return class AdminFooterView extends app.FooterView {

    	defaults() {
            activeButton: ''
    	}

        initialize(eventid) {
            this.eventid = eventid;
        }

        events() {
            return {'click .link': 'clicked'};
        }

        clicked(e) {
            e.preventDefault();

            var href = $(e.currentTarget).attr('href');

            this.changeHash(href);
        }

        render() {
            this.renderTemplate(Template, {activeButton: this.activeButton,
                                           link: "#admin/event/modify/" + this.eventid});

            return this;
        }
    }

} );