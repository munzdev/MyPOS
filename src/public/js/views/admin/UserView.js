define(['text!templates/admin/user.phtml',
        'text!templates/admin/user-item.phtml'
], function(Template,
            TemplateItem) {
    "use strict";

    return class UserView extends app.AdminView {

        events() {
            return {'click #add-btn': 'click_add_btn',
                    'click .edit-btn': 'click_edit_btn',
                    'click .delete-btn': 'click_delete_btn',
                    'click #delete-dialog-finished': 'click_delete_finished_btn'}
        }

        initialize() {
            this.users = new app.collections.User.UserCollection();
            this.refresh(); 
        }
        
        refresh() {
            let i18n = this.i18n();
            this.$('#users-list').empty();

            if(!this.rendered) {
                this.render();
                this.rendered = true;
            }
            
            this.fetchData(this.users.fetch(), i18n.loading);
        }

        click_add_btn()
        {
            this.changeHash("admin/user/add");
        }

        click_edit_btn(event)
        {
            var user = this.users.get({cid: $(event.currentTarget).attr('data-user-cid')});

            this.changeHash("admin/user/" + user.get('Userid'));
        }

        click_delete_btn(event)
        {
            var cid = $(event.currentTarget).attr('data-user-cid');

            this.deleteId = cid;

            this.$('#delete-dialog').popup('open');
        }

        click_delete_finished_btn()
        {
            this.$('#delete-dialog').popup('close');

            var user = this.users.get({cid: this.deleteId});
            user.destroy()
                .done(() => {
                    this.reload();
                });
        }
        
        onDataFetched() {
            let template = _.template(TemplateItem);
            let i18n = this.i18n();

            this.users.each((user) => {
                this.$('#users-list').append(template({user: user,
                                                        t: i18n}));
            });
        }

        render() {
            this.renderTemplate(Template);

            this.changePage(this);
        }

    }
} );