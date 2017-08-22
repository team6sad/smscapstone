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
        { "width": "70px", "targets": 4 },
        { "width": "70px", "targets": 3 }
        ],
        ajax: {
            type: 'POST',
            url: dataurl,
            data: function(d) {
                d.strUserFirstName = $('#strUserFirstName').val(),
                d.strUserMiddleName = $('#strUserMiddleName').val(),
                d.strUserLastName = $('#strUserLastName').val(),
                d.intDistID = $('#intDistID').val(),
                d.intCounID = $('#intCounID').val(),
                d.intBaraID = $('#intBaraID').val(),
                d.intBatcID = $('#intBatcID').val(),
                d.strPersStreet = $('#strPersStreet').val(),
                d.strPersReligion = $('#strPersReligion').val()
            }
        },
        columns: [
        { data: 'id', name: 'users.id' },
        { data: 'strStudName', name: 'strStudName' },
        { data: 'application_date', name: 'student_details.application_date', searchable: false },
        { data: 'checkbox', name: 'users.is_active', searchable: false },
        { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
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
    $('body').on('change', '#student_status', function() {
        var link_id = $(this).attr('selectedbox');
        var thisbox = $(this);
        var formData = {
            student_status: $(this).val()
        }
        $.ajax({
            url: dataurl + '/' + link_id,
            type: "PUT",
            data: formData,
            dataType: 'json',
            success: function(data) {
                Pace.restart();
                if (data.student_status == 'Continuing')
                    thisbox.attr('class', 'btn btn-md btn-flat btn-warning');
                else if (data.student_status == 'Graduated')
                    thisbox.attr('class', 'btn btn-md btn-flat btn-success');
                else
                    thisbox.attr('class', 'btn btn-md btn-flat btn-danger');
            },
            error: function(data) {}
        });
    });
    $('#btn-advSearch').on('click', function(e) {
        table.draw();
        e.preventDefault();
        $('#frmAdv').trigger("reset");
        $('#advanced_search').modal('hide')
    });
    $('#advanced_search').on('hide.bs.modal', function() {
        $('#frmAdv').trigger("reset");
    });
    $('#student-list').on('click', '.open-modal', function() {
        var link_id = $(this).val();
        $.get(url + '/' + link_id, function(data) {
            var gender = 'Male';
            if (data.gender) {
                gender = 'Female';
            }
            var modalbody = "<div class='row'>" +
            "<div class='col-sm-5'>" +
            "<div class='form-group'><label>Name:</label><br>" + data.strStudName + "</div>" +
            "<div class='form-group'><label>E-mail Address:</label><br>" + data.email + "</div>" +
            "<div class='form-group'><label>Contact Number:</label><br>" + data.cell_no + "</div>" +
            "<div class='form-group'><label>Gender:</label><br>" + gender + "</div>" +
            "<div class='form-group'><label>Birthday:</label><br>" + data.date + "</div>" +
            "<div class='form-group'><label>Address:</label><br>" + data.house_no + " " + data.street +
            " " + data.barangay + " " + data.district + "</div></div>" +
            "<div class='col-sm-5'>" +
            "<div class='form-group'><label>Course:</label><br>" + data.course + "</div>" +
            "<div class='form-group'><label>Religion:</label><br>" + data.religion + "</div>" +
            "<div class='form-group'><label>School:</label><br>" + data.school + "</div></div>" +
            "<div class='col-sm-2'><img src='" + asset + "/" + data.picture +
            "' class='profile-user-img img-responsive img-square' alt='User Image'>" +
            "</div></div>";
            bootbox.alert({
                title: "Student Information",
                message: modalbody,
                backdrop: true,
                size: 'large',
                buttons: {
                    ok: {
                        label: 'Ok',
                        className: 'btn-success btn-md'
                    }
                }
            });
            var selected1 = '';
            var selected2 = '';
            var selected3 = '';
            var color = '';
            if (data.student_status == 'Continuing') {
                color = 'warning';
                selected1 = 'selected';
            } else if (data.student_status == 'Graduated') {
                color = 'success';
                selected2 = 'selected';
            } else {
                color = 'danger';
                selected3 = 'selected';
            }
            var footer = "<select id='student_status' selectedbox = " + data.id + " class='btn btn-md btn-flat btn-" + color + "' name='student_status'>" +
            "<option value = 'Continuing' class = 'btn-warning' " + selected1 + " >Continuing</option> " +
            "<option value = 'Graduated' class = 'btn-success' " + selected2 + " >Graduated</option> " +
            "<option value = 'Forfeit' class = 'btn-danger' " + selected3 + ">Forfeit</option></select> " +
            "<button data-bb-handler='ok' type='button' class='btn btn-success btn-md'>Ok</button>";
            $('.modal-footer').empty().append(footer);
        });
    });
    $('#advsearch').click(function() {
        $('#advanced_search').modal('show');
    });
});