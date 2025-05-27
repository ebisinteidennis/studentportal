<?php
require_once 'config.php';

if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit;
}

$student_id = $_SESSION['student_id'];
$current_session = '2024/2025';
$current_semester = 'First';

// Handle course registration
if ($_POST && isset($_POST['register_courses'])) {
    $selected_courses = $_POST['courses'] ?? [];
    
    foreach ($selected_courses as $course_id) {
        // Check if already registered
        $stmt = $pdo->prepare("SELECT id FROM course_registrations WHERE student_id = ? AND course_id = ? AND session = ? AND semester = ?");
        $stmt->execute([$student_id, $course_id, $current_session, $current_semester]);
        
        if (!$stmt->fetch()) {
            $stmt = $pdo->prepare("INSERT INTO course_registrations (student_id, course_id, session, semester) VALUES (?, ?, ?, ?)");
            $stmt->execute([$student_id, $course_id, $current_session, $current_semester]);
        }
    }
    
    echo '<div class="alert alert-success">Courses registered successfully!</div>';
}

// Handle course drop
if ($_POST && isset($_POST['drop_course'])) {
    $course_id = $_POST['course_id'];
    $stmt = $pdo->prepare("DELETE FROM course_registrations WHERE student_id = ? AND course_id = ? AND session = ? AND semester = ?");
    $stmt->execute([$student_id, $course_id, $current_session, $current_semester]);
    echo '<div class="alert alert-info">Course dropped successfully!</div>';
}

// Get available courses
$stmt = $pdo->query("SELECT * FROM courses ORDER BY level, course_code");
$available_courses = $stmt->fetchAll();

// Get registered courses
$stmt = $pdo->prepare("
    SELECT c.*, cr.id as reg_id 
    FROM courses c 
    JOIN course_registrations cr ON c.id = cr.course_id 
    WHERE cr.student_id = ? AND cr.session = ? AND cr.semester = ?
");
$stmt->execute([$student_id, $current_session, $current_semester]);
$registered_courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration - BYSCONS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">BYSCONS Portal</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">Welcome, <?php echo $_SESSION['student_name']; ?></span>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="list-group">
                    <a href="dashboard.php" class="list-group-item list-group-item-action">Dashboard</a>
                    <a href="course_registration.php" class="list-group-item list-group-item-action active">Course Registration</a>
                    <a href="academic_records.php" class="list-group-item list-group-item-action">Academic Records</a>
                    <a href="profile_settings.php" class="list-group-item list-group-item-action">Profile Settings</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <h3>Course Registration - <?php echo $current_semester; ?> Semester <?php echo $current_session; ?></h3>
                
                <!-- Registered Courses -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Registered Courses</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($registered_courses)): ?>
                            <p class="text-muted">No courses registered yet.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Course Code</th>
                                            <th>Course Title</th>
                                            <th>Credit Unit</th>
                                            <th>Level</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($registered_courses as $course): ?>
                                        <tr>
                                            <td><?php echo $course['course_code']; ?></td>
                                            <td><?php echo $course['course_title']; ?></td>
                                            <td><?php echo $course['credit_unit']; ?></td>
                                            <td><?php echo $course['level']; ?></td>
                                            <td>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                                    <button type="submit" name="drop_course" class="btn btn-sm btn-danger" onclick="return confirm('Drop this course?')">Drop</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-3">
                                <a href="?print=1" class="btn btn-primary" target="_blank">Print Registration Slip</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Available Courses -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Available Courses</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Course Code</th>
                                            <th>Course Title</th>
                                            <th>Credit Unit</th>
                                            <th>Level</th>
                                            <th>Semester</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $registered_ids = array_column($registered_courses, 'id');
                                        foreach ($available_courses as $course): 
                                            $is_registered = in_array($course['id'], $registered_ids);
                                        ?>
                                        <tr class="<?php echo $is_registered ? 'table-success' : ''; ?>">
                                            <td>
                                                <?php if (!$is_registered): ?>
                                                    <input type="checkbox" name="courses[]" value="<?php echo $course['id']; ?>" class="form-check-input">
                                                <?php else: ?>
                                                    <span class="badge bg-success">Registered</span>
                                                <?php endif; ?>
                                            </td>
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
                            
                            <button type="submit" name="register_courses" class="btn btn-success">Register Selected Courses</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['print'])): ?>
    <script>
        window.onload = function() {
            var printContent = `
                <h2>BAYELSA STATE COLLEGE OF NURSING SCIENCES</h2>
                <h3>Course Registration Slip</h3>
                <p><strong>Student:</strong> <?php echo $_SESSION['student_name']; ?></p>
                <p><strong>Matric No:</strong> <?php echo $_SESSION['matric_number']; ?></p>
                <p><strong>Email:</strong> <?php echo $_SESSION['student_email']; ?></p>
                <p><strong>Session:</strong> <?php echo $current_session; ?></p>
                <p><strong>Semester:</strong> <?php echo $current_semester; ?></p>
                <table border="1" style="width:100%; border-collapse:collapse;">
                    <tr><th>Course Code</th><th>Course Title</th><th>Credit Unit</th></tr>
                    <?php foreach ($registered_courses as $course): ?>
                    <tr><td><?php echo $course['course_code']; ?></td><td><?php echo $course['course_title']; ?></td><td><?php echo $course['credit_unit']; ?></td></tr>
                    <?php endforeach; ?>
                </table>
                <p>Date: ${new Date().toLocaleDateString()}</p>
            `;
            
            var printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Registration Slip</title></head><body>' + printContent + '</body></html>');
            printWindow.document.close();
            printWindow.print();
        };
    </script>
    <?php endif; ?>
</body>
</html>