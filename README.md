# Paper-Review-SystemIndex.php
<?php                                    // Starts PHP scripting.                                      

session_start();                        // Starts a session to store user information temporarily.     

$errors = [                                             // Creates an array called $errors.
  'login' => $_SESSION['login_error'] ?? '',            // Stores error messages from session.
  'register' => $_SESSION['register_error'] ?? ''       // ?? ''Means: If session value exists → use it Otherwise → empty string    
];
$activeForm = $_SESSION['active_form'] ?? 'login';     // Decides which form should stay visible.

session_unset();                    // Clears all session variables after reading them. Used so old error messages disappear after refresh.

function showError($error) {                                              // Displays error message if available.
  return !empty($error) ? "<p class='error-message'>$error</p>" : '';    
}

function isActiveForm($formName, $activeForm) {           // Adds CSS class active to selected form.
  return $formName === $activeForm ? 'active' : '';       
}

?>

<!DOCTYPE html>                             // Defines HTML5 document.
<html lang="en">                            // English language webpage
<head>
    <meta charset="UTF-8">                  // Supports all text characters.
    <meta name="viewport" content="width=device-width, initial-scale=1.0">                  //Makes page responsive on mobile.→ 
    <title>Login & Register</title>                                                         //Browser tab title.
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">                                                //Connects CSS file.
</head>
<body>
    <div class="container">
        
        <!-- Login Form -->
        <div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form">         // Creates login box.
            <form action="login_register.php" method="post">                                      //Sends form data to:login_register.php Using: POST method
                <h2>Login</h2>
                <?= showError($errors['login']); ?>
                
                <input type="email" name="email" placeholder="Email" required>         // Email input field. 'required' means user cannot leave empty.
                <input type="password" name="password" placeholder="Password" required>                
                <button type="submit" name="login">Login</button>                           // When clicked: $_POST['login'] becomes available.
                <p>Don't have an account? 
                    <a href="#" onclick="showForm('register-form')">Register</a>
                </p>
            </form>
        </div>

        <!-- Register Form -->
        <div class="form-box <?= isActiveForm('register', $activeForm); ?>" id="register-form">  
            <form action="login_register.php" method="post">        
                <h2>Register</h2>
                <?= showError($errors['register']); ?>
                
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>

                <select name="role" required>                             // Dropdown for selecting role. Options: Author, Reviewer, Organizer, Admin.
                    <option value="">----Select Role----</option>
                    <option value="Author">Author</option>
                    <option value="Reviewer">Reviewer</option>
                    <option value="Organizer">Organizer</option>
                    <option value="Admin">Admin</option>
                </select>

                <button type="submit" name="register">Register</button>
                <p>Already have an account? 
                    <a href="#" onclick="showForm('login-form')">Login</a>
                </p>
            </form>
        </div>

    </div>

    <script src="script.js"></script>                 // Loads JavaScript file.
</body>
</html>


Style.css
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');   /*Imports Google font: Poppins*/

*{
    margin:0;                            /*Removes default browser spacing.*/
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:#f4f7fc;              /*Sets webpage background color.*/
    color:#333;
}

/* ================= LOGIN PAGE ================= */

.container{                                /*Flexbox Centers content: horizontally vertically*/
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

.form-box{                              /*initially hidden, then */
    width:420px;
    background:#fff;
    padding:35px;
    border-radius:14px;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
    display:none;
}

.form-box.active{                     /*initially hidden, then Visible only when active class exists.*/
    display:block;
}

.form-box h2{
    text-align:center;
    margin-bottom:25px;
    color:#1e293b;
}

input,                         /*Inputs occupy full width.*/
select{
    width:100%;
    padding:14px;
    border:none;
    outline:none;
    background:#eef2ff;
    border-radius:8px;
    margin-bottom:18px;
    font-size:15px;
}

button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:8px;
    background:#4f46e5;
    color:#fff;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{                        /*Changes color when mouse hovers.*/
    background:#4338ca;
}

p{
    margin-top:15px;
    text-align:center;
}

a{
    text-decoration:none;
    color:#4f46e5;
}

.error-message{
    background:#fee2e2;
    color:#dc2626;
    padding:12px;
    border-radius:8px;
    margin-bottom:15px;
}

/* ================= DASHBOARD ================= */

.dashboard{                       /*Sidebar + content side-by-side.*/
    display:flex;
    min-height:100vh;
}

