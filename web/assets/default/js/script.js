// Archive navigation
$('.archive-navigation .archive-item').on('click', function(e) {
	e.preventDefault();
	$(this).siblings('.archive-'+$(this).data('archive')).slideToggle();
});

// Submit comment
$('#commentForm').submit(function(e) {
	$(this).find('button').addClass('disabled');
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

// Apply read more modal on long comments
var $commentReadMore = $('.comment-read-more')[0];
var commentMaxHeight = parseInt($('.comment-body').css('max-height')) - 30;
$('.comment-body').each(function(i) {
	if ($(this).height() >= commentMaxHeight) {
		$(this).append($commentReadMore).find('.comment-read-more').show();
	};
});

// Expand comment
$('body').on('click', '.comment-read-more', function() {
	$(this).hide().parent('.comment-body').css('max-height', 'none');
});
