$(document).ready(function() {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  var table = $('#table').DataTable({
    responsive: true,
    processing: true,
    serverSide: true,
    ajax: {
      type: 'POST',
      url: dataurl,
      data: function(d) {
        d.status = $('#status').val()
      }
    },
    "order": [4, 'asc'],
    "columnDefs": [
    { "width": "70px", "targets": 5 }
    ],
    columns: [
    { data: 'id', name: 'users.id' },
    { data: 'strUserName', name: 'strUserName' },
    { data: 'description', name: 'schools.description' },
    { data: 'courses_description', name: 'courses.description' },
    { data: 'application_date', name: 'student_details.application_date', searchable: false },
    { data: 'action', name: 'action', orderable: false, searchable: false }
    ]
  });
  $('#status').change(function(){
    table.draw();
  })
});