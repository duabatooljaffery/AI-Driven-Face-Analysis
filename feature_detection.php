<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emotion Detection</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    nav {
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        text-align: center;
    }

    .container {
        display: flex;
        padding: 20px;
        gap: 20px;
    }

    .sidebar {
        background: linear-gradient(135deg, rgba(5, 17, 164, 0.8), rgba(0, 102, 204, 0.8));
        width: 250px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        padding: 15px;
        transition: all 0.3s ease-in-out;
    }

    .sidebar:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
    }

    .sidebar h2 {
        color: white;
        text-align: center;
        margin-bottom: 15px;
        font-size: 22px;
        font-weight: bold;
    }

    .sidebar ul {
        display: flex;
        flex-direction: column;
        padding: 0;
        margin: 0;
        list-style-type: none;
    }

    .sidebar ul li {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        transition: background-color 0.3s ease, transform 0.2s ease;
        cursor: pointer;
    }

    .sidebar ul li:hover {
        background-color: rgba(255, 255, 255, 0.5);
        transform: translateX(5px);
        font-weight: bold;
    }

    .sidebar ul li:nth-child(odd) {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .camera-container {
        flex-grow: 1;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }

    .camera-container h2 {
        color: rgb(5, 17, 164);
        margin-bottom: 20px;
        font-size: 24px;
    }

    .camera-container #cameraFeed {
        width: 500px;
        height: 300px;
        border-radius: 10px;
        border: 5px solid rgb(5, 17, 164);
        background: black;
        box-shadow: 0 0 10px rgba(5, 17, 164, 0.8),
                    0 0 20px rgba(5, 17, 164, 0.6),
                    0 0 30px rgba(5, 17, 164, 0.4);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 0 10px rgba(5, 17, 164, 0.8),
                        0 0 20px rgba(5, 17, 164, 0.6),
                        0 0 30px rgba(5, 17, 164, 0.4);
        }
        50% {
            box-shadow: 0 0 20px rgba(5, 17, 164, 1),
                        0 0 30px rgba(5, 17, 164, 0.8),
                        0 0 40px rgba(5, 17, 164, 0.6);
        }
    }

    .images li {
        margin-left: 10px;
    }

    .images li img {
        height: 20px;
        width: 20px;
        transform: scale(2.5);
        transition: transform 0.3s ease;
        display: inline;
    }

    </style>
</head>
<body>
    <?php include 'navbar.html'; ?>
    <div class="container">
        <div class="sidebar">
            <h2>Emotional Features</h2>
            <ul class="images">
                <li>Happy</li>
                <li>Sad</li>
                <li>Angry</li>
                <li>Surprise</li>
                <li>Neutral</li>
                <li>Fear</li>
                <li>Disgust</li>
            </ul>
        </div>
        <div class="camera-container">
            <h2>Show Your Face</h2>
            <img id="cameraFeed" src="http://127.0.0.1:5000/video_feed" alt="Emotion Detection Stream">
            <h3 id="emotionResult" class="emotion-text">Emotion: Detecting...</h3>
        </div>
    </div>

    <script>
        async function fetchEmotionData() {
            try {
                const response = await fetch('http://127.0.0.1:5000/emotion');
                if (response.ok) {
                    const data = await response.json();
                    document.getElementById('emotionResult').innerText = `Emotion: ${data.emotion}`;
                } else {
                    console.error('Error fetching emotion data:', response.statusText);
                }
            } catch (error) {
                console.error('Error fetching emotion data:', error);
            }
        }

        setInterval(fetchEmotionData, 2000);
    </script>
</body>
</html>
