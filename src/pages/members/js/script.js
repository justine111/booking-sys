document.addEventListener('DOMContentLoaded', () => {
  const forms = [
    { id: '#add-new-member', action: 'add-new-member-action.php' },
    { id: '#updateMember', action: 'update-member-action.php' },
    { id: '#uploadProfile', action: 'upload-profile-action.php' },
    { id: '#addMilestone', action: 'add-milestone-action.php' }
  ];

  forms.forEach(({ id, action }) => {
    const form = document.querySelector(id);
    if (!form) return;

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      clearInputErrors();
  
      const formData = new FormData(this);
      await sendRequest(`/tupas/src/pages/members/action/${action}`, formData, this);
    });
  
  });
});
