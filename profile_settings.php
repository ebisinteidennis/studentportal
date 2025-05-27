<?php
require_once 'config.php';

if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit;
}

$student_id = $_SESSION['student_id'];

// Handle form submission
if ($_POST) {
    $errors = [];
    
    // Handle passport upload
    if (isset($_FILES['passport']) && $_FILES['passport']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['passport']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($file_ext, $allowed)) {
            $new_filename = 'passport_' . $student_id . '.' . $file_ext;
            $upload_path = 'uploads/' . $new_filename;
            
            if (!file_exists('uploads')) {
                mkdir('uploads', 0755, true);
            }
            
            if (move_uploaded_file($_FILES['passport']['tmp_name'], $upload_path)) {
                $stmt = $pdo->prepare("UPDATE students SET passport = ? WHERE id = ?");
                $stmt->execute([$new_filename, $student_id]);
                $success[] = "Passport photo uploaded successfully!";
            } else {
                $errors[] = "Failed to upload passport photo.";
            }
        } else {
            $errors[] = "Only JPG, JPEG, and PNG files are allowed.";
        }
    }
    
    // Handle profile update
    if (isset($_POST['update_profile'])) {
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $contact_address = $_POST['contact_address'];
        $marital_status = $_POST['marital_status'];
        
        $stmt = $pdo->prepare("UPDATE students SET phone = ?, email = ?, contact_address = ?, marital_status = ? WHERE id = ?");
        if ($stmt->execute([$phone, $email, $contact_address, $marital_status, $student_id])) {
            $_SESSION['student_email'] = $email; // Update session email
            $success[] = "Profile updated successfully!";
        } else {
            $errors[] = "Failed to update profile.";
        }
    }
    
    // Handle password change
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Get current password
        $stmt = $pdo->prepare("SELECT password FROM students WHERE id = ?");
        $stmt->execute([$student_id]);
        $student = $stmt->fetch();
        
        if ($student['password'] != $current_password) {
            $errors[] = "Current password is incorrect.";
        } elseif ($new_password != $confirm_password) {
            $errors[] = "New passwords do not match.";
        } elseif (strlen($new_password) < 6) {
            $errors[] = "New password must be at least 6 characters long.";
        } else {
            $stmt = $pdo->prepare("UPDATE students SET password = ? WHERE id = ?");
            if ($stmt->execute([$new_password, $student_id])) {
                $success[] = "Password changed successfully!";
            } else {
                $errors[] = "Failed to change password.";
            }
        }
    }
}

// Get student information
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - BYSCONS</title>
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
                    <a href="academic_records.php" class="list-group-item list-group-item-action">Academic Records</a>
                    <a href="profile_settings.php" class="list-group-item list-group-item-action active">Profile Settings</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <h3>Profile Settings</h3>
                
                <!-- Display Messages -->
                <?php if (isset($errors) && !empty($errors)): ?>
                    <?php foreach ($errors as $error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <?php if (isset($success) && !empty($success)): ?>
                    <?php foreach ($success as $msg): ?>
                        <div class="alert alert-success"><?php echo $msg; ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Basic Information (Read-only) -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Matric Number</label>
                                    <input type="text" class="form-control" value="<?php echo $student['matric_number']; ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" value="<?php echo $student['first_name']; ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" value="<?php echo $student['last_name']; ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" value="<?php echo $student['middle_name']; ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Department</label>
                                    <input type="text" class="form-control" value="<?php echo $student['department']; ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="text" class="form-control" value="<?php echo $student['date_of_birth']; ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Passport Photo Upload -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Passport Photograph</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <?php if ($student['passport']): ?>
                                    <img src="uploads/<?php echo $student['passport']; ?>" class="img-thumbnail" width="200">
                                <?php else: ?>
                                    <div class="bg-light border rounded p-5 text-center">
                                        <p class="text-muted">No photo uploaded</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-8">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label">Upload New Photo</label>
                                        <input type="file" class="form-control" name="passport" accept=".jpg,.jpeg,.png">
                                        <small class="text-muted">Accepted formats: JPG, JPEG, PNG. Max size: 2MB</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Upload Photo</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Editable Information -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" name="phone" value="<?php echo $student['phone']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo $student['email']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Marital Status</label>
                                        <select class="form-select" name="marital_status">
                                            <option value="Single" <?php echo $student['marital_status'] == 'Single' ? 'selected' : ''; ?>>Single</option>
                                            <option value="Married" <?php echo $student['marital_status'] == 'Married' ? 'selected' : ''; ?>>Married</option>
                                            <option value="Divorced" <?php echo $student['marital_status'] == 'Divorced' ? 'selected' : ''; ?>>Divorced</option>
                                            <option value="Widowed" <?php echo $student['marital_status'] == 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Contact Address</label>
                                        <textarea class="form-control" name="contact_address" rows="3"><?php echo $student['contact_address']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-success">Update Profile</button>
                        </form>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" class="form-control" name="current_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" name="new_password" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" name="confirm_password" required>
                                    </div>
                                    <button type="submit" name="change_password" class="btn btn-warning">Change Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>