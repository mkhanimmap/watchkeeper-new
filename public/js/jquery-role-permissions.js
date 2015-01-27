$('#id-field-roles').on('change', function(evt, params) {
    window.location.href = '/admin/roles/' + params.selected + '/get/permissions';
    evt.preventDefault();
 });
