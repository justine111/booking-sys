document.addEventListener('DOMContentLoaded', () => {
  const forms = [
    { id: '#update-booking-form', action: 'update-booking-action.php' },
  ];

  forms.forEach(({ id, action }) => {
    const form = document.querySelector(id);
    if (!form) return;

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      clearInputErrors();
  
      const formData = new FormData(this);
      await sendRequest(`/booking-sys/src/pages/admin/booking/action/${action}`, formData, this);
    });
  
  });
});
