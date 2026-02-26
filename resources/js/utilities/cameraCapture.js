// Camera Capture Utility for Children
// Usage: Add data-camera-input attribute to a container element
// The container should have a data-name attribute for the hidden input name

import { showConsole } from "../config/debug";

export function attachCameraCapture(container) {
    if (!container || container.dataset.cameraAttached) return;

    const originalName = container.getAttribute('data-name') || 'childPhoto';
    const containerId = container.id || 'camera-' + Math.random().toString(36).slice(2);

    // Create hidden input for storing the photo data
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = originalName;
    hiddenInput.value = '';
    container.appendChild(hiddenInput);

    // Create the camera capture UI
    const cameraWrapper = document.createElement('div');
    cameraWrapper.className = 'camera-capture-wrapper';

    // Create video element for camera stream
    const video = document.createElement('video');
    video.className = 'camera-video w-full h-48 bg-gray-900 rounded-lg object-cover';
    video.setAttribute('playsinline', '');
    video.setAttribute('autoplay', '');
    video.style.display = 'none';

    // Create canvas for capturing photo
    const canvas = document.createElement('canvas');
    canvas.className = 'camera-canvas hidden';
    canvas.width = 320;
    canvas.height = 240;

    // Create preview image
    const preview = document.createElement('img');
    preview.className = 'camera-preview w-full h-48 bg-gray-200 rounded-lg object-cover border-2 border-teal-500';
    preview.alt = 'Child photo';
    preview.style.display = 'none';

    // Create placeholder when no photo
    const placeholder = document.createElement('div');
    placeholder.className = 'camera-placeholder w-full h-48 bg-gray-100 rounded-lg flex flex-col items-center justify-center border-2 border-dashed border-gray-300';
    placeholder.innerHTML = `
        <i class="fa-solid fa-camera text-4xl text-gray-400 mb-2"></i>
        <p class="text-sm text-gray-500">No photo captured</p>
    `;

    // Create buttons
    const buttonWrapper = document.createElement('div');
    buttonWrapper.className = 'camera-buttons flex gap-2 mt-2 relative z-10';

    const startCameraBtn = document.createElement('button');
    startCameraBtn.type = 'button';
    startCameraBtn.className = 'start-camera-btn flex-1 bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors relative z-10';
    startCameraBtn.innerHTML = '<i class="fa-solid fa-camera mr-2"></i> Take Photo';

    const uploadBtn = document.createElement('button');
    uploadBtn.type = 'button';
    uploadBtn.className = 'upload-btn flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors relative z-10';
    uploadBtn.innerHTML = '<i class="fa-solid fa-upload mr-2"></i> Upload';

    const retakeBtn = document.createElement('button');
    retakeBtn.type = 'button';
    retakeBtn.className = 'retake-btn flex-1 bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors hidden relative z-10';
    retakeBtn.innerHTML = '<i class="fa-solid fa-rotate-right mr-2"></i> Retake';

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'remove-btn flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors hidden relative z-10';
    removeBtn.innerHTML = '<i class="fa-solid fa-trash mr-2"></i> Remove';

    // Hidden file input for upload
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/*';
    fileInput.className = 'hidden';

    buttonWrapper.appendChild(startCameraBtn);
    buttonWrapper.appendChild(uploadBtn);
    buttonWrapper.appendChild(retakeBtn);
    buttonWrapper.appendChild(removeBtn);

    // Create modal for camera
    const cameraModal = document.createElement('div');
    cameraModal.className = 'camera-modal fixed inset-0 z-50 flex items-center justify-center bg-black/70 hidden';
    cameraModal.innerHTML = `
        <div class="camera-modal-content bg-white rounded-2xl p-4 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Take Photo</h3>
                <button type="button" class="close-camera-btn text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            <video class="modal-video w-full h-56 bg-gray-900 rounded-lg object-cover" playsinline autoplay></video>
            <canvas class="modal-canvas hidden"></canvas>
            <div class="flex gap-2 mt-4">
                <button type="button" class="capture-btn flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                    <i class="fa-solid fa-circle text-xl mr-2"></i> Capture
                </button>
                <button type="button" class="cancel-camera-btn flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    `;

    // Assemble the UI
    cameraWrapper.appendChild(video);
    cameraWrapper.appendChild(canvas);
    cameraWrapper.appendChild(preview);
    cameraWrapper.appendChild(placeholder);
    cameraWrapper.appendChild(buttonWrapper);
    cameraWrapper.appendChild(fileInput);

    container.appendChild(cameraWrapper);
    document.body.appendChild(cameraModal);

    // Get modal elements
    const modalVideo = cameraModal.querySelector('.modal-video');
    const modalCanvas = cameraModal.querySelector('.modal-canvas');
    const captureBtn = cameraModal.querySelector('.capture-btn');
    const closeCameraBtn = cameraModal.querySelector('.close-camera-btn');
    const cancelCameraBtn = cameraModal.querySelector('.cancel-camera-btn');

    let stream = null;

    // Open camera modal
    const openCamera = async () => {
        try {
            // Stop any existing stream first
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            
            // Request camera access
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: 'user',
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                },
                audio: false
            });
            
            modalVideo.srcObject = stream;
            modalVideo.style.display = 'block';
            cameraModal.classList.remove('hidden');
        } catch (err) {
            showConsole('error', 'Error accessing camera:', err)
            let errorMessage = 'Unable to access camera.';
            
            if (err.name === 'NotReadableError') {
                errorMessage = 'Camera is already in use by another application. Please close other apps using the camera.';
            } else if (err.name === 'NotAllowedError') {
                errorMessage = 'Camera permission denied. Please allow camera access in your browser settings.';
            } else if (err.name === 'NotFoundError') {
                errorMessage = 'No camera found. Please connect a camera to your device.';
            }
            
            alert(errorMessage);
        }
    };

    // Close camera modal
    const closeCamera = () => {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        cameraModal.classList.add('hidden');
    };

    // Capture photo
    const capturePhoto = () => {
        modalCanvas.width = modalVideo.videoWidth || 320;
        modalCanvas.height = modalVideo.videoHeight || 240;
        const ctx = modalCanvas.getContext('2d');
        ctx.drawImage(modalVideo, 0, 0, modalCanvas.width, modalCanvas.height);
        
        // Get base64 data
        const dataUrl = modalCanvas.toDataURL('image/jpeg', 0.8);
        
        // Store in hidden input
        hiddenInput.value = dataUrl;
        
        // Show as profile picture
        preview.src = dataUrl;
        preview.style.display = 'block';
        placeholder.style.display = 'none';
        
        // Add profile picture styling
        preview.classList.add('rounded-full', 'object-cover', 'border-4', 'border-teal-500', 'mx-auto');
        preview.style.width = '100px';
        preview.style.height = '100px';
        
        // Show retake and remove buttons, hide start button
        startCameraBtn.classList.add('hidden');
        uploadBtn.classList.add('hidden');
        retakeBtn.classList.remove('hidden');
        retakeBtn.classList.add('flex-1');
        removeBtn.classList.remove('hidden');
        removeBtn.classList.add('flex-1');
        
        closeCamera();
    };

    // Retake photo
    const retakePhoto = () => {
        openCamera();
    };

    // Remove photo
    const removePhoto = () => {
        hiddenInput.value = '';
        preview.src = '';
        preview.style.display = 'none';
        preview.classList.remove('rounded-full', 'object-cover', 'border-4', 'border-teal-500', 'mx-auto');
        preview.style.width = '';
        preview.style.height = '';
        placeholder.style.display = 'flex';
        startCameraBtn.classList.remove('hidden');
        uploadBtn.classList.remove('hidden');
        retakeBtn.classList.add('hidden');
        removeBtn.classList.add('hidden');
    };

    // Event listeners
    startCameraBtn.addEventListener('click', openCamera);
    uploadBtn.addEventListener('click', () => fileInput.click());
    retakeBtn.addEventListener('click', retakePhoto);
    removeBtn.addEventListener('click', removePhoto);
    captureBtn.addEventListener('click', capturePhoto);
    closeCameraBtn.addEventListener('click', closeCamera);
    cancelCameraBtn.addEventListener('click', closeCamera);

    // File input change handler
    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                const dataUrl = event.target.result;
                hiddenInput.value = dataUrl;
                preview.src = dataUrl;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
                
                // Add profile picture styling
                preview.classList.add('rounded-full', 'object-cover', 'border-4', 'border-teal-500', 'mx-auto');
                preview.style.width = '100px';
                preview.style.height = '100px';
                
                startCameraBtn.classList.add('hidden');
                uploadBtn.classList.add('hidden');
                retakeBtn.classList.remove('hidden');
                retakeBtn.classList.add('flex-1');
                removeBtn.classList.remove('hidden');
                removeBtn.classList.add('flex-1');
            };
            reader.readAsDataURL(file);
        }
    });

    // Close modal on backdrop click
    cameraModal.addEventListener('click', (e) => {
        if (e.target === cameraModal) {
            closeCamera();
        }
    });

    // Escape key to close
    const handleKeydown = (e) => {
        if (e.key === 'Escape' && !cameraModal.classList.contains('hidden')) {
            closeCamera();
            document.removeEventListener('keydown', handleKeydown);
        }
    };
    document.addEventListener('keydown', handleKeydown);

    container.dataset.cameraAttached = '1';
}

export function initCameraCaptures(scope = document) {
    const containers = scope.querySelectorAll('[data-camera-input]');
    containers.forEach(attachCameraCapture);
}

// Initialize on DOM ready
if (typeof document !== 'undefined') {
    document.addEventListener('DOMContentLoaded', () => {
        initCameraCaptures();
    });
}
