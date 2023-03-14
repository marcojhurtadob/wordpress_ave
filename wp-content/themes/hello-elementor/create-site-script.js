jQuery(document).ready(function($) {
  $('body').on('click', '.create-site-button', function(e) {
    e.preventDefault();

    var userId = $(this).data('user-id');
    var siteName = prompt('Enter site name');

    if (siteName === null) {
      return;
    }

    var data = {
      action: 'create_site_for_user_with_active_subscription',
      nonce: create_site_ajax_object.nonce,
      user_id: userId,
      site_name: siteName,
    };

    $.post(create_site_ajax_object.ajax_url, data, function(response) {
      alert(response.data.message);
    }).fail(function(response) {
      alert(response.responseJSON.data.message);
    });
  });
});
