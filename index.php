<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '1234';
$database = 'students';
$port = 3307;

// Create a connection
$connection = mysqli_connect($host, $user, $password, $database, $port);

// Check the connection
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Add Student
if (isset($_POST['add'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $location = $_POST['location'];

    $stmt = $connection->prepare("INSERT INTO student_info (first_name, last_name, email, location) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $location);
    if (!$stmt->execute()) {
        echo "Error adding student: " . $stmt->error;
    }
    $stmt->close();
}

// Update Student
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $location = $_POST['location'];

    $stmt = $connection->prepare("UPDATE student_info SET first_name = ?, last_name = ?, email = ?, location = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $first_name, $last_name, $email, $location, $id);
    if (!$stmt->execute()) {
        echo "Error updating student: " . $stmt->error;
    }
    $stmt->close();
}

// Delete Student
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $connection->prepare("DELETE FROM student_info WHERE id = ?");
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        echo "Error deleting student: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch Students
$students = $connection->query("SELECT * FROM student_info");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        button {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .add-button {
            background-color: #007bff;
            color: white;
        }
        .edit-button {
            background-color: #ffa500;
            color: white;
        }
        .update-button {
            background-color: #28a745;
            color: white;
            display: none; /* Hidden by default */
        }
        .delete-button {
            background-color: #dc3545;
            color: white;
            border: 1px solid transparent;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .delete-button:hover {
            background-color: #c82333;
            border-color: #b21f2d;
        }
        .add-form {
            margin-bottom: 20px;
        }
        .add-form input {
            margin-right: 10px;
            padding: 8px;
            font-size: 14px;
        }
        .add-form button {
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            padding: 10px 15px;
            font-size: 14px;
        }
    </style>
    <script>
        function enableEditing(rowId) {
            const row = document.getElementById('row-' + rowId);

            // Enable inputs
            row.querySelectorAll('input').forEach(input => input.disabled = false);

            // Show update button and hide edit button
            row.querySelector('.update-button').style.display = 'inline-block';
            row.querySelector('.edit-button').style.display = 'none';
        }
    </script>
</head>
<body>
    <h1>Student Management System</h1>

    <!-- Add Student Form -->
    <div class="add-form">
        <form method="POST" action="">
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="location" placeholder="Location" required>
            <button type="submit" name="add" class="add-button">Add Student</button>
        </form>
    </div>

    <!-- Students Table -->
    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Location</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $students->fetch_assoc()): ?>
            <tr id="row-<?php echo $row['id']; ?>">
                <form method="POST" action="">
                    <td>
                        <?php echo htmlspecialchars($row['id']); ?>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                    </td>
                    <td><input type="text" name="first_name" value="<?php echo htmlspecialchars($row['first_name']); ?>" disabled></td>
                    <td><input type="text" name="last_name" value="<?php echo htmlspecialchars($row['last_name']); ?>" disabled></td>
                    <td><input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" disabled></td>
                    <td><input type="text" name="location" value="<?php echo htmlspecialchars($row['location']); ?>" disabled></td>
                    <td>
                        <button type="button" class="edit-button" onclick="enableEditing(<?php echo $row['id']; ?>)">Edit</button>
                        <button type="submit" name="update" class="update-button">Update</button>
                        <a href="?delete=<?php echo $row['id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
