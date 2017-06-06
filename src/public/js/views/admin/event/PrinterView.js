define(['views/admin/event/AdminEventView',
        'text!templates/admin/event/printer.phtml',
        'text!templates/admin/event/printer-item.phtml'
], function(AdminEventView,
            Template,
            TemplateItem) {
    "use strict";

    return class PrinterView extends AdminEventView {

        events() {
            return {'click #add-btn': 'click_add_btn',
                    'click .default-btn': 'click_default_btn',
                    'click .edit-btn': 'click_edit_btn',
                    'click .delete-btn': 'click_delete_btn',
                    'click #delete-dialog-finished': 'click_delete_finished_btn'};
        }

        initialize(options) {
            super.initialize(options);

            this.printers = new app.collections.Event.EventPrinterCollection();
            this.refresh();
        }

        onClose() {
            if (this.fetching) {
               this.fetching.abort();
            }
        }

        refresh() {
            this.$('#printers-list').empty();

            if(!this.rendered) {
                this.render();
                this.rendered = true;
            }

            $.mobile.loading("show", {
                text: 'Lade Drucker ...',
                textVisible: true,
                theme: 'b'
            });

            this.fetching = this.printers.fetch({url: this.printers.url() + '/Eventid/' + this.eventid})
                                        .done(() => {
                                            this.fetching = null;
                                            this.renderPrinterList();
                                        });
        }

        click_add_btn() {
            this.changeHash(this.getMenuLink() + '/printer/add');
        }

        click_default_btn(event) {
            let printer = this.printers.get({cid: $(event.currentTarget).attr('data-printer-cid')});

            printer.save({put: true})
                    .done(() => {
                        this.reload();
                    });
        }

        click_edit_btn(event) {
            let printer = this.printers.get({cid: $(event.currentTarget).attr('data-printer-cid')});

            this.changeHash(this.getMenuLink() + '/printer/' + printer.get('EventPrinterid'));
        }

        click_delete_btn(event) {
            let cid = $(event.currentTarget).attr('data-printer-cid');

            this.deleteId = cid;

            this.$('#delete-dialog').popup('open');
        }

        click_delete_finished_btn() {
            this.$('#delete-dialog').popup('close');

            let printer = this.printers.get({cid: this.deleteId});
            printer.destroy()
                    .done(() => {
                        this.reload();
                    });
        }

        renderPrinterList() {
            $.mobile.loading("hide");
            let template = _.template(TemplateItem);
            let i18n = this.i18n();

            this.printers.each((printer) => {
                this.$('#printers-list').append(template({printer: printer,
                                                          t: i18n}));
            });
        }

        render() {
            this.renderTemplate(Template);

            this.changePage(this);

            return this;
        }
    }
} );