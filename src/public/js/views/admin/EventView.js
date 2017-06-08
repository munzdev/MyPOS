define(['text!templates/admin/event.phtml',
        'text!templates/admin/event-item.phtml'
], function(Template,
            TemplateItem) {
    "use strict";

    return class EventView extends app.AdminView {
        
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
            this.refresh();            
        }
        
        refresh() {
            let i18n = this.i18n();
            this.$('#events-list').empty();

            if(!this.rendered) {
                this.render();
                this.rendered = true;
            }
            
            this.fetchData(this.events.fetch(), i18n.loading);
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
        
        onDataFetched() {
            let template = _.template(TemplateItem);
            let i18n = this.i18n();

            this.events.each((event) => {
                this.$('#events-list').append(template({event: event,
                                                        t: i18n}));
            });
        }

        render() {
            this.renderTemplate(Template);

            this.changePage(this);         
        }
    }

} );