<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - BYSCONS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white text-center">
                        <h4>BAYELSA STATE COLLEGE OF NURSING SCIENCES</h4>
                        <p>Administrator Login Portal</p>
                    </div>
                    <div class="card-body">
                        <?php
                        require_once 'config.php';
                        
                        if ($_POST) {
                            $username = $_POST['username'];
                            $password = md5($_POST['password']);
                            
                            $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
                            $stmt->execute([$username, $password]);
                            $admin = $stmt->fetch();
                            
                            if ($admin) {
                                $_SESSION['admin_id'] = $admin['id'];
                                $_SESSION['admin_name'] = $admin['full_name'];
                                $_SESSION['admin_username'] = $admin['username'];
                                $_SESSION['admin_program'] = $admin['program'] ?? 'National Diploma';
                                header('Location: admin_dashboard.php');
                                exit;
                            } else {
                                echo '<div class="alert alert-danger">Invalid login credentials!</div>';
                            }
                        }
                        ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">Login</button>
                        </form>
                        
                        <!-- Program Admin Information -->
                        <div class="mt-4">
                            <h6 class="text-center mb-3">Program Administrator Logins</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card border-primary">
                                        <div class="card-body text-center p-3">
                                            <h6 class="card-title">National Diploma</h6>
                                            <p class="card-text small">
                                                <strong>Username:</strong> nd_admin<br>
                                                <strong>Password:</strong> admin123
                                            </p>
                                            <span class="badge bg-success">With CGPA System</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-success">
                                        <div class="card-body text-center p-3">
                                            <h6 class="card-title">Basic Midwifery</h6>
                                            <p class="card-text small">
                                                <strong>Username:</strong> sbm_admin<br>
                                                <strong>Password:</strong> admin123
                                            </p>
                                            <span class="badge bg-info">Grade Only System</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-info">
                                        <div class="card-body text-center p-3">
                                            <h6 class="card-title">General Nursing</h6>
                                            <p class="card-text small">
                                                <strong>Username:</strong> sgn_admin<br>
                                                <strong>Password:</strong> admin123
                                            </p>
                                            <span class="badge bg-info">Grade Only System</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Features Information -->
                        <div class="mt-4">
                            <div class="alert alert-info">
                                <h6>Admin Panel Features:</h6>
                                <ul class="mb-0 small">
                                    <li><strong>Manage Sessions:</strong> Create academic sessions for each program</li>
                                    <li><strong>Upload Results:</strong> Upload student results with decimal scores</li>
                                    <li><strong>Manage Students:</strong> Add and manage student records</li>
                                    <li><strong>Manage Courses:</strong> Add courses to sessions and semesters</li>
                                    <li><strong>CGPA Calculation:</strong> Automatic for National Diploma only</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="student_login.php" class="text-secondary">Student Login</a> |
                            <a href="index.php" class="text-secondary">Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>