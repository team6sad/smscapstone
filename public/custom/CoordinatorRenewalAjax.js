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
		ajax: dataurl,
		columns: [
		{ data: 'strStudName', name: 'strStudName' },
		{ data: 'failed', name: 'failed', orderable: false, searchable: false },
		{ data: 'action', name: 'action', orderable: false, searchable: false }
		]
	});
	$('#isActive').change(function() {
		var is_active = 1;
		if ($(this).prop('checked')) {
			var is_active = 0;
		}
		var formData = {
			is_active: is_active
		}
		$.ajax({
			url: url,
			type: "POST",
			data: formData,
			success: function(data) {
				Pace.restart();
				if (data.renewal_status == 0) {
					$('.callout').removeClass().addClass('callout callout-danger');
					$('h5').text('Renewal Phase Closed');
				} else {
					$('.callout').removeClass().addClass('callout callout-success');
					$('h5').text('Renewal Phase Ongoing');
				}
			},
			error: function(data) {}
		});
	});
});