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

// Callback to load admin-modal
var loadModalBody = function(title, body, size = '') {
    var $adminModal = $('#admin-modal');
    $adminModal.find('.modal-title').text(title);
    $adminModal.find('.modal-body').html(body);
    $adminModal.find('.modal-dialog').addClass(size);
}

// Modal with custom content
$('#admin-modal').on('show.bs.modal', function (e) {
  var button = $(e.relatedTarget).data('action');

  // File upload form
  if (button === 'uploadFile') {
    loadModalBody('Upload File', $('#uploadFileForm').clone().html());
  }

  // View file gallery
  if (button === 'viewFiles') {
    $.ajax({
        url: basePath + '/' + adminSegment + '/loadfiles',
        method: 'GET',
        success: function(r) {
            loadModalBody('Gallery', r, 'modal-lg');
        },
        error: function(r) {
            loadModalBody('Error', 'Failed to load files' + r);
        }
    });
  }
});

// Clear modal when hidden
$('#admin-modal').on('hidden.bs.modal', function() {
    var $modal = $(this);
    $modal.find('.modal-title').text('');
    $modal.find('.modal-body').html('');
    $modal.find('.modal-dialog').removeClass('modal-lg modal-sm modal-preview-post');
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
$('#admin-modal').on('submit', '#imageUploadForm', function(e) {
    $.ajax({
        url: basePath + '/' + adminSegment + '/uploadfile',
        method: 'POST',
        processData: false,
        contentType:false,
        data:  new FormData(this),
        success: function(r) {
            if (r.status == 1) {
                $('.modal .modal-body').slideUp(function() {
                    $(this).find('form').hide();
                    $(this).append(r.source).slideDown();
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

// Preview post
$('#save-post').on('submit', function(e) {
    // If preview was clicked, send to server via ajax, otherwise just submit
    if (document.activeElement.value === 'preview') {
        $.ajax({
            url: basePath + '/' + adminSegment + '/savepost',
            method: 'POST',
            processData: false,
            contentType:false,
            data:  new FormData(this),
            success: function(r) {
                loadModalBody('Preview', '<iframe src="about:blank" frameborder="0" width="100%" height="100%"></iframe>', 'modal-preview-post');
                $('#admin-modal').modal('show').find('.modal-body iframe').attr('srcdoc', r).on('load', function() {
                    this.style.height = this.contentDocument.body.scrollHeight +'px';
                });
            },
            error: function(r) {
                console.log('Unable to preview post')
                console.log(r)
            }
        });

        return false;
    }
});
