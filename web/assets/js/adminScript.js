// WYSWYG editor
$('.wysiwyg').summernote({
	height: 500
});

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
		url: basePath + adminSegment + '/api/validateurl',
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
			console.log('nope!'+r)
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