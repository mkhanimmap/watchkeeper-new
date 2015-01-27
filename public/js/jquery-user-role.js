$('#id-field-users').on('change', function(evt, params) {
    window.location.href = '/admin/users/' + params.selected + '/get/role';
    evt.preventDefault();
 });
