$(document).ready(function() {
    var url = '/coordinator/list';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var table = $('#student-table').DataTable({
        processing: true,
        serverSide: true,
        "order": [1, 'desc'],
        "columnDefs": [
        { "width": "70px", "targets": 5 },
        { "width": "70px", "targets": 4 },
        { "width": "150px", "targets": 3 },
        { "width": "150px", "targets": 2 },
        { "width": "100px", "targets": 0 }
        ],
        ajax: {
            type: 'POST',
            url: dataurl,
            data: function(d) {
                d.status = $('#status').val()
            }
        },
        columns: [
        { data: 'id', name: 'users.id' },
        { data: 'strStudName', name: 'strStudName' },
        { data: 'counter', name: 'counter', searchable: false, orderable: false },
        { data: 'stipend', name: 'stipend', searchable: false, orderable: false },
        { data: 'checkbox', name: 'users.is_active', searchable: false },
        { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
    $('#status').change(function(){
        table.draw();
    })
    var url2 = "/coordinator/list/checkbox";
    $('#student-list').on('change', '#isActive', function() {
        var link_id = $(this).val();
        var is_active = 0;
        if ($(this).prop('checked')) {
            is_active = 1;
        }
        var formData = {
            is_active: is_active
        }
        $.ajax({
            url: url2 + '/' + link_id,
            type: "PUT",
            data: formData,
            success: function(data) {
                Pace.restart();
                if (data == "Deleted") {
                    refresh();
                }
            },
            error: function(data) {}
        });
    });
});