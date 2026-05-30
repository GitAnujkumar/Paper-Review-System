<?php

session_start();
require_once "config.php";

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$reviewer_id = $_SESSION['user_id'];

# ================= SUBMIT REVIEW =================

if(isset($_POST['submit_review'])){

    $paper_id = $_POST['paper_id'];
    $review_comment = $_POST['review_comment'];
    $review_score = $_POST['review_score'];
    $recommendation = $_POST['recommendation'];

    # insert review

    $stmt = $conn->prepare("
    INSERT INTO reviews(    
    paper_id,
    reviewer_id,
    review_comment,
    review_score,
    recommendation
    )
    VALUES(?,?,?,?,?)
    ");

    $stmt->bind_param(
    "iisis",
    $paper_id,
    $reviewer_id,
    $review_comment,
    $review_score,
    $recommendation
    );

    $stmt->execute();

    # update review status

    $update = $conn->prepare("
    UPDATE assign_reviewers                 
    SET review_status='Completed'
    WHERE paper_id=? AND reviewer_id=?
    ");

    $update->bind_param(
    "ii",
    $paper_id,
    $reviewer_id
    );

    $update->execute();
}

# ================= FETCH ASSIGNED PAPERS =================

$papers = $conn->prepare("
SELECT papers.*
FROM papers

INNER JOIN assign_reviewers                  
ON papers.id = assign_reviewers.paper_id

WHERE assign_reviewers.reviewer_id=?
");

$papers->bind_param("i",$reviewer_id);

$papers->execute();

$assignedPapers = $papers->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reviewer Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard">

    <div class="sidebar">
        <h2>Reviewer Panel</h2>

        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Assigned Papers</a></li>
            <li><a href="#">Write Review</a></li>
            <li><a href="#">Completed Reviews</a></li>
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
                <h3>Assigned Papers</h3>
                <p>8 Papers</p>
            </div>

            <div class="card">
                <h3>Completed Reviews</h3>
                <p>5 Reviews Submitted</p>
            </div>

            <div class="card">
                <h3>Pending Reviews</h3>
                <p>3 Reviews Left</p>
            </div>

        </div>

        <div class="table-box">

    <h2>Assigned Papers</h2>

    <br>

    <table>

        <tr>
            <th>Paper Title</th>
            <th>File</th>
            <th>Write Review</th>
        </tr>

        <?php while($paper = $assignedPapers->fetch_assoc()) { ?>

        <tr>

            <td>

                <?= $paper['title']; ?>

            </td>

            <td>

                <a
                href="uploads/<?= $paper['filename']; ?>"
                target="_blank">

                View Paper

                </a>

            </td>

            <td>

                <form method="POST">

                    <input
                    type="hidden"
                    name="paper_id"
                    value="<?= $paper['id']; ?>">

                    <textarea
                    name="review_comment"
                    placeholder="Write review"
                    required
                    style="width:100%; height:100px;"></textarea>

                    <br><br>

                    <input
                    type="number"
                    name="review_score"
                    placeholder="Score (1-10)"
                    min="1"
                    max="10"
                    required>

                    <br><br>

                    <select
                    name="recommendation"
                    required>

                        <option value="">
                            Recommendation
                        </option>

                        <option value="Accept">
                            Accept
                        </option>

                        <option value="Reject">
                            Reject
                        </option>

                        <option value="Revision Required">
                            Revision Required
                        </option>

                    </select>

                    <br><br>

                    <button
                    type="submit"
                    name="submit_review">

                    Submit Review

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
