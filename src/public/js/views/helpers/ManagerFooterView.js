define(['text!templates/helpers/manager-footer.phtml'],
 function(Template ) {
    "use strict";

    return class ManagerFooterView extends app.FooterView {

        defaults() {
            return {activeButton: ''}
    	}

        events() {
            return {'click .footer-link': 'clicked'};
        }

        clicked(event) {
            event.preventDefault();

            var href = $(event.currentTarget).attr('href');

            this.changeHash(href);
        }

        // Renders all of the Category models on the UI
        render() {
            this.renderTemplate(Template, {activeButton: this.activeButton});

            return this;
        }
    };

} );