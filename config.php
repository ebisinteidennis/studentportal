<?php
// config.php - Database configuration
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

// Helper functions
function calculateGPA($student_id, $pdo) {
    $stmt = $pdo->prepare("SELECT AVG(grade_point) as gpa FROM results WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $result = $stmt->fetch();
    return round($result['gpa'], 2);
}

function getGrade($score) {
    if ($score >= 70) return ['A', 4.0];
    if ($score >= 60) return ['B', 3.0];
    if ($score >= 50) return ['C', 2.0];
    if ($score >= 45) return ['D', 1.0];
    return ['F', 0.0];
}

session_start();
?>