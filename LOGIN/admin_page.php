<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'Admin') {                   
    header("Location: index.php");
    exit();
}

$name = $_SESSION['name'];

/* ================= EXAMPLE: HANDLE DECISION ================= */

if (isset($_POST['make_decision'])) {

    $paper_id = $_POST['paper_id'];
    $author_id = $_POST['author_id'];
    $decision = $_POST['decision'];
    $comment = $_POST['comment'];

    $admin_id = $_SESSION['user_id'];

    // Insert decision
    $stmt = $conn->prepare("
        INSERT INTO decisions (paper_id, admin_id, decision, comment)      
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("iiss", $paper_id, $admin_id, $decision, $comment);
    $stmt->execute();

    // Update paper status
    $stmt2 = $conn->prepare("
        UPDATE papers SET status=? WHERE id=?                     
    ");

    $stmt2->bind_param("si", $decision, $paper_id);
    $stmt2->execute();

    // ================= NOTIFICATIONS =================

    if ($decision == "Accepted") {
        sendNotification($conn, $author_id, "Your paper has been ACCEPTED.");          
    }

    elseif ($decision == "Rejected") {
        sendNotification($conn, $author_id, "Your paper has been REJECTED.");
    }

    elseif ($decision == "Revision Required") {
        sendNotification($conn, $author_id, "Revision required for your paper.");
    }

    $success = "Decision submitted successfully!";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard">

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Manage Conference</a></li>
            <li><a href="#">Users</a></li>
            <li><a href="#">Decisions</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">

        <div class="topbar">
            <h1>Welcome, <?= $name; ?></h1>
            <a class="logout-btn" href="logout.php">Logout</a>
        </div>

        <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

        <div class="table-box">

            <h2>Make Decision</h2>

            <form method="POST">

                <input type="number" name="paper_id" placeholder="Paper ID" required>
                <input type="number" name="author_id" placeholder="Author ID" required>

                <select name="decision" required>
                    <option value="Accepted">Accept</option>
                    <option value="Rejected">Reject</option>
                    <option value="Revision Required">Revision Required</option>
                </select>

                <input type="text" name="comment" placeholder="Comment">

                <button type="submit" name="make_decision">
                    Submit Decision
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>
