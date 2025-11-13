// utils.js
(function () {
  'use strict';

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' && event.target.tagName !== 'TEXTAREA') {
      event.preventDefault();
    }
  });

  window.showToast = function (message, type = 'success', reloadAfter = false) {
    const icons = {
      success: `<div class="inline-flex items-center justify-center shrink-0 w-6 h-6 text-green-500 bg-green-100 rounded-lg dark:text-green-300 dark:bg-green-900">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>`,
      error: `<div class="inline-flex items-center justify-center shrink-0 w-6 h-6 text-red-500 bg-red-100 rounded-lg dark:text-red-300 dark:bg-red-900">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>`
    };
    
    const toastHtml = `
      <div class="w-full max-w-xs p-3 text-gray-600 bg-white rounded-lg border shadow-lg" role="alert">
        <div class="flex">
          ${icons[type]}
          <div class="ms-3 font-normal">
            <span class="mb-1 text-xs font-semibold text-gray-900">${type === 'success' ? 'Success' : 'Error'}</span>
            <div class="mb-2 text-xs font-normal">${message}</div>
          </div>
        </div>
      </div>`;

    Toastify({
      text: toastHtml,
      duration: 2500,
      gravity: 'bottom',
      position: 'right',
      close: false,
      escapeMarkup: false,
      stopOnFocus: true,
      className: 'no-bg'
    }).showToast();

    if (reloadAfter) setTimeout(() => location.reload(), 3000);
  };

  window.setInputError = function (errors) {
    for (const [key, message] of Object.entries(errors)) {
      const el = document.querySelector(`#${key}-error`);
      if (el) {
        el.textContent = message;
        el.classList.remove('hidden');
      }
    }
  };

  window.clearInputErrors = function () {
    document.querySelectorAll('[id$="-error"]').forEach(el => {
      el.textContent = '';
      el.classList.add('hidden');
    });
  };

  window.sendRequest = async function (url, formData) {
    try {
      const response = await fetch(url, {
        credentials: 'include',
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.error) {
        if (result.fields) setInputError(result.fields);
        showToast(result.message || 'Something went wrong', 'error');
      } else {
        clearInputErrors();
        showToast(result.message || 'Success!', 'success', true);
      }

      return result;
    } catch (err) {
      console.error('Request error:', err);
      showToast('Network error, please try again', 'error');
    }
  };

  window.showConfirm = function (message) {
    return new Promise((resolve) => {
      const modal = document.getElementById("confirmModal");
      const msg = document.getElementById("confirmMessage");
      const okBtn = document.getElementById("confirmOk");
      const cancelBtn = document.getElementById("confirmCancel");

      msg.textContent = message;
      modal.classList.remove("hidden");

      const cleanup = (confirmed) => {
        modal.classList.add("hidden");
        okBtn.removeEventListener("click", onOk);
        cancelBtn.removeEventListener("click", onCancel);
        resolve(confirmed);
      };

      const onOk = () => cleanup(true);
      const onCancel = () => cleanup(false);

      okBtn.addEventListener("click", onOk);
      cancelBtn.addEventListener("click", onCancel);
    });
  };
})();
