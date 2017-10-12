$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('li').click(function() {
        $('.btn-sm.android').attr('style', 'width: 88px;');
    });
    $('#student_status').change(function(){
        postStatus();
    })
    $('#isActive').change(function() {
        postStatus();
    })
    function postStatus() {
        var is_active = 0;
        if ($('#isActive').prop('checked')) {
            is_active = 1;
        }
        var formData = {
            is_active: is_active,
            student_status: $('#student_status').val()
        }
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            success: function(data) {
                Pace.restart();
                $('.student_status').text($('#student_status').val());
            },
            error: function(data) {
                $.notify({
                    icon: 'fa fa-warning',
                    message: data.responseText.replace(/['"]+/g, '')
                }, {
                    type: 'warning',
                    z_index: 2000,
                    delay: 5000,
                });
            }
        });
    }
    $('#table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: dataurl,
        "columnDefs": [
        { "width": "70px", "targets": 3 }
        ],
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            $('td:eq(1)', nRow).addClass( "text-right" );
        },
        columns: [
        { data: 'id', name: 'id' },
        { data: 'total', name: 'total', orderable: false, searchable: false },
        { data: 'date_claimed', name: 'date_claimed'},
        { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});