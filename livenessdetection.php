<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Image Detector | Real vs AI-Generated Check</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #4895ef;
            --secondary: #3f37c9;
            --dark: #1a1a2e;
            --light: #f8f9fa;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --info: #560bad;
        }
        
       
        
        body {
            background-color: #f5f7fa;
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
            margin-bottom: 3rem;
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary);
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .subtitle {
            font-size: 1.1rem;
            color: #666;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .detector-container {
            display: flex;
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .upload-section {
            flex: 1;
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        
        .upload-section:hover {
            transform: translateY(-5px);
        }
        
        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 3rem 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .upload-area:hover {
            border-color: var(--primary-light);
            background: rgba(67, 97, 238, 0.03);
        }
        
        .upload-area.active {
            border-color: var(--primary);
            background: rgba(67, 97, 238, 0.05);
        }
        
        .upload-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        .upload-text {
            margin-bottom: 1rem;
        }
        
        .upload-text h3 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .upload-text p {
            color: #777;
        }
        
        .btn {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 0.8rem 1.8rem;
            border-radius: 50px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }
        
        .btn:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            box-shadow: none;
        }
        
        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }
        
        .result-section {
            flex: 1;
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
        
        .result-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        
        .result-title {
            font-size: 1.5rem;
            color: var(--dark);
        }
        
        .result-content {
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .default-result {
            color: #999;
        }
        
        .default-result .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #ddd;
        }
        
        .image-preview {
            max-width: 100%;
            max-height: 250px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: none;
        }
        
        .analysis-result {
            width: 100%;
            display: none;
        }
        
        .result-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .result-type {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .result-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.5rem;
        }
        
        .real .result-icon {
            background: rgba(76, 201, 240, 0.2);
            color: var(--success);
        }
        
        .ai .result-icon {
            background: rgba(247, 37, 133, 0.2);
            color: var(--danger);
        }
        
        .result-label {
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .confidence-meter {
            width: 100%;
            height: 10px;
            background: #eee;
            border-radius: 5px;
            margin-top: 1rem;
            overflow: hidden;
        }
        
        .confidence-level {
            height: 100%;
            border-radius: 5px;
            transition: width 0.5s ease;
        }
        
        .real .confidence-level {
            background: var(--success);
            width: 0%;
        }
        
        .ai .confidence-level {
            background: var(--danger);
            width: 0%;
        }
        
        .result-details {
            margin-top: 1.5rem;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }
        
        .detail-label {
            color: #777;
        }
        
        .detail-value {
            font-weight: 500;
        }
        
        .loading {
            display: none;
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 2rem auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 3rem;
        }
        
        .feature-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        .feature-title {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .feature-desc {
            color: #777;
        }
        
        footer {
            text-align: center;
            margin-top: 4rem;
            padding: 2rem 0;
            color: #777;
            border-top: 1px solid #eee;
        }
        
        @media (max-width: 768px) {
            .detector-container {
                flex-direction: column;
            }
            
            h1 {
                font-size: 2rem;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <?php include 'navbar.html' ?>
    <div class="container">
        <header>
            <h1>AI Image Detector</h1>
            <p class="subtitle">Upload any image to check if it was generated by artificial intelligence or captured from real life. Our advanced detection system analyzes multiple factors to determine authenticity.</p>
        </header>
        
        <div class="detector-container">
            <div class="upload-section">
                <div class="upload-area" id="uploadArea">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text">
                        <h3>Upload Your Image</h3>
                        <p>Drag & drop your image here or click to browse files</p>
                    </div>
                    <button class="btn">Select Image</button>
                    <input type="file" id="fileInput" accept="image/*" style="display: none;">
                </div>
                
                <div class="upload-details">
                    <h3>How to get accurate results:</h3>
                    <ul style="margin-left: 1.5rem; color: #666; margin-top: 0.5rem;">
                        <li>Use clear, high-quality images</li>
                        <li>Upload original files (not screenshots)</li>
                        <li>Face images work best for detection</li>
                        <li>Avoid heavily edited or filtered images</li>
                    </ul>
                </div>
            </div>
            
            <div class="result-section">
                <div class="result-header">
                    <h2 class="result-title">Detection Result</h2>
                    <div class="result-actions">
                        <!-- Can add buttons here if needed -->
                    </div>
                </div>
                
                <div class="result-content">
                    <div class="default-result">
                        <div class="icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>No Image Analyzed</h3>
                        <p>Upload an image to check if it's AI-generated or real</p>
                    </div>
                    
                    <img src="" alt="Preview" class="image-preview" id="imagePreview">
                    
                    <div class="loading" id="loadingSpinner"></div>
                    
                    <div class="analysis-result" id="analysisResult">
                        <div class="result-card" id="resultCard">
                            <div class="result-type">
                                <div class="result-icon">
                                    <i class="fas" id="resultIcon"></i>
                                </div>
                                <div>
                                    <h3 class="result-label" id="resultLabel">Result</h3>
                                    <p id="resultDescription">Description will appear here</p>
                                </div>
                            </div>
                            <div class="confidence-meter">
                                <div class="confidence-level" id="confidenceLevel"></div>
                            </div>
                        </div>
                        
                        <div class="result-details">
                            <h4>Analysis Details</h4>
                            <div class="detail-item">
                                <span class="detail-label">Color Analysis</span>
                                <span class="detail-value" id="colorValue">--</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Texture Patterns</span>
                                <span class="detail-value" id="textureValue">--</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Frequency Analysis</span>
                                <span class="detail-value" id="frequencyValue">--</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Noise Patterns</span>
                                <span class="detail-value" id="noiseValue">--</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-atom"></i>
                </div>
                <h3 class="feature-title">Advanced AI Detection</h3>
                <p class="feature-desc">Our system analyzes multiple technical aspects of images to identify AI-generated content with high accuracy.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="feature-title">Privacy Focused</h3>
                <p class="feature-desc">Your images are processed securely and never stored on our servers after analysis is complete.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3 class="feature-title">Fast Results</h3>
                <p class="feature-desc">Get detection results in seconds with our optimized analysis algorithms.</p>
            </div>
        </div>
        
        
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('fileInput');
            const imagePreview = document.getElementById('imagePreview');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const analysisResult = document.getElementById('analysisResult');
            const defaultResult = document.querySelector('.default-result');
            const resultCard = document.getElementById('resultCard');
            const resultIcon = document.getElementById('resultIcon');
            const resultLabel = document.getElementById('resultLabel');
            const resultDescription = document.getElementById('resultDescription');
            const confidenceLevel = document.getElementById('confidenceLevel');
            
            // Detail values
            const colorValue = document.getElementById('colorValue');
            const textureValue = document.getElementById('textureValue');
            const frequencyValue = document.getElementById('frequencyValue');
            const noiseValue = document.getElementById('noiseValue');
            
            // Handle drag and drop
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('active');
            });
            
            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('active');
            });
            
            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('active');
                
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    handleFileUpload(fileInput.files[0]);
                }
            });
            
            // Handle click to select file
            uploadArea.addEventListener('click', () => {
                fileInput.click();
            });
            
            // Handle file selection
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length) {
                    handleFileUpload(e.target.files[0]);
                }
            });
            
            function handleFileUpload(file) {
                // Check if file is an image
                if (!file.type.match('image.*')) {
                    alert('Please select an image file (JPEG, PNG, etc.)');
                    return;
                }
                
                // Check file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File is too large. Maximum size is 5MB.');
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    defaultResult.style.display = 'none';
                    
                    // Show loading spinner
                    loadingSpinner.style.display = 'block';
                    analysisResult.style.display = 'none';
                    
                    // Simulate analysis (in a real app, this would be an API call)
                    setTimeout(() => {
                        analyzeImage(file);
                    }, 1500);
                };
                reader.readAsDataURL(file);
            }
            
            function analyzeImage(file) {
                // Hide loading spinner
                loadingSpinner.style.display = 'none';
                
                // Show result section
                analysisResult.style.display = 'block';
                
                // Simulate analysis results (random for demo)
                // In a real app, you would call your backend API here
                const isReal = Math.random() > 0.5;
                const confidence = Math.floor(Math.random() * 30) + 70; // 70-100%
                
                // Random detail values
                const colorScore = (Math.random() * 50 + 50).toFixed(1);
                const textureScore = (Math.random() * 50 + 50).toFixed(1);
                const frequencyScore = (Math.random() * 5).toFixed(3);
                const noiseScore = (Math.random()).toFixed(3);
                
                // Set the UI based on results
                if (isReal) {
                    resultCard.className = 'result-card real';
                    resultIcon.className = 'fas fa-check-circle';
                    resultLabel.textContent = 'Real Image Detected';
                    resultDescription.textContent = 'Our analysis suggests this is a genuine photograph.';
                } else {
                    resultCard.className = 'result-card ai';
                    resultIcon.className = 'fas fa-robot';
                    resultLabel.textContent = 'AI-Generated Detected';
                    resultDescription.textContent = 'Our analysis suggests this image was created by AI.';
                }
                
                // Animate confidence meter
                setTimeout(() => {
                    confidenceLevel.style.width = confidence + '%';
                }, 100);
                
                // Set detail values
                colorValue.textContent = colorScore;
                textureValue.textContent = textureScore;
                frequencyValue.textContent = frequencyScore;
                noiseValue.textContent = noiseScore;
                
                // In a real implementation, you would:
                // 1. Send the image to your backend API
                // 2. Get back the analysis results
                // 3. Update the UI with the real data
                // Here's what that might look like:
                /*
                const formData = new FormData();
                formData.append('image', file);
                
                fetch('/api/analyze', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    loadingSpinner.style.display = 'none';
                    
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    
                    analysisResult.style.display = 'block';
                    
                    if (data.isReal) {
                        // Update UI for real image
                    } else {
                        // Update UI for AI image
                    }
                    
                    // Update all the detail fields
                    colorValue.textContent = data.details.colorScore;
                    // ... and so on for other fields
                })
                .catch(error => {
                    loadingSpinner.style.display = 'none';
                    alert('Error analyzing image: ' + error.message);
                });
                */
            }
        });
    </script>
    <?php include 'footer.html' ?>
</body>
</html>