document.addEventListener('DOMContentLoaded', () => {
  const forms = [
    { id: '#add-hotel', action: 'add-hotel-action.php' },
  ];

  forms.forEach(({ id, action }) => {
    const form = document.querySelector(id);
    if (!form) return;

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      clearInputErrors();
  
      const formData = new FormData(this);
      await sendRequest(`/booking-sys/src/pages/admin/room/action/${action}`, formData, this);
    });
  
  });
});
