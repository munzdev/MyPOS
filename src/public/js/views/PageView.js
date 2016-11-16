// Login View
// =============

// Includes file dependencies
define([ "views/AbstractView"],
function( AbstractView ) {
    "use strict";
    
    return class PageView extends AbstractView {
        jqmAttributes() {
            return {'data-role': 'page'};
        }
        
        renderTemplate(Template, Datas) {
            super.renderTemplate(Template, Datas);    
            
            // Verify global menu swipe is available on page
            this.$el.on("swiperight", app.sideMenu.open);
            this.$el.on('click .side-menu-open', app.sideMenu.open);
        }
        
        close() {
            this.$el.off();
        }
    }

} );