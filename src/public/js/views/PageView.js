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
    }

} );