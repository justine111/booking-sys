document.addEventListener('DOMContentLoaded', () => {
  const forms = [
    { id: '#create-booking-form', action: 'create-booking-action.php' },
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

  // Handle all update booking forms (multiple instances)
  const updateBookingForms = document.querySelectorAll('.update-booking-form');
  updateBookingForms.forEach(form => {
    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      clearInputErrors();
  
      const formData = new FormData(this);
      await sendRequest(`/booking-sys/src/pages/admin/booking/action/update-booking-action.php`, formData, this);
    });
  });
});
