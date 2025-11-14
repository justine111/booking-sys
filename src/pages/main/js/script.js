(function() {
  'use strict';
    
  const formErrorBox = document.querySelector('#form-error');
  const formErrorMessage = document.querySelector('#error-message');
  
  const passwordInput = document.querySelector('#password');
  const togglePassword = document.querySelector('#toggle-password');
  
  const initPasswordToggle = () => {
    if (!passwordInput || !togglePassword) return;
    
    togglePassword.addEventListener('click', () => {
      const isHidden = passwordInput.getAttribute('type') === 'password';
      passwordInput.setAttribute('type', isHidden ? 'text' : 'password');
      togglePassword.innerHTML = isHidden
        ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
           </svg>`
        : `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.957 9.957 0 012.35-3.982m3.034-2.47A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.963 9.963 0 01-4.5 5.818M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
           </svg>`;
    });
  };

  //Show and hide main form error box
  const showFormError = (message) => {
    formErrorMessage.textContent = message || 'Login failed.';
    formErrorBox.classList.remove('hidden');
  };

  const hideFormError = () => {
    formErrorMessage.textContent = '';
    formErrorBox.classList.add('hidden');
  };
  
  const sendRequest = async (url, formData) => {
    const response = await fetch(url, {
      credentials: 'include',
      method: 'POST',
      body: formData
    });
    const result = await response.json();

    if (result.error) {
      hideFormError();

      if (result.fields) {
        setInputError(result.fields);
        const message = result.fields.username || result.fields.password || result.message;
        showFormError(message);
      } else {
        showFormError(result.message || 'Login failed.');
      }
    } else {
      hideFormError();
      window.location.href = result.message;
    }
    return result;
  };
  
  const handleLogin = async (event) => {
    event.preventDefault();
    hideFormError();
    
    const formData = new FormData(event.target);
    try {
      const result = await sendRequest('/booking-sys/src/pages/main/action/login-action.php', formData);
      console.log(result);
    } catch (error) {
      console.error(error);
      showFormError('An unexpected error occurred. Please try again.');
    }
  };
    
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('#login-form')?.addEventListener('submit', handleLogin);
    initPasswordToggle();
  });
})();
