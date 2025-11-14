document.addEventListener('DOMContentLoaded', function() {
  const imageInputs = document.querySelectorAll('.image-input');
  const previewSlots = document.querySelectorAll('.image-preview-slot');
  const filenameInputs = document.querySelectorAll('input[name$="_filename"]');
  const imageCount = document.getElementById('imageCount');
  
  let uploadedImages = {
    image_1: null,
    image_2: null,
    image_3: null,
    image_4: null
  };

  // Initialize Lucide icons if available
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }

  // Add click event to each preview slot
  previewSlots.forEach(slot => {
    slot.addEventListener('click', function() {
      const slotNumber = this.getAttribute('data-slot');
      const fileInput = document.getElementById(`image_${slotNumber}`);
      fileInput.click();
    });
  });

  // Handle file input changes
  imageInputs.forEach(input => {
    input.addEventListener('change', function(e) {
      const file = e.target.files[0];
      const slotNumber = this.getAttribute('data-slot');
      
      if (!file) return;

      // Validate file type
      if (!file.type.startsWith('image/')) {
        alert('Please select only image files.');
        this.value = '';
        return;
      }

      // Validate file size (10MB max)
      if (file.size > 10 * 1024 * 1024) {
        alert(`File "${file.name}" is too large. Maximum size is 10MB.`);
        this.value = '';
        return;
      }

      const reader = new FileReader();
      
      reader.onload = function(e) {
        const slot = document.querySelector(`.image-preview-slot[data-slot="${slotNumber}"]`);
        const filenameInput = document.getElementById(`image_${slotNumber}_filename`);
        
        // Update preview
        slot.classList.remove('border-dashed', 'border-gray-300');
        slot.classList.add('border-solid', 'border-blue-400');
        
        slot.innerHTML = `
          <img src="${e.target.result}" alt="Preview ${slotNumber}" class="w-full h-full object-cover">
          <button type="button" class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors duration-200 remove-image" data-slot="${slotNumber}">
            ×
          </button>
          <div class="absolute bottom-1 left-1 right-1 bg-black/50 text-white text-xs p-1 rounded text-center truncate">
            ${file.name}
          </div>
        `;

        // Store file data
        uploadedImages[`image_${slotNumber}`] = file;
        
        // Set filename in hidden input (you can modify this to store the actual filename you want in DB)
        filenameInput.value = file.name;
        
        // Add remove event listener
        slot.querySelector('.remove-image').addEventListener('click', function(e) {
          e.stopPropagation();
          removeImage(slotNumber);
        });

        updateImageCount();
      };
      
      reader.readAsDataURL(file);
    });
  });

  function removeImage(slotNumber) {
    const slot = document.querySelector(`.image-preview-slot[data-slot="${slotNumber}"]`);
    const fileInput = document.getElementById(`image_${slotNumber}`);
    const filenameInput = document.getElementById(`image_${slotNumber}_filename`);
    
    // Reset file input
    fileInput.value = '';
    
    // Clear stored data
    uploadedImages[`image_${slotNumber}`] = null;
    filenameInput.value = '';
    
    // Reset preview slot
    slot.innerHTML = '';
    slot.classList.add('border-dashed', 'border-gray-300');
    slot.classList.remove('border-solid', 'border-blue-400');
    
    const defaultContent = `
      <div class="text-center p-2">
        <svg class="w-6 h-6 text-gray-400 mx-auto mb-1 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        <p class="text-xs text-gray-500 group-hover:text-blue-400 transition-colors">Image ${slotNumber}</p>
      </div>
    `;
    slot.innerHTML = defaultContent;
    
    // Re-add click event
    slot.addEventListener('click', function() {
      fileInput.click();
    });
    
    updateImageCount();
  }

  function updateImageCount() {
    const count = Object.values(uploadedImages).filter(img => img !== null).length;
    imageCount.textContent = `${count}/4 images selected`;
    imageCount.className = `text-xs font-medium mt-1 text-center ${count === 0 ? 'text-gray-500' : 'text-green-600'}`;
  }

  // Drag and drop functionality
  previewSlots.forEach(slot => {
    slot.addEventListener('dragover', function(e) {
      e.preventDefault();
      const slotNumber = this.getAttribute('data-slot');
      if (!uploadedImages[`image_${slotNumber}`]) {
        this.classList.add('border-blue-400', 'bg-blue-50');
      }
    });

    slot.addEventListener('dragleave', function(e) {
      e.preventDefault();
      this.classList.remove('border-blue-400', 'bg-blue-50');
    });

    slot.addEventListener('drop', function(e) {
      e.preventDefault();
      this.classList.remove('border-blue-400', 'bg-blue-50');
      
      const slotNumber = this.getAttribute('data-slot');
      const fileInput = document.getElementById(`image_${slotNumber}`);
      
      if (e.dataTransfer.files.length > 0 && !uploadedImages[`image_${slotNumber}`]) {
        fileInput.files = e.dataTransfer.files;
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
      }
    });
  });

  // Form submission handler (example)
  document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Here you would typically:
    // 1. Upload images to server
    // 2. Get back the stored filenames
    // 3. Submit the form with image_1_filename, image_2_filename, etc.
    
    const formData = new FormData(this);
    
    // Add the uploaded files to FormData
    Object.keys(uploadedImages).forEach(key => {
      if (uploadedImages[key]) {
        formData.append(key, uploadedImages[key]);
      }
    });
    
    // Example: Submit via fetch
    /*
    fetch('/your-upload-endpoint', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      // Handle response
      console.log('Upload successful:', data);
    })
    .catch(error => {
      console.error('Upload failed:', error);
    });
    */
  });
});