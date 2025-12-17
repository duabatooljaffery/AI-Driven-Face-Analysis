<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Analysis | Age & Gender Predictor</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
   <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
            --danger: #f72585;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        h1 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .subtitle {
            color: #666;
            font-weight: 300;
        }
        
        .input-options {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .input-option {
            flex: 1;
            text-align: center;
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .input-option:hover {
            border-color: var(--primary);
            background: rgba(67, 97, 238, 0.05);
        }
        
        .input-option.active {
            border-color: var(--primary);
            background: rgba(67, 97, 238, 0.1);
        }
        
        .input-option i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .main-content {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
        }
        
        .upload-section {
            flex: 1;
            min-width: 300px;
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .upload-area {
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 3rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 1.5rem;
        }
        
        .upload-area:hover {
            border-color: var(--primary);
            background: rgba(67, 97, 238, 0.05);
        }
        
        .upload-area i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        .upload-area p {
            margin-bottom: 0.5rem;
        }
        
        .small {
            font-size: 0.8rem;
            color: #999;
        }
        
        .btn {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
            margin-right: 10px;
        }
        
        .btn:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
        }
        
        .camera-container {
            display: none;
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        #video {
            width: 100%;
            border-radius: 8px;
            display: block;
        }
        
        #canvas {
            display: none;
        }
        
        .camera-controls {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .results-section {
            flex: 1;
            min-width: 300px;
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .result-card {
            display: none;
            text-align: center;
        }
        
        .result-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            max-height: 300px;
        }
        
        .result-details {
            text-align: left;
        }
        
        .detail-item {
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--primary);
        }
        
        .progress-container {
            width: 100%;
            background-color: #e9ecef;
            border-radius: 5px;
            margin: 0.5rem 0;
        }
        
        .progress-bar {
            height: 10px;
            border-radius: 5px;
            background-color: var(--accent);
            width: 0%;
            transition: width 0.5s;
        }
        
        .confidence-value {
            font-weight: 600;
            color: var(--secondary);
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }
        
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid var(--primary);
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        footer {
            text-align: center;
            margin-top: 3rem;
            color: #666;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
            }
            
            .input-options {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
<?php include 'navbar.html'?>
    <div class="container">
        <header>
            <h1>Face Analysis AI</h1>
            <p class="subtitle">Detect age and gender from photos or live camera</p>
        </header>
        
        <div class="main-content">
            <div class="upload-section">
                <div class="input-options">
                    <div class="input-option active" id="uploadOption">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <h3>Upload Photo</h3>
                    </div>
                    <div class="input-option" id="cameraOption">
                        <i class="fas fa-camera"></i>
                        <h3>Live Camera</h3>
                    </div>
                </div>
                
                <!-- Upload Section -->
                <div id="uploadSection">
                    <div class="upload-area" id="uploadArea">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <h3>Upload Your Image</h3>
                        <p>Drag & drop your image here or click to browse</p>
                        <p class="small">Supports JPG, PNG (Max 5MB)</p>
                    </div>
                    <input type="file" id="fileInput" accept="image/*" style="display: none;">
                    <button class="btn" id="analyzeBtn" disabled>Analyze Image</button>
                </div>
                
                <!-- Camera Section -->
                <div class="camera-container" id="cameraContainer">
                    <video id="video" autoplay playsinline></video>
                    <canvas id="canvas"></canvas>
                    <div class="camera-controls">
                        <button class="btn" id="captureBtn"><i class="fas fa-camera"></i> Capture</button>
                        <button class="btn btn-secondary" id="retakeBtn" style="display: none;"><i class="fas fa-redo"></i> Retake</button>
                        <button class="btn" id="analyzeCameraBtn" style="display: none;">Analyze Photo</button>
                    </div>
                </div>
                
                <button class="btn btn-secondary" id="resetBtn" style="display: none; margin-top: 1rem;">Reset</button>
                
                <div class="loading" id="loadingIndicator">
                    <div class="spinner"></div>
                    <p>Analyzing image...</p>
                </div>
            </div>
            
            <div class="results-section">
                <div class="result-card" id="resultCard">
                    <img src="" alt="Analyzed Image" class="result-image" id="resultImage">
                    
                    <div class="result-details">
                        <div class="detail-item">
                            <div class="detail-label">Gender:</div>
                            <div id="genderResult">-</div>
                            <div class="progress-container">
                                <div class="progress-bar" id="genderConfidence"></div>
                            </div>
                            <div>Confidence: <span class="confidence-value" id="genderConfidenceValue">0%</span></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Age:</div>
                            <div id="ageResult">-</div>
                            <div class="progress-container">
                                <div class="progress-bar" id="ageConfidence"></div>
                            </div>
                            <div>Confidence: <span class="confidence-value" id="ageConfidenceValue">0%</span></div>
                        </div>
                    </div>
                </div>
                
                <div id="emptyState">
                    <h3 style="text-align: center; color: #666; margin-top: 3rem;">Your analysis results will appear here</h3>
                    <p style="text-align: center; color: #999;">Upload or capture a photo to see age and gender analysis</p>
                </div>
            </div>
        </div>
        
       
    </div>
    <script>
        // DOM Elements
        const uploadOption = document.getElementById('uploadOption');
        const cameraOption = document.getElementById('cameraOption');
        const uploadSection = document.getElementById('uploadSection');
        const cameraContainer = document.getElementById('cameraContainer');
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('captureBtn');
        const retakeBtn = document.getElementById('retakeBtn');
        const analyzeBtn = document.getElementById('analyzeBtn');
        const analyzeCameraBtn = document.getElementById('analyzeCameraBtn');
        const resetBtn = document.getElementById('resetBtn');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const resultCard = document.getElementById('resultCard');
        const emptyState = document.getElementById('emptyState');
        const resultImage = document.getElementById('resultImage');
        const genderResult = document.getElementById('genderResult');
        const ageResult = document.getElementById('ageResult');
        const genderConfidence = document.getElementById('genderConfidence');
        const ageConfidence = document.getElementById('ageConfidence');
        const genderConfidenceValue = document.getElementById('genderConfidenceValue');
        const ageConfidenceValue = document.getElementById('ageConfidenceValue');

        let stream = null;
        let capturedImage = null;

        // Load models
        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('./models'),
            faceapi.nets.ageGenderNet.loadFromUri('./models'),
            faceapi.nets.faceLandmark68TinyNet.loadFromUri('./models')
        ]).then(startApp).catch(handleModelError);

        function startApp() {
            initializeEventListeners();
            uploadOption.click();
        }

        function initializeEventListeners() {
            // Mode switching
            uploadOption.addEventListener('click', switchToUpload);
            cameraOption.addEventListener('click', switchToCamera);

            // File upload
            uploadArea.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', handleFileUpload);

            // Camera controls
            captureBtn.addEventListener('click', captureImage);
            retakeBtn.addEventListener('click', retakePhoto);
            analyzeBtn.addEventListener('click', analyzeUploadedImage);
            analyzeCameraBtn.addEventListener('click', analyzeCapturedImage);
            resetBtn.addEventListener('click', resetApplication);

            // Window cleanup
            window.addEventListener('beforeunload', stopCamera);
        }

        async function switchToUpload() {
            uploadOption.classList.add('active');
            cameraOption.classList.remove('active');
            uploadSection.style.display = 'block';
            cameraContainer.style.display = 'none';
            stopCamera();
            resetApplication();
        }

        async function switchToCamera() {
            cameraOption.classList.add('active');
            uploadOption.classList.remove('active');
            uploadSection.style.display = 'none';
            cameraContainer.style.display = 'block';
            await startCamera();
            resetApplication();
        }

        async function handleFileUpload(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (file.size > 5 * 1024 * 1024) {
                alert('File size exceeds 5MB limit');
                return;
            }

            if (!['image/jpeg', 'image/png'].includes(file.type)) {
                alert('Only JPG/PNG files allowed');
                return;
            }

            const img = await faceapi.bufferToImage(file);
            resultImage.src = img.src;
            analyzeBtn.disabled = false;
        }

        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { width: 1280, height: 720, facingMode: 'user' },
                    audio: false
                });
                video.srcObject = stream;
                captureBtn.disabled = false;
            } catch (err) {
                console.error("Camera error:", err);
                alert("Camera access denied. Please check permissions.");
                switchToUpload();
            }
        }

        function captureImage() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            capturedImage = canvas.toDataURL('image/jpeg');
            resultImage.src = capturedImage;
            
            captureBtn.style.display = 'none';
            retakeBtn.style.display = 'inline-block';
            analyzeCameraBtn.style.display = 'inline-block';
            stopCamera();
        }

        function retakePhoto() {
            resultImage.src = '';
            captureBtn.style.display = 'inline-block';
            retakeBtn.style.display = 'none';
            analyzeCameraBtn.style.display = 'none';
            startCamera();
        }

        async function analyzeImage(imageElement) {
            loadingIndicator.style.display = 'block';
            try {
                const detections = await faceapi.detectSingleFace(
                    imageElement,
                    new faceapi.TinyFaceDetectorOptions()
                ).withFaceLandmarks(true).withAgeAndGender();

                if (!detections) throw new Error('No faces detected');

                displayResults({
                    gender: detections.gender,
                    age: Math.round(detections.age),
                    genderProbability: detections.genderProbability
                });
            } catch (error) {
                console.error('Analysis error:', error);
                alert('Analysis failed: ' + error.message);
            } finally {
                loadingIndicator.style.display = 'none';
                resultCard.style.display = 'block';
                emptyState.style.display = 'none';
                resetBtn.style.display = 'inline-block';
            }
        }

        function displayResults(data) {
            genderResult.textContent = data.gender;
            ageResult.textContent = `${data.age} years`;
            genderConfidence.style.width = `${(data.genderProbability * 100).toFixed(1)}%`;
            genderConfidenceValue.textContent = `${(data.genderProbability * 100).toFixed(1)}%`;
            document.querySelector('.detail-item:nth-child(2) .progress-container').style.display = 'none';
            ageConfidenceValue.textContent = 'N/A';
        }

        async function analyzeUploadedImage() {
            if (!fileInput.files[0]) return;
            const img = await faceapi.bufferToImage(fileInput.files[0]);
            analyzeImage(img);
        }

        async function analyzeCapturedImage() {
            if (!capturedImage) return;
            const blob = dataURLtoBlob(capturedImage);
            const img = await faceapi.bufferToImage(blob);
            analyzeImage(img);
        }

        function dataURLtoBlob(dataURL) {
            const arr = dataURL.split(',');
            const mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]);
            const u8arr = new Uint8Array(bstr.length);
            
            for (let i = 0; i < bstr.length; i++) {
                u8arr[i] = bstr.charCodeAt(i);
            }
            return new Blob([u8arr], { type: mime });
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        }

        function resetApplication() {
            fileInput.value = '';
            resultImage.src = '';
            analyzeBtn.disabled = true;
            capturedImage = null;
            stopCamera();
            
            if (cameraContainer.style.display === 'block') {
                startCamera();
            }

            resultCard.style.display = 'none';
            emptyState.style.display = 'block';
            resetBtn.style.display = 'none';
            genderResult.textContent = '-';
            ageResult.textContent = '-';
            genderConfidence.style.width = '0%';
            genderConfidenceValue.textContent = '0%';
        }

        function handleModelError(err) {
            console.error('Model loading error:', err);
            alert('Failed to load AI models. Please check console for details.');
        }
    </script>

    <?php include 'footer.html'?>
</body>
</html>....now no error but the buttons are not working upload and live camera