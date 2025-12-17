from flask import Flask, request, jsonify
import cv2
import numpy as np
from werkzeug.utils import secure_filename
import os
from datetime import datetime
import urllib.request

app = Flask(__name__)

# Configuration
UPLOAD_FOLDER = 'uploads'
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg'}
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

# Model files and URLs
MODEL_DIR = "models"
MODEL_FILES = {
    "face_model": {
        "file": "opencv_face_detector_uint8.pb",
        "url": "https://raw.githubusercontent.com/spmallick/learnopencv/master/AgeGender/opencv_face_detector_uint8.pb"
    },
    "face_proto": {
        "file": "opencv_face_detector.pbtxt",
        "url": "https://raw.githubusercontent.com/spmallick/learnopencv/master/AgeGender/opencv_face_detector.pbtxt"
    },
    "age_model": {
        "file": "age_net.caffemodel",
        "url": "https://raw.githubusercontent.com/spmallick/learnopencv/master/AgeGender/age_net.caffemodel"
    },
    "age_proto": {
        "file": "age_deploy.prototxt",
        "url": "https://raw.githubusercontent.com/spmallick/learnopencv/master/AgeGender/age_deploy.prototxt"
    },
    "gender_model": {
        "file": "gender_net.caffemodel",
        "url": "https://raw.githubusercontent.com/spmallick/learnopencv/master/AgeGender/gender_net.caffemodel"
    },
    "gender_proto": {
        "file": "gender_deploy.prototxt",
        "url": "https://raw.githubusercontent.com/spmallick/learnopencv/master/AgeGender/gender_deploy.prototxt"
    }
}

def download_file(file_name, url):
    file_path = os.path.join(MODEL_DIR, file_name)
    if not os.path.exists(file_path):
        print(f"Downloading {file_name}...")
        urllib.request.urlretrieve(url, file_path)
    return file_path

# Load pre-trained models
def load_models():
    os.makedirs(MODEL_DIR, exist_ok=True)

    face_model = download_file(MODEL_FILES["face_model"]["file"], MODEL_FILES["face_model"]["url"])
    face_proto = download_file(MODEL_FILES["face_proto"]["file"], MODEL_FILES["face_proto"]["url"])
    age_model = download_file(MODEL_FILES["age_model"]["file"], MODEL_FILES["age_model"]["url"])
    age_proto = download_file(MODEL_FILES["age_proto"]["file"], MODEL_FILES["age_proto"]["url"])
    gender_model = download_file(MODEL_FILES["gender_model"]["file"], MODEL_FILES["gender_model"]["url"])
    gender_proto = download_file(MODEL_FILES["gender_proto"]["file"], MODEL_FILES["gender_proto"]["url"])

    face_net = cv2.dnn.readNet(face_model, face_proto)
    age_net = cv2.dnn.readNet(age_model, age_proto)
    gender_net = cv2.dnn.readNet(gender_model, gender_proto)

    return face_net, age_net, gender_net

# Model lists
MODEL_MEAN_VALUES = (78.4263377603, 87.7689143744, 114.895847746)
AGE_LIST = ['0-2', '4-6', '8-12', '15-20', '25-32', '38-43', '48-53', '60-100']
GENDER_LIST = ['Male', 'Female']

# Initialize models
face_net, age_net, gender_net = load_models()

def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

def get_faces(img, net, conf_threshold=0.7):
    blob = cv2.dnn.blobFromImage(img, 1.0, (300, 300), [104, 117, 123], True, False)
    net.setInput(blob)
    detections = net.forward()
    faces = []
    h, w = img.shape[:2]

    for i in range(detections.shape[2]):
        confidence = detections[0, 0, i, 2]
        if confidence > conf_threshold:
            box = detections[0, 0, i, 3:7] * np.array([w, h, w, h])
            (startX, startY, endX, endY) = box.astype("int")
            startX, startY = max(0, startX), max(0, startY)
            endX, endY = min(w - 1, endX), min(h - 1, endY)
            faces.append((startX, startY, endX, endY))
    return faces

def predict_age_gender(img, face):
    (startX, startY, endX, endY) = face
    face_img = img[startY:endY, startX:endX]
    if face_img.shape[0] < 20 or face_img.shape[1] < 20:
        return None
    face_blob = cv2.dnn.blobFromImage(face_img, 1.0, (227, 227), MODEL_MEAN_VALUES, swapRB=False)

    gender_net.setInput(face_blob)
    gender_preds = gender_net.forward()
    gender = GENDER_LIST[gender_preds[0].argmax()]
    gender_confidence = gender_preds[0].max()

    age_net.setInput(face_blob)
    age_preds = age_net.forward()
    age = AGE_LIST[age_preds[0].argmax()]
    age_low, age_high = map(int, age.split('-'))
    age_avg = (age_low + age_high) // 2

    return {
        'gender': gender,
        'gender_confidence': float(gender_confidence),
        'age': age_avg,
        'age_low': age_low,
        'age_high': age_high,
        'face_box': [int(startX), int(startY), int(endX), int(endY)]
    }

@app.route('/predict', methods=['POST'])
def predict():
    if 'image' not in request.files:
        return jsonify({'error': 'No image file provided'}), 400

    file = request.files['image']
    if file.filename == '':
        return jsonify({'error': 'No selected file'}), 400

    try:
        img_bytes = file.read()
        nparr = np.frombuffer(img_bytes, np.uint8)
        img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)

        if img is None:
            return jsonify({'error': 'Could not read image file'}), 400

        img_rgb = cv2.cvtColor(img, cv2.COLOR_BGR2RGB)
        faces = get_faces(img, face_net)

        if not faces:
            return jsonify({'predictions': []})

        predictions = []
        for face in faces:
            result = predict_age_gender(img, face)
            if result:
                predictions.append(result)

        return jsonify({'predictions': predictions})

    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/')
def index():
    return "Age and Gender Prediction API is running. Use the frontend to interact with the system."

if __name__ == '__main__':
    os.makedirs(UPLOAD_FOLDER, exist_ok=True)
    app.run(host='0.0.0.0', port=5000, debug=True)
