document.addEventListener('DOMContentLoaded', function () {

    function validate(form) {
        const firstName = form.querySelector('#first-name').value;
        const lastName = form.querySelector('#last-name').value;
        const password = form.querySelector('#password').value;
        const receptionistId = form.querySelector('#receptionist-id').value;
        const emailConfirmation = form.querySelector('#email-confirmation').checked;
        const email = form.querySelector('#email').value;
        const phone = form.querySelector('#phone').value;

        const namePattern = /^[A-Za-z\s]+$/;
        const passwordPattern = /^(?=.*\d)(?=.*[A-Z])(?=.*[@#$%^&+=.]).{8,16}$/;
        const idPattern = /^\d{4}$/;
        const phonePattern = /^\d{10}$/;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]{3,5}$/;

        if (!namePattern.test(firstName) || !namePattern.test(lastName)) {
            alert('Please enter a valid first and last name (alphabet characters only).');
            return false;
        }

        if (!passwordPattern.test(password)) {
            alert('Password must contain 1 uppercase letter, 1 special character, and be 8-16 characters long.');
            return false;
        }

        if (!idPattern.test(receptionistId)) {
            alert('Receptionist ID must be a 4-digit number.');
            return false;
        }

        if (!phonePattern.test(phone.replace(/\D/g, ''))) {
            alert('Phone number must be exactly 10 digits with no spaces or dashes.');
            return false;
        }

        if (emailConfirmation && !emailPattern.test(email)) {
            alert('Please enter a valid email address.');
            return false;
        }

        return true;
    }

    const loginForm = document.querySelector('#login-form');
    loginForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const isValid = validate(loginForm);

        if (isValid) {
            const formData = new FormData(loginForm);
            formData.append('action', 'verify_login');
            
            fetch('verify_login.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.status === 'success') {
                    alert('Verification successful. ' + data.message);
                    
                    // Check if there's a redirect value in the JSON response
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    alert('Verification failed. ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });

    const resetButton = document.querySelector('#reset-button');
    resetButton.addEventListener('click', function () {
        loginForm.reset();
    });

});
