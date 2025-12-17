<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Age and Gender Prediction</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background-color: #4285f4;
            color: white;
            padding: 20px 0;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            margin: 0;
            font-size: 2.2em;
        }
        
        .main-content {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
        }
        
        .section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 300px;
        }
        
        .section-title {
            color: #4285f4;
            margin-top: 0;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        
        .video-container {
            position: relative;
            margin-bottom: 20px;
        }
        
        #video {
            width: 100%;
            border-radius: 8px;
            background-color: #333;
        }
        
        #canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        
        .controls {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        button {
            background-color: #4285f4;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            flex: 1;
            min-width: 120px;
        }
        
        button:hover {
            background-color: #3367d6;
        }
        
        button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        
        #uploadForm {
            margin-bottom: 20px;
        }
        
        #fileInput {
            margin-bottom: 10px;
            width: 100%;
        }
        
        .results {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #4285f4;
        }
        
        .result-item {
            margin-bottom: 10px;
        }
        
        .result-label {
            font-weight: bold;
            color: #555;
        }
        
        #imagePreview {
            max-width: 100%;
            max-height: 300px;
            display: block;
            margin: 0 auto 20px;
            border-radius: 8px;
        }
        
        .hidden {
            display: none;
        }
        
        .loading {
            text-align: center;
            padding: 20px;
        }
        
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid #4285f4;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: #777;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Age and Gender Prediction</h1>
            <p>Upload an image or use your camera to detect age and gender</p>
        </header>
        
        <div class="main-content">
            <div class="section">
                <h2 class="section-title">Live Camera Detection</h2>
                <div class="video-container">
                    <video id="video" width="100%" height="auto" autoplay muted></video>
                    <canvas id="canvas"></canvas>
                </div>
                <div class="controls">
                    <button id="startCamera">Start Camera</button>
                    <button id="stopCamera" disabled>Stop Camera</button>
                    
                </div>
                <div id="cameraResults" class="results hidden">
                    <h3>Detection Results</h3>
                    <div id="cameraResultContent"></div>
                </div>
            </div>
            
            <div class="section">
                <h2 class="section-title">Image Upload</h2>
                <form id="uploadForm">
                    <input type="file" id="fileInput" accept="image/*">
                    <button type="button" id="uploadButton">Upload & Detect</button>
                </form>
                <img id="imagePreview" class="hidden">
                <div id="uploadResults" class="results hidden">
                    <h3>Detection Results</h3>
                    <div id="uploadResultContent"></div>
                </div>
                <div id="loading" class="loading hidden">
                    <div class="spinner"></div>
                    <p>Processing image...</p>
                </div>
            </div>
        </div>
        
        <footer>
            <p>Age and Gender Prediction System &copy; 2023</p>
        </footer>
    </div>

    <script>
        // DOM Elements
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const startCameraBtn = document.getElementById('startCamera');
        const stopCameraBtn = document.getElementById('stopCamera');
       
        const fileInput = document.getElementById('fileInput');
        const uploadButton = document.getElementById('uploadButton');
        const imagePreview = document.getElementById('imagePreview');
        const uploadResults = document.getElementById('uploadResults');
        const uploadResultContent = document.getElementById('uploadResultContent');
        const cameraResults = document.getElementById('cameraResults');
        const cameraResultContent = document.getElementById('cameraResultContent');
        const loading = document.getElementById('loading');
        
        // Variables
        let stream = null;
        let isDetecting = false;
        let detectionInterval = null;
        
        // Event Listeners
        startCameraBtn.addEventListener('click', startCamera);
        stopCameraBtn.addEventListener('click', stopCamera);
        
        uploadButton.addEventListener('click', uploadImage);
        
        // Start Camera
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
                startCameraBtn.disabled = true;
                stopCameraBtn.disabled = false;
                captureImageBtn.disabled = false;
                detectLiveBtn.disabled = false;
            } catch (err) {
                console.error("Error accessing camera:", err);
                alert("Could not access the camera. Please ensure you've granted camera permissions.");
            }
        }
        
        // Stop Camera
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                video.srcObject = null;
                startCameraBtn.disabled = false;
                stopCameraBtn.disabled = true;
                captureImageBtn.disabled = true;
                detectLiveBtn.disabled = true;
                
                if (isDetecting) {
                    toggleLiveDetection();
                }
            }
        }
        
        // Capture Image from Camera
        function captureImage() {
            if (!stream) return;
            
            // Set canvas dimensions to match video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Draw current video frame to canvas
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Convert canvas to blob and process
            canvas.toBlob(processImage, 'image/jpeg', 0.95);
        }
        
        // Toggle Live Detection
        function toggleLiveDetection() {
            if (isDetecting) {
                // Stop detection
                clearInterval(detectionInterval);
                detectLiveBtn.textContent = "Detect Live";
                isDetecting = false;
                cameraResults.classList.add('hidden');
            } else {
                // Start detection
                detectLiveBtn.textContent = "Stop Detection";
                isDetecting = true;
                cameraResults.classList.remove('hidden');
                
                // Run detection every 2 seconds
                detectionInterval = setInterval(() => {
                    captureImage();
                }, 2000);
            }
        }
        
        // Upload Image
        function uploadImage() {
            const file = fileInput.files[0];
            if (!file) {
                alert("Please select an image file first.");
                return;
            }
            
            // Display preview
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
                uploadResults.classList.add('hidden');
                loading.classList.remove('hidden');
                
                // Process the image
                fetch(e.target.result)
                    .then(res => res.blob())
                    .then(processImage);
            };
            reader.readAsDataURL(file);
        }
        
        // Process Image (send to backend)
        function processImage(blob) {
            const formData = new FormData();
            formData.append('image', blob, 'image.jpg');
            
            fetch('/predict', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                loading.classList.add('hidden');
                displayResults(data, isDetecting ? cameraResultContent : uploadResultContent);
                
                if (!isDetecting) {
                    uploadResults.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loading.classList.add('hidden');
                alert("An error occurred during detection. Please try again.");
            });
        }
        
        // Display Results
        function displayResults(data, container) {
            container.innerHTML = '';
            
            if (data.error) {
                container.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }
            
            if (data.predictions && data.predictions.length > 0) {
                data.predictions.forEach((pred, idx) => {
                    const predDiv = document.createElement('div');
                    predDiv.className = 'result-item';
                    
                    predDiv.innerHTML = `
                        <p><span class="result-label">Face ${idx + 1}:</span></p>
                        <p><span class="result-label">Gender:</span> ${pred.gender} (${(pred.gender_confidence * 100).toFixed(1)}%)</p>
                        <p><span class="result-label">Age:</span> ${pred.age} years</p>
                        <p><span class="result-label">Age Range:</span> ${pred.age_low}-${pred.age_high} years</p>
                    `;
                    
                    container.appendChild(predDiv);
                });
            } else {
                container.innerHTML = '<p>No faces detected in the image.</p>';
            }
        }
        
        // Clean up on page unload
        window.addEventListener('beforeunload', () => {
            if (stream) {
                stopCamera();
            }
        });
    </script>
</body>
</html>