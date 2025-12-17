<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Health Monitoring</title>
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
        ul {
            display: flex;
            list-style-type: none;
            padding: 0; 
            margin: 0; 
        }
        ul li {
            margin-right: 8px;
            font-size: 0.9em;
        }
        ul li:first-child {
            margin-right: 40px;
        }
        ul li a {
            text-decoration: none;
            color: rgb(255, 255, 255);
        }
        ul li a:hover,a:active {
            color: rgb(33, 34, 36);
            font-size: larger;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            padding: 5px; 
        }

        /* Body Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .camera-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }
        .results-section {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .metric-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 200px;
            text-align: center;
        }
        video {
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            background: #000;
        }
        h2 {
            color: #2c3e50;
            margin-top: 0;
        }
        #heartRate, #bloodPressure, #stressLevel {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        .heart-rate-box { border-top: 5px solid #e74c3c; }
        .blood-pressure-box { border-top: 5px solid #3498db; }
        .stress-level-box { border-top: 5px solid #f39c12; }
        .status-indicator {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: inline-block;
            margin-left: 10px;
        }
        .status-normal { background-color: #2ecc71; }
        .status-warning { background-color: #f39c12; }
        .status-danger { background-color: #e74c3c; }
        .instructions {
            margin-top: 15px;
            font-size: 14px;
            color: #666;
        }

        /* Footer Styles */
        footer {
            background-color: rgb(5, 17, 164);
            color: white;
            text-align: center;
            padding: 15px 10px;
            font-size: 14px;
            position: relative;
            margin-top: 40px;
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

    <div class="container">
        <!-- Live Camera Feed -->
        <section class="camera-section">
            <h2>Live Health Monitoring</h2>
            <video id="liveCamera" autoplay playsinline></video>
            <p class="instructions">Ensure good lighting and keep your face visible.</p>
        </section>

        <!-- Real-Time Health Metrics -->
        <section class="results-section">
            <!-- Heart Rate -->
            <div class="metric-box heart-rate-box">
                <h2>Heart Rate</h2>
                <p id="heartRate">--</p>
                <p>Status: <span id="hrStatus">Calculating...</span> <span class="status-indicator" id="hrIndicator"></span></p>
            </div>

            <!-- Blood Pressure -->
            <div class="metric-box blood-pressure-box">
                <h2>Blood Pressure</h2>
                <p id="bloodPressure">--/--</p>
                <p>Status: <span id="bpStatus">Calculating...</span> <span class="status-indicator" id="bpIndicator"></span></p>
            </div>

            <!-- Stress Level -->
            <div class="metric-box stress-level-box">
                <h2>Stress Level</h2>
                <p id="stressLevel">--%</p>
                <p>Status: <span id="stressStatus">Calculating...</span> <span class="status-indicator" id="stressIndicator"></span></p>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2025 AI-Driven Face Analysis. All rights reserved.
    </footer>

    <script>
        // DOM Elements
        const video = document.getElementById('liveCamera');
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        const heartRateEl = document.getElementById('heartRate');
        const bloodPressureEl = document.getElementById('bloodPressure');
        const stressLevelEl = document.getElementById('stressLevel');
        
        const hrStatusEl = document.getElementById('hrStatus');
        const bpStatusEl = document.getElementById('bpStatus');
        const stressStatusEl = document.getElementById('stressStatus');
        const hrIndicator = document.getElementById('hrIndicator');
        const bpIndicator = document.getElementById('bpIndicator');
        const stressIndicator = document.getElementById('stressIndicator');

        async function startHealthMonitoring() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { width: { ideal: 640 }, height: { ideal: 480 } } 
                });
                video.srcObject = stream;
                setInterval(() => {
                    captureAndSendFrame();
                }, 1000);
            } catch (error) {
                console.error("Camera error:", error);
                alert("Could not access camera. Please enable permissions.");
            }
        }

        function captureAndSendFrame() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL('image/jpeg', 0.8);
            fetch('http://localhost:5000/video_feed', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ image: imageData })
            }).catch(err => console.error("Frame send error:", err));
            fetchHealthMetrics();
        }

        function fetchHealthMetrics() {
            fetch('http://localhost:5000/get_health_metrics')
                .then(response => response.json())
                .then(data => { updateHealthUI(data); })
                .catch(err => console.error("Metrics fetch error:", err));
        }

        function updateHealthUI(data) {
            if (data.heart_rate) {
                heartRateEl.textContent = `${Math.round(data.heart_rate)} BPM`;
                updateStatus('hr', data.heart_rate, { normal: [60, 100], warning: [50, 110] });
            }
            if (data.blood_pressure) {
                const bp = data.blood_pressure;
                bloodPressureEl.textContent = `${Math.round(bp.systolic)}/${Math.round(bp.diastolic)} mmHg`;
                updateStatus('bp', bp.systolic, { normal: [90, 120], warning: [80, 140] });
            }
            if (data.stress_level) {
                const stressValue = data.stress_level === "High" ? 75 : 25;
                stressLevelEl.textContent = `${stressValue}%`;
                updateStatus('stress', stressValue, { normal: [0, 40], warning: [40, 70] });
            }
        }

        function updateStatus(type, value, ranges) {
            let statusEl, indicatorEl, status = 'normal';
            if (type === 'hr') { statusEl = hrStatusEl; indicatorEl = hrIndicator; }
            else if (type === 'bp') { statusEl = bpStatusEl; indicatorEl = bpIndicator; }
            else { statusEl = stressStatusEl; indicatorEl = stressIndicator; }
            if (value < ranges.normal[0] || value > ranges.normal[1]) {
                status = value < ranges.warning[0] || value > ranges.warning[1] ? 'danger' : 'warning';
            }
            statusEl.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            indicatorEl.className = 'status-indicator status-' + status;
        }

        startHealthMonitoring();
    </script>
</body>
</html>
