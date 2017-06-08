define(['text!templates/admin/event-copy.phtml'
], function(Template) {
    "use strict";

    return class EventCopyView extends app.AdminView {

        events() {
            return {'click #finished-btn': 'click_finished_btn'};
        }
        
        initialize(options) {
            this.event = new app.models.Event.Event();
            this.event.set('Eventid', options.eventid);
            this.event.fetch()
                    .done(() => {
                       this.render();
                    });
        }

        click_finished_btn()
        {
            if (!this.$('#form').valid()) {
                return;
            }
        }
       
        render() {
            let t = this.i18n();

            this.renderTemplate(Template, {event: this.event});
            
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