// Login View
// =============

// Includes file dependencies
define([ "views/AbstractView"],
function( AbstractView ) {
    "use strict";
    
    return class PopupView extends AbstractView {        
        jqmAttributes() {
            return {'data-role': 'popup'};
        }       
    }
} );