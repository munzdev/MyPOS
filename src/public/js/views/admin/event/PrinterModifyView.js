define(['views/admin/event/AdminEventView',
        'text!templates/admin/event/printer-modify.phtml'
], function(AdminEventView,
            Template) {
    "use strict";

    return class PrinterModifyView extends AdminEventView {

        events() {
            return {'click #save-btn': 'click_save_btn'};
        }

        initialize(options) {
            super.initialize(options);

            this.eventPrinter = new app.models.Event.EventPrinter();

            if(options.printerid === 'new') {
                this.render();
            } else {
                this.eventPrinter.set('EventPrinterid', options.printerid);
                this.eventPrinter.fetch()
                                    .done(() => {
                                        this.render();
                                    });
            }
        }

        click_save_btn() {
            if (!this.$('#form').valid()) {
                return;
            }

            this.eventPrinter.set('Eventid', this.eventid);
            this.eventPrinter.set('Name', $.trim(this.$('#name').val()));
            this.eventPrinter.set('Type', $.trim(this.$('#type').val()));
            this.eventPrinter.set('Attr1', $.trim(this.$('#attr1').val()));
            this.eventPrinter.set('Attr2', $.trim(this.$('#attr2').val()));
            this.eventPrinter.set('CharactersPerRow', $.trim(this.$('#charactersPerRow').val()));
            this.eventPrinter.save()
                            .done(() => {
                                this.changeHash(this.getMenuLink() + '/printer');
                            });
        }

        render() {
            let t = this.i18n();
            this.renderTemplate(Template, {eventPrinter: this.eventPrinter});

            this.$('#form').validate({
                rules: {
                    name: {required: true},
                    type: {required: true},
                    charactersPerRow: {required: true}
                },
                messages: {
                    name: t.errorName,
                    type: t.errorType,
                    charactersPerRow: t.errorCharactersPerRow
                },
                /*errorPlacement: function (error, element) {
                    if(element.is('select'))
                        error.appendTo(element.parent().parent().prev());
                    else
                        error.appendTo(element.parent().prev());
                }*/
            });

            this.changePage(this);

            return this;
        }

    }
} );