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
        }
    </style>
</head>
<body>

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
            
            <button type="submit">Classify</button>
        </form>

        <div class="result" id="result" style="display: none;">
            <p><strong>Face Shape:</strong> <span id="faceShape"></span></p>
            <div class="suggestion">
                <p><strong>Suggestions:</strong></p>
                <p class="male"><strong>For Males:</strong> <span id="maleSuggestion"></span></p>
                <p class="female"><strong>For Females:</strong> <span id="femaleSuggestion"></span></p>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('uploadForm');
        const resultDiv = document.getElementById('result');
        const faceShape = document.getElementById('faceShape');
        const maleSuggestion = document.getElementById('maleSuggestion');
        const femaleSuggestion = document.getElementById('femaleSuggestion');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(form);

            try {
                const response = await fetch('http://127.0.0.1:5000/classify', { method: 'POST', body: formData });
                const data = await response.json();

                if (data.success) {
                    faceShape.textContent = data.data.face_shape;

                    // Display suggestions based on gender
                    if (data.data.gender === 'male') {
                        maleSuggestion.textContent = data.data.suggestions.male || "No suggestions available.";
                        femaleSuggestion.textContent = "";
                    } else {
                        femaleSuggestion.textContent = data.data.suggestions.female || "No suggestions available.";
                        maleSuggestion.textContent = "";
                    }

                    resultDiv.style.display = 'block';
                } else {
                    alert(data.message || 'An error occurred.');
                }
            } catch (error) {
                console.error(error);
                alert('Failed to classify the image.');
            }
        });
    </script>
</body>
</html>