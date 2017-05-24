define(['views/helpers/HeaderView',
    'views/helpers/ManagerFooterView',
        'text!templates/manager/manager-statistic.phtml'
], function(HeaderView,
            ManagerFooterView,
            Template ) {
    "use strict";

    return class ManagerStatisticView extends app.PageView {

    	initialize() {
            this.render();
        }

        // Renders all of the Category models on the UI
        render() {
            var header = new HeaderView();
            var footer = new ManagerFooterView();
            this.registerSubview(".nav-header", header);
            this.registerSubview(".manager-footer", footer);

            this.renderTemplate(Template);

            this.changePage(this);

            return this;
        }

    }

} );