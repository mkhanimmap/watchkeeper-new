$('#classification_group').on('change', function(evt, params) {
    window.location.href = window.location.href.replace( /[\?#].*|$/, "?category=" + params.selected );
    evt.preventDefault();
 });
