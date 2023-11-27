
$(document).ready(function() {
  $.ajax({
    url: 'http://localhost/Guvi-login/php/profile.php',
    method: 'GET',
    dataType: 'json',
    success: function(data) {
      console.log(data);
      // Update the div with the retrieved data
      $('#fname').text(data.firstname);
      $('#lname').text(data.lastname);
      $('#country').text(data.country);
      $('#state').text(data.state);
      $('#city').text(data.city);
      $('#email').text(data.email);

      console.log('User ID: ' + data.user_id);
        console.log('First Name: ' + data.firstname);
        console.log('Last Name: ' + data.lastname);
        console.log('Country: ' + data.country);
        console.log('Email: ' + data.email);
        console.log('City: ' + data.city);
        console.log('State: ' + data.state);
    },
    error: function(xhr, status, error) {
      console.log('AJAX request failed:', error);
    }
  });
});
