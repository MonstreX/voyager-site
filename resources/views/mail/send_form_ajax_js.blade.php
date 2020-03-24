<script>
    var formSendAJAX = function (event, el) {
        event.preventDefault();

        var form_submit = $(el);

        var form_holder_id = '#' + form_submit.data('holder-id');
        var form = $(form_holder_id +' form')[0];

        var el_loader = $(form_holder_id + ' .btn-loader');
        var el_title_normal = $(form_holder_id + ' .btn-title-normal');
        var el_title_sending = $(form_holder_id + ' .btn-title-sending');
        var el_message = $(form_holder_id + ' .form-message');
        var el_button = $(form_holder_id + ' .btn-send-form');

        if (el_loader.hasClass('hidden')) {

            el_loader.removeClass('hidden');
            el_title_normal.addClass('hidden');
            el_title_sending.removeClass('hidden');
            el_button.attr("disabled", true);

            $.ajax( {
                url: $(form_holder_id + ' form').attr("action"),
                type: 'POST',
                data: new FormData(form),
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                success: function (data) {
                    el_loader.addClass('hidden');
                    el_title_normal.removeClass('hidden');
                    el_title_sending.addClass('hidden');

                    if (data.type !== 'success') {
                        el_button.attr("disabled", false);
                    }

                    el_message.addClass(data.type).fadeIn().html(data.messages);
                },
                error: function (e) {
                    el_loader.addClass('hidden');
                    el_title_normal.removeClass('hidden');
                    el_title_sending.addClass('hidden');
                    el_button.attr("disabled", false);

                    el_message.fadeIn().addClass('error').html(e.responseText);
                    setTimeout(function(){ el_message.fadeOut(); }, 3000);
                }
            });
        }
    }
</script>

