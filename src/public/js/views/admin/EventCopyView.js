define(['text!templates/admin/event-copy.phtml'
], function(Template) {
    "use strict";

    return class EventCopyView extends app.AdminView {

        events() {
            return {'click #finished-btn': 'click_finished_btn'};
        }
        
        initialize(options) {
            this.render();

            this.event = new app.models.Event.Event();
            this.event.set('Eventid', options.eventid);
            this.fetchData(this.event.fetch());
        }

        click_finished_btn()
        {
            if (!this.$('#form').valid()) {
                return;
            }
        }

        onDataFetched() {
            this.$('#name').val(this.event.get('Name'));
            this.$('#date').val(app.i18n.toDate(this.event.get('Date')));
        }
       
        render() {
            let t = this.i18n();

            this.renderTemplate(Template);
            
            this.$('#form').validate({
                rules: {
                    name: {required: true},
                    date: {required: true}
                },
                messages: {
                    name: t.errorName,
                    date: t.errorDate
                }
            });

            this.changePage(this);                    
        }

    }
} );