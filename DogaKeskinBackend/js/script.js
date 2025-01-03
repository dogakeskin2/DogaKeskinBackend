document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('marketRegBtn').addEventListener('click', function () {
      toggleRegistration('marketRegistration');
    });
  
    document.getElementById('consumerRegBtn').addEventListener('click', function () {
      toggleRegistration('consumerRegistration');
    });
  });
  
  function toggleRegistration(formId) {
    var marketRegistrationForm = document.getElementById('marketRegistration');
    var consumerRegistrationForm = document.getElementById('consumerRegistration');

    if (formId === 'marketRegistration') {
        marketRegistrationForm.style.display = 'block';
        consumerRegistrationForm.style.display = 'none';
    } else {
        marketRegistrationForm.style.display = 'none';
        consumerRegistrationForm.style.display = 'block';
    }
}