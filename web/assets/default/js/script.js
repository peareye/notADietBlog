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
// Weird: http://stackoverflow.com/a/16861927/452133
// Need to wait to get height, although this script is in the footer
$(window).load(function() {
	var commentMaxHeight = 150; // Desired eight
	var $commentReadMoreDiv = $('.comment-read-more');
	$('.comment-body').each(function(i) {
		var commentHeight = $(this).outerHeight();
		$(this).data('height', commentHeight);
		if (commentHeight >= commentMaxHeight) {
			$(this).css('height', commentMaxHeight).append($commentReadMoreDiv.clone().show());
		};
	});

	// Expand comment
	$('body').on('click', '.comment-read-more', function() {
		$(this).hide().parent('.comment-body').animate({'height':  $(this).parent('.comment-body').data('height')});
	});
});