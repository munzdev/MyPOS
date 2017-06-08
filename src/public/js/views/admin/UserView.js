define(['text!templates/admin/user.phtml'
], function(Template) {
    "use strict";

    return class UserView extends app.AdminView {

        events() {
            return {'click #add-btn': 'click_add_btn',
                    'click .edit-btn': 'click_edit_btn',
                    'click .delete-btn': 'click_delete_btn',
                    'click #delete-dialog-finished': 'click_delete_finished_btn'}
        }

        initialize() {
            this.user = new app.collections.User.UserCollection();
            this.user.fetch()
                               .done(() => {
                                   this.render();
                               });
        }

        click_add_btn()
        {
            this.changeHash("admin/user/add");
        }

        click_edit_btn(event)
        {
            var user = this.user.get({cid: $(event.currentTarget).attr('data-user-cid')});

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

            var user = this.user.get({cid: this.deleteId});
            user.destroy()
                .done(() => {
                    this.reload();
                });
        }

        render() {
            this.renderTemplate(Template, {users: this.user});

            this.changePage(this);
        }

    }
} );