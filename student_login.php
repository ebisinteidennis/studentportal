<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYSCONS - Student Portal Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>BAYELSA STATE COLLEGE OF NURSING SCIENCES</h4>
                        <p>Student Portal Login</p>
                    </div>
                    <div class="card-body">
                        <?php
                        require_once 'config.php';
                        
                        if ($_POST) {
                            $email = $_POST['email'];
                            $password = $_POST['password'];
                            
                            $stmt = $pdo->prepare("SELECT * FROM students WHERE email = ? AND password = ?");
                            $stmt->execute([$email, $password]);
                            $student = $stmt->fetch();
                            
                            if ($student) {
                                $_SESSION['student_id'] = $student['id'];
                                $_SESSION['student_name'] = $student['first_name'] . ' ' . $student['last_name'];
                                $_SESSION['student_email'] = $student['email'];
                                $_SESSION['matric_number'] = $student['matric_number'];
                                header('Location: dashboard.php');
                                exit;
                            } else {
                                echo '<div class="alert alert-danger">Invalid login credentials!</div>';
                            }
                        }
                        ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                                <small class="text-muted">Default password: student123</small>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="admin_login.php" class="text-secondary">Admin Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>