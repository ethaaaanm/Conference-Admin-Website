<!DOCTYPE html>
<html>

<head>
    <title>Sponsors Management</title>
    <link rel="stylesheet" href="../res/styles/global.css">
    <link rel="stylesheet" href="../res/styles/pages.css">
</head>

<body>
    <div class="page-wrapper">
        <div class="header">
            <h3>CISC 332</h3>
            <h3><?php echo date("Y-m-d"); ?></h3>
        </div>
        <div class="container">
            <h1>Sponsors Management</h1>

            <div id="tabs">
                <ul>
                    <li><a href="#sponsor_list">Sponsor List</a></li>
                    <li><a href="#company_jobs">Company Jobs</a></li>
                    <li><a href="#all_jobs">All Jobs</a></li>
                    <li><a href="#add_sponsor">Add Sponsor</a></li>
                    <li><a href="#delete_sponsor">Delete Sponsor</a></li>
                </ul>

                <div id="sponsor_list">
                    <h2>Sponsor List</h2>
                    <?php
                    require_once('../database/db_connect.php');
                    $pdo = $connection;
                    $query = $pdo->query("SELECT SPCName, SponsorLvl FROM SponsorCompany");
                    $results = $query->fetchAll(PDO::FETCH_ASSOC);

                    if ($results) {
                        echo "<table>";
                        echo "<tr><th>Company Name</th><th>Sponsorship Level</th></tr>";
                        foreach ($results as $row) {
                            echo "<tr><td>" . $row['SPCName'] . "</td><td>" . $row['SponsorLvl'] . "</td></tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>No sponsors found.</p>";
                    }
                    ?>
                </div>

                <div id="company_jobs">
                    <h2>Company Jobs</h2>
                    <form method="post" action="">
                        <label for="spcid_jobs">Select Company:</label>
                        <select name="spcid_jobs" id="spcid_jobs">
                            <?php
                            $query = $pdo->query("SELECT SPCid, SPCName FROM SponsorCompany");
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                $selected = (isset($_POST['spcid_jobs']) && $_POST['spcid_jobs'] == $row['SPCid']) ? 'selected' : '';
                                echo "<option value='" . $row['SPCid'] . "' " . $selected . ">" . $row['SPCName'] . "</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" name="show_company_jobs">Show Jobs</button>
                    </form>
                    <?php
                    if (isset($_POST['show_company_jobs']) && isset($_POST['spcid_jobs'])) {
                        $spcid_jobs = $_POST['spcid_jobs'];
                        $query = $pdo->prepare("SELECT JobTitle, LocationCity, LocationProv, PayRate FROM Ad WHERE SPCid = ?");
                        $query->execute(params: [$spcid_jobs]);
                        $results = $query->fetchAll(PDO::FETCH_ASSOC);

                        if ($results) {
                            echo "<table>";
                            echo "<tr><th>Job Title</th><th>Location City</th><th>Location Province</th><th>Pay Rate</th></tr>";
                            foreach ($results as $row) {
                                echo "<tr><td>" . $row['JobTitle'] . "</td><td>" . $row['LocationCity'] . "</td><td>" . $row['LocationProv'] . "</td><td>" . $row['PayRate'] . "</td></tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "<p>No jobs found for this company.</p>";
                        }
                    }
                    ?>
                </div>

                <div id="all_jobs">
                    <h2>All Jobs</h2>
                    <?php
                    $query = $pdo->query("SELECT JobTitle, LocationCity, LocationProv, PayRate, SPCName FROM Ad JOIN SponsorCompany on Ad.SPCid = SponsorCompany.SPCid");
                    $results = $query->fetchAll(PDO::FETCH_ASSOC);

                    if ($results) {
                        echo "<table>";
                        echo "<tr><th>Job Title</th><th>Location City</th><th>Location Province</th><th>Pay Rate</th><th>Company Name</th></tr>";
                        foreach ($results as $row) {
                            echo "<tr><td>" . $row['JobTitle'] . "</td><td>" . $row['LocationCity'] . "</td><td>" . $row['LocationProv'] . "</td><td>" . $row['PayRate'] . "</td><td>" . $row['SPCName'] . "</td></tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>No jobs found.</p>";
                    }
                    ?>
                </div>

                <div id="add_sponsor">
                    <h2>Add Sponsor</h2>
                    <form method="post" action="">
                        <label for="spcname">Company Name:</label>
                        <input type="text" name="spcname" id="spcname" required><br>
                        <label for="sponsorlvl">Sponsor Level:</label>
                        <select name="sponsorlvl" id="sponsorlvl">
                            <option value="Platinum">Platinum</option>
                            <option value="Gold">Gold</option>
                            <option value="Silver">Silver</option>
                            <option value="Bronze">Bronze</option>
                        </select><br>
                        <button type="submit" name="add_new_sponsor">Add Sponsor</button>
                    </form>
                    <?php
                    if (isset($_POST['add_new_sponsor'])) {
                        $spcname = $_POST['spcname'];
                        $sponsorlvl = $_POST['sponsorlvl'];
                    
                        try {
                            $lastIdQuery = $pdo->query("SELECT MAX(SPCid) AS lastId FROM SponsorCompany");
                            $lastIdResult = $lastIdQuery->fetch(PDO::FETCH_ASSOC);
                            $lastId = $lastIdResult['lastId'];
                            $newId = $lastId + 1;
                            $query = $pdo->prepare("INSERT INTO SponsorCompany (SPCid, SPCName, SponsorLvl) VALUES (?, ?, ?)");
                            $query->execute([$newId, $spcname, $sponsorlvl]);
                    
                            echo "<p>Sponsor added successfully.</p>";
                        } catch (PDOException $e) {
                            echo "<p>Error adding sponsor: " . $e->getMessage() . "</p>";
                        }
                    }
                    ?>
                </div>

                <div id="delete_sponsor">
                    <h2>Delete Sponsor</h2>
                    <form method="post" action="">
                        <label for="spcid_delete">Select Company to Delete:</label>
                        <select name="spcid_delete" id="spcid_delete">
                            <?php
                            $query = $pdo->query("SELECT SPCid, SPCName FROM SponsorCompany");
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['SPCid'] . "'>" . $row['SPCName'] . "</option>";
                            }
                            ?>
                        </select><br>

                        <button type="submit" name="delete_selected_sponsor">Delete Sponsor</button>
                    </form>
                    <?php

                    if (isset($_POST['delete_selected_sponsor'])) {
                        $spcid_delete = $_POST['spcid_delete'];
                        $query = $pdo->prepare("DELETE FROM SponsorCompany WHERE SPCid = ?");
                        $query->execute([$spcid_delete]);
                        echo "<p>Sponsor deleted successfully.</p>";
                    }
                    ?>
                </div>
            </div>
            <a href="../conference.php"> Back Home </a>

        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script>
        $(function () {
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $("#tabs").tabs({ active: activeTab });
            } else {
                $("#tabs").tabs();
            }

            $("#tabs").tabs({
                activate: function (event, ui) {
                    localStorage.setItem('activeTab', ui.newTab.index());
                }
            });
        });
    </script>
</body>

</html>
<?php include 'footer.php'; ?>