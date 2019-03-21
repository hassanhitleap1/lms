$(function () {
    // Exportable table
    var table = $('.js-exportable').DataTable( {
        // buttons: {
        //             dom: {
        //                 button: {
        //                     tag: 'a',
        //                     className: 'waves-effect waves-block',
        //                     extend: ['print', 'pdf', 'excel', 'copy']
        //                 },
        //                 buttonLiner: {
        //                     tag: null
        //                 }
        //             }
        //         },

        buttons: [
            {
                tag: 'a',

                text:'<i class="material-icons">local_printshop</i> Print',
                className:'waves-effect waves-block hide-last-col',
                extend:'print'


            },
            {
                tag: 'a',
                className:'waves-effect waves-block hide-last-col',
                extend:'copyHtml5',
                text:'<i class="material-icons">content_copy</i> Copy'
            },
            {
                tag: 'a',
                className:'waves-effect waves-block hide-last-col',
                extend:'excelHtml5', footer: true,
                text:'<i style="width: 17px;margin-top: -1px;" class="material-icons">Excel</i>Excel'
            },
            {
                tag: 'a',
                className:'waves-effect waves-block hide-last-col',
                extend:'pdfHtml5',
                text:'<i class="material-icons">picture_as_pdf</i> pdf'
            }
        ],
        buttonLiner: {
            tag: null
        },
        searching: false,
        paging:   false,
        ordering: false,
        info:     false
    } );

    table
        .buttons()
        .container()
        .appendTo( '.header-dropdown:first-child .dropdown-menu' );

    // table
    //     .buttons()
    //     .container()
    //     .click(function () {
    //     $(".js-exportable tr th:last-child").hide();
    //     $(".js-exportable tr td:last-child").hide();
    // })
});

