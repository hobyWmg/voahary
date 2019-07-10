
/** Main app js */
$(document).ready(function(){
    var deleteButton;

    $('.btn-confirm').click(function(e) {
        e.preventDefault();

        deleteButton = $(this);

        if (deleteButton.is("a")) {
            $('#confirmation-modal #confirmation-modal-confirm').attr('href', deleteButton.attr('href'));
        }

        $('#confirmation-modal').modal('show');
    });

    $('#confirmation-modal #confirmation-modal-confirm').click(function(e) {
        if (deleteButton.is("button")) {
            e.preventDefault();
            deleteButton.closest('form').submit();
        }

        if (deleteButton.is("a")) {
            window.location.href = $(this).attr('href');
        }
    });

    // $('.icheck').iCheck({
    //     checkboxClass: 'icheckbox_flat-green',
    // });
	

    
});
