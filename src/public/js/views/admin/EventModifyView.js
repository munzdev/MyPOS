define(['text!templates/admin/event-modify.phtml'
], function(Template) {
    "use strict";

    return class EventModifyView extends app.AdminView {

        events() {
            return {'click #save-btn': 'click_save_btn'}
        }
        
        initialize(options) {
            this.event = new app.models.Event.Event();

            if(options.eventid === 'new') {
                this.render();
            } else {
                this.event.set('Eventid', options.eventid);
                this.event.fetch()
                        .done(() => {
                           this.render();
                        });
            }
        }

        click_save_btn() {            
            if (!this.$('#form').valid()) {
                return;
            }
            
            this.event.set('Name', $.trim(this.$('#name').val()));
            this.event.set('Date', $.trim(this.$('#date').val()));
            this.event.save()
                    .done(() => {
                        this.changeHash('admin/event');
                    });
        }       

        // Renders all of the Category models on the UI
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