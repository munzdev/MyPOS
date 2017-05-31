define(['views/admin/event/AdminEventView',
        'text!templates/admin/event/table-modify.phtml'
], function(AdminEventView,
            Template) {
    "use strict";

    return class TableModifyView extends AdminEventView {

        events() {
            return {'click #save-btn': 'click_save_btn'};
        }

        initialize(options) {
            super.initialize(options);

            this.eventTable = new app.models.Event.EventTable();

            if(options.tableid === 'new') {
                this.render();
            } else {
                this.eventTable.set('EventTableid', options.tableid);
                this.eventTable.fetch()
                    .done(() => {
                        this.render();
                    });
            }
        }

        click_save_btn() {
            if (!this.$('#form').valid()) {
                return;
            }

            this.eventTable.set('Name', $.trim(this.$('#name').val()));
            this.eventTable.set('Data', $.trim(this.$('#data').val()));
            this.eventTable.save()
                .done(() => {
                    this.changeHash(this.getMenuLink() + '/table');
                });
        }

        render() {
            let t = this.i18n();
            this.renderTemplate(Template, {eventTable: this.eventTable});

            this.$('#form').validate({
                rules: {
                    name: {required: true}
                },
                messages: {
                    name: t.errorName
                }
            });

            this.changePage(this);

            return this;
        }
    }
} );