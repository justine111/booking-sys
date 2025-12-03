document.addEventListener('DOMContentLoaded', () => {
  const forms = [
    { id: '#add-hotel', action: 'add-hotel-action.php' },
    { id: '#edit-hotel-form', action: 'edit-hotel-action.php' },
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

  // Handle Edit Room - Fetch room data and populate modal
  window.editRoom = function(propertyId) {
    // Fetch room details
    fetch(`/booking-sys/src/pages/admin/room/action/get-hotel-data.php?id=${propertyId}`)
      .then(response => response.json())
      .then(data => {
        if (!data.error) {
          document.getElementById('edit-property-id').value = data.property_id;
          document.getElementById('edit-hotel-name').value = data.title;
          document.getElementById('edit-address').value = data.address;
          document.getElementById('edit-city').value = data.city || '';
          document.getElementById('edit-price').value = data.price_per_night;
          document.getElementById('edit-host').value = data.host_id || '';
          document.getElementById('edit-description').value = data.description || '';
          document.getElementById('edit-amenities').value = data.amenities || '';
          
          // Show current images
          if (data.img1) document.getElementById('current-img1').src = `/booking-sys/src/repositories/uploads/${data.img1}`;
          if (data.img2) document.getElementById('current-img2').src = `/booking-sys/src/repositories/uploads/${data.img2}`;
          if (data.img3) document.getElementById('current-img3').src = `/booking-sys/src/repositories/uploads/${data.img3}`;
          if (data.img4) document.getElementById('current-img4').src = `/booking-sys/src/repositories/uploads/${data.img4}`;
        }
      })
      .catch(error => console.error('Error:', error));
  };

  // Handle Approve Property
  window.approveProperty = function(propertyId) {
    if (!confirm('Are you sure you want to approve this property? It will be visible on the public website.')) {
      return;
    }

    const formData = new FormData();
    formData.append('property_id', propertyId);

    fetch('/booking-sys/src/pages/admin/room/action/approve-property-action.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        alert('Error: ' + data.message);
      } else {
        alert(data.message);
        location.reload();
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while approving the property.');
    });
  };

  // Handle Reject Property
  window.rejectProperty = function(propertyId) {
    if (!confirm('Are you sure you want to reject this property? It will remain hidden from the public website.')) {
      return;
    }

    const formData = new FormData();
    formData.append('property_id', propertyId);

    fetch('/booking-sys/src/pages/admin/room/action/reject-property-action.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        alert('Error: ' + data.message);
      } else {
        alert(data.message);
        location.reload();
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while rejecting the property.');
    });
  };
});
