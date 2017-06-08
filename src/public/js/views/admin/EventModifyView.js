define(['text!templates/admin/event-modify.phtml'
], function(Template) {
    "use strict";

    return class EventModifyView extends app.AdminView {

        events() {
            return {'click #save-btn': 'click_save_btn'}
        }
        
        initialize(options) {
            this.event = new app.models.Event.Event();
            this.isNew = options.eventid === 'new';
            this.render();

            if (!this.isNew) {
                this.event.set('Eventid', options.eventid);
                this.fetchData(this.event.fetch());
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
        
        onDataFetched() {
            this.$('#name').val(this.event.get('Name'));
            this.$('#date').val(app.i18n.toDate(this.event.get('Date')));
        }

        render() {
            let t = this.i18n();

            this.renderTemplate(Template, {isNew: this.isNew});
            
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