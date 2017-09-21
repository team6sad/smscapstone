$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var url = "/admin/credit";
    var id = '';
    var table = $('#table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: dataurl,
        "columnDefs": [
        { "width": "130px", "targets": 4 },
        { "width": "70px", "targets": 3 },
        { "width": "70px", "targets": 2 }
        ],
        columns: [
        { data: 'schools_description', name: 'schools.description' },
        { data: 'courses_description', name: 'courses.description' },
        { data: 'year', name: 'school_course.year' },
        { data: 'semester', name: 'school_course.semester' },
        { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
    $('#add_course').on('hide.bs.modal', function() {
        $('#frm').parsley().destroy();
        $('#frm').trigger("reset");
    });
    function refresh() {
        swal({
            title: "Record Deleted!",
            type: "warning",
            text: "<center>Refresh Records?</center>",
            html: true,
            showCancelButton: true,
            confirmButtonClass: "btn-success",
            confirmButtonText: "Refresh",
            cancelButtonText: "Cancel",
            closeOnConfirm: true,
            allowOutsideClick: true,
            closeOnCancel: true
        },
        function(isConfirm) {
            if (isConfirm) {
                table.draw();
            }
        });
    }
    //display modal form for task editing
    $('#list').on('click', '.open-modal', function() {
        var link_id = $(this).val();
        id = link_id;
        $.get(url + '/' + link_id + '/edit', function(data) {
            if (data == "Deleted") {
                refresh();
            } else {
                var textToFind1 = data.schools_description;
                var dd1 = document.getElementById('school_id');
                for (var i = 0; i < dd1.options.length; i++) {
                    if (dd1.options[i].text === textToFind1) {
                        dd1.selectedIndex = i;
                        break;
                    }
                }
                var textToFind2 = data.courses_description;
                var dd2 = document.getElementById('course_id');
                for (var i = 0; i < dd2.options.length; i++) {
                    if (dd2.options[i].text === textToFind2) {
                        dd2.selectedIndex = i;
                        break;
                    }
                }
                var textToFind3 = data.year;
                var dd3 = document.getElementById('year');
                for (var i = 0; i < dd3.options.length; i++) {
                    if (dd3.options[i].text === textToFind3) {
                        dd3.selectedIndex = i;
                        break;
                    }
                }
                var textToFind4 = data.semester;
                var dd4 = document.getElementById('semester');
                for (var i = 0; i < dd4.options.length; i++) {
                    if (dd4.options[i].text === textToFind4) {
                        dd4.selectedIndex = i;
                        break;
                    }
                }
                $('h4').text('Edit Credit');
                $('#btn-save').val("update");
                $('#add_course').modal('show');
            }
        })
    });
    //display modal form for creating new task
    $('#btn-add').click(function() {
        $('h4').text('Add Credit');
        $('#btn-save').val("add");
        $('#frm').trigger("reset");
        $('#add_course').modal('show');
    });
    //delete task and remove it from list
    $('#list').on('click', '.btn-delete', function() {
        var link_id = $(this).val();
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
            closeOnConfirm: false,
            allowOutsideClick: true,
            showLoaderOnConfirm: true,
            closeOnCancel: true
        },
        function(isConfirm) {
            setTimeout(function() {
                if (isConfirm) {
                    $.ajax({
                        url: url + '/' + link_id,
                        type: "DELETE",
                        success: function(data) {
                            if (data == "Deleted") {
                                refresh();
                            } else {
                                if (data[0] == "true") {
                                    swal({
                                        title: "Failed!",
                                        text: "<center>Data in use</center>",
                                        type: "error",
                                        showConfirmButton: false,
                                        allowOutsideClick: true,
                                        html: true
                                    });
                                } else {
                                    table.draw();
                                    swal({
                                        title: "Deleted!",
                                        text: "<center>Data Deleted</center>",
                                        type: "success",
                                        timer: 1000,
                                        showConfirmButton: false,
                                        html: true
                                    });
                                }
                            }
                        },
                        error: function(data) {
                        }
                    });
                }
            }, 500);
        });
    });
    //create new task / update existing task
    $("#btn-save").click(function() {
        $('#frm').parsley().destroy();
        if ($('#frm').parsley().isValid()) {
            $("#btn-save").attr('disabled', 'disabled');
            setTimeout(function() {
                $("#btn-save").removeAttr('disabled');
            }, 1000);
            var formData = {
                school_id: $('#school_id').val(),
                course_id: $('#course_id').val(),
                year: $('#year').val(),
                semester: $('#semester').val()
            }
                //used to determine the http verb to use [add=POST], [update=PUT]
                var state = $('#btn-save').val();
            var type = "POST"; //for creating new resource
            var my_url = url;
            if (state == "update") {
                type = "PUT"; //for updating existing resource
                my_url += '/' + id;
            }
            $.ajax({
                type: type,
                url: my_url,
                data: formData,
                dataType: 'json',
                success: function(data) {
                    $('#add_course').modal('hide');
                    table.draw();
                    swal({
                        title: "Success!",
                        text: "<center>Data Stored</center>",
                        type: "success",
                        timer: 1000,
                        showConfirmButton: false,
                        html: true
                    });
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
    });
});
