// Archive navigation
$('.archive-navigation .archive-item').on('click', function(e) {
	e.preventDefault();
	$(this).siblings('.archive-'+$(this).data('archive')).slideToggle();
});

// Submit comment
$('#post-comments').on('submit', '.comment-form', function(e) {
	var $submittedForm = $(this);
	$submittedForm.find('button').addClass('disabled').html('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Submitting...');
	$.ajax({
		url: basePath + '/savecomment',
		method: 'POST',
		processData: false,
		contentType:false,
		data:  new FormData(this),
		success: function(r) {
			console.log(r)
			if (r.status == 1) {
				$submittedForm.replaceWith(r.source).fadeIn('fast');
			};
		},
		error: function(r) {
			console.log('Comment submit failed')
		}
	});

	return false;
});

// Show comment reply
var $commentForm = $('#post-comments .comment-form');
$('#post-comments').on('click', '.comment-reply-link', function(e) {
	e.preventDefault();
	var $reply = $commentForm.clone();
	$reply.find('input[name="reply_id"]').val($(this).data('id'));
	$(this).after($reply[0].outerHTML).toggleClass('comment-reply-link cancel-reply').html('Cancel Reply');
});

// Cancel comment reply
$('#post-comments').on('click', '.cancel-reply', function(e) {
	e.preventDefault();
	$(this).siblings('.comment-form').remove();
	$(this).toggleClass('cancel-reply comment-reply-link').html('Reply');
});

$('#post-comments').on('click', '.toggle-comments', function (e) {
    var $this = $(this);
    if (!$this.hasClass('panel-collapsed')) {
        $this.closest('.panel').find('.panel-body').slideUp();
        $this.addClass('panel-collapsed');
        $this.find('i').removeClass('glyphicon-minus').addClass('glyphicon-plus');
    } else {
        $this.closest('.panel').find('.panel-body').slideDown();
        $this.removeClass('panel-collapsed');
        $this.find('i').removeClass('glyphicon-plus').addClass('glyphicon-minus');
    }
});

// Apply read more modal on long comments
// Weird: http://stackoverflow.com/a/16861927/452133
// Need to wait to get height, although this script is in the footer
// $(window).load(function() {
// 	var commentMaxHeight = 150; // Desired max height
// 	var $commentReadMoreDiv = $('.comment-read-more');
// 	var readMoreHeight = $('.comment-read-more').outerHeight();

// 	$('.comment-body').each(function(i) {
// 		var commentHeight = $(this).outerHeight();
// 		if (commentHeight >= commentMaxHeight) {
// 			$(this).data({'height': commentHeight + readMoreHeight, 'status': 'closed'})
// 			.css('height', commentMaxHeight).append($commentReadMoreDiv.clone().show());
// 		};
// 	});

// 	// Expand comment
// 	$('body').on('click', '.comment-read-more', function() {
// 		var $commentBody = $(this).parent('.comment-body');
// 		if ($commentBody.data('status') == 'closed') {
// 			$commentBody.animate({'height': $commentBody.data('height')}).data('status', 'open').find('.comment-read-more h6').html("Read less...");
// 		} else {
// 			$commentBody.animate({'height': commentMaxHeight}).data('status', 'closed').find('.comment-read-more h6').html("Read more...");
// 		}
// 	});
// });
