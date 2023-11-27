$(document).ready(function(){
    $('#login-form').validate({
        rules:{
            email:{
                required:true,
                email:true
            },
            password:{
                required:true
            }
        },
        messages:{
            email:{
                required:'Please enter your email address',
                email:'Please enter a valid email address'
            },
            password:{
                required:'Please enter a password',
            }
        },
        submitHandler: function(form) {
            $.ajax({
                type: 'POST',
                url: 'php/login.php',
                data: $(form).serialize(),
                success: function(response) {
                    console.log('Success:', response);
                    if (!isNaN(response))
                    {
                        window.location.href = 'http://localhost/Guvi-login/profile.html?user_id='+ response;
                    } 
                    else if (response.trim() === "invalid password")
                    {
                        // console.log("Alert redirect to profile");
                        alert('Password and email do not match');
                    } 
                    else if (response.trim() === "invalid email") 
                    {
                        alert('Email does not exist. Register as a new user!');
                    }
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        }
    });
});