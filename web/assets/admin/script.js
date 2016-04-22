// Delete prompt handler
$('.deleteButton').on('click', function() {
  var reply = confirm('Are you sure you want to delete?');
  return reply;
});

// Validate post title URL
$('input[name="title"]').on('focusout', function() {
	if ($('input[name="url_locked"]').val() === 'Y') {
		return;
	}
	$.ajax({
		url: basePath + adminSegment + '/validateurl',
		method: 'POST',
		data: {title: $(this).val()},
		success: function(r) {
			if (r.status === 'success') {
				$('.post-title-status').removeClass('glyphicon-question-sign glyphicon-exclamation-sign post-title-error').addClass('glyphicon-ok post-title-ok').parent().parent().removeClass('has-error');
				$('.post-url var').html(r.url);
				$('input[name="url"]').val(r.url);
			} else if (r.status === 'fail') {
				$('.post-title-status').removeClass('glyphicon-question-sign glyphicon-ok post-title-ok').addClass('glyphicon-exclamation-sign post-title-error').parent().parent().addClass('has-error');
			}
		},
		error: function(r) {
			console.log('Validate URL failed')
		}
	});
});

// Toggle URL lock
$('.post-url span.glyphicon').on('click', function(me) {
	if ($('input[name="url_locked"]').val() === 'N') {
		// Lock
		$('input[name="url_locked"]').val('Y');
		$(this).removeClass('glyphicon-unchecked').addClass('glyphicon-lock');
	} else {
		$('input[name="url_locked"]').val('N');
		$(this).addClass('glyphicon-unchecked').removeClass('glyphicon-lock');
	}
});

// Load gallery images on modal open
$('#image-gallery-modal').on('show.bs.modal', function (e) {
	$.ajax({
		url: basePath + adminSegment + '/loadimages',
		method: 'GET',
		success: function(r) {
			$('#image-gallery-modal .modal-body').html(r);
			// Autofocus gallery input field
			$('input').on('click', function() {
				$(this).select();
			});
		},
		error: function(r) {
			console.log('Load gallery images failed')
		}
	});
});

// Validate image size before up load
$('#imageUploadForm input[name="new-image"]').bind('change', function() {
	if (this.files[0].size > 4000000) {
		alert('The file size is greater than 4mb');
	};
});

// Upload image
$('#imageUploadForm').submit(function(e) {
	$.ajax({
		url: basePath + adminSegment + '/uploadimage',
		method: 'POST',
		processData: false,
		contentType:false,
		data:  new FormData(this),
		success: function(r) {
			$('#image-upload-modal').modal('hide')
			$('#imageUploadForm').find('input').val('');
		},
		error: function(r) {
			console.log('Upload image failed')
		}
	});

	return false;
});

// Modify thumbnail link width
$('.thumbPathModal').on('keyup', '.width', function() {
	var width = $(this).val();
	var $link = $(this).closest('.caption').find('.gallery-image-path');
	$link.val($link.val().replace(/(.*)thumbs\/\d*x(.*)/, '$1thumbs/'+width+'x$2'));
});

// // Modify thumbnail link height
$('#image-gallery-modal').on('keyup', '.height', function() {
	var height = $(this).val();
	var $link = $(this).closest('.caption').find('.gallery-image-path');
	$link.val($link.val().replace(/(.*)thumbs\/(\d*)x\d*\/(.*)/, '$1thumbs/$2x'+height+'/$3'));
});
