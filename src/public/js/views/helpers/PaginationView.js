define(['text!templates/helpers/pagination.phtml'],
function(Template) {
    "use strict";

    return class PaginationView extends app.RenderView
    {
        initialize(pageChangeCallback) {
            this.orgel = this.$el;
            this.totalPages = 1;
            this.currentPage = 1;
            this.pageChangeCallback = pageChangeCallback;
        }

        setTotalPages(totalPages) {
            this.totalPages = totalPages;
        }

        setCurrentPage(currentPage) {
            this.currentPage = currentPage;
        }

        jqmAttributes() {
            return {'data-role': 'controlgroup',
                    'data-type': 'horizontal',
                    'data-mini': 'true'};
        }

        events() {
            return {"click #first": "first",
                    "click #back": "back",
                    "click #next": "next",
                    "click #last": "last"}
        }

        first() {
            this.currentPage = 1;

            this.pageChangeCallback(this.currentPage);
            this.updateStatus();
        }

        back() {
            this.currentPage--;

            if(this.currentPage < 1)
                this.currentPage = 1;

            this.pageChangeCallback(this.currentPage);
            this.updateStatus();
        }

        next() {
            this.currentPage++;

            if(this.currentPage > this.totalPages)
                this.currentPage = this.totalPages;

            this.pageChangeCallback(this.currentPage);
            this.updateStatus();
        }

        last() {
            this.currentPage = this.totalPages;

            this.pageChangeCallback(this.currentPage);
            this.updateStatus();
        }

        updateStatus() {
            if(this.currentPage == 1) {
                this.$('#first').hide();
                this.$('#back').hide();
            } else {
                this.$('#first').show();
                this.$('#back').show();
            }

            let i18n = this.i18n();

            this.$('#current').text(i18n.page + ' ' + this.currentPage + '/' + this.totalPages);

            if(this.currentPage == this.totalPages) {
                this.$('#last').hide();
                this.$('#next').hide();
            } else {
                this.$('#last').show();
                this.$('#next').show();
            }
        }

        render() {
            if(this.totalPages > 1) {
                this.renderTemplate(Template);
                this.updateStatus();
            }

            return this;
        }
    }
} );