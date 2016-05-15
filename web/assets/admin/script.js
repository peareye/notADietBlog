// Delete prompt handler
$('body').on('click', '.deleteButton', function() {
  var reply = confirm('Are you sure you want to delete?');
  return reply;
});

// Validate post title URL
$('input[name="title"]').on('focusout', function() {
	if ($('input[name="url_locked"]').val() === 'Y') {
		return;
	}
	$.ajax({
		url: basePath + '/' + adminSegment + '/validateurl',
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
		url: basePath + '/' + adminSegment + '/loadfiles',
		method: 'GET',
		success: function(r) {
			$('#image-gallery-modal .modal-body').html(r);
		},
		error: function(r) {
			console.log('Load gallery images failed')
		}
	});
});

// Add focus to thumbnail path input
$('body .modal').on('click', '.thumbnail input', function() {
	$(this).select();
});

// Validate image size before up load
var url = window.URL || window.webkitURL;
$('#imageUploadForm input[name="new-image"]').bind('change', function() {
    var image, file;
    if ((file = this.files[0])) {
        var image = new Image();
        image.onload = function() {
			if (image.width > 3074 || image.height > 3074) {
				alert('The file size is greater than 3074 x 3074');
			}
	     }
        image.src = url.createObjectURL(file);
    }
});

// Upload file
$('#imageUploadForm').submit(function(e) {
	$.ajax({
		url: basePath + '/' + adminSegment + '/uploadfile',
		method: 'POST',
		processData: false,
		contentType:false,
		data:  new FormData(this),
		success: function(r) {
			if (r.status == 1) {
				$('#image-upload-modal .modal-body').slideUp(function() {
					$(this).find('form').hide();
					$(this).append(r.source).slideDown();
				});
				// Reset on hide
				$('#image-upload-modal').on('hidden.bs.modal', function() {
					$(this).find('form').show();
					$(this).find('input').val('');
					$(this).find('.thumbnail').remove();
				});
			};
		},
		error: function(r) {
			console.log('Upload file failed')
		}
	});

	return false;
});

// Modify thumbnail url width & height in modal
$('.thumbPathModal').on('focus', '.dim', function() {
	var $thumbCaption = $(this).closest('.caption');
	var aspect = parseFloat($thumbCaption.find('.aspect').val());
	var width = parseInt($thumbCaption.find('.width').val());
	var height = parseInt($thumbCaption.find('.height').val());
	var $link = $thumbCaption.find('.thumbUri');

	$(this).on('keyup', function() {
		if($(this).hasClass('width')) {
			width = parseInt($(this).val()) || width;
			height = parseInt(width / aspect) || height;
			$thumbCaption.find('.height').val(height);
		} else if ($(this).hasClass('height')) {
			width = parseInt(height * aspect) || width;
			height = parseInt($(this).val()) || height;
			$thumbCaption.find('.width').val(width);
		}

		if (isNaN(width) || isNaN(height)) {
			return;
		};

		$link.val($link.val().replace(/(\d*x\d*)\/(.*)/, width+'x'+height+'/$2'));
	});
});

// Delete file
$('body').on('submit', '.image-delete', function(e) {
	$thisThumb = $(this).closest('.thumb-gallery');
	$.ajax({
		url: basePath + '/' + adminSegment + '/deletefile',
		method: 'POST',
		processData: false,
		contentType:false,
		data:  new FormData(this),
		success: function(r) {
			if (r.status == 1) {
				// Remove thumbnail from display
				$thisThumb.remove();
			}
		},
		error: function(r) {
			console.log('Delete file failed')
		}
	});

	return false;
});