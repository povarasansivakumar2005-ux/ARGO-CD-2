<?php

$dbHost = getenv("DB_HOST");
$dbUser = getenv("DB_USER");
$dbPassword = getenv("DB_PASSWORD");
$dbName = getenv("DB_NAME");

$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->query("
    CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        course VARCHAR(100) NOT NULL
    )
");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["add"])) {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $course = $_POST["course"];

        $stmt = $conn->prepare(
            "INSERT INTO students (name, email, course) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $name, $email, $course);
        $stmt->execute();
        $message = "Student added successfully!";
    }

    if (isset($_POST["delete"])) {
        $id = $_POST["id"];

        $stmt = $conn->prepare(
            "DELETE FROM students WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $message = "Student deleted successfully!";
    }
}

$result = $conn->query("SELECT * FROM students ORDER BY id DESC");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="container">

    <h1>Student Management System</h1>

    <?php if ($message != ""): ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2>Add Student</h2>

        <form method="POST">
            <input type="text" name="name" placeholder="Student Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="course" placeholder="Course" required>

            <button type="submit" name="add">
                Add Student
            </button>
        </form>
    </div>

    <div class="card">
        <h2>Student List</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Action</th>
            </tr>

            <?php while ($student = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $student["id"]; ?></td>
                <td><?php echo htmlspecialchars($student["name"]); ?></td>
                <td><?php echo htmlspecialchars($student["email"]); ?></td>
                <td><?php echo htmlspecialchars($student["course"]); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id"
                               value="<?php echo $student["id"]; ?>">
                        <button type="submit" name="delete" class="delete">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>
</body>
</html>
