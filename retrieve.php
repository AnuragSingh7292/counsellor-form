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

// Check if a counselor ID is submitted
$counselor_id = isset($_POST['counseling_id']) ? $_POST['counseling_id'] : '';

// Query to retrieve data
$sql = "SELECT * FROM students";
if ($counselor_id) {
    $sql .= " WHERE counseling_id = '$counselor_id'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <link rel="stylesheet" href="retrieve.css">
</head>
<body>

<form action="retrieve.php" method="post">
    <h1>Retrieve Student Data</h1>
    <label for="counseling_id">Enter Counseling ID:</label>
    <input type="text" id="counseling_id" name="counseling_id" required>
    <button type="submit">Submit</button>
</form>

<?php
if ($counselor_id && $result->num_rows > 0) 
{
    echo "<div class='table-container'>";
    echo "<table class='student-table'>";
    echo "<caption>Student Records</caption>";

    // Output data for the specific row
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td class='label'>Counseling ID</td><td>" . $row['counseling_id'] . "</td></tr>";
        echo "<tr><td class='label'>First Name</td><td>" . $row['first_name'] . "</td></tr>";
        echo "<tr><td class='label'>Last Name</td><td>" . $row['last_name'] . "</td></tr>";
        echo "<tr><td class='label'>Email</td><td>" . $row['email'] . "</td></tr>";
        echo "<tr><td class='label'>Phone</td><td>" . $row['phone'] . "</td></tr>";
        echo "<tr><td class='label'>Address</td><td>" . $row['address'] . "</td></tr>";
        echo "<tr><td class='label'>Photo</td><td><img src='" . $row['photo'] . "' width='100' alt='Photo'></td></tr>";
        echo "<tr><td class='label'>Intermediate Certificate</td><td><a href='" . $row['intermediate_cert'] . "' target='_blank'>View Intermediate Certificate</a></td></tr>";
        echo "<tr><td class='label'>Tenth Certificate</td><td><a href='" . $row['tenth_cert'] . "' target='_blank'>View Tenth Certificate</a></td></tr>";
    }

    echo "</table>";
    echo "</div>";
} elseif ($counselor_id) {
    echo "<script>alert('No records found for Counseling ID: $counselor_id');</script>";

}
$conn->close();
?>

</body>
</html>
