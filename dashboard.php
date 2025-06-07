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

// Check if student is in National Diploma program (for GPA calculation)
$show_gpa = ($student['program'] == 'National Diploma');

$gpa = null;
$cgpa = null;
if ($show_gpa) {
    $gpa = calculateGPA($student_id, $pdo);
    $cgpa = calculateCGPA($student_id, $pdo);
}

// Get semester results for GPA trend (only for ND)
$gpa_trend = [];
if ($show_gpa) {
    $stmt = $pdo->prepare("
        SELECT semester, session, AVG(grade_point) as semester_gpa 
        FROM results 
        WHERE student_id = ? 
        GROUP BY semester, session 
        ORDER BY session, semester
    ");
    $stmt->execute([$student_id]);
    $gpa_trend = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - BYSCONS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php if ($show_gpa): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php endif; ?>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">BYSCONS Portal</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">Welcome, <?php echo $_SESSION['student_name']; ?></span>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <?php if ($student['passport']): ?>
                            <img src="uploads/<?php echo $student['passport']; ?>" class="rounded-circle mb-3" width="100" height="100">
                        <?php else: ?>
                            <div class="bg-secondary rounded-circle mx-auto mb-3" style="width:100px;height:100px;"></div>
                        <?php endif; ?>
                        <h5><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></h5>
                        <p class="text-muted"><?php echo $student['matric_number']; ?></p>
                        <p class="text-muted"><?php echo $student['email']; ?></p>
                        <p class="badge bg-info"><?php echo $student['program']; ?></p>
                    </div>
                </div>

                <div class="list-group mt-3">
                    <a href="dashboard.php" class="list-group-item list-group-item-action active">Dashboard</a>
                    <!-- Course Registration Disabled -->
                    <div class="list-group-item list-group-item-action disabled">
                        Course Registration
                        <small class="d-block text-muted">Disabled - Payment System Required</small>
                    </div>
                    <a href="academic_records.php" class="list-group-item list-group-item-action">Academic Records</a>
                    <a href="profile_settings.php" class="list-group-item list-group-item-action">Profile Settings</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="row">
                    <!-- Show GPA only for National Diploma -->
                    <?php if ($show_gpa): ?>
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h4><?php echo $gpa ?: '0.00'; ?></h4>
                                <p>Current GPA</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h4><?php echo $cgpa ?: '0.00'; ?></h4>
                                <p>CGPA</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h4><?php echo count($gpa_trend); ?></h4>
                                <p>Semesters</p>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- No GPA for other programs -->
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h5>Program: <?php echo $student['program']; ?></h5>
                            <p>This program uses grade-only assessment. No GPA/CGPA calculation.</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if ($show_gpa && !empty($gpa_trend)): ?>
                <!-- GPA Trend Chart (only for ND) -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>GPA Trend</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="gpaChart" width="400" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Quick Links -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5>Course Registration</h5>
                                <p class="text-muted">Course registration is temporarily disabled. It will be enabled when online payment system is implemented.</p>
                                <button class="btn btn-secondary" disabled>Coming Soon</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5>Academic Records</h5>
                                <p>View your results</p>
                                <a href="academic_records.php" class="btn btn-success">View Results</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5>Profile Settings</h5>
                                <p>Update your profile</p>
                                <a href="profile_settings.php" class="btn btn-info">Update Profile</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($show_gpa && !empty($gpa_trend)): ?>
    <script>
        // GPA Trend Chart
        const ctx = document.getElementById('gpaChart').getContext('2d');
        const gpaData = <?php echo json_encode($gpa_trend); ?>;
        
        const labels = gpaData.map(item => item.semester + ' ' + item.session);
        const data = gpaData.map(item => parseFloat(item.semester_gpa));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'GPA',
                    data: data,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 4.0
                    }
                }
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>