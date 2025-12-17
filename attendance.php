<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Attendance Tracker</title>
    <style>
        /* Navbar Styles */
        .navbar {
            background-color: rgb(5, 17, 164); 
            padding: 18px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        .appname {
            color: white;
            margin-right: auto; 
            font-size: 1.5em;
        }
        .navbar ul {
            display: flex;
            list-style-type: none;
            padding: 0; 
            margin: 0; 
        }
        .navbar ul li {
            margin-right: 8px;
            font-size: 0.9em;
        }
        .navbar ul li:first-child {
            margin-right: 40px;
        }
        .navbar ul li a {
            text-decoration: none;
            color: rgb(255, 255, 255);
        }
        .navbar ul li a:hover, .navbar ul li a:active {
            color: rgb(33, 34, 36);
            font-size: larger;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            padding: 5px; 
        }

        /* Main Content Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2, h3 {
            color: #333;
        }
        .camera-section {
            margin-bottom: 20px;
        }
        video {
            width: 100%;
            max-width: 640px;
            border: 2px solid #ddd;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .add-face-section {
            margin-top: 30px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background:rgb(5, 17, 164);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #45a049;
        }
        input {
            padding: 8px;
            margin: 5px 0;
            width: 200px;
        }

        /* Footer Styles */
        footer {
            background-color: rgb(5, 17, 164);
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="appname">AI-Driven Face Analysis</div>
        <ul>
            <li><a href="mainpage.php">Home</a></li>
            <li><a href="healthmonitoring.php">Health Monitoring</a></li>
            <li><a href="feature_detection.php">Emotion Detection</a></li>
            <li><a href="livenessdetection.php">Liveness Detection & Anti Spoofing</a></li>
            <li><a href="faceclassification.php">Face Classification</a></li>
            <li><a href="attendance.php">Smart Attendance Tracker</a></li>
            <li><a href="attendance.php">Age & Gender Prediction</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h2>Smart Attendance System</h2>

        <!-- Live Camera Section -->
        <div class="camera-section">
            <h3>Live Camera Feed</h3>
            <video id="liveCamera" autoplay></video>
        </div>

        <hr>

        <!-- Attendance Table -->
        <h3>Attendance Records</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Timestamp</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="attendance-list-body">
                <!-- Attendance records will be displayed here -->
            </tbody>
        </table>

        <!-- Add New Face Section -->
        <div class="add-face-section">
            <h3>Register New Person</h3>
            <input type="text" id="personName" placeholder="Enter name">
            <button id="captureBtn">Capture & Register Face</button>
            <div id="message"></div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 AI-Driven Face Analysis.</p>
    </footer>

    <script>
    document.addEventListener("DOMContentLoaded", async function() {
        const videoElement = document.getElementById("liveCamera");
        const captureBtn = document.getElementById("captureBtn");
        const personNameInput = document.getElementById("personName");
        const messageDiv = document.getElementById("message");

        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                videoElement.srcObject = stream;
            } catch (error) {
                console.error("Error accessing camera:", error);
                alert("Camera access failed: " + error.message);
            }
        }

        function captureFrame(video) {
            const canvas = document.createElement("canvas");
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext("2d");
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            return canvas.toDataURL("image/png");
        }

        async function markAttendance() {
            if (!videoElement.srcObject) return;

            const imageData = captureFrame(videoElement);
            try {
                const response = await fetch('http://127.0.0.1:5000/mark-attendance', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ image: imageData })
                });

                const result = await response.json();
                if (result.name && result.name !== "Unknown") {
                    const timestamp = new Date().toLocaleString();
                    const newRow = `<tr><td>${result.name}</td><td>${timestamp}</td><td>${result.status}</td></tr>`;
                    document.getElementById("attendance-list-body").insertAdjacentHTML('afterbegin', newRow);
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }

        // Register new face
        captureBtn.addEventListener("click", async () => {
            const name = personNameInput.value.trim();
            if (!name) {
                messageDiv.textContent = "Please enter a name";
                return;
            }

            const imageData = captureFrame(videoElement);
            try {
                const response = await fetch('http://127.0.0.1:5000/add-face', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name: name, image: imageData })
                });

                const result = await response.json();
                if (result.success) {
                    messageDiv.textContent = `Face registered successfully for ${name}!`;
                    personNameInput.value = "";
                }
            } catch (error) {
                messageDiv.textContent = "Error registering face";
                console.error("Error:", error);
            }
        });

        await startCamera();
        setInterval(markAttendance, 5000); // Check every 5 seconds
    });
    </script>
</body>
</html>
