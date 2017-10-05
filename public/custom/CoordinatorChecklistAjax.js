$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var url = '/coordinator/';
    $('#table-school').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: dataurlschool,
        columns: [
        { data: 'abbreviation', name: 'abbreviation' },
        { data: 'description', name: 'description' },
        { data: 'is_active', name: 'is_active', searchable: false, orderable: false }
        ]
    });
    $('#list-school').on('change', '#isActive', function() {
        var link_id = $(this).val();
        var is_active = 0;
        if ($(this).prop('checked')) {
            is_active = 1;
        }
        var formData = {
            is_active: is_active
        }
        $.ajax({
            url: url + 'school/checkbox/' + link_id,
            type: "PUT",
            data: formData,
            success: function(data) {
                Pace.restart();
            },
            error: function(data) {
            }
        });
    });
    $('#table-course').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: dataurlcourse,
        columns: [
        { data: 'abbreviation', name: 'abbreviation' },
        { data: 'description', name: 'description' },
        { data: 'is_active', name: 'is_active', searchable: false, orderable: false }
        ]
    });
    $('#list-course').on('change', '#isActive', function() {
        var link_id = $(this).val();
        var is_active = 0;
        if ($(this).prop('checked')) {
            is_active = 1;
        }
        var formData = {
            is_active: is_active
        }
        $.ajax({
            url: url + 'course/checkbox/' + link_id,
            type: "PUT",
            data: formData,
            success: function(data) {
                Pace.restart();
            },
            error: function(data) {
            }
        });
    });
    $('#table-claiming').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: dataurlclaiming,
        columns: [
        { data: 'description', name: 'description' },
        { data: 'is_active', name: 'is_active', searchable: false, orderable: false }
        ]
    });
    $('#list-claiming').on('change', '#isActive', function() {
        var link_id = $(this).val();
        var is_active = 0;
        if ($(this).prop('checked')) {
            is_active = 1;
        }
        var formData = {
            is_active: is_active
        }
        $.ajax({
            url: url + 'claiming/checkbox/' + link_id,
            type: "PUT",
            data: formData,
            success: function(data) {
                Pace.restart();
            },
            error: function(data) {}
        });
    });
    $('li').click(function() {
        $('.btn-xs.android').attr('style', 'width: 72px;');
    });
});
