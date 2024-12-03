<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liveness Detection</title>
    <style>
        .flex-container {
            display: flex;
            justify-content: space-between; 
            margin: 20px; 
        }

        .sidebar {
            background-color: rgb(5 17 164 / 10%);
            width: 23%; 
            border-radius: 10px;
            padding:1px;
            height: 80vh; 
            overflow-y: auto;
            
        }

        .container {
            text-align: center;
            background-color: rgb(5 17 164 / 10%);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            animation: containerAnimation 2s ease-out;
            max-width: 600px;
            width: 75%; /* Adjust width to fit the layout */
            margin-left: 20px; /* Add space between sidebar and container */
            margin-top: 3%;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        .button-container button,
        .button-container label {
            padding: 10px;
            background-color: rgb(5, 17, 164);
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button-container button:hover,
        .button-container label:hover {
            background-color: #218838;
        }

        .comparison-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }

        .comparison-container img {
            width: 100px;
            height: auto;
            margin: 0 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .comparison-container img:hover {
            transform: scale(1.3);
        }

        .camera-container {
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .camera-container h2 {
            color: rgb(5, 17, 164);
            margin-bottom: 20px;
            font-size: 24px;
        }

        .camera-container video {
            width: 80%;
            border-radius: 10px;
            border: 2px solid rgb(5, 17, 164);
            background-color: black;
        }

        input[type="file"] {
            display: none;
        }

        @media (max-width: 480px) {
            .comparison-container {
                flex-direction: column;
            }

            .comparison-container img {
                margin: 10px 0;
            }
        }
        .text{
            margin:10px;
            padding:10px;
            line-height:1.6;
        }
    </style>
</head>
<body>
    <?php include 'navbar.html'?>
    <div class='flex-container'>
        <div class="sidebar">
            <div class='text'>
            <h3>What is Liveness Detection?</h3>
            <p>Liveness detection ensures that the face presented is from a live person, not a photo or video.</p>
            <h4>Steps to Use:</h4>
            <ol>
                <li>Click on "Show Live Camera" to activate your webcam.</li>
                <li>If prompted, allow the browser to access your webcam.</li>
                <li>Ensure your face is clearly visible in the camera for accurate detection.</li>
                <li>Alternatively,you can also upload a picture using the "Upload Your Picture" button.</li>
                <li>Make sure the uploaded picture is clear for better analysis.</li>
                <li>Follow the on-screen instructions to verify if the image is AI-generated or from a live person.</li>
            </ol>
    </div>       
        </div>
        <div class="container">
            <p class="info">Verify Face is Real Or AI-Generated</p>

            <div class="button-container">
                <button id="showCameraBtn">Show Live Camera</button>
                <label for="fileInput" class="upload-button">Upload Your Picture</label>
                <input type="file" id="fileInput" accept=".jpg,.png,.jpeg">
            </div>

            <div class="camera-container" id="cameraContainer" style="display:none;">
                <h2>Show Your Face</h2>
                <video id="cameraFeed" autoplay muted playsinline></video>
            </div>

            <div class="comparison-container">
                <img src="images/head.png" alt="Human Head">
                <p style="font-size:24px; font-weight:bold;">vs</p>
                <img src="images/humanoid-robot.png" alt="Humanoid Robot">
            </div>
        </div>
    </div>

    <script>
        const video = document.getElementById('cameraFeed');

        document.getElementById('showCameraBtn').addEventListener('click', function() {
            document.getElementById('cameraContainer').style.display = 'block';

            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then((stream) => {
                        video.srcObject = stream;
                    })
                    .catch((error) => {
                        alert('Unable to access the camera.');
                    });
            } else {
                alert('Your browser does not support camera functionality.');
            }
        });

        document.getElementById('fileInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    alert('Picture uploaded successfully!');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>