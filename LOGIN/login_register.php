<?php 

session_start();
require_once 'config.php';

// REGISTER
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = strtolower(trim($_POST['email']));
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $allowed_roles = ['Author', 'Reviewer', 'Organizer', 'Admin'];
    if (!in_array($role, $allowed_roles)) {
        $_SESSION['register_error'] = 'Invalid role selected!';
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT email FROM all_users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $checkEmail = $stmt->get_result();

    if ($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered!';
        $_SESSION['active_form'] = 'register';
    } else {
        $stmt = $conn->prepare("INSERT INTO all_users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
        $stmt->execute();
    }

    header("Location: index.php");
    exit();
}