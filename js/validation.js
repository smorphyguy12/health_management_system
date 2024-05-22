 document.querySelector('form').addEventListener('submit', function(event) {

    //Validation handler select services
    var errorMessage = document.getElementById('error-message-role');
    var selectedOption = document.getElementById('role').value;

    if (selectedOption === 'select service') {
        errorMessage.textContent = 'Please select a service.';
        event.preventDefault();
    } else {
        errorMessage.textContent = '';
    }

    //Validation handler password
    var errorMessagepass = document.getElementById('error-message-pass');
    var passwordInput = document.getElementById('password').value;

    if (passwordInput.length < 8 ) {
        errorMessagepass.textContent = 'Password must 8 letters above.';
        event.preventDefault();
    } else {
        errorMessagepass.textContent = '';
    }

    //Validation handler confirm password
    var errorMessageconfirmpass = document.getElementById('error-message-confirm-pass');
    var confirmPass = document.getElementById('confirm_pass').value;
    
    if (confirmPass !== passwordInput) {
        errorMessageconfirmpass.textContent = 'The password didn\'t match. Please try again';
        event.preventDefault();
    } else {
        errorMessageconfirmpass.textContent = '';
    }


})