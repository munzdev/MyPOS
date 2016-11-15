// Login View
// =============

// Includes file dependencies
define([ "views/AbstractView"],
function( AbstractView ) {
    "use strict";
    
    return class PopupView extends AbstractView {        
        jqmAttributes() {
            return {'data-role': 'popup',
                    'style': 'visibility: hidden'};
        }       
        
        renderTemplate(Template, Datas) {
            $( "body" ).one( "pagecontainershow", ( event, ui ) => {
                this.$el.removeAttr('style');
                this.$el.popup().enhanceWithin();
            } );

            super.renderTemplate(Template, Datas);            
        }
        
    }
} );