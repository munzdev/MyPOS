// Login View
// =============

// Includes file dependencies
define([ 'Webservice',
         'views/headers/AdminHeaderView',
         'views/footers/AdminFooterView',
         'collections/admin/MenuTypeCollection',
         'collections/admin/EventUserCollection',
         'collections/admin/TableCollection',
         'collections/PrinterCollection',
         'text!templates/pages/admin/event/distribution-modify.phtml'],
function( Webservice,
          AdminHeaderView,
          AdminFooterView,
          MenuTypeCollection,
          EventUserCollection,
          TableCollection,
          PrinterCollection,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var DistributionModifyView = Backbone.View.extend( {

    	title: 'admin-event-modify-distribution-modify',
    	el: 'body',
        events: {
            'click #admin-event-modify-distribution-modify-save-btn': 'click_save_btn',
            'change #admin-event-modify-distribution-modify-users': 'change_users',
            'change #admin-event-modify-distribution-modify-groupes': 'change_groupes'
        },

        // The View Constructor
        initialize: function(options) {
            _.bindAll(this, "render",
                            "change_groupes",
                            "click_save_btn");

            var self = this;

            this.eventid = options.id;

            this.nameValue = "";
            this.menuesValue = [];
            this.usersValue = {};

            this.menuList = new MenuTypeCollection();
            this.user = new EventUserCollection();
            this.userListData = {};
            this.tables = new TableCollection();
            this.tableListData = {};

            this.printerList = new PrinterCollection();
            this.printerList.url = app.API + "Admin/GetEventPrinterList/";

            $.when(this.menuList.fetch(),
                   this.user.fetch({data: {eventid: this.eventid}}),
                   this.printerList.fetch({data: {eventid: this.id}}),
                   this.tables.fetch()).then(function() {

                if(typeof options.distributions_placeid === 'undefined')
                {
                    self.mode = 'new';
                    self.distributions_placeid = 0;
                    self.render();
                }
                else
                {
                    self.mode = 'edit';
                    self.distributions_placeid = options.distributions_placeid;

                    var webservice = new Webservice();
                    webservice.action = "Admin/GetEventDistribution";
                    webservice.formData = {distributions_placeid: self.distributions_placeid};
                    webservice.callback = {
                        success: function(data)
                        {
                            self.nameValue = data.name;
                            self.menuesValue = data.menues;
                            self.usersValue = data.users;
                            self.render();
                            self.change_users(null, null, data.users);
                            self.change_groupes(null, null, data.tables);
                        }
                    };
                    webservice.call();
                }
            });
        },

        click_save_btn: function()
        {
            var self = this;

            var name = $.trim($('#admin-event-modify-distribution-modify-name').val());
            var menues = $('#admin-event-modify-distribution-modify-groupes').val();
            var users = $('#admin-event-modify-distribution-modify-users').val();

            if(name == '')
            {
                MyPOS.DisplayError('Bitte eine Namen eingebe!');
                return;
            }

            if(menues.length == 0)
            {
                MyPOS.DisplayError('Bitte mindestens eine Menügruppe auswählen!');
                return;
            }

            if(users.length == 0)
            {
                MyPOS.DisplayError('Bitte mindestens einen Benutzer auswählen!');
                return;
            }
            
            var tablesList = $('.admin-event-modify-distribution-modify-tables-list');
            var selectedUserValues = {};
            var selectedTableValues = {};
            
            tablesList.each(function() {
                var id = $(this).attr('data-menu-groupid');
                
                selectedTableValues[id] = $(this).val();
            });                       
                       
            _.each(users, function(id)
            {
                selectedUserValues[id] = $('#admin-event-modify-distribution-modify-users-printer-' + id).val();
            });

            if(this.mode == 'new')
            {
                var webservice = new Webservice();
                webservice.action = "Admin/AddEventDistribution";
                webservice.formData = {eventid: this.eventid,
                                       name: name,
                                       menues: menues,
                                       users: selectedUserValues,
                                       tablesList: selectedTableValues};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/event/modify/' + self.eventid + "/distribution");
                    }
                };
                webservice.call();
            }
            else
            {
                var webservice = new Webservice();
                webservice.action = "Admin/SetEventDistribution";
                webservice.formData = {distributions_placeid: this.distributions_placeid,
                                       name: name,
                                       menues: menues,
                                       users: selectedUserValues,
                                       tablesList: selectedTableValues};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/event/modify/' + self.eventid + "/distribution");
                    }
                };
                webservice.call();
            }
        },

        change_groupes: function(event, handler, selectedTableValues)
        {
            var self = this;
            
            var tables = $('.admin-event-modify-distribution-modify-tables-list');
            
            if(selectedTableValues === undefined)
            {
                selectedTableValues = {};

                tables.each(function() {
                    var id = $(this).attr('data-menu-groupid');

                    selectedTableValues[id] = $(this).val();
                });
            }

            $('#admin-event-modify-distribution-modify-tables').html("");

            var groupes = $('#admin-event-modify-distribution-modify-groupes option:selected');

            var options = "";

            self.tables.each(function(table) {
                options += "<option value='" + table.get('tableid') + "'>" + table.get('name') + "</option>";
            });

            var html = "";

            self.menuList.each(function(type) {
                type.get('groupes').each(function(group) {
                    groupes.each(function()
                    {
                        var groupid = $(this).val();

                        if(groupid == group.get('menu_groupid'))
                        {
                            html += "<div class='ui-corner-all custom-corners'><div class='ui-bar ui-bar-a'><h3>" + group.get('name') + "</h3></div><div class='ui-body ui-body-a'><select class='admin-event-modify-distribution-modify-tables-list' data-menu-groupid='" + group.get('menu_groupid') + "' id='admin-event-modify-distribution-modify-tables-" + group.get('menu_groupid') + "'  multiple='multiple'>" + options + "</select></div></div>";
                        }
                    });
                });
            });
            
            $('#admin-event-modify-distribution-modify-tables').html(html);
            
            _.each(selectedTableValues, function(values, id) {
                var selectObject = $("#admin-event-modify-distribution-modify-tables-" + id);
                
                if(selectObject && values && values.length > 0)
                {
                    selectObject.val(values);
                }
            });            
        },

        change_users: function(event, handler, selectedUserValues)
        {
            var self = this;
            
            var users = $('.admin-event-modify-distribution-modify-users-printer-group');
            
            if(selectedUserValues === undefined)
            {
                selectedUserValues = {};

                users.each(function() {
                    var id = $(this).attr('data-userid');

                    selectedUserValues[id] = $(this).val();
                });
            }

            $('#admin-event-modify-distribution-modify-users-printer').html("");

            var users = $('#admin-event-modify-distribution-modify-users option:selected');

            var options = "";

            self.printerList.each(function(printer) {
                options += "<option value='" + printer.get('events_printerid') + "'>" + printer.get('name') + "</option>";
            });

            var html = "";

            users.each(function()
            {
                var user = self.user.findWhere({userid: $(this).val()});

                html += "<div class='ui-field-contain'><label for='admin-event-modify-distribution-modify-users-printer-" + user.get('userid') + "'>" + user.get('name') + " Drucker:</lable><select data-mini='true' data-userid='" + user.get('userid') + "' class='admin-event-modify-distribution-modify-users-printer-group' id='admin-event-modify-distribution-modify-users-printer-" + user.get('userid') + "'>" + options + "</select></div>";
            });

            $('#admin-event-modify-distribution-modify-users-printer').html(html);
            
            _.each(selectedUserValues, function(value, id) {
                var selectObject = $("#admin-event-modify-distribution-modify-users-printer-" + id);
                
                if(selectObject && value)
                {
                    selectObject.val(value);
                }
            });            
            
            $('.admin-event-modify-distribution-modify-users-printer-group').selectmenu();
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();
            var footer = new AdminFooterView({id: this.eventid});

            header.activeButton = 'event';
            footer.activeButton = 'distribution';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  footer: footer.render(),
                                                                  mode: this.mode,                                                                  
                                                                  menu: this.menuList,
                                                                  users: this.user,
                                                                  printers: this.printerList,
                                                                  name: this.nameValue,
                                                                  menuesValue: this.menuesValue,
                                                                  usersValue: this.usersValue});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");
            footer.setElement("#" + this.title + " .nav-footer");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminEventModifyDistributionModifyView;

} );