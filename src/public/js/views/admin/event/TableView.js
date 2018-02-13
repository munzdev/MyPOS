define(['views/admin/event/AdminEventView',
        'text!templates/admin/event/table.phtml',
        'text!templates/admin/event/table-item.phtml'
], function(AdminEventView,
            Template,
            TemplateItem) {
    "use strict";

    return class TableView extends AdminEventView {

        events() {
            return {'click #add-btn': 'click_add_btn',
                    'click .edit-btn': 'click_edit_btn',
                    'click .delete-btn': 'click_delete_btn',
                    'click #delete-dialog-finished': 'click_delete_finished_btn'}
        }

        initialize(options) {
            super.initialize(options);

            this.tables = new app.collections.Event.EventTableCollection();
            this.refresh();
        }

        refresh() {
            this.$('#tables-list').empty();

            if(!this.rendered) {
                this.render();
                this.rendered = true;
            }
            
            this.fetchData(this.tables.fetch({url: this.tables.url() + '/Eventid/' + this.eventid}), 'Lade Tabellen ...');
        }

        click_add_btn() {
            this.changeHash(this.getMenuLink() + '/table/add');
        }

        click_edit_btn(event) {
            let table = this.tables.get({cid: $(event.currentTarget).attr('data-table-cid')});

            this.changeHash(this.getMenuLink() + '/table/' + table.get('EventTableid'));
        }

        click_delete_btn(event) {
            let cid = $(event.currentTarget).attr('data-table-cid');

            this.deleteId = cid;

            this.$('#delete-dialog').popup('open');
        }

        click_delete_finished_btn() {
            this.$('#delete-dialog').popup('close');

            let table = this.tables.get({cid: this.deleteId});
            table.destroy()
                .done(() => {
                    this.reload();
                });
        }

        onDataFetched() {
            let template = _.template(TemplateItem);
            let i18n = this.i18n();

            this.tables.each((table) => {
                this.$('#tables-list').append(template({table: table,
                                                        t: i18n}));
            });
        }

        render() {
            this.renderTemplate(Template);

            this.changePage(this);
        }
    }
} );