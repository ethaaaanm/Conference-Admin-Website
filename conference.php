<!DOCTYPE html>
<html>

<head>
    <title>Conference Admin Portal</title>
    <link rel="stylesheet" href="./res/styles/global.css">
</head>

<body>
    <div class="page-wrapper">
        <div class="header">
            <h3>CISC 332</h3>
            <h3> <?php echo date("Y-m-d"); ?> </h3>
        </div>
        <div class="container">
            <h1>Conference Admin Portal</h1>
            <img src="./res/images/conference_banner.png" alt="Conference Image" class="conference-banner">
            <h2> Navigate to an Admin Page:</h2>
            <div class="menu">
                <a href="./pages/subcommittee_page.php">Sub-Committee Page</a>|
                <a href="./pages/attendees_page.php">Attendees Info Page</a> |
                <a href="./pages/conference_page.php">Conference Management Page</a> |
                <a href="./pages/sponsors_page.php">Sponsor Management</a> |
            </div>
        </div>
    </div>
</body>
<?php
include 'footer.php';
?>

</html>