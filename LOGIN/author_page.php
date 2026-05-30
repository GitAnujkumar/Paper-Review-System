<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email'])) {                
    header("Location: index.php");
    exit();
}

$author_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

/* ================= PAPER UPLOAD ================= */

if (isset($_POST['upload_paper'])) {

    $title = trim($_POST['title']);
    $conference_id = $_POST['conference_id'];
    $abstract = trim($_POST['abstract']);

    $fileName = $_FILES['paper']['name'];               
    $tmpName = $_FILES['paper']['tmp_name'];

    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));  

    $allowed = ['pdf', 'doc', 'docx'];                                 

    if (in_array($extension, $allowed)) {

        $newFileName = time() . "_" . basename($fileName);          

        $uploadDir = __DIR__ . "/uploads/";

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);                      
        }

        $uploadPath = $uploadDir . $newFileName;

        if (move_uploaded_file($tmpName, $uploadPath)) {       

            $stmt = $conn->prepare("
                INSERT INTO papers(                         
                    conference_id,
                    author_id,
                    title,
                    abstract,
                    filename
                )
                VALUES(?,?,?,?,?)
            ");

            $stmt->bind_param(
                "iisss",
                $conference_id,
                $author_id,
                $title,
                $abstract,
                $newFileName
            );

            $stmt->execute();

            $success = "Paper uploaded successfully!";

        } else {
            $error = "File upload failed!";
        }

    } else {
        $error = "Only PDF, DOC, DOCX files allowed!";
    }
}

/* ================= RESUBMIT PAPER ================= */

if (isset($_POST['resubmit_paper'])) {                           

    $paperId = $_POST['paper_id'];

    $fileName = $_FILES['revised_paper']['name'];
    $tmpName = $_FILES['revised_paper']['tmp_name'];

    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));   

    $allowed = ['pdf', 'doc', 'docx'];                                  

    if (in_array($extension, $allowed)) {

        $newFileName = time() . "_" . basename($fileName);

        $uploadPath = "uploads/" . $newFileName;

        move_uploaded_file($tmpName, $uploadPath);

        $stmt = $conn->prepare("
            UPDATE papers
            SET filename=?, status='Resubmitted'
            WHERE id=?
        ");

        $stmt->bind_param("si", $newFileName, $paperId);
        $stmt->execute();

        $success = "Paper resubmitted successfully!";
    }
}

/* ================= FETCH PAPERS ================= */

$stmt = $conn->prepare("
    SELECT * FROM papers
    WHERE author_id=?                                      
    ORDER BY uploaded_at DESC
");

if(!$stmt){
    die("Paper Fetch Error: " . $conn->error);
}

$stmt->bind_param("i", $author_id);
$stmt->execute();

$papers = $stmt->get_result();

/* ================= FETCH REVISION PAPERS ================= */

$stmt3 = $conn->prepare("
    SELECT * FROM papers
    WHERE author_id=?
    AND status='Revision Required'
");

if(!$stmt3){
    die("Revision Error: " . $conn->error);
}

$stmt3->bind_param("i", $author_id);
$stmt3->execute();

$revisionPapers = $stmt3->get_result();

/* ================= FETCH NOTIFICATIONS ================= */

$stmt2 = $conn->prepare("
    SELECT * FROM notifications                       
    WHERE user_id=?
    ORDER BY created_at DESC
");

if(!$stmt2){
    die("Notification Error: " . $conn->error);
}

$stmt2->bind_param("i", $author_id);
$stmt2->execute();

$notifications = $stmt2->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Author Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="dashboard">

    <div class="sidebar">

        <h2>Author Panel</h2>

        <ul>
            <li><a href="#upload">Submit Paper</a></li>
            <li><a href="#mypapers">My Papers</a></li>
            <li><a href="#revision">Revision Requests</a></li>
            <li><a href="#notifications">Notifications</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>

    </div>

    <div class="main-content">

        <div class="topbar">
            <h1>Welcome, <?= $name; ?></h1>
            <a class="logout-btn" href="logout.php">Logout</a>
        </div>

        <!-- ================= UPLOAD ================= -->

        <div class="table-box" id="upload">

            <h2>Submit Paper</h2>

            <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
            <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

            <form method="POST" enctype="multipart/form-data">

                <select name="conference_id" required>
                    <option value="">Select Conference</option>

                    <?php
                    $conferences = $conn->query("SELECT * FROM conference_settings");
                    while($c = $conferences->fetch_assoc()){
                    ?>
                        <option value="<?= $c['conference_id']; ?>">
                            <?= $c['title']; ?>
                        </option>
                    <?php } ?>

                </select>

                <input type="text" name="title" placeholder="Paper Title" required>

                <textarea name="abstract" placeholder="Abstract" required></textarea>

                <input type="file" name="paper" required>

                <button type="submit" name="upload_paper">Upload Paper</button>

            </form>

        </div>

        <br><br>

        <!-- ================= MY PAPERS ================= -->

        <div class="table-box" id="mypapers">

            <h2>My Papers</h2>

            <table>

                <tr>
                    <th>Title</th>
                    <th>File</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>

                <?php while($paper = $papers->fetch_assoc()) { ?>

                <tr>

                    <td><?= $paper['title']; ?></td>

                    <td>
                        <a href="uploads/<?= $paper['filename']; ?>" target="_blank">View</a>
                    </td>

                    <td><?= $paper['status']; ?></td>

                    <td><?= $paper['uploaded_at']; ?></td>

                </tr>

                <?php } ?>

            </table>

        </div>

        <br><br>

        <!-- ================= REVISION ================= -->

        <div class="table-box" id="revision">

            <h2>Revision Requests</h2>

            <?php while($rev = $revisionPapers->fetch_assoc()) { ?>

                <div style="padding:15px; background:#f5f5f5; margin-bottom:10px;">

                    <h4><?= $rev['title']; ?></h4>

                    <form method="POST" enctype="multipart/form-data">

                        <input type="hidden" name="paper_id" value="<?= $rev['id']; ?>">

                        <input type="file" name="revised_paper" required>

                        <button type="submit" name="resubmit_paper">Resubmit</button>

                    </form>

                </div>

            <?php } ?>

        </div>

        <br><br>

        <!-- ================= NOTIFICATIONS ================= -->

        <div class="table-box" id="notifications">

            <h2>Notifications</h2>

            <?php if($notifications->num_rows > 0) { ?>

                <?php while($note = $notifications->fetch_assoc()) { ?>

                    <div style="padding:10px; background:#eef; margin-bottom:10px;">

                        <b><?= $note['created_at']; ?></b><br>
                        <?= $note['message']; ?>

                    </div>

                <?php } ?>

            <?php } else { ?>

                <p>No notifications yet</p>

            <?php } ?>

        </div>

    </div>

</div>

</body>
</html>
