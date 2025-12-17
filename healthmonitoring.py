from flask import Flask, request, jsonify
from flask_cors import CORS
import cv2
import numpy as np
import time
import threading
import base64
from scipy.signal import find_peaks
from collections import deque
import random

app = Flask(__name__)
CORS(app)

# Global variables
current_hr = 70.0
current_stress = 0.0
current_bp = {"systolic": 120.0, "diastolic": 80.0}
latest_frame = None

# Buffers for smoothing
HR_HISTORY = deque(maxlen=15)
STRESS_HISTORY = deque(maxlen=10)
BP_HISTORY = deque(maxlen=10)

# Physiological limits
MAX_HR_CHANGE = 2.0
MAX_BP_CHANGE = 1.5

# Load face detector
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')


def detect_face_roi(frame):
    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
    faces = face_cascade.detectMultiScale(gray, 1.3, 5)
    if len(faces) > 0:
        x, y, w, h = faces[0]
        roi = frame[y:y + h // 3, x:x + w]
        return roi
    return None


def extract_green_channel_ppg(frame):
    roi = detect_face_roi(frame)
    if roi is not None:
        green_channel = roi[:, :, 1]
        avg_intensity = np.mean(green_channel)
        return avg_intensity
    return None


PPG_BUFFER = deque(maxlen=100)  # holds the green intensities

def simulate_ppg_analysis(frame):
    intensity = extract_green_channel_ppg(frame)
    if intensity is not None:
        PPG_BUFFER.append(intensity)
    else:
        PPG_BUFFER.append(70 + random.uniform(-5, 5))

    # Smooth PPG signal
    smoothed_signal = np.array(PPG_BUFFER)
    smoothed_signal -= np.mean(smoothed_signal)
    return np.mean(smoothed_signal) + 70, smoothed_signal


def calculate_hrv(ppg_signal):
    peaks, _ = find_peaks(ppg_signal, height=0.1, distance=10)
    if len(peaks) < 2:
        return 30
    rr_intervals = np.diff(peaks)
    if len(rr_intervals) > 1:
        diff_rr = np.diff(rr_intervals)
        rmssd = np.sqrt(np.mean(diff_rr ** 2))
        return np.clip(rmssd * 10, 10, 60)
    return 30


def smooth_value(current, new, max_change, history):
    if history:
        avg = np.mean(history)
        adjusted = current + np.clip(new - current, -max_change, max_change)
        return 0.7 * adjusted + 0.3 * avg
    return new


def process_health_metrics():
    global current_hr, current_stress, current_bp

    while True:
        if latest_frame is not None:
            try:
                hr, ppg_signal = simulate_ppg_analysis(latest_frame)
                hrv = calculate_hrv(ppg_signal)

                raw_stress = np.clip(1.5 - (hrv / 40) + (0.1 * (hr - 70)), 0, 2)

                HR_HISTORY.append(hr)
                smoothed_hr = smooth_value(current_hr, hr, MAX_HR_CHANGE, HR_HISTORY)

                STRESS_HISTORY.append(raw_stress)
                smoothed_stress = np.mean(STRESS_HISTORY)

                new_bp = {
                    "systolic": 110 + (smoothed_hr * 0.25) + (smoothed_stress * 5),
                    "diastolic": 65 + (smoothed_hr * 0.15) + (smoothed_stress * 3)
                }

                BP_HISTORY.append(new_bp)
                smoothed_bp = {
                    "systolic": smooth_value(current_bp["systolic"], new_bp["systolic"],
                                              MAX_BP_CHANGE, [x["systolic"] for x in BP_HISTORY]),
                    "diastolic": smooth_value(current_bp["diastolic"], new_bp["diastolic"],
                                               MAX_BP_CHANGE, [x["diastolic"] for x in BP_HISTORY])
                }

                current_hr = smoothed_hr
                current_stress = smoothed_stress
                current_bp = smoothed_bp

            except Exception as e:
                print(f"Processing error: {e}")
        time.sleep(1)


threading.Thread(target=process_health_metrics, daemon=True).start()


@app.route('/video_feed', methods=['POST'])
def video_feed():
    global latest_frame
    try:
        image_data = request.json['image'].split(',')[1]
        image_bytes = base64.b64decode(image_data)
        latest_frame = cv2.imdecode(np.frombuffer(image_bytes, dtype=np.uint8), cv2.IMREAD_COLOR)
        return jsonify({"status": "Frame received"})
    except Exception as e:
        return jsonify({"error": str(e)}), 400


@app.route('/get_health_metrics', methods=['GET'])
def get_health_metrics():
    stress_levels = ["Low", "Moderate", "High"]
    stress_idx = min(2, max(0, int(round(current_stress))))

    return jsonify({
        "heart_rate": round(current_hr, 1),
        "stress_level": stress_levels[stress_idx],
        "stress_score": round(current_stress, 2),
        "blood_pressure": {k: round(v, 1) for k, v in current_bp.items()},
        "message": get_stress_message(stress_idx),
        "stability": "stable"
    })


def get_stress_message(stress_level):
    messages = [
        "Normal stress level detected",
        "Moderate stress - consider relaxation techniques",
        "High stress detected - recommend taking a break"
    ]
    return messages[stress_level]


if __name__ == '__main__':
    HR_HISTORY.extend([70] * 15)
    STRESS_HISTORY.extend([0] * 10)
    BP_HISTORY.extend([{"systolic": 120, "diastolic": 80}] * 10)

    app.run(host='0.0.0.0', port=5000, debug=True)
