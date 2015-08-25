$(function () {

    image_manager.init();

});

var image_manager = {
    add_button: function () {
        return $('<button class="btn js-admin_get_image_manager">Add Image</button>');
    },
    thumb: function ($thumb) {
        var $insertedThumb = $thumb.clone();
        var id = $insertedThumb.data('id');
        $insertedThumb.addClass('inserted');
        return $insertedThumb;
    },
    modal: function () {
        return $('' +
            '<div class="js-admin_image_manager modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">' +
            '<div class="modal-dialog" role="document">' +
            '<div class="modal-content">' +
            '<div class="modal-header">' +
            '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            '<h4 class="modal-title" id="myModalLabel">Choose an Image</h4>' +
            '</div>' +
            '<div class="modal-body"></div>' +
            ' <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' +
            '</div> </div> </div> </div>' +
            '');

    },
    current_initializer: null,
    current_target: null,
    limit: null,
    current_manager: null,
    curr_number: null,
    init: function () {
        $('input[type="file"].js-image_upload').dropUpload(dropUploadDefaults);

        $('.js-admin_images_container').find('.js-admin_image').addClass('inserted');

        image_manager.add_listeners();

        $(document).on('click', '.js-admin_image_manager .js-admin_image', function () {
            var $thumb = $(this);
            var $selected = image_manager.thumb($thumb);

            if (image_manager.limit == 1) {
                image_manager.current_initializer.hide();
                var fieldId = "#"+ image_manager.current_initializer.data('field-id');
                $(fieldId).val($thumb.data('id'));
                $selected.find('input').remove();
            }

            image_manager.current_target.append($selected);

            image_manager.current_manager.modal('hide');
        });
    },
    get_manager: function () {
        if (image_manager.current_manager != null) {
            image_manager.current_manager.modal('show');
            return;
        }

        image_manager.current_manager = image_manager.modal();

        $.ajax({
            type: 'get',
            url: imagesUrlBase + '/?type=modal',
            success: function (data) {
                image_manager.current_manager.find('.modal-body').append(data);
                image_manager.current_manager.find('input[type="file"]').dropUpload(dropUploadDefaults);
            }
        });

        image_manager.current_manager.modal('show');
    },
    add_listeners: function () {
        $(document).on('click', '.js-admin_get_image_manager', function () {
            image_manager.current_initializer = $(this);
            image_manager.current_target = $(this).next();
            image_manager.limit = $(this).data('limit');

            image_manager.get_manager();
            return false;
        });

        $(document).on('click', '.js-admin_image_delete', function () {
            var url = $(this).attr('href');
            var $thumb = $(this).closest('.js-admin_image');
            image_manager.current_initializer = $(this).closest('.controls').find(".js-admin_get_image_manager");
            var fieldId = "#"+ image_manager.current_initializer.data('field-id');

            if ($thumb.hasClass('disabled')) {
                return false;
            }

            if ($thumb.hasClass('inserted')) {
                image_manager.current_initializer.show();
                $(fieldId).val('');
                $thumb.remove();

                return false;
            }

            if (!confirm('Are you sure?')) {
                return false;
            }

            $thumb.addClass('disabled');

            $.ajax({
                type: 'post',
                url: url,
                success: function (data) {
                    $thumb.hide();
                }
            });

            $thumb.removeClass('disabled');
            return false;
        });
    }
};

var dropUploadDefaults = {
    defaultText: 'Drag`n`Drop or click here to upload images',
    uploadUrl: imagesUrlBase + '/add',
    uploaded: function (data) {
        $('.js-image_manager_images_list').prepend($(data));
        image_manager.add_listeners();
    },
    uploadFailed: function (data) {
        alert('This file cannot be uploaded');
    },
    keepInputName: true
};