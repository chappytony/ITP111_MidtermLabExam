<?php
$conn = new mysqli('localhost', 'root', '', 'students');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add Student
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $course = $_POST['course'];

    $sql = "INSERT INTO student_info (name, age, course) VALUES ('$name', $age, '$course')";
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
    <title>Student Management</title>
</head>
<body>
    <h1>Student Management</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <input type="number" name="age" placeholder="Age" required>
        <input type="text" name="course" placeholder="Course" required>
        <button type="submit" name="add">Add Student</button>
    </form>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Course</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $students->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['age']; ?></td>
                <td><?php echo $row['course']; ?></td>
                <td>
                    <a href="?delete=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
