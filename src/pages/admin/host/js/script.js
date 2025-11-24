document.addEventListener('DOMContentLoaded', () => {
  const forms = [
    { id: '#add-new-host-form', action: 'add-new-host-action.php' }
  ];

  forms.forEach(({ id, action }) => {
    const form = document.querySelector(id);
    if (!form) return;

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      clearInputErrors();
  
      const formData = new FormData(this);
      await sendRequest(`/booking-sys/src/pages/admin/host/action/${action}`, formData, this);
    });
  
  });
});
