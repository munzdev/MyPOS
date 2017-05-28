define(['views/helpers/HeaderView',
        'text!templates/admin/admin-event.phtml'
], function(HeaderView,
            Template) {
    "use strict";

    return class AdminEventView extends app.PageView {
        
        events() {
            return {'click .add-btn': 'click_add_btn',
                    'click .copy-btn': 'click_copy_btn',
                    'click .edit-details-btn': 'click_edit_details_btn',
                    'click .edit-settings-btn': 'click_edit_settings_btn',
                    'click .active-btn': 'click_active_btn',
                    'click #activate-dialog-finished': 'click_active_finished_btn'};
        }
        
        initialize() {
            this.events = new app.collections.Event.EventCollection();
            this.events.fetch()
                        .done(() => {
                            this.render();
                        });
        }

        click_add_btn()
        {
            this.changeHash('admin/event/add');
        }

        click_copy_btn(event)
        {
            var event = this.events.get({cid: $(event.currentTarget).attr('data-event-cid')});

            this.changeHash('admin/event/copy/' + event.get('Eventid'));
        }

        click_edit_details_btn(event)
        {
            var event = this.events.get({cid: $(event.currentTarget).attr('data-event-cid')});

            this.changeHash('admin/event/' + event.get('Eventid'));
        }
        
        click_edit_settings_btn(event)
        {
            var event = this.events.get({cid: $(event.currentTarget).attr('data-event-cid')});

            this.changeHash('admin/event/modify/' + event.get('Eventid'));
        }

        click_active_finished_btn()
        {
            this.$('#activate-dialog').popup('close');
                                
            var event = this.events.get({cid: this.activateId});
            
            event.set('Active', true);
            event.save()
                .done(() => {
                    this.reload();
                });
        }

        click_active_btn(event)
        {
            var cid = $(event.currentTarget).attr('data-event-cid');

            this.activateId = cid;

            this.$('#activate-dialog').popup('open');
        }

        // Renders all of the Category models on the UI
        render() {
            var header = new HeaderView();
            this.registerSubview(".nav-header", header);

            this.renderTemplate(Template, {events: this.events});

            this.changePage(this);

            return this;            
        }
    }

} );