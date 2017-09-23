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
		ajax: dataurl,
		"columnDefs": [
		{ "width": "200px", "targets": 2 },
		{ "width": "130px", "targets": 1 }
		],
		columns: [
		{ data: 'strStudName', name: 'strStudName' },
		{ data: 'failed', name: 'failed', orderable: false, searchable: false },
		{ data: 'action', name: 'action', orderable: false, searchable: false }
		]
	});
	$('#list').on('click', '.btn-accept', function(e) {
		e.preventDefault();
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
					});
					$.get('/coordinator/budget/getlatest', function(data){
						$('.slot').text(data.slot_count);
						$('.budget').text(data.amount);
					});
				}
			}, 500);
		});
	});
	$('#list').on('click', '.btn-decline', function(e) {
		e.preventDefault();
		current = $(this).val();
		swal({
			title: "Are you sure?",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Decline",
			cancelButtonText: "Cancel",
			closeOnConfirm: false,
			allowOutsideClick: true,
			showLoaderOnConfirm: true,
			closeOnCancel: true
		},
		function(isConfirm) {
			setTimeout(function() {
				if (isConfirm) {
					$.get(url + '/decline/' + current, function(data) {
						table.draw();
						swal({
							title: "Declined!",
							text: "<center>Student Declined</center>",
							type: "success",
							timer: 1000,
							showConfirmButton: false,
							html: true
						});
					});
				}
			}, 500);
		});
	});
});