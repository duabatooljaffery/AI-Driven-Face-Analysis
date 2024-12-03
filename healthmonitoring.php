<!DOCTYPE html>
<html>
<head>
    <title>Health Monitoring</title>
    <style>
      
        .sidebar {
            width: 250px;
            background-color: rgb(5 17 164 / 10%);
            padding: 15px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            height: 300px;
            margin-top:20px;
            display:inline-block;
        }
        .sidebar h2 {
    font-size: 1.5em; /* Font size for the heading */
    margin-top: 0; /* Remove top margin */
}
.sidebar ul {
    list-style-type: none; /* Remove bullet points */
    padding: 0; /* Remove default padding */
}

.sidebar li {
   /* Space between list items */
    align:vertical;
}

.sidebar li:hover {
    background-color: #e2e2e2; /* Change background on hover */
    cursor: pointer; /* Change cursor to pointer */
}
        .main-content {
            flex: 1;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .upload-form {
            margin-top: 20px;
        }
        .upload-form input[type="file"] {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <?php include 'navbar.html'; ?> 

    <div class="sidebar">
        <h2>Features</h2>
        <ul>
            <li>Measure Heart Rate</li>
            <li>Monitor Blood Pressure</li>
            <li>Stress Assessment via Facial Scanning</li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Health Monitoring</h1>
        
        <div class="upload-form">
            <h2>Upload Your PC Data</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="pcData" accept=".txt,.csv,.json,.jpg,.png" required>
                <input type="submit" value="Upload Data">
            </form>
        </div>
    </div>

    <?php include 'footer.html'; ?> 

</body>
</html>