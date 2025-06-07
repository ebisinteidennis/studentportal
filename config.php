<?php
// config.php - Enhanced Database configuration
$servername = "localhost";
$username = "byscpdmi_studentportal";
$password = "!mDd^X2]1=zL";
$database = "byscpdmi_studentportal";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Enhanced Helper functions
function calculateGPA($student_id, $pdo) {
    // Only calculate for National Diploma students
    $stmt = $pdo->prepare("SELECT program FROM students WHERE id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();
    
    if (!$student || $student['program'] != 'National Diploma') {
        return null; // No GPA for other programs
    }
    
    $stmt = $pdo->prepare("SELECT AVG(grade_point) as gpa FROM results WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $result = $stmt->fetch();
    return $result['gpa'] ? round($result['gpa'], 2) : 0.00;
}

function calculateCGPA($student_id, $pdo) {
    // Only calculate for National Diploma students
    $stmt = $pdo->prepare("SELECT program FROM students WHERE id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();
    
    if (!$student || $student['program'] != 'National Diploma') {
        return null; // No CGPA for other programs
    }
    
    $stmt = $pdo->prepare("
        SELECT SUM(r.grade_point * c.credit_unit) / SUM(c.credit_unit) as cgpa 
        FROM results r 
        JOIN courses c ON r.course_id = c.id 
        WHERE r.student_id = ?
    ");
    $stmt->execute([$student_id]);
    $result = $stmt->fetch();
    return $result['cgpa'] ? round($result['cgpa'], 2) : 0.00;
}

function getGrade($score) {
    $score = floatval($score);
    if ($score >= 70) return ['A', 4.0];
    if ($score >= 60) return ['B', 3.0];
    if ($score >= 50) return ['C', 2.0];
    if ($score >= 45) return ['D', 1.0];
    return ['F', 0.0];
}

function generateMatricNumber($program, $year, $pdo) {
    // Program codes
    $codes = [
        'National Diploma' => 'ND',
        'School of Basic Midwifery' => 'SBM', 
        'School of General Nursing' => 'SGN'
    ];
    
    $code = $codes[$program] ?? 'ND';
    
    // Get next number
    $stmt = $pdo->prepare("SELECT COUNT(*) + 1 as next_num FROM students WHERE program = ?");
    $stmt->execute([$program]);
    $result = $stmt->fetch();
    $num = str_pad($result['next_num'], 3, '0', STR_PAD_LEFT);
    
    return "BYSCONS/$code/$year/$num";
}

session_start();
?>