$(function(){
    $(document).on('focus', 'input[data-plugin-name="datetimepicker"]', function(){
        $(this).blur();
    });
});