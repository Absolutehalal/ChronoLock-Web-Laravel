// DataTable 
$(document).ready(function () {
    $('#example').DataTable({
        // scrollX: true,
        rowReorder: true,
        pagingType: 'simple_numbers',
        responsive: true,
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        stateSave: true
        // "searching": false,
    } );
    });

