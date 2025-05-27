<?php
require_once 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Handle result upload
if ($_POST && isset($_POST['upload_results'])) {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $semester = $_POST['semester'];
    $session = $_POST['session'];
    $score = $_POST['score'];
    
    // Calculate grade and grade point
    $grade_info = getGrade($score);
    $grade = $grade_info[0];
    $grade_point = $grade_info[1];
    
    // Check if result already exists
    $stmt = $pdo->prepare("SELECT id FROM results WHERE student_id = ? AND course_id = ? AND semester = ? AND session = ?");
    $stmt->execute([$student_id, $course_id, $semester, $session]);
    
    if ($stmt->fetch()) {
        // Update existing result
        $stmt = $pdo->prepare("UPDATE results SET score = ?, grade = ?, grade_point = ? WHERE student_id = ? AND course_id = ? AND semester = ? AND session = ?");
        $stmt->execute([$score, $grade, $grade_point, $student_id, $course_id, $semester, $session]);
        $message = "Result updated successfully!";
    } else {
        // Insert new result
        $stmt = $pdo->prepare("INSERT INTO results (student_id, course_id, semester, session, score, grade, grade_point) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$student_id, $course_id, $semester, $session, $score, $grade, $grade_point]);
        $message = "Result uploaded successfully!";
    }
}

// Get all students
$stmt = $pdo->query("SELECT id, matric_number, first_name, last_name, email FROM students ORDER BY matric_number");
$students = $stmt->fetchAll();

// Get all courses
$stmt = $pdo->query("SELECT * FROM courses ORDER BY course_code");
$courses = $stmt->fetchAll();

// Get recent results
$stmt = $pdo->query("
    SELECT r.*, s.matric_number, s.first_name, s.last_name, s.email, c.course_code, c.course_title 
    FROM results r 
    JOIN students s ON r.student_id = s.id 
    JOIN courses c ON r.course_id = c.id 
    ORDER BY r.created_at DESC 
    LIMIT 10
");
$recent_results = $stmt->fetchAll();

// Get statistics
$total_students = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$total_courses = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$total_results = $pdo->query("SELECT COUNT(*) FROM results")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BYSCONS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">BYSCONS Admin Panel</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">Welcome, <?php echo $_SESSION['admin_name']; ?></span>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="list-group">
                    <a href="#dashboard" class="list-group-item list-group-item-action active" data-bs-toggle="pill">Dashboard</a>
                    <a href="#upload-results" class="list-group-item list-group-item-action" data-bs-toggle="pill">Upload Results</a>
                    <a href="#manage-students" class="list-group-item list-group-item-action" data-bs-toggle="pill">Manage Students</a>
                    <a href="#manage-courses" class="list-group-item list-group-item-action" data-bs-toggle="pill">Manage Courses</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="tab-content">
                    <!-- Dashboard Tab -->
                    <div class="tab-pane fade show active" id="dashboard">
                        <h3>Dashboard Overview</h3>
                        
                        <!-- Statistics Cards -->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h4><?php echo $total_students; ?></h4>
                                        <p>Total Students</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h4><?php echo $total_courses; ?></h4>
                                        <p>Total Courses</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h4><?php echo $total_results; ?></h4>
                                        <p>Results Uploaded</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Results -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5>Recent Results</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Course</th>
                                                <th>Score</th>
                                                <th>Grade</th>
                                                <th>Semester</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_results as $result): ?>
                                            <tr>
                                                <td><?php echo $result['first_name'] . ' ' . $result['last_name']; ?></td>
                                                <td><?php echo $result['course_code']; ?></td>
                                                <td><?php echo $result['score']; ?></td>
                                                <td><span class="badge bg-<?php echo $result['grade'] == 'F' ? 'danger' : 'success'; ?>"><?php echo $result['grade']; ?></span></td>
                                                <td><?php echo $result['semester'] . ' ' . $result['session']; ?></td>
                                                <td><?php echo date('M j, Y', strtotime($result['created_at'])); ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Results Tab -->
                    <div class="tab-pane fade" id="upload-results">
                        <h3>Upload Student Results</h3>
                        
                        <?php if (isset($message)): ?>
                            <div class="alert alert-success"><?php echo $message; ?></div>
                        <?php endif; ?>

                        <div class="card mt-3">
                            <div class="card-body">
                                <form method="POST">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Student</label>
                                                <select class="form-select" name="student_id" required>
                                                    <option value="">Select Student</option>
                                                    <?php foreach ($students as $student): ?>
                                                        <option value="<?php echo $student['id']; ?>">
                                                            <?php echo $student['matric_number'] . ' - ' . $student['first_name'] . ' ' . $student['last_name'] . ' (' . $student['email'] . ')'; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Course</label>
                                                <select class="form-select" name="course_id" required>
                                                    <option value="">Select Course</option>
                                                    <?php foreach ($courses as $course): ?>
                                                        <option value="<?php echo $course['id']; ?>">
                                                            <?php echo $course['course_code'] . ' - ' . $course['course_title']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Session</label>
                                                <select class="form-select" name="session" required>
                                                    <option value="2024/2025">2024/2025</option>
                                                    <option value="2023/2024">2023/2024</option>
                                                    <option value="2022/2023">2022/2023</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Semester</label>
                                                <select class="form-select" name="semester" required>
                                                    <option value="First">First</option>
                                                    <option value="Second">Second</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Score (0-100)</label>
                                                <input type="number" class="form-control" name="score" min="0" max="100" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" name="upload_results" class="btn btn-primary">Upload Result</button>
                                </form>
                            </div>
                        </div>

                        <!-- Grading Scale Reference -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5>Grading Scale</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr><th>Score</th><th>Grade</th><th>Point</th></tr>
                                            </thead>
                                            <tbody>
                                                <tr><td>70-100</td><td>A</td><td>4.0</td></tr>
                                                <tr><td>60-69</td><td>B</td><td>3.0</td></tr>
                                                <tr><td>50-59</td><td>C</td><td>2.0</td></tr>
                                                <tr><td>45-49</td><td>D</td><td>1.0</td></tr>
                                                <tr><td>0-44</td><td>F</td><td>0.0</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Manage Students Tab -->
                    <div class="tab-pane fade" id="manage-students">
                        <h3>Manage Students</h3>
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Matric Number</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Department</th>
                                                <th>Phone</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $stmt = $pdo->query("SELECT * FROM students ORDER BY matric_number");
                                            $all_students = $stmt->fetchAll();
                                            foreach ($all_students as $student): 
                                            ?>
                                            <tr>
                                                <td><?php echo $student['matric_number']; ?></td>
                                                <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                                                <td><?php echo $student['email']; ?></td>
                                                <td><?php echo $student['department']; ?></td>
                                                <td><?php echo $student['phone']; ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Manage Courses Tab -->
                    <div class="tab-pane fade" id="manage-courses">
                        <h3>Manage Courses</h3>
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Course Code</th>
                                                <th>Course Title</th>
                                                <th>Credit Unit</th>
                                                <th>Level</th>
                                                <th>Semester</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($courses as $course): ?>
                                            <tr>
                                                <td><?php echo $course['course_code']; ?></td>
                                                <td><?php echo $course['course_title']; ?></td>
                                                <td><?php echo $course['credit_unit']; ?></td>
                                                <td><?php echo $course['level']; ?></td>
                                                <td><?php echo $course['semester']; ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>