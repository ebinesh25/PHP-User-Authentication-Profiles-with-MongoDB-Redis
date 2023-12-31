$(document).ready(function() {
  $('#signup-form').validate({
    rules: {
      'first-name': {
        required: true
      },
      'last-name': {
        required: true
      },
      email: {
        required: true,
        email: true
      },
      password: {
        required: true,
        minlength: 7,
        strongPassword: true
      },
      'confirm-password': {
        required: true,
        equalTo: '#form3Example4'
      }
    },
    messages: {
      'first-name': {
        required: 'Please enter your first name'
      },
      'last-name': {
        required: 'Please enter your last name'
      },
      email: {
        required: 'Please enter your email address',
        email: 'Please enter a valid email address'
      },
      password: {
        required: 'Please enter a password',
        minlength: 'Password should be at least 7 characters long',
        strongPassword: 'Password should contain at least 1 symbol, uppercase letter, and number'
      },
      'confirm-password': {
        required: 'Please confirm your password',
        equalTo: 'Passwords do not match'
      }
    },
    
    submitHandler: function(form) {
      $.ajax({
        type: 'POST',
        url: 'php/register.php', // Update with the correct path to your register.php file
        data: $(form).serialize(),
        success: function(response) {
          console.log('Success:', response);
          if (response != 'Email already exists') {
                window.location.href = 'http://localhost/Guvi-login/login.html';
          } else {
            alert('Email already exists');

          }              
        }
      });
    }
  });

  $.validator.addMethod('strongPassword', function(value) {
    return /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&.]{7,}$/.test(value);
  });
});
