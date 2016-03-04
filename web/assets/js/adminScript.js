// WYSWYG editor
$('.wysiwyg').summernote({
	height: 500
});

// Validate post title URL
$('input[name="post-title"]').on('focusout', function() {
	console.log()
	$.ajax({
		url: basePath + '/admin/api/validateurl',
		method: 'POST',
		data: {title: $(this).val()},
		success: function(r) {
			if (r.status === 'success') {
				$('.post-title-status').removeClass('glyphicon-question-sign glyphicon-exclamation-sign post-title-error').addClass('glyphicon-ok post-title-ok').parent().parent().removeClass('has-error');
				$('.post-url > var').html(r.url)
			} else if (r.status === 'fail') {
				$('.post-title-status').removeClass('glyphicon-question-sign glyphicon-ok post-title-ok').addClass('glyphicon-exclamation-sign post-title-error').parent().parent().addClass('has-error');
			}
		}
		// error: function(r) {
		// 	console.log('nope!'+r)
		// }
	});

});
