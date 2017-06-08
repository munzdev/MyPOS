define(['text!templates/manager/statistic.phtml'
], function(Template ) {
    "use strict";

    return class StatisticView extends app.ManagerView {

    	initialize() {
            this.render();
        }

        // Renders all of the Category models on the UI
        render() {
            this.renderTemplate(Template);

            this.changePage(this);
        }

    }

} );