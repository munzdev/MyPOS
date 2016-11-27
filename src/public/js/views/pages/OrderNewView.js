define(['views/helpers/HeaderView',
        'text!templates/pages/order-new.phtml'
], function(HeaderView,
            Template) {
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

        // Renders all of the Category models on the UI
        render() {
            var header = new HeaderView();
            this.registerSubview(".nav-header", header);
            
            this.renderTemplate(Template);

            this.changePage(this);

            return this;
        }
    }
} );