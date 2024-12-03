<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <style>
        body {
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
        }
        
        .main-content {
            padding: 20px; 
        }
        
        h1 {
            color: rgb(5, 17, 164); 
        }

        .features {
            display: flex;
            flex-direction: column; 
            margin-top: 20px;
        }

        .feature-item {
            background-color: rgba(5, 17, 164, 0.1); 
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .feature-item h2 {
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }

        .feature-item img {
            height: 60px; 
            width: 60px; 
            transform: scale(1.5); 
            transition: transform 0.3s ease; 
        }

        .feature-item img:hover {
            transform: scale(1.8); 
        }
    </style>
</head>
<body>

    <?php include 'navbar.html'; ?> <!-- Include the navbar -->

    <div class="main-content">
        <h1>Welcome to AI-Driven Face Analysis</h1>
        <p>Explore our advanced features that help you analyze facial data effectively.</p>

        <div class="features">
            <div class="feature-item">
                <h2>Feature Detection <img src="images/authentication.png" alt="Scan Icon"></h2>
                <p>Monitor various facial features with our advanced technology.</p>
            </div>

            <div class="feature-item">
                <h2>Liveness Detection <img src="images/task.png" alt="Liveness Icon"></h2>
                <p>Ensure the authenticity of image or video through our liveness detection technology.</p>
            </div>

            <div class="feature-item">
                <h2>Health Monitoring <img src="images/health-monitor-watch.png" alt="Health Icon"></h2>
                <p>Ensure the authenticity of measuring heart rate, blood pressure and stress using facial scanning.</p>
            </div>

            <div class="feature-item">
                <h2>Smart Attendance Tracker <img src="images/check.png" alt="Attendance Icon"></h2>
                <p>Track attendance effectively using facial recognition technology.</p>
            </div>

            <div class="feature-item">
                <h2>Demographics Analysis <img src="images/privacy.png" alt="Demographics Icon"></h2>
                <p>Analyze demographic data based on facial recognition analysis.</p>
            </div>
        </div>

    </div>

    <?php include 'footer.html'; ?> <!-- Include the footer -->

</body>
</html>