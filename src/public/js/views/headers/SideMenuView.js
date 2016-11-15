// Login Vew
// =============

// Includes file dependencies
define(['text!templates/headers/side-menu.phtml'],
 function(Template ) {
    "use strict";
    
    return class SideMenuView extends app.HeaderView
    {
        initialize(options) {
            _.bindAll(this, "swiperight");
        }
        
        events() {
            return {"swiperight": "swiperight"}
        }
        
        setElement(element) {            
            super.setElement(element);           
            if(element instanceof $ == false) return;            
            element.find('.side-menu-open').click(this.swiperight);
        }

        swiperight() {
            this.$( "#side-menu" ).panel( "open");
        }

        // Renders all of the Category models on the UI
        render() {
            this.renderTemplate(Template);
            return this;
        }
    }
} );