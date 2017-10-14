$(document).ready(function() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	var url = '/coordinator/renewal';
	var current = '';
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
		"columnDefs": [
		{ "width": "200px", "targets": 4 },
		{ "width": "130px", "targets": 3 },
		{ "width": "130px", "targets": 2 }
		],
		columns: [
		{ data: 'id', name: 'users.id' },
		{ data: 'strStudName', name: 'strStudName' },
		{ data: 'withdraw', name: 'withdraw', orderable: false, searchable: false },
		{ data: 'drop', name: 'drop', orderable: false, searchable: false },
		{ data: 'action', name: 'action', orderable: false, searchable: false }
		]
	});
	$('#list').on('click', '.btn-accept', function() {
		current = $(this).val();
		swal({
			title: "Are you sure?",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-success",
			confirmButtonText: "Accept",
			cancelButtonText: "Cancel",
			closeOnConfirm: false,
			allowOutsideClick: true,
			showLoaderOnConfirm: true,
			closeOnCancel: true
		},
		function(isConfirm) {
			setTimeout(function() {
				if (isConfirm) {
					$.get(url + '/accept/' + current, function(data) {
						table.draw();
						swal({
							title: "Accepted!",
							text: "<center>Student Accepted</center>",
							type: "success",
							timer: 1000,
							showConfirmButton: false,
							html: true
						});
					}).fail(function(data) {
						swal({
							title: "Failed!",
							text: "<center>No Available Slot</center>",
							type: "error",
							confirmButtonClass: "btn-success",
							showConfirmButton: true,
							html: true
						});
					});
					$.get('/coordinator/budget/getlatest', function(data){
						$('.slot').text(data.slot_count);
						$('.budget').text(data.amount);
					});
				}
			}, 500);
		});
	});
	$('#list').on('click', '.btn-decline', function() {
		$('#btn-save').val($(this).val());
		$('#frm').trigger("reset");
		$('#message').modal('show');
	});
	$('#message').on('hide.bs.modal', function() {
		$('#frm').parsley().destroy();
		$('#frm').trigger("reset");
	});
	$("#btn-save").click(function() {
		$('#frm').parsley().destroy();
		if ($('#frm').parsley().isValid()) {
			$("#btn-save").attr('disabled', 'disabled');
			setTimeout(function() {
				$("#btn-save").removeAttr('disabled');
			}, 1000);
			var formData = {
				title: $('#title').val(),
				description: $('#description').val()
			}
            var type = "POST"; //for creating new resource
            var my_url = url + '/decline/' + $("#btn-save").val();
            $.ajax({
            	type: type,
            	url: my_url,
            	data: formData,
            	dataType: 'json',
            	success: function(data) {
            		$('#message').modal('hide');
            		table.draw();
            		swal({
            			title: "Declined!",
            			text: "<center>Student Declined</center>",
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
	$('#status').change(function(){
		table.draw();
	});
	$('#cri').click(function(){
		$('#criteria').modal('show');
	});
});