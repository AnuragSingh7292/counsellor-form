<?php
// Database connection
$host = 'localhost';
$db = 'college_admission';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $counseling_id = $_POST['counseling_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Validate phone format
    if (!preg_match("/^\(\d{3}\) \d{3}-\d{4}$/", $phone)) {
        die("Invalid phone format. Use (123) 456-7890.");
    }

    // Ensure the uploads directory exists
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // File upload paths
    $photo_path = $upload_dir . basename($_FILES["photo"]["name"]);
    $intermediate_cert_path = $upload_dir . basename($_FILES["intermediate_cert"]["name"]);
    $tenth_cert_path = $upload_dir . basename($_FILES["tenth_cert"]["name"]);

    // Validate file types and move uploads
    if ($_FILES["photo"]["type"] != "image/jpeg") {
        die("Photo must be in JPEG format.");
    }
    if ($_FILES["intermediate_cert"]["type"] != "application/pdf" || $_FILES["tenth_cert"]["type"] != "application/pdf") {
        die("Certificates must be in PDF format.");
    }
    if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_path) ||
        !move_uploaded_file($_FILES["intermediate_cert"]["tmp_name"], $intermediate_cert_path) ||
        !move_uploaded_file($_FILES["tenth_cert"]["tmp_name"], $tenth_cert_path)) {
        die("Failed to upload files.");
    }

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO students (counseling_id, first_name, last_name, email, phone, address, photo, intermediate_cert, tenth_cert) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $counseling_id, $first_name, $last_name, $email, $phone, $address, $photo_path, $intermediate_cert_path, $tenth_cert_path);

    if ($stmt->execute()) {
        echo "<script>alert('Form submitted successfully!');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Admission Counseling Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="index.php" method="POST" enctype="multipart/form-data">
        <a href="https://vignan.ac.in" target="main"><img src="https://vignan.ac.in/newvignan/assets/images/Logo%20with%20Deemed.svg"
            height="" width="100%"> </a>

        <h2>College Admission Counseling Form</h2>

        <label for="counseling_id">Counseling ID:</label>
        <input type="text" name="counseling_id" required><br>

        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required><br>

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="phone">Phone Number:</label>
        <input type="text" name="phone" required placeholder="(123) 456-7890"><br>

        <label for="address">Address:</label>
        <textarea name="address" required></textarea><br>

        <label for="photo">Upload Photo (JPEG):</label>
        <input type="file" name="photo" accept="image/jpeg" required><br>

        <label for="intermediate_cert">Upload Intermediate Marks Certificate (PDF):</label>
        <input type="file" name="intermediate_cert" accept="application/pdf" required><br>

        <label for="tenth_cert">Upload Tenth Marks Certificate (PDF):</label>
        <input type="file" name="tenth_cert" accept="application/pdf" required><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
