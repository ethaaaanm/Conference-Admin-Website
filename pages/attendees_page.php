<!DOCTYPE html>
<html>

<head>
    <title>Conference Attendees</title>
    <link rel="stylesheet" href="../res/styles/global.css">
    <link rel="stylesheet" href="../res/styles/pages.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>

<body>
    <div class="page-wrapper">
        <div class="header">
            <h3>CISC 332</h3>
            <h3><?php echo date("Y-m-d"); ?></h3>
        </div>
        <div class="container">
            <h1>Attendees Information Page</h1>

            <div id="tabs">
                <ul>
                    <li><a href="#view_attendees">View Attendees</a></li>
                    <li><a href="#room_students">Students in Room</a></li>
                    <li><a href="#add_attendee">Add Attendee</a></li>
                    <li><a href="#delete_attendee">Delete Attendee</a></li>
                </ul>

                <div id="view_attendees">
                    <h2>Attendee Lists</h2>

                    <h3>Students</h3>
                    <?php
                    require_once('../database/db_connect.php');
                    $pdo = $connection;
                    $student_query = $pdo->query("SELECT Attendee.Aid, Attendee.FName, Attendee.LName, Student.RoomNum FROM Attendee JOIN Student ON Attendee.Aid = Student.Aid");
                    $students = $student_query->fetchAll(PDO::FETCH_ASSOC);

                    if ($students) {
                        echo "<table>";
                        echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Room Num</th></tr>";
                        foreach ($students as $student) {
                            echo "<tr><td>" . $student['Aid'] . "</td><td>" . $student['FName'] . "</td><td>" . $student['LName'] . "</td><td>" . $student['RoomNum'] . "</td></tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>No students found.</p>";
                    }
                    ?>

                    <h3>Professionals</h3>
                    <?php
                    $professional_query = $pdo->query("SELECT Attendee.Aid, Attendee.FName, Attendee.LName FROM Attendee JOIN Professional ON Attendee.Aid = Professional.Aid");
                    $professionals = $professional_query->fetchAll(PDO::FETCH_ASSOC);

                    if ($professionals) {
                        echo "<table>";
                        echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th></tr>";
                        foreach ($professionals as $professional) {
                            echo "<tr><td>" . $professional['Aid'] . "</td><td>" . $professional['FName'] . "</td><td>" . $professional['LName'] . "</td></tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>No professionals found.</p>";
                    }
                    ?>

                    <h3>Sponsors</h3>
                    <?php
                    $sponsor_query = $pdo->query("SELECT Attendee.Aid, Attendee.FName, Attendee.LName, SponsorCompany.SPCName FROM Attendee JOIN SponsorAttendee ON Attendee.Aid = SponsorAttendee.Aid JOIN SponsorCompany ON SponsorAttendee.SPCid = SponsorCompany.SPCid");
                    $sponsors = $sponsor_query->fetchAll(PDO::FETCH_ASSOC);

                    if ($sponsors) {
                        echo "<table>";
                        echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Sponsor Company</th></tr>";
                        foreach ($sponsors as $sponsor) {
                            echo "<tr><td>" . $sponsor['Aid'] . "</td><td>" . $sponsor['FName'] . "</td><td>" . $sponsor['LName'] . "</td><td>" . $sponsor['SPCName'] . "</td></tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>No sponsors found.</p>";
                    }
                    ?>
                </div>

                <div id="room_students">
                    <h2>Students in Hotel Room</h2>
                    <form method="post">
                        <label for="room_number">Select Hotel Room:</label>
                        <select name="room_number" id="room_number">
                            <option value="">Select Room Number</option>
                            <?php
                            $room_query = $pdo->query("SELECT DISTINCT RoomNum FROM HotelRoom");
                            while ($row = $room_query->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_number']) && $_POST['room_number'] == $row['RoomNum']) ? 'selected' : '';
                                echo "<option value='" . $row['RoomNum'] . "' " . $selected . ">" . $row['RoomNum'] . "</option>";
                            }
                            ?>
                        </select>
                        <button type="submit">Show Students</button>
                    </form>

                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_number']) && $_POST['room_number'] != '') {
                        $room_number = $_POST['room_number'];
                        $query = $pdo->prepare("SELECT Attendee.Aid, Attendee.FName, Attendee.LName FROM Attendee JOIN Student ON Attendee.Aid = Student.Aid WHERE Student.RoomNum = ?");
                        $query->execute([$room_number]);
                        $results = $query->fetchAll(PDO::FETCH_ASSOC);

                        if ($results) {
                            echo "<table>";
                            echo "<tr><th>Attendee ID</th><th>First Name</th><th>Last Name</th></tr>";
                            foreach ($results as $row) {
                                echo "<tr><td>" . $row['Aid'] . "</td><td>" . $row['FName'] . "</td><td>" . $row['LName'] . "</td></tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "<p>No students found for room " . $room_number . ".</p>";
                        }
                    } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_number']) && $_POST['room_number'] == '') {
                        echo "<p>Please select a room number</p>";
                    }
                    ?>
                </div>

                <div id="add_attendee">
                    <h2>Add New Attendee</h2>
                    <form method="post">
                        <label for="fname">First Name:</label>
                        <input type="text" name="fname" id="fname" required><br>

                        <label for="lname">Last Name:</label>
                        <input type="text" name="lname" id="lname" required><br>

                        <label for="attendee_type">Attendee Type:</label>
                        <select name="attendee_type" id="attendee_type" required>
                            <option value="student">Student</option>
                            <option value="professional">Professional</option>
                            <option value="sponsor">Sponsor</option>
                        </select><br>

                        <label for="room_num">Room Number (Students Only):</label>
                        <select name="room_num" id="room_num">
                            <option value="">Select Room Number</option>
                            <?php
                            $room_query = $pdo->query("SELECT RoomNum FROM HotelRoom");
                            while ($row = $room_query->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['RoomNum'] . "'>" . $row['RoomNum'] . "</option>";
                            }
                            ?>
                        </select><br>

                        <label for="spcid">Sponsor Company (Sponsors Only):</label>
                        <select name="spcid" id="spcid">
                            <option value="">Select Sponsor Company</option>
                            <?php
                            $sponsor_query = $pdo->query("SELECT SPCid, SPCName FROM SponsorCompany");
                            while ($row = $sponsor_query->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['SPCid'] . "'>" . $row['SPCName'] . "</option>";
                            }
                            ?>
                        </select><br>

                        <button type="submit" name="add_attendee">Add Attendee</button>
                    </form>

                    <?php
                    if (isset($_POST['add_attendee'])) {
                        $fname = $_POST['fname'];
                        $lname = $_POST['lname'];
                        $attendee_type = $_POST['attendee_type'];
                        $room_num = $_POST['room_num'];
                        $spcid = $_POST['spcid'];

                        $pdo->beginTransaction();
                        try {
                            $new_aid_query = $pdo->query("SELECT MAX(Aid) FROM Attendee");
                            $max_aid = $new_aid_query->fetchColumn();
                            $new_aid = $max_aid + 1;

                            $insert_attendee = $pdo->prepare("INSERT INTO Attendee (Aid, FName, LName) VALUES (?, ?, ?)");
                            $insert_attendee->execute([$new_aid, $fname, $lname]);

                            if ($attendee_type === 'student') {
                                $insert_student = $pdo->prepare("INSERT INTO Student (Aid, RoomNum) VALUES (?, ?)");
                                $insert_student->execute([$new_aid, $room_num]);
                            } elseif ($attendee_type === 'professional') {
                                $insert_professional = $pdo->prepare("INSERT INTO Professional (Aid) VALUES (?)");
                                $insert_professional->execute([$new_aid]);
                            } elseif ($attendee_type === 'sponsor') {
                                $insert_sponsor = $pdo->prepare("INSERT INTO SponsorAttendee (Aid, SPCid) VALUES (?, ?)");
                                $insert_sponsor->execute([$new_aid, $spcid]);
                            }

                            $pdo->commit();
                            echo "<p>Attendee added successfully.</p>";
                        } catch (PDOException $e) {
                            $pdo->rollBack();
                            echo "<p>Error adding attendee: " . $e->getMessage() . "</p>";
                        }
                    }
                    ?>
                </div>

                <div id="delete_attendee">
                    <h2>Delete Attendee</h2>
                    <form method="post">
                        <label for="delete_aid">Attendee ID to Delete:</label>
                        <select name="delete_aid" id="delete_aid">
                            <option value="">Select Attendee ID</option>
                            <?php
                            $attendee_query = $pdo->query("SELECT Aid FROM Attendee");
                            while ($row = $attendee_query->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['Aid'] . "'>" . $row['Aid'] . "</option>";
                            }
                            ?>
                        </select><br>

                        <button type="submit" name="delete_attendee">Delete Attendee</button>
                    </form>

                    <?php
                    if (isset($_POST['delete_attendee'])) {
                        $delete_aid = $_POST['delete_aid'];

                        try {
                            $query = $pdo->prepare("DELETE FROM Attendee WHERE Aid = ?");
                            $query->execute([$delete_aid]);

                            $rowsAffected = $query->rowCount();
                            if ($rowsAffected > 0) {
                                echo "<p>Attendee with ID " . $delete_aid . " deleted successfully.</p>";
                            } else {
                                echo "<p>No attendee found with ID " . $delete_aid . ".</p>";
                            }
                        } catch (PDOException $e) {
                            echo "<p>Error deleting attendee: " . $e->getMessage() . "</p>";
                        }
                    }
                    ?>
                </div>
            </div>

            <a href="../conference.php">Back Home</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function () {
            var activeTabIndex = localStorage.getItem('activeTab') || 0;
            $("#tabs").tabs({
                active: parseInt(activeTabIndex),
                activate: function (event, ui) {
                    localStorage.setItem('activeTab', ui.newTab.index());
                }
            });
        });
    </script>
</body>

</html>
<?php include '../footer.php'; ?>