/* Sidebar */

.sidebar{                         /*Creates navigation panel.*/
    width:260px;
    background:#111827;
    color:#fff;
    padding:25px 20px;
}

.sidebar h2{
    text-align:center;
    margin-bottom:35px;
    font-size:24px;
}

.sidebar ul{
    list-style:none;
}

.sidebar ul li{
    margin:18px 0;
}

.sidebar ul li a{
    color:#d1d5db;
    display:block;
    padding:12px 15px;
    border-radius:8px;
    transition:0.3s;
}

.sidebar ul li a:hover{
    background:#4f46e5;
    color:#fff;
}

/* Main Content */

.main-content{
    flex:1;
    padding:30px;
}

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.topbar h1{
    font-size:28px;
}

.logout-btn{
    background:#ef4444;
    padding:10px 20px;
    border-radius:8px;
    color:#fff;
}

.logout-btn:hover{
    background:#dc2626;
}

/* Cards */

.card-container{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.card{
    background:#fff;
    padding:25px;
    border-radius:14px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

.card h3{
    margin-bottom:10px;
    color:#4f46e5;
}

.card p{
    text-align:left;
    margin-top:0;
    color:#555;
}

/* Tables */

.table-box{
    background:#fff;
    border-radius:14px;
    padding:20px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

table{                                /*Removes gaps between table borders.*/
    width:100%;
    border-collapse:collapse;
}

table th,
table td{
    padding:14px;
    text-align:left;
    border-bottom:1px solid #e5e7eb;
}

table th{
    background:#eef2ff;
}

.status{
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    color:#fff;
}

.accepted{
    background:#22c55e;
}

.pending{
    background:#f59e0b;
}

.rejected{
    background:#ef4444;
}

/* Responsive */

@media(max-width:768px){               /*For mobile devices. Changes dashboard layout vertically.*/

    .dashboard{
        flex-direction:column;
    }

    .sidebar{
        width:100%;
    }

}

form input[type="file"]{
    background:#fff;
    padding:12px;
    border:1px solid #ddd;
}

.table-box h2{
    margin-bottom:10px;
}

table a{
    color:#4f46e5;
    text-decoration:none;
    font-weight:500;
}

table a:hover{
    text-decoration:underline;
}


Script.js
function showForm(formId) {            /*Creates function. Parameter: formId*/
    // Hide all forms
    document.querySelectorAll('.form-box').forEach(form => {form.classList.remove('active');});  //Select all forms and removes active class from all forms.
    // Show selected form
    document.getElementById(formId).classList.add('active');   //Adds active class to selected form.
}

Config.php
<?php

$host = "localhost";           //Database credentials.
$user = "root";
$password = "";
$database = "login";

$conn = new mysqli($host, $user, $password, $database);   //Creates MySQL connection.

if ($conn->connect_error) {                              //Checks database connection failure.
    die("Connection failed: " . $conn->connect_error);
}

/* ================= GLOBAL FUNCTION (ONLY ONCE) ================= */

if (!function_exists('sendNotification')) {              //Avoids duplicate function declaration.

    function sendNotification($conn, $user_id, $message)    //Parameters: database connection, user ID, notification text
    {
        $stmt = $conn->prepare("
            INSERT INTO notifications (user_id, message)      /*Adds notification into database.*/
            VALUES (?, ?)
        ");

        if (!$stmt) {
            die("Notification Prepare Failed: " . $conn->error);
        }

        $stmt->bind_param("is", $user_id, $message);
        $stmt->execute();
    }
}

?>



Login_register.php
<?php

session_start();
require_once "config.php";

# ================= REGISTER =================

if(isset($_POST['register'])){                 //Checks if Register button clicked

    $name = trim($_POST['name']);                                        //Removes extra spaces.
    $email = strtolower(trim($_POST['email']));                          //Converts email to lowercase.
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);     //Encrypts password securely.
    $role = $_POST['role'];

    $check = $conn->prepare("SELECT id FROM all_users WHERE email=?");   //Checks duplicate email.
    $check->bind_param("s",$email);                                      //Protects against SQL Injection. s means: string
    $check->execute();

    $result = $check->get_result();

    if($result->num_rows > 0){

        $_SESSION['register_error'] = "Email already exists!";
        $_SESSION['active_form'] = "register";

        header("Location:index.php");
        exit();
    }

    $stmt = $conn->prepare("
        INSERT INTO all_users(name,email,password,role)           //Stores user data.
        VALUES(?,?,?,?)
    ");

    $stmt->bind_param("ssss",$name,$email,$password,$role);

    if($stmt->execute()){

        $_SESSION['login_error'] = "Registration Successful.";

    }else{

        $_SESSION['register_error'] = "Registration Failed!";
        $_SESSION['active_form'] = "register";
    }

    header("Location:index.php");
    exit();
}

# ================= LOGIN =================

if(isset($_POST['login'])){                              //Checks login request.

    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    $stmt = $conn->prepare("
        SELECT * FROM all_users                     // Finds matching email
        WHERE email=?
    ");

    $stmt->bind_param("s",$email);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){

        $user = $result->fetch_assoc();

        if(password_verify($password,$user['password'])){     //Compares entered password with hashed password.

            $_SESSION['user_id'] = $user['id'];              //Stores logged-in user info.
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            switch($user['role']){                          //Redirects user according to role.

                case 'Admin':
                    header("Location:admin_page.php");
                    break;

                case 'Organizer':
                    header("Location:organizer_page.php");
                    break;

                case 'Reviewer':
                    header("Location:reviewer_page.php");
                    break;

                default:
                    header("Location:author_page.php");
            }

            exit();
        }
    }

    $_SESSION['login_error'] = "Invalid Email or Password!";
    $_SESSION['active_form'] = "login";

    header("Location:index.php");
    exit();
}

?>




Author_page.php
<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email'])) {                   //If user not logged in: redirect to login page.
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

    $fileName = $_FILES['paper']['name'];                // Access uploaded file.
    $tmpName = $_FILES['paper']['tmp_name'];

    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));   //Gets: pdf/doc/docx

    $allowed = ['pdf', 'doc', 'docx'];                                  //Only these formats allowed.

    if (in_array($extension, $allowed)) {

        $newFileName = time() . "_" . basename($fileName);            //Prevents duplicate filenames.

        $uploadDir = __DIR__ . "/uploads/";

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);                         //Creates uploads folder automatically.
        }

        $uploadPath = $uploadDir . $newFileName;

        if (move_uploaded_file($tmpName, $uploadPath)) {        //Moves uploaded file into server storage.

            $stmt = $conn->prepare("
                INSERT INTO papers(                            //Stores paper information.
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

if (isset($_POST['resubmit_paper'])) {                           // Updates: filename status = Resubmitted

    $paperId = $_POST['paper_id'];

    $fileName = $_FILES['revised_paper']['name'];
    $tmpName = $_FILES['revised_paper']['tmp_name'];

    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));   //Gets: pdf/doc/docx

    $allowed = ['pdf', 'doc', 'docx'];                                  //Only these formats allowed.

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
    WHERE author_id=?                                      //Shows author's papers.
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
    SELECT * FROM notifications                         //Displays admin notifications.
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



Reviewer_page.php
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
    INSERT INTO reviews(            //Stores review.
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
    UPDATE assign_reviewers                     //Marks review completed.
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

INNER JOIN assign_reviewers                   //Combines: papers table and assign_reviewers table
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



Organizer_page.php
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
    INSERT INTO assign_reviewers(              //Creates reviewer assignment.
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
    SET status='Under Review'               //Paper now under review
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
WHERE role='Reviewer'                   //Gets only reviewers
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


Admin_page.php
<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'Admin') {                   //Only admins allowed.
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
        INSERT INTO decisions (paper_id, admin_id, decision, comment)         //Stores final admin decision.
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("iiss", $paper_id, $admin_id, $decision, $comment);
    $stmt->execute();

    // Update paper status
    $stmt2 = $conn->prepare("
        UPDATE papers SET status=? WHERE id=?                      //Updates paper result.
    ");

    $stmt2->bind_param("si", $decision, $paper_id);
    $stmt2->execute();

    // ================= NOTIFICATIONS =================

    if ($decision == "Accepted") {
        sendNotification($conn, $author_id, "Your paper has been ACCEPTED.");           //Updates paper result.
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



logout.php
<?php
session_start();            //Starts session
session_unset();            //Removes all session variables.
session_destroy();          //Completely deletes session.

header("Location: index.php");    //Redirects to login page.
exit();
?>

