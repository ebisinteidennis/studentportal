<?php
// =====================================================
// FILE: index.php - Enhanced
// =====================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYSCONS Student Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <img src="https://byscons.edu.ng/wp-content/uploads/2024/10/1715410211226-removebg-preview.png" alt="BYSCONS Logo" class="mb-2">
                        <h2>BAYELSA STATE COLLEGE OF NURSING SCIENCES</h2>
                        <p class="lead">Student Portal System</p>
                    </div>
                    <div class="card-body">
                        <!-- Programs Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4 class="text-center mb-4">Our Academic Programs</h4>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-primary h-100">
                                    <div class="card-body text-center">
                                        <h5 class="text-primary">National Diploma</h5>
                                        <p class="text-muted">Professional nursing program</p>
                                        <span class="badge bg-success">CGPA System</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-success h-100">
                                    <div class="card-body text-center">
                                        <h5 class="text-success">School of Basic Midwifery</h5>
                                        <p class="text-muted">Specialized midwifery training</p>
                                        <span class="badge bg-info">Grade Only</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-info h-100">
                                    <div class="card-body text-center">
                                        <h5 class="text-info">School of General Nursing</h5>
                                        <p class="text-muted">General nursing practice</p>
                                        <span class="badge bg-info">Grade Only</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h4 class="text-primary">Students</h4>
                                        <p>Access your academic records, register for courses, and manage your profile using your email address.</p>
                                        <div class="d-grid gap-2">
                                            <a href="student_login.php" class="btn btn-primary btn-lg">Student Login</a>
                                            <a href="student_registration.php" class="btn btn-outline-primary">New Student Registration</a>
                                        </div>
                                        <hr>
                                        <small class="text-muted">Default Password: student123</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h4 class="text-danger">Administrators</h4>
                                        <p>Manage student records, upload results, and oversee the portal system.</p>
                                        <a href="admin_login.php" class="btn btn-danger btn-lg">Admin Login</a>
                                        <hr>
                                        <small class="text-muted">
                                            Program-specific admin access:<br>
                                            • nd_admin (National Diploma)<br>
                                            • sbm_admin (Basic Midwifery)<br>
                                            • sgn_admin (General Nursing)
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h5>Portal Features</h5>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <strong>📚</strong>
                                        </div>
                                        <h6 class="mt-2">Course Registration</h6>
                                        <small class="text-muted">Currently disabled - payment system required</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <strong>📊</strong>
                                        </div>
                                        <h6 class="mt-2">Academic Records</h6>
                                        <small class="text-muted">Program-specific GPA calculation</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <strong>👤</strong>
                                        </div>
                                        <h6 class="mt-2">Profile Management</h6>
                                        <small class="text-muted">Complete student information</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <strong>📈</strong>
                                        </div>
                                        <h6 class="mt-2">Session Management</h6>
                                        <small class="text-muted">Academic session organization</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center text-muted">
                        <small>&copy; 2025 Bayelsa State College of Nursing Sciences. All rights reserved.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>