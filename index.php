<?php
$conn = new mysqli('localhost', 'root', '1234', 'students');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add Student
if (isset($_POST['add'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $location = $_POST['location'];

    $sql = "INSERT INTO student_info (first_name, last_name, email, location) VALUES ('$first_name', '$last_name', '$email', '$location')";
    $conn->query($sql);
}

// Delete Student
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM student_info WHERE id=$id");
}

// Fetch Students
$students = $conn->query("SELECT * FROM student_info");
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
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        .actions button {
            margin-right: 5px;
            padding: 5px 10px;
            border: none;
            color: white;
            cursor: pointer;
        }
        .edit {
            background-color: orange;
        }
        .delete {
            background-color: red;
        }
        .add {
            background-color: green;
        }
        form {
            margin-bottom: 20px;
        }
        form input, form button {
            padding: 10px;
            margin-right: 10px;
        }
        .add-student {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Student Management System</h1>
    <div class="add-student">
        <form method="POST">
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="location" placeholder="Location" required>
            <button type="submit" name="add" class="add">Add Student</button>
        </form>
    </div>
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
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['first_name']; ?></td>
                <td><?php echo $row['last_name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['location']; ?></td>
                <td class="actions">
                    <button class="edit">Edit</button>
                    <a href="?delete=<?php echo $row['id']; ?>">
                        <button class="delete">Delete</button>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
