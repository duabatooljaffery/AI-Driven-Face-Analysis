<?php
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['image'])) {
    echo json_encode(['error' => 'No image received']);
    exit;
}

$imageData = $data['image'];
$imageData = explode(",", $imageData)[1];
$imagePath = "captured.jpg";
file_put_contents($imagePath, base64_decode($imageData));

// Call Python script to detect emotion
$output = shell_exec("python3 detect_emotion.py $imagePath");

// Example output from Python: "happy"
$emotion = trim($output);

// Quotes based on emotion
$quotes = [
    "happy" => "Happiness is contagious. Share your smile!",
    "sad" => "It’s okay to feel sad. Brighter days are ahead.",
    "angry" => "Take a deep breath. Let peace in.",
    "surprised" => "Life is full of surprises—embrace them.",
    "disgusted" => "Focus on what brings you joy.",
    "fearful" => "You are braver than you believe.",
    "neutral" => "Stay calm and carry on.",
    "unknown" => "We couldn’t detect your emotion."
];

$response = [
    'emotion' => ucfirst($emotion),
    'quote' => $quotes[$emotion] ?? "Stay strong and positive!"
];

echo json_encode($response);
