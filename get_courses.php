<?php
/**
 * OPTIONAL FILE: get_courses.php
 * AJAX endpoint to get courses dynamically based on session and semester
 * This makes the result upload more dynamic
 */

header('Content-Type: application/json');
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$session = $_GET['session'] ?? '';
$semester = $_GET['semester'] ?? '';

if (empty($session) || empty($semester)) {
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, course_code, course_title, credit_unit FROM courses WHERE session = ? AND semester = ? AND course_code != 'SESSION' ORDER BY course_code");
    $stmt->execute([$session, $semester]);
    $courses = $stmt->fetchAll();
    
    echo json_encode(['success' => true, 'courses' => $courses]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>