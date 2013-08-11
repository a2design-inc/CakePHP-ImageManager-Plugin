/*
 * Copyright (c) 2011 Arron Bailiss <arron@arronbailiss.com>
 *
 * Permission to use, copy, modify, and distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
 * ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
 * OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */


(function($) {

    var settings = {
        'uploadUrl'	 : '/upload', // URL to POST files to
        'uploaded'       : null, // Callback function fired when files have been uploaded - defaults to methods.uploaded
        'startSending'   : null, // Callback function fired when files start go to server
        'dropClass' 	 : 'file-drop', // Default class for the drop div
        'dropHoverClass' : 'file-drop-hover', // Class applied to the drop div when dragging over it
        'defaultText'  	 : 'Drop your files here!', // Default HTML content for the drop div
        'hoverText'	 : 'Let go to upload!', // HTML content shown when hovering over the drop div
        'uploadFailed' : null, // Callback function fired when upload fails on server side
        'progressText' : 'Uploading...',
        'progressClass' : 'active',
        'keepInputName' : false
    };
    var $this = null;
    var $dropDiv = null;
    var xhr = new XMLHttpRequest();
    var formData = new FormData();

    var methods = {
        init : function(options) { // Initialises the plugin
            return $(this).each(function() {
                $this = $(this);
                if (options) $.extend(settings, options);

                // Default callback
                if (settings.uploaded === null) settings.uploaded = methods.uploaded;

                if (settings.uploadFailed === null) settings.uploadFailed = methods.uploadFailed;

                if (settings.startSending === null) settings.startSending = methods.startSending;

                if (methods.supported()) {
                    $form = $this.closest('form');
                    $form.children().css({display: 'none'});
                    $this.attr('multiple', true).bind('change.dropUpload', methods.fileChange);
                    $dropDiv = $('<div>').addClass(settings.dropClass)
                        //.html(settings.defaultText)
                        .html($('<span style="pointer-events: none;">' + settings.defaultText + '</span>'))
                        .bind('dragenter.dropUpload dragleave.dropUpload', methods.dragHover) // Bind event for dragging file over/out of the drop area
                        .bind('drop.dropUpload', methods.fileChange) // Bind event for drag+drop file selection
                        .bind('dragover.dropUpload dragstart.dropUpload dragend.dropUpload', methods.cancelEvent) // Block events
                        .bind('click', function () { $this.trigger('click'); });

                    $form.after($dropDiv);
                }
            });
        },
        supported : function() { // Checks support for functionality
            return 'draggable' in document.createElement('span') && xhr.upload;
        },
        fileChange : function(e) { // Fired when new files are selected manually or by drag+drop
            methods.dragHover(e);
            var files = e.originalEvent.target.files || e.originalEvent.dataTransfer.files;
            for (var i = 0, f; f = files[i]; i++) {
                formData.append((settings.keepInputName) ? $this.attr('name') : f.name, f); // Append each files to the form data
            }
            methods.sendFormData();
            return false;
        },
        sendFormData : function() { // Sends (POST) populated form data to the upload URL
            settings.startSending();
            $dropDiv.addClass(settings.progressClass).find('span').text(settings.progressText);
            xhr.open('POST', settings.uploadUrl, true);
            xhr.onreadystatechange = methods.xhrStateChange;
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
            formData = new FormData(); // Reset form data
        },
        xhrStateChange : function() { // Used to call the callback when appropriate
            if(xhr.readyState === 4) {
                if (xhr.status === 200) {
                    settings.uploaded(xhr.response);
                } else {
                    settings.uploadFailed(xhr.response);
                }
                $dropDiv.removeClass(settings.progressClass).find('span').text(settings.defaultText);
            }
        },
        uploaded : function(resp) { // Default callback
            console.log(resp);
        },
        uploadFailed : function(resp) {
            console.log(resp);
        },
        startSending : function(resp) { // Default callback
            console.log('startSending');
        },
        dragHover : function(e) { // Fired when a file is dragged over the drop area
            e.stopPropagation();
            e.preventDefault();

            // Add/remove class dropHoverClass to the drop area
            if($dropDiv.hasClass(settings.dropHoverClass) || e.type == 'drop' || e.type == 'change') {
                $dropDiv.removeClass(settings.dropHoverClass).find('span').text(settings.defaultText);
            } else {
                $dropDiv.addClass(settings.dropHoverClass).find('span').text(settings.hoverText);
            }
            return false;
        },
        cancelEvent : function(e) { // Function to cancel an event's default behaviour
            e.stopPropagation();
            e.preventDefault();
            return false;
        }
    };

    $.fn.dropUpload = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' +  method + ' does not exist on jQuery.dropUpload');
        }
    };
})(jQuery);