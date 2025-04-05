<!DOCTYPE html>
<html>

<head>
    <title>Subcommittee Members Page</title>
    <link rel="stylesheet" href="../res/styles/global.css">
    <link rel="stylesheet" href="../res/styles/pages.css">
</head>

<body>
    <div class="page-wrapper">
        <div class="header">
            <h3>CISC 332</h3>
            <h3> <?php echo date("Y-m-d"); ?> </h3>
        </div>
        <div class="container">
            <h1>Sub-Committee Members</h1>
            <form method="post">
                <label for="scid">Select Sub-Committee:</label>
                <select name="scid" id="scid">
                    <option value="">Select Sub-Committee</option>
                    <?php
                    require_once('../database/db_connect.php');
                    $pdo = $connection;
                    $query = $pdo->query("SELECT SCid, SCname FROM SubCommittee");
                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['scid']) && $_POST['scid'] == $row['SCid']) ? 'selected' : '';
                        echo "<option value='" . $row['SCid'] . "' " . $selected . ">" . $row['SCname'] . "</option>";
                    }
                    ?>
                </select>
                <button type="submit">Show Members</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['scid']) && $_POST['scid'] != '') { 
                $scid = $_POST['scid'];
                $query = $pdo->prepare("SELECT Member.Mid, Member.FName, Member.LName FROM Member JOIN Made_of ON Member.Mid = Made_of.Mid WHERE Made_of.SCid = ?");
                $query->execute([$scid]);
                $results = $query->fetchAll(PDO::FETCH_ASSOC);

                if ($results) {
                    echo "<table>";
                    echo "<tr><th>Member ID</th><th>First Name</th><th>Last Name</th></tr>";
                    foreach ($results as $row) {
                        echo "<tr><td>" . $row['Mid'] . "</td><td>" . $row['FName'] . "</td><td>" . $row['LName'] . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No members found for this sub-committee.</p>";
                }
            } else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['scid']) && $_POST['scid'] == ''){
                echo "<p>Please select a Sub-Committee</p>";
            }
            ?>

            <a href="../conference.php"> Back Home </a>
        </div>
    </div>
</body>

</html>

<?php
include '../footer.php';
?>