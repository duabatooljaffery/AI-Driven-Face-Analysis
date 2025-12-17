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
    flex-wrap: wrap;
    gap: 20px; /* Add space between items */
    margin-top: 20px;
}

.feature-item {
    background-color: rgba(5, 17, 164, 0.1);
    border: 1px solid rgba(5, 17, 164, 0.2); /* Add a border */
    border-radius: 8px;
    padding: 15px;
    flex: 1 1 calc(33.33% - 20px);
    box-sizing: border-box;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add shadow for separation */
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.feature-item:hover {
    transform: translateY(-5px); /* Slight lift effect on hover */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Stronger shadow on hover */
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

       
    </style>
</head>
<body>

    <?php include 'navbar.html'; ?> <!-- Include the navbar -->

    <div class="main-content">
        <h1>Welcome to AI-Driven Face Analysis</h1>
        <p>Explore our advanced features that help you analyze facial data effectively.</p>

        <div class="features">
            <div class="feature-item">
                <h2>Emotion Detection with Suggestion<img src="images/authentication.png" alt="Scan Icon"></h2>
                <p>Monitor various facial features with our advanced technology.</p>
            </div>

            <div class="feature-item">
                <h2>Liveness Detection <img src="images/task.png" alt="Liveness Icon"></h2>
                <p>Ensure the authenticity of image or video through our liveness detection technology.</p>
            </div>

            <div class="feature-item">
                <h2>FaceShape & StyleGuide <img src="images/faceshape.png" alt="Liveness Icon"></h2>
                <p>Identify your face shape and get personalized hairstyle, glasses and Facial Hair recommendations for a confident look.</p>
            </div>

            <div class="feature-item">
                <h2>Health Monitoring <img src="images/health-monitor-watch.png" alt="Health Icon"></h2>
                <p>Ensure the authenticity of measuring heart rate, blood pressure and stress using facial scanning.</p>
            </div>

            <div class="feature-item">
                <h2>Track Smart Attendance<img src="images/check.png" alt="Attendance Icon"></h2>
                <p>Track attendance effectively using facial recognition technology.</p>
            </div>

            <div class="feature-item">
                <h2>Age & Gender Prediction<img src="images/age-range.png" alt="Attendance Icon"></h2>
                <p>Predict age and gender accurately with advanced facial analysis.</p>
            </div>

           
        </div>

    </div>

    <?php include 'footer.html'?> <!-- Include the footer -->

</body>
</html>