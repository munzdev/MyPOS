define(['text!templates/pages/order-new.phtml'
], function(Template) {
    "use strict";
    
    return class OrderNewView extends app.PageView
    {
        initialize() {
            this.render();
        }
        
        events() {
            return {"click .table-nr": 'tableNrClicked',
                    "click #tableNrClear": "tableNrReset",
                    "click #next": "orderNext"}
        }
        
        tableNrClicked(event) {
            event.preventDefault();

            this.$('#tableNr').append($(event.currentTarget).html());
        }

        tableNrReset(event) {
            event.preventDefault();

            this.$('#tableNr').empty();
        }

        orderNext(event) {
            event.preventDefault();

            var tableNr = this.$('#tableNr').text();

            if(tableNr == '')
            {
                let i18n = this.i18n();
                
                app.error.showAlert(i18n.error, i18n.errorText);
                return;
            }

            this.changeHash("order-modify/tableNr/" + tableNr);
        }

        render() {
            this.renderTemplate(Template);

            this.changePage(this);
        }
    }
} );