<?php

session_start();
require_once "config.php";

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$organizer_id = $_SESSION['user_id'];

# ================= ASSIGN REVIEWER =================

if(isset($_POST['assign_reviewer'])){

    $paper_id = $_POST['paper_id'];
    $reviewer_id = $_POST['reviewer_id'];

    $stmt = $conn->prepare("
    INSERT INTO assign_reviewers(              
    paper_id,
    reviewer_id,
    organizer_id
    )
    VALUES(?,?,?)
    ");

    $stmt->bind_param(
    "iii",
    $paper_id,
    $reviewer_id,
    $organizer_id
    );

    $stmt->execute();

    # update paper status

    $update = $conn->prepare("
    UPDATE papers
    SET status='Under Review'               
    WHERE id=?
    ");

    $update->bind_param("i",$paper_id);
    $update->execute();
}

# ================= FETCH PAPERS =================

$papers = $conn->query("
SELECT papers.*, all_users.name
FROM papers

INNER JOIN all_users
ON papers.author_id = all_users.id
");

# ================= FETCH REVIEWERS =================

$reviewers = $conn->query("
SELECT *
FROM all_users
WHERE role='Reviewer'                  
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Organizer Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard">

    <div class="sidebar">
        <h2>Organizer Panel</h2>

        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Assign Reviewers</a></li>
            <li><a href="#">Schedule Timeline</a></li>
            <li><a href="#">Monitor Progress</a></li>
            <li><a href="#">Send Reminders</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">

        <div class="topbar">
            <h1>Welcome, <?= $_SESSION['name']; ?></h1>
            <a class="logout-btn" href="logout.php">Logout</a>
        </div>

        <div class="card-container">

            <div class="card">
                <h3>Total Papers</h3>
                <p>25 Submissions</p>
            </div>

            <div class="card">
                <h3>Reviewers</h3>
                <p>12 Active Reviewers</p>
            </div>

            <div class="card">
                <h3>Pending Reviews</h3>
                <p>6 Remaining</p>
            </div>

        </div>

        <div class="table-box">

    <h2>Assign Reviewers</h2>

    <br>

    <table>

        <tr>
            <th>Paper</th>
            <th>Author</th>
            <th>Assign Reviewer</th>
        </tr>

        <?php while($paper = $papers->fetch_assoc()) { ?>

        <tr>

            <td>

                <?= $paper['title']; ?>

            </td>

            <td>

                <?= $paper['name']; ?>

            </td>

            <td>

                <form method="POST">

                    <input
                    type="hidden"
                    name="paper_id"
                    value="<?= $paper['id']; ?>">

                    <select
                    name="reviewer_id"
                    required>

                        <option value="">
                            Select Reviewer
                        </option>

                        <?php
                        $reviewers->data_seek(0);

                        while($reviewer = $reviewers->fetch_assoc()) {
                        ?>

                        <option
                        value="<?= $reviewer['id']; ?>">

                        <?= $reviewer['name']; ?>

                        </option>

                        <?php } ?>

                    </select>

                    <button
                    type="submit"
                    name="assign_reviewer">

                    Assign

                    </button>

                </form>

            </td>

        </tr>

        <?php } ?>

    </table>

</div>
    </div>

</div>

</body>
</html>
