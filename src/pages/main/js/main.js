document.addEventListener('DOMContentLoaded', () => {
  const forms = [
    { id: '#reservation-form', action: 'booked-action.php' },
  ];

  forms.forEach(({ id, action }) => {
    const form = document.querySelector(id);
    if (!form) return;

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      clearInputErrors();
  
      const formData = new FormData(this);
      await sendRequest(`/booking-sys/src/pages/main/action/${action}`, formData, this);
    });
  
  });
});
