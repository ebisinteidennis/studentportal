<?php
// =====================================================
// FILE: index.php
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
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMjgiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iNCIvPgo8cGF0aCBkPSJNMTUgMjVIMzBIMzBIMzBIMzBINDVNMjAgMzVIMzVNMjUgNDVIMzUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIi8+CjwvcGF0aD4KPC9zdmc+" alt="BYSCONS Logo" class="mb-2">
                        <h2>BAYELSA STATE COLLEGE OF NURSING SCIENCES</h2>
                        <p class="lead">Student Portal System</p>
                    </div>
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h4 class="text-primary">Students</h4>
                                        <p>Access your academic records, register for courses, and manage your profile using your email address.</p>
                                        <a href="student_login.php" class="btn btn-primary btn-lg">Student Login</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h4 class="text-danger">Administrators</h4>
                                        <p>Manage student records, upload results, and oversee the portal system.</p>
                                        <a href="admin_login.php" class="btn btn-danger btn-lg">Admin Login</a>
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
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <strong>📊</strong>
                                        </div>
                                        <h6 class="mt-2">Academic Records</h6>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <strong>👤</strong>
                                        </div>
                                        <h6 class="mt-2">Profile Management</h6>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <strong>📈</strong>
                                        </div>
                                        <h6 class="mt-2">GPA Tracking</h6>
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