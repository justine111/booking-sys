  lucide.createIcons();
  
  document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('room-images');
    const imagePreviewGrid = document.getElementById('imagePreviewGrid');
    const imageCount = document.getElementById('imageCount');
    const previewSlots = document.querySelectorAll('.image-preview-slot');
    let uploadedImages = [];

    // Make each preview slot clickable to trigger file input
    previewSlots.forEach((slot, index) => {
      slot.addEventListener('click', function() {
        // Only allow clicking empty slots
        if (!uploadedImages[index]) {
          fileInput.click();
          // Store which slot was clicked
          fileInput.setAttribute('data-target-slot', index);
        }
      });
    });

    fileInput.addEventListener('change', function(e) {
      const files = Array.from(e.target.files);
      const targetSlot = parseInt(fileInput.getAttribute('data-target-slot')) || 0;

      // Reset if more than 4 files selected
      if (files.length > 4) {
        alert('Maximum 4 images allowed. Please select up to 4 images.');
        fileInput.value = '';
        return;
      }

      // Process new files
      files.forEach((file, index) => {
        const slotIndex = (targetSlot + index) % 4; // Cycle through slots

        if (slotIndex >= 4) return; // Safety check

        if (!file.type.startsWith('image/')) {
          alert('Please select only image files.');
          return;
        }
        
        if (file.size > 10 * 1024 * 1024) {
          alert(`File "${file.name}" is too large. Maximum size is 10MB.`);
          return;
        }

        const reader = new FileReader();

        reader.onload = function(e) {
          const slot = previewSlots[slotIndex];
          slot.classList.remove('border-dashed', 'border-gray-300');
          slot.classList.add('border-solid', 'border-blue-400');
          
          slot.innerHTML = `
          <img src="${e.target.result}" alt="Preview ${slotIndex + 1}" class="w-full h-full object-cover">
          <button type="button" class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600 transition-colors duration-200 remove-image" data-index="${slotIndex}">
            ×
          </button>
          <div class="absolute bottom-1 left-1 right-1 bg-black/50 text-white text-xs p-1 rounded text-center truncate">
            ${file.name}
          </div>
        `;
          
          uploadedImages[slotIndex] = file;

          // Add remove event listener
          slot.querySelector('.remove-image').addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent triggering the file input
            removeImage(slotIndex);
          });
        };
        
        reader.readAsDataURL(file);
      });

      updateImageCount();
      fileInput.removeAttribute('data-target-slot');
    });

    function removeImage(index) {
      // Remove the image from array
      uploadedImages[index] = null;

      // Reset the preview slot
      const slot = previewSlots[index];
      slot.innerHTML = '';
      slot.classList.add('border-dashed', 'border-gray-300');
      slot.classList.remove('border-solid', 'border-blue-400');

      const defaultContent = `
      <div class="text-center p-2">
        <svg class="w-6 h-6 text-gray-400 mx-auto mb-1 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        <p class="text-xs text-gray-500 group-hover:text-blue-400 transition-colors">Add Image</p>
      </div>
    `;
      slot.innerHTML = defaultContent;

      // Re-add click event
      slot.addEventListener('click', function() {
        fileInput.click();
        fileInput.setAttribute('data-target-slot', index);
      });

      updateImageCount();
    }

    function updateImageCount() {
      const count = uploadedImages.filter(img => img !== null).length;
      imageCount.textContent = `${count}/4 images selected`;
      imageCount.className = `text-xs font-medium mt-1 text-center ${count === 0 ? 'text-gray-500' : 'text-green-600'}`;
    }

    // Allow drag and drop
    previewSlots.forEach(slot => {
      slot.addEventListener('dragover', function(e) {
        e.preventDefault();
        if (!uploadedImages[Array.from(previewSlots).indexOf(slot)]) {
          slot.classList.add('border-blue-400', 'bg-blue-50');
        }
      });

      slot.addEventListener('dragleave', function(e) {
        e.preventDefault();
        slot.classList.remove('border-blue-400', 'bg-blue-50');
      });
      
      slot.addEventListener('drop', function(e) {
        e.preventDefault();
        slot.classList.remove('border-blue-400', 'bg-blue-50');

        if (e.dataTransfer.files.length > 0 && !uploadedImages[Array.from(previewSlots).indexOf(slot)]) {
          const slotIndex = Array.from(previewSlots).indexOf(slot);
          fileInput.files = e.dataTransfer.files;
          fileInput.setAttribute('data-target-slot', slotIndex);
          const event = new Event('change', {
            bubbles: true
          });
          fileInput.dispatchEvent(event);
        }
      });
    });
  });