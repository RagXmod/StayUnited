// Import bootstrap dependencies

export default class Ads {

    /*
     * Auto called when creating a new instance
     *
     */
    constructor() {

        this.objUri   = {};
        this.itemId   = 'null';
        this.targetTr = null;
        this.domIds = {
            page_table : '#ads-table',
            del_modal  : '#deleteModal',
            del_btn    : ':button.del_btn',
            confirm_del: '#confirm_delete'
        }

        this.delModal = jQuery(this.domIds.del_modal);

        this.loadTables();
        this.deleteModal();
        this.confirmDeletion();

    }

    loadTables() {

        jQuery(this.domIds.page_table).DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: window.dcmUri['resource'],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 25,
            columns: [
                {
                    data: 'title',
                    name: 'title',
                    title: 'Title',
                },
                {
                    data: 'identifier',
                    name: 'identifier',
                    title: 'Ads Identifier',
                },
                {
                    data: 'action',
                    name: 'action',
                    title: 'Action',
                    sortable: false,
                    searchable: false,
                    class : 'text-center'
                },
            ]
        });
    }
    deleteModal() {

        var that = this;

        jQuery(this.domIds.page_table).on('click', this.domIds.del_btn, function(evt) {

            const currentRow = jQuery(this).closest('tr');
            const title      = currentRow.attr('title');

            that.itemId   = currentRow.attr('id');       // Get record ID.
            that.targetTr = jQuery(this).parents('tr');

            that.delModal.on('shown.bs.modal', function () {

                jQuery('#itemId').html( title );
            });
            that.delModal.modal('show');
        });
    }


    confirmDeletion() {

        var that = this;
        jQuery(this.domIds.confirm_del ).on('click', function() {
            if ( that.itemId ) {
                // request delete here..
                axios.delete( window.dcmUri['resource'] + '/' + that.itemId)
                .then( data => {
                    that.delModal.on('hidden.bs.modal', function () {
                        var table = $(that.domIds.page_table).DataTable(); // Select DataTable by ID.
                        table.row(that.targetTr).remove().draw(); // Remove record from DataTable.
                    });
                    that.delModal.modal('hide');

                })
                .catch(err => {
                    const errDescription = jQuery('.description');
                    const hasError = jQuery('.hasError');

                    errDescription.addClass('d-none');
                    hasError.removeClass('d-none').html(  err.response.data.errors );

                    that.delModal.on('hidden.bs.modal', function () {
                        $('.modal-backdrop').remove();
                        errDescription.removeClass('d-none');
                        hasError.addClass('d-none')
                    });
                });
            }
        });
    }

}

// Once everything is loaded
jQuery(() => {

    // Create a new instance of Ads
   window.dcmAds = new Ads();


});
