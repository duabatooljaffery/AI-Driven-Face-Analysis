<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Shape Classifier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 3rem auto;
            background: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007bff;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        input[type="file"], select {
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 0.7rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        button:hover {
            background-color: #0056b3;
        }
        .result {
            margin-top: 1.5rem;
            padding: 1rem;
            border: 1px solid #007bff;
            border-radius: 4px;
            background-color: #e9f5ff;
            display: none;
        }
        .image-preview {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }
        .image-preview img {
            max-width: 100%;
            height: auto;
            max-height: 300px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <?php include 'navbar.html'; ?>
    <div class="container">
        <h1>Face Shape Classifier</h1>
        <form id="uploadForm">
            <label for="genderSelect">Select Gender:</label>
            <select id="genderSelect" name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            
            <label for="imageInput">Upload an image:</label>
            <input type="file" id="imageInput" name="image" accept="image/*" required>
            
            <div class="image-preview" id="imagePreview">
                <p>No image uploaded yet.</p>
            </div>
            
            <button type="submit">Classify</button>
        </form>

        <div class="result" id="result">
            <p><strong>Face Shape:</strong> <span id="faceShape"></span></p>
            <p><strong>Hairstyle Suggestion:</strong> <span id="hairstyleSuggestion"></span></p>
            <p><strong>Glasses Suggestion:</strong> <span id="glassesSuggestion"></span></p>
            <p><strong>Facial Hair Suggestion:</strong> <span id="facialHairSuggestion"></span></p>
        </div>
    </div>
    
    <script>
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');

        // Show image preview when an image is selected
        imageInput.addEventListener('change', () => {
            const file = imageInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" alt="Image Preview">`;
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.innerHTML = '<p>No image uploaded yet.</p>';
            }
        });

        document.getElementById('uploadForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData();
            const fileInput = document.getElementById('imageInput');
            const genderSelect = document.getElementById('genderSelect').value;

            formData.append('image', fileInput.files[0]);
            formData.append('gender', genderSelect);

            try {
                const response = await fetch('http://127.0.0.1:5000/faceclassification', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                if (data.success) {
                    document.getElementById('faceShape').textContent = data.data.face_shape;
                    document.getElementById('hairstyleSuggestion').textContent = data.data.hairstyle;
                    document.getElementById('glassesSuggestion').textContent = data.data.glasses;
                    document.getElementById('facialHairSuggestion').textContent = data.data.facial_hair || "N/A";
                    document.getElementById('result').style.display = 'block';
                } else {
                    alert(data.error || 'An error occurred.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to classify the image.');
            }
        });
    </script>
</body>
</html>
