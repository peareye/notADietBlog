// Archive navigation
$('.archive-navigation .archive-item').on('click', function(e) {
	e.preventDefault();
	$(this).siblings('.archive-'+$(this).data('archive')).slideToggle();
});

// Submit comment
$('#commentForm').submit(function(e) {
	$.ajax({
		url: basePath + '/savecomment',
		method: 'POST',
		processData: false,
		contentType:false,
		data:  new FormData(this),
		success: function(r) {
			console.log(r)
			if (r.status == 1) {
				$('#commentForm').parent('.blog-post').fadeOut('fast', function() {
					$(this).replaceWith(r.source).fadeIn('fast');
				});
			};
		},
		error: function(r) {
			console.log('Comment submit failed')
		}
	});

	return false;
});