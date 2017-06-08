define(['collections/custom/distributionPlace/DistributionSummaryCollection',
        'text!templates/pages/distribution-summary.phtml'
], function(DistributionSummaryCollection,
            Template) {
    "use strict";

    return class DistributionSummaryView extends app.PageView {
        initialize() {
            this.distributionSummary = new DistributionSummaryCollection();
            this.distributionSummary.fetch()
                                    .done(() => {
                                        this.render();
                                    });
        }

        events() {
            return {};
        }

        apiCommandReciever(command) {
            if(command == 'distribution-summary-update')
            {
                this.reload();
            }
        }

        render() {
            this.renderTemplate(Template, {distributionSummary: this.distributionSummary});

            this.changePage(this);
        }
    }
} );