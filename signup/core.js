function checkEmail(){
    $.ajax({
        url: '../includes/checkEmail.php',
        type: 'POST',
        data: {
            email: $('.email-input').val()
        },
        success: function(response) {
            //If the email exists
            if (response === 'e') {
                $('#emailError').html('<li class="small">This email is already in use.</li>');
            } else {
                $('#emailError').html('');
            }
        }
    });
}
function checkPassword(){
    let value = $('.password-input').val();
    if (value.length < 8) {
        $('#passError').html('<li class="small">The password must be at least 8 characters long.</li>');
    } else {
        $('#passError').html('');
    }
}

$('.email-input').on('focusout', function() {
    checkEmail();
});

$('.password-input').on('focusout', function() {
    checkPassword();
});