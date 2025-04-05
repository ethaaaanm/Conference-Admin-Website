<!DOCTYPE html>
<html>

<head>
    <title>Conference Management</title>
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
            <h1>Conference Management</h1>

            <div id="tabs">
                <ul>
                    <li><a href="#schedule">Conference Schedule</a></li>
                    <li><a href="#intake">Total Intake</a></li>
                    <li><a href="#switch_session">Switch Session</a></li>
                </ul>

                <div id="schedule">
                    <h2>Conference Schedule</h2>
                    <form method="post">
                        <label for="schedule_date">Select Date:</label>
                        <select name="schedule_date" id="schedule_date">
                            <option value="">Select Date</option>
                            <?php
                            require_once('../database/db_connect.php');
                            $pdo = $connection;
                            $query = $pdo->query("SELECT DISTINCT Day FROM Session ORDER BY Day ASC");
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule_date']) && $_POST['schedule_date'] == $row['Day']) ? 'selected' : '';
                                echo "<option value='" . $row['Day'] . "' " . $selected . ">" . $row['Day'] . "</option>";
                            }
                            ?>
                        </select>
                        <button type="submit">Show Schedule</button>
                    </form>

                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule_date']) && $_POST['schedule_date'] != '') {
                        $schedule_date = $_POST['schedule_date'];
                        $query = $pdo->prepare("SELECT SName, RoomNum, StartTime, EndTime FROM Session WHERE Day = ?");
                        $query->execute([$schedule_date]);
                        $results = $query->fetchAll(PDO::FETCH_ASSOC);

                        if ($results) {
                            echo "<table>";
                            echo "<tr><th>Session Name</th><th>Room Number</th><th>Start Time</th><th>End Time</th></tr>";
                            foreach ($results as $row) {
                                echo "<tr><td>" . $row['SName'] . "</td><td>" . $row['RoomNum'] . "</td><td>" . $row['StartTime'] . "</td><td>" . $row['EndTime'] . "</td></tr>";
                            }
                            echo "</table>";
                        } else {
                            echo "<p>No sessions found for " . $schedule_date . ".</p>";
                        }
                    } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule_date']) && $_POST['schedule_date'] == '') {
                        echo "<p>Please select a date.</p>";
                    }
                    ?>
                </div>

                <div id="intake">
                    <h2>Total Conference Intake</h2>
                    <?php
                    $student_query = $pdo->query("SELECT SUM(SRate) as total_student FROM Student");
                    $student_result = $student_query->fetch(PDO::FETCH_ASSOC);
                    $student_total = $student_result['total_student'] ?? 0;

                    $professional_query = $pdo->query("SELECT SUM(PRate) as total_professional FROM Professional");
                    $professional_result = $professional_query->fetch(PDO::FETCH_ASSOC);
                    $professional_total = $professional_result['total_professional'] ?? 0;

                    $sponsor_levels = ['Platinum', 'Gold', 'Silver', 'Bronze'];
                    $sponsor_totals = [];
                    $total_sponsor = 0;

                    foreach ($sponsor_levels as $level) {
                        $sponsor_query = $pdo->prepare("SELECT SUM(CASE WHEN SponsorLvl = ? THEN CASE SponsorLvl WHEN 'Platinum' THEN 10000 WHEN 'Gold' THEN 5000 WHEN 'Silver' THEN 2500 WHEN 'Bronze' THEN 1000 ELSE 0 END ELSE 0 END) as total FROM SponsorCompany");
                        $sponsor_query->execute([$level]);
                        $sponsor_result = $sponsor_query->fetch(PDO::FETCH_ASSOC);
                        $sponsor_totals[$level] = $sponsor_result['total'] ?? 0;
                        $total_sponsor += $sponsor_totals[$level];
                    }

                    $total_intake = $student_total + $professional_total + $total_sponsor;
                    echo "<h3>Attendee Totals:</h3>";
                    echo "<p>Total Student Registration: $" . $student_total . "</p>";
                    echo "<p>Total Professional Registration: $" . $professional_total . "</p>";

                    echo "<h3>Sponsorship Totals:</h3>";
                    foreach ($sponsor_totals as $level => $total) {
                        echo "<p>Total " . $level . " Sponsorship: $" . $total . "</p>";
                    }

                    echo "<p>Total Sponsorship: $" . $total_sponsor . "</p>";
                    echo "<h3>Overall Total:</h3>";
                    echo "<p>Total Conference Intake: $" . $total_intake . "</p>";
                    ?>
                </div>
                <div id="switch_session">
                    <h2>Switch Session</h2>
                    <form method="post">
                        <label for="session_id">Current Session:</label>
                        <select name="session_id" id="session_id">
                            <?php
                            $query = $pdo->query("SELECT SessionID, SName, Day, RoomNum FROM Session");
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['SessionID'] . "'>" . $row['SName'] . " - " . $row['Day'] . " - Room " . $row['RoomNum'] . "</option>";
                            }
                            ?>
                        </select><br>

                        <label for="new_day">New Day:</label>
                        <input type="date" name="new_day" id="new_day"><br>

                        <label for="new_start_time">New Start Time:</label>
                        <input type="time" name="new_start_time" id="new_start_time"><br>

                        <label for="new_end_time">New End Time:</label>
                        <input type="time" name="new_end_time" id="new_end_time"><br>

                        <label for="new_room_num">New Room Number:</label>
                        <select name="new_room_num" id="new_room_num">
                            <?php
                            $query = $pdo->query("SELECT RoomNum FROM HotelRoom");
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['RoomNum'] . "'>" . $row['RoomNum'] . "</option>";
                            }
                            ?>
                        </select><br>

                        <button type="submit" name="switch_session_submit">Switch Session</button>
                    </form>

                    <?php
                    if (isset($_POST['switch_session_submit'])) {
                        $session_id = $_POST['session_id'];
                        $new_day = $_POST['new_day'];
                        $new_start_time = $_POST['new_start_time'];
                        $new_end_time = $_POST['new_end_time'];
                        $new_room_num = $_POST['new_room_num'];

                        try {
                            $pdo->beginTransaction();

                            $updateSessionQuery = $pdo->prepare("UPDATE Session SET Day = ?, StartTime = ?, EndTime = ?, RoomNum = ? WHERE SessionID = ?");
                            $updateSessionQuery->execute([$new_day, $new_start_time, $new_end_time, $new_room_num, $session_id]);

                            $pdo->commit();

                            echo "<p>Session switched successfully.</p>";
                        } catch (PDOException $e) {
                            $pdo->rollBack();
                            echo "<p>Error switching session: " . $e->getMessage() . "</p>";
                            print_r($updateSessionQuery->errorInfo());
                        }
                    }
                    ?>
                </div>
            </div>

            <a href="../conference.php"> Back Home </a>
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
<?php include 'footer.php'; ?>