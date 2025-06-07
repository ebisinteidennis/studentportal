<?php
require_once 'config.php';

if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit;
}

$student_id = $_SESSION['student_id'];

// Get all results with student program info
$stmt = $pdo->prepare("
    SELECT r.*, c.course_code, c.course_title, c.credit_unit, s.program
    FROM results r 
    JOIN courses c ON r.course_id = c.id 
    JOIN students s ON r.student_id = s.id
    WHERE r.student_id = ? 
    ORDER BY r.session, r.semester, c.course_code
");
$stmt->execute([$student_id]);
$all_results = $stmt->fetchAll();

// Get student program info
$stmt = $pdo->prepare("SELECT program FROM students WHERE id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();
$student_program = $student['program'];

// Check if student should have GPA calculated
$show_gpa = ($student_program == 'National Diploma');

// Group results by session and semester
$grouped_results = [];
foreach ($all_results as $result) {
    $key = $result['session'] . ' - ' . $result['semester'] . ' Semester';
    $grouped_results[$key][] = $result;
}

// Calculate overall GPA/CGPA only for National Diploma
$overall_gpa = null;
$overall_cgpa = null;
if ($show_gpa) {
    $overall_gpa = calculateGPA($student_id, $pdo);
    $overall_cgpa = calculateCGPA($student_id, $pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Records - BYSCONS</title>
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
                    <div class="list-group-item list-group-item-action disabled">
                        Course Registration
                        <small class="d-block text-muted">Disabled</small>
                    </div>
                    <a href="academic_records.php" class="list-group-item list-group-item-action active">Academic Records</a>
                    <a href="profile_settings.php" class="list-group-item list-group-item-action">Profile Settings</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>Academic Records</h3>
                        <p class="text-muted">Program: <?php echo $student_program; ?></p>
                    </div>
                    
                    <!-- Show GPA only for National Diploma -->
                    <?php if ($show_gpa): ?>
                    <div class="row">
                        <div class="col-6">
                            <div class="card bg-primary text-white text-center">
                                <div class="card-body">
                                    <h5>GPA: <?php echo $overall_gpa ?: '0.00'; ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-success text-white text-center">
                                <div class="card-body">
                                    <h5>CGPA: <?php echo $overall_cgpa ?: '0.00'; ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info">
                        <strong>Assessment System:</strong> Grade Only (No GPA/CGPA calculation)
                    </div>
                    <?php endif; ?>
                </div>

                <?php if (empty($all_results)): ?>
                    <div class="alert alert-info mt-3">
                        <h5>No Results Available</h5>
                        <p>Your academic results will appear here once they are uploaded by the administration.</p>
                    </div>
                <?php else: ?>
                    <!-- Results by Semester -->
                    <?php foreach ($grouped_results as $semester_key => $semester_results): ?>
                    <div class="card mt-4">
                        <div class="card-header bg-secondary text-white">
                            <h5><?php echo $semester_key; ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Course Code</th>
                                            <th>Course Title</th>
                                            <th>Credit Unit</th>
                                            <th>Score</th>
                                            <th>Grade</th>
                                            <?php if ($show_gpa): ?><th>Grade Point</th><?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $total_credit_units = 0;
                                        $total_grade_points = 0;
                                        foreach ($semester_results as $result): 
                                            $total_credit_units += $result['credit_unit'];
                                            if ($show_gpa) {
                                                $total_grade_points += ($result['grade_point'] * $result['credit_unit']);
                                            }
                                        ?>
                                        <tr>
                                            <td><?php echo $result['course_code']; ?></td>
                                            <td><?php echo $result['course_title']; ?></td>
                                            <td><?php echo $result['credit_unit']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $result['score'] >= 50 ? 'success' : 'danger'; ?>">
                                                    <?php echo number_format($result['score'], 1); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $result['grade'] == 'F' ? 'danger' : 'success'; ?>">
                                                    <?php echo $result['grade']; ?>
                                                </span>
                                            </td>
                                            <?php if ($show_gpa): ?>
                                            <td><?php echo number_format($result['grade_point'], 2); ?></td>
                                            <?php endif; ?>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <?php if ($show_gpa): ?>
                                    <tfoot class="table-info">
                                        <tr>
                                            <th colspan="2">Semester Summary</th>
                                            <th><?php echo $total_credit_units; ?></th>
                                            <th colspan="2">
                                                Semester GPA: <?php echo $total_credit_units > 0 ? number_format($total_grade_points / $total_credit_units, 2) : '0.00'; ?>
                                            </th>
                                            <th><?php echo number_format($total_grade_points, 2); ?></th>
                                        </tr>
                                    </tfoot>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <!-- Grade Scale -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Grading Scale</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Score Range</th>
                                                <th>Grade</th>
                                                <?php if ($show_gpa): ?><th>Grade Point</th><?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>70 - 100</td><td>A</td><?php if ($show_gpa): ?><td>4.0</td><?php endif; ?></tr>
                                            <tr><td>60 - 69</td><td>B</td><?php if ($show_gpa): ?><td>3.0</td><?php endif; ?></tr>
                                            <tr><td>50 - 59</td><td>C</td><?php if ($show_gpa): ?><td>2.0</td><?php endif; ?></tr>
                                            <tr><td>45 - 49</td><td>D</td><?php if ($show_gpa): ?><td>1.0</td><?php endif; ?></tr>
                                            <tr><td>0 - 44</td><td>F</td><?php if ($show_gpa): ?><td>0.0</td><?php endif; ?></tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6>Program Information</h6>
                                    <p><strong>Program:</strong> <?php echo $student_program; ?></p>
                                    <p><strong>Assessment:</strong> 
                                        <?php if ($show_gpa): ?>
                                            CGPA System (4.0 Scale)
                                        <?php else: ?>
                                            Grade-Only System
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>