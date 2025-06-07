<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - BYSCONS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>BAYELSA STATE COLLEGE OF NURSING SCIENCES</h4>
                        <p>Student Registration</p>
                    </div>
                    <div class="card-body">
                        <?php
                        require_once 'config.php';
                        
                        if ($_POST) {
                            $first_name = $_POST['first_name'];
                            $last_name = $_POST['last_name'];
                            $middle_name = $_POST['middle_name'];
                            $email = $_POST['email'];
                            $phone = $_POST['phone'];
                            $program = $_POST['program'];
                            $admission_year = $_POST['admission_year'];
                            
                            // Generate matric number
                            $matric_number = generateMatricNumber($program, $admission_year, $pdo);
                            
                            try {
                                $stmt = $pdo->prepare("INSERT INTO students (matric_number, first_name, last_name, middle_name, email, phone, program, department) VALUES (?, ?, ?, ?, ?, ?, ?, 'Nursing')");
                                $stmt->execute([$matric_number, $first_name, $last_name, $middle_name, $email, $phone, $program]);
                                
                                echo '<div class="alert alert-success">Registration successful!<br>Your Matric Number: <strong>' . $matric_number . '</strong><br>Default Password: student123</div>';
                                echo '<div class="text-center"><a href="student_login.php" class="btn btn-primary">Login Now</a></div>';
                            } catch (Exception $e) {
                                echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
                            }
                        } else {
                        ?>
                        
                        <form method="POST">
                            <!-- Program Selection -->
                            <div class="mb-3">
                                <label class="form-label">Select Program</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <input type="radio" name="program" value="National Diploma" id="nd" required>
                                                <label for="nd" class="form-label">
                                                    <h6>National Diploma</h6>
                                                    <small class="text-muted">With CGPA Calculation</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <input type="radio" name="program" value="School of Basic Midwifery" id="sbm" required>
                                                <label for="sbm" class="form-label">
                                                    <h6>Basic Midwifery</h6>
                                                    <small class="text-muted">Grade Only</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <input type="radio" name="program" value="School of General Nursing" id="sgn" required>
                                                <label for="sgn" class="form-label">
                                                    <h6>General Nursing</h6>
                                                    <small class="text-muted">Grade Only</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="first_name" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="last_name" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" name="middle_name">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" name="phone">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Admission Year</label>
                                <select class="form-select" name="admission_year" required>
                                    <option value="2025">2025</option>
                                    <option value="2024" selected>2024</option>
                                    <option value="2023">2023</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                        
                        <?php } ?>
                        
                        <div class="text-center mt-3">
                            <a href="student_login.php" class="text-secondary">Already have account? Login</a> |
                            <a href="index.php" class="text-secondary">Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>