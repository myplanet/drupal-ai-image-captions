(function($) {
  /**
   *
   */
  Drupal.behaviors.imageCaptioning = {
    attach: function (context, settings) {
      $('div[class$="ibm-select-option"]').addClass('hidden')
      /**
       *
       */
      $('input.ibm-select-button', context).once('generate-image-captioning-click').on('click', function(e){
        e.preventDefault();
        var obj = this;
        var fid = $(obj).data('fid');
        var use_probability = $(obj).data('use-probability') || 0;
        var throbber = $(obj).parent().find('.throbber');
        var parent_fieldset = $(obj).parents('.fieldset-wrapper');
        var selectbox = $(parent_fieldset).find('select');
        var form_item = $(selectbox).parent('.form-item');

        $(throbber).removeClass('hidden');
        $(form_item).addClass('hidden');

        $.ajax({
          url: "/image_captioning?fid=" + fid + '&use_probability=' + use_probability,
          method: "GET",
          headers: {
            "Content-Type": "application/json"
          },
          success: function(response, status, xhr) {
            if (response.data) {

              $(selectbox).find('option').remove();
              $(selectbox).append('<option value="0">' + Drupal.t('Please select one of the options') + '</option>');
              for (var key in response.data) {
                $(selectbox).append('<option value="0" data-text="' + response.data[key] + '">' + response.data[key] + '</option>');
              }
              $(throbber).addClass('hidden');
              $(selectbox).removeClass('hidden');
              $(form_item).removeClass('hidden');
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            $(throbber).addClass('hidden');
            $(form_item).addClass('hidden');
          }
        })
      });

      /**
       *
       */
      $('select.ibm-select-option', context).once('change-auto-suggestion').on('change', function(){
          var value = $(this).find(':selected').data('text');
          console.log(value);
          if (value != "") {
            var parent = $(this).parents('div.image-widget-data');
            var alt_field = $(parent).find('input[name$="[alt]"]');
            if (alt_field.length) {
              alt_field.val(value);
            }
          }
      });
    }
  };

})(jQuery);
