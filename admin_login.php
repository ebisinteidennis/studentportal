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
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white text-center">
                        <h4>BAYELSA STATE COLLEGE OF NURSING SCIENCES</h4>
                        <p>Administrator Login</p>
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
                                <small class="text-muted">Default: admin / admin123</small>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">Login</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="student_login.php" class="text-secondary">Student Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>