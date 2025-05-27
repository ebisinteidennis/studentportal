<?php
require_once 'config.php';

if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit;
}

$student_id = $_SESSION['student_id'];

// Get all results
$stmt = $pdo->prepare("
    SELECT r.*, c.course_code, c.course_title, c.credit_unit 
    FROM results r 
    JOIN courses c ON r.course_id = c.id 
    WHERE r.student_id = ? 
    ORDER BY r.session, r.semester, c.course_code
");
$stmt->execute([$student_id]);
$all_results = $stmt->fetchAll();

// Group results by session and semester
$grouped_results = [];
foreach ($all_results as $result) {
    $key = $result['session'] . ' - ' . $result['semester'] . ' Semester';
    $grouped_results[$key][] = $result;
}

// Calculate overall GPA
$overall_gpa = calculateGPA($student_id, $pdo);
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
                    <a href="course_registration.php" class="list-group-item list-group-item-action">Course Registration</a>
                    <a href="academic_records.php" class="list-group-item list-group-item-action active">Academic Records</a>
                    <a href="profile_settings.php" class="list-group-item list-group-item-action">Profile Settings</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Academic Records</h3>
                    <div class="card bg-primary text-white p-3">
                        <h5 class="mb-0">Overall GPA: <?php echo $overall_gpa ?: '0.00'; ?></h5>
                    </div>
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
                                            <th>Grade Point</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $total_credit_units = 0;
                                        $total_grade_points = 0;
                                        foreach ($semester_results as $result): 
                                            $total_credit_units += $result['credit_unit'];
                                            $total_grade_points += ($result['grade_point'] * $result['credit_unit']);
                                        ?>
                                        <tr>
                                            <td><?php echo $result['course_code']; ?></td>
                                            <td><?php echo $result['course_title']; ?></td>
                                            <td><?php echo $result['credit_unit']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $result['score'] >= 50 ? 'success' : 'danger'; ?>">
                                                    <?php echo $result['score']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $result['grade'] == 'F' ? 'danger' : 'success'; ?>">
                                                    <?php echo $result['grade']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo number_format($result['grade_point'], 2); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="table-info">
                                        <tr>
                                            <th colspan="2">Semester GPA</th>
                                            <th><?php echo $total_credit_units; ?></th>
                                            <th colspan="2">
                                                <?php 
                                                $semester_gpa = $total_credit_units > 0 ? $total_grade_points / $total_credit_units : 0;
                                                echo number_format($semester_gpa, 2);
                                                ?>
                                            </th>
                                            <th><?php echo number_format($total_grade_points, 2); ?></th>
                                        </tr>
                                    </tfoot>
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
                                                <th>Grade Point</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>70 - 100</td><td>A</td><td>4.0</td></tr>
                                            <tr><td>60 - 69</td><td>B</td><td>3.0</td></tr>
                                            <tr><td>50 - 59</td><td>C</td><td>2.0</td></tr>
                                            <tr><td>45 - 49</td><td>D</td><td>1.0</td></tr>
                                            <tr><td>0 - 44</td><td>F</td><td>0.0</td></tr>
                                        </tbody>
                                    </table>
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