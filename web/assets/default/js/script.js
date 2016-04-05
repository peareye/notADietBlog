// Archive navigation
$('.archive-navigation a').on('click', function(e) {
	$(this).siblings('.'+$(this).attr('class')).slideToggle();
	e.preventDefault();
});
