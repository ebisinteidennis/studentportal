<?php
require_once 'config.php';

if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit;
}

$student_id = $_SESSION['student_id'];

// Get student info
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();
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
                <h3>Course Registration</h3>
                
                <!-- Course Registration Disabled Notice -->
                <div class="card mt-3 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>Course Registration Currently Disabled
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6>Course registration is temporarily unavailable</h6>
                                <p class="mb-3">
                                    We are working on implementing an online payment system to enable course registration. 
                                    This feature will be available once the payment integration is complete.
                                </p>
                                
                                <h6>What you can do now:</h6>
                                <ul>
                                    <li>View your academic records and results</li>
                                    <li>Update your profile information</li>
                                    <li>Contact the administration for any course-related queries</li>
                                </ul>

                                <div class="mt-4">
                                    <a href="academic_records.php" class="btn btn-success me-2">View Academic Records</a>
                                    <a href="profile_settings.php" class="btn btn-info">Update Profile</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <h6>Your Program</h6>
                                        <p class="badge bg-info fs-6"><?php echo $student['program']; ?></p>
                                        
                                        <h6 class="mt-3">Assessment System</h6>
                                        <?php if ($student['program'] == 'National Diploma'): ?>
                                            <p class="badge bg-success">CGPA System</p>
                                        <?php else: ?>
                                            <p class="badge bg-primary">Grade-Only System</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Future Features Preview -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Coming Soon: Enhanced Course Registration</h5>
                    </div>
                    <div class="card-body">
                        <p>When the online payment system is implemented, you will be able to:</p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul>
                                    <li>Register for courses online</li>
                                    <li>Make secure online payments</li>
                                    <li>Print registration confirmation</li>
                                    <li>View payment receipts</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li>Track registration status</li>
                                    <li>Add/drop courses during registration period</li>
                                    <li>View course schedules and timetables</li>
                                    <li>Receive registration notifications</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Need Help?</h5>
                    </div>
                    <div class="card-body">
                        <p>For course registration assistance, please contact:</p>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Academic Office:</strong><br>
                                Email: academic@byscons.edu.ng<br>
                                Phone: +234-XXX-XXX-XXXX</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Student Affairs:</strong><br>
                                Email: students@byscons.edu.ng<br>
                                Phone: +234-XXX-XXX-XXXX</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>