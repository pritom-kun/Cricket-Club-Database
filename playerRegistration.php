<?php
	
	if (isset($_POST['submit']))
    {
        require "connection.php";
        require "manualCommit.php";


        // Players Table -----------------------------------------------------------------------------------------------
        $first_name = $_POST['firstName'];
        $middle_name = $_POST['middleName'];
        $last_name = $_POST['lastName'];
        $father_name = $_POST['father'];
        $mother_name = $_POST['mother'];
        $presentLocationID = $_POST['currentlID'];
        $permanentLocationID = $_POST['permanentlID'];
        $date_of_birth = $_POST['dob'];
        $date_of_registration = $_POST['dor'];

        // Age of a player cannot be more than 35 years
        $date2 = date("d-m-Y");//today's date

        $date1 = new DateTime($date_of_birth);
        $date2 = new DateTime($date2);

        $interval = $date1->diff($date2);

        $age = $interval->y;

        if ($age > 35)
        {
            echo "<script> alert('Age of a player cannot be more than 35 years'); </script>";
            mysqli_close($conn);
        }

        else
        {
            // Check whether the permanent address is the same as the present address
            if (isset($_POST['sameAdrs']))
                $sameAddress = $_POST['sameAdrs'];
            else
                $sameAddress = 0;

            if ($sameAddress == 1)
                $permanentLocationID = $presentLocationID;

            // Insert into the players table
            $playerQuery = "INSERT INTO players (first_name, middle_name, last_name, father_name, mother_name, present_locationID, permanent_locationID, date_of_birth, registration_date) 
		                VALUES ('$first_name', '$middle_name', '$last_name', '$father_name', '$mother_name', '$presentLocationID', '$permanentLocationID', '$date_of_birth', '$date_of_registration')";

            commitTable($conn, $playerQuery);


            // Locations Table -----------------------------------------------------------------------------------------
            $house = array($_POST['currentHouse'], $_POST['permanentHouse']);
            $street = array($_POST['currentStreet'], $_POST['permanentStreet']);
            $postCode = array($_POST['currentPost'], $_POST['permanentPost']);
            $thana = array($_POST['currentThana'], $_POST['permanentThana']);
            $district = array($_POST['currentDistrict'], $_POST['permanentDistrict']);

            // Check for duplicate LocationID
            $presentLocationExists = false;
            $permanentLocationExists = false;

            $getLocationID = "SELECT locationID FROM locations";

            if ($result = mysqli_query($conn, $getLocationID))
            {
                if (mysqli_num_rows($result) > 0)
                {
                    while ($lID = mysqli_fetch_assoc($result))
                    {
                        if ($lID['locationID'] == $presentLocationID)
                            $presentLocationExists = true;

                        if ($lID['locationID'] == $permanentLocationID)
                            $permanentLocationExists = true;
                    }
                }
            }

            // Insert into the locations table
            if (!$presentLocationExists)
            {
                $locationQuery = "INSERT INTO locations (locationID, house, street, postCode, thana, district) 
		                      VALUES ('$presentLocationID', '$house[0]', '$street[0]', '$postCode[0]', '$thana[0]', '$district[0]')";

                if ($sameAddress != 1)
                    $locationQuery .= ", ('$permanentLocationID', '$house[1]', '$street[1]', '$postCode[1]', '$thana[1]', '$district[1]')";

                commitTable($conn, $locationQuery);
            }

            if (!$permanentLocationExists)
            {
                if ($sameAddress != 1)
                {
                    $locationQuery = "INSERT INTO locations (locationID, house, street, postCode, thana, district)
                                  VALUES ('$permanentLocationID', '$house[1]', '$street[1]', '$postCode[1]', '$thana[1]', '$district[1]')";


                    commitTable($conn, $locationQuery);
                }
            }


            // player_history Table ------------------------------------------------------------------------------------
            $club = array();
            $transferred_to = array();
            $transferred_from = array();
            $total_runs = array();
            $total_wickets = array();
            $team_leader = array();

            for ($i = 0; $i < 10; $i++)
            {
                if (isset($_POST["clubPlayedFor" . $i]))
                {
                    $club[$i] = $_POST["clubPlayedFor" . $i];
                    $transferred_to[$i] = $_POST["transferredTo" . $i];
                    $transferred_from[$i] = $_POST["transferredFrom" . $i];
                    $total_runs[$i] = $_POST["totalRuns" . $i];
                    $total_wickets[$i] = $_POST["totalWickets" . $i];
                    $team_leader[$i] = $_POST["teamLeader" . $i];
                }
            }

            // Get player ID from players table
            $getPlayerID = "SELECT MAX(playerID) AS LastPlayerID FROM players";

            if ($result = mysqli_query($conn, $getPlayerID))
                if (mysqli_num_rows($result) > 0)
                    $pID = mysqli_fetch_assoc($result);

            // Insert into the player_history table
            if (!empty($club[0]))
            {
                $playerHistoryQuery = "INSERT INTO player_history (playerID, club_name, transferred_to, transferred_from, total_runs, total_wickets, team_leader)
                                  VALUES ('" . $pID['LastPlayerID'] . "', '$club[0]', '$transferred_to[0]', '$transferred_from[0]', '$total_runs[0]', '$total_wickets[0]', '$team_leader[0]')";


                for ($i = 1; $i < 10; $i++)
                {
                    if (!empty($club[$i]))
                        $playerHistoryQuery .= ", ('" . $pID['LastPlayerID'] . "', '$club[$i]', '$transferred_to[$i]', '$transferred_from[$i]', '$total_runs[$i]', '$total_wickets[$i]', '$team_leader[$i]')";
                    else
                        break;
                }
                commitTable($conn, $playerHistoryQuery);
            }


            // personal_best Table -------------------------------------------------------------------------------------
            $club_for = array();
            $club_against = array();
            $runs = array();
            $wickets = array();
            $matchID = array();
            $eventID = array();

            for ($i = 0; $i < 10; $i++)
            {
                if (isset($_POST["clubFor" . $i]))
                {
                    $club_for[$i] = $_POST["clubFor" . $i];
                    $club_against[$i] = $_POST["clubAgainst" . $i];
                    $runs[$i] = $_POST["runs" . $i];
                    $wickets[$i] = $_POST["wickets" . $i];
                    $matchID[$i] = $_POST["matchID" . $i];
                    $eventID[$i] = $_POST["eventID" . $i];
                }
            }

            // Insert into the personal_best table
            if (!empty($club_for[0]))
            {
                $personalBestQuery = "INSERT INTO personal_best (playerID, club_name, club_against, runs, wickets, matchID, eventID)
                                  VALUES ('" . $pID['LastPlayerID'] . "', '$club_for[0]', '$club_against[0]', '$runs[0]', '$wickets[0]', '$matchID[0]', '$eventID[0]')";

                for ($i = 1; $i < 10; $i++)
                {
                    if (!empty($club_for[$i]))
                        $personalBestQuery .= ", ('" . $pID['LastPlayerID'] . "', '$club_for[$i]', '$club_against[$i]', '$runs[$i]', '$wickets[$i]', '$matchID[$i]', '$eventID[$i]')";
                    else
                        break;
                }

                commitTable($conn, $personalBestQuery);
            }


            // education Table -----------------------------------------------------------------------------------------
            $degree = array();
            $institution = array();
            $department = array();
            $result = array();
            $year = array();

            for ($i = 0; $i < 10; $i++)
            {
                if (isset($_POST["degree" . $i]))
                {
                    $degree[$i] = $_POST["degree" . $i];
                    $institution[$i] = $_POST["institute" . $i];
                    $department[$i] = $_POST["dept" . $i];
                    $result[$i] = $_POST["result" . $i];
                    $year[$i] = $_POST["year" . $i];
                }
            }

            if (!empty($degree[0]))
            {
                $educationQuery = "INSERT INTO education (playerID, degree, institution, department, result, year) 
                                VALUES ('" . $pID['LastPlayerID'] . "', '$degree[0]', '$institution[0]', '$department[0]', '$result[0]', '$year[0]')";


                for ($i = 1; $i < 10; $i++)
                {
                    if (!empty($degree[$i]))
                        $educationQuery .= ", ('" . $pID['LastPlayerID'] . "', '$degree[$i]', '$institution[$i]', '$department[$i]', '$result[$i]', '$year[$i]')";
                    else
                        break;
                }

                commitTable($conn, $educationQuery);
            }


            // membership Table ----------------------------------------------------------------------------------------
            $membership_id = array();
            $membership_name = array();
            $membership_type = array();
            $reg_date = array();
            $exp_date = array();

            for ($i = 0; $i < 10; $i++)
            {
                if (isset($_POST["memID" . $i]))
                {
                    $membership_id[$i] = $_POST["memID" . $i];
                    $membership_name[$i] = $_POST["memName" . $i];
                    $membership_type[$i] = $_POST["memType" . $i];
                    $reg_date[$i] = $_POST["regDate" . $i];
                    $exp_date[$i] = $_POST["expDate" . $i];
                }
            }

            if (!empty($membership_id[0]))
            {
                $memberQuery = "INSERT INTO membership_details (playerID, membershipID, Membership_name, membership_type, Regi_date, exp_date)
                                VALUES ('" . $pID['LastPlayerID'] . "', '$membership_id[0]', '$membership_name[0]', '$membership_type[0]', '$reg_date[0]', '$exp_date[0]')";


                for ($i = 1; $i < 10; $i++)
                {
                    if (!empty($membership_id[$i]))
                        $memberQuery .= ", ('" . $pID['LastPlayerID'] . "', '$membership_id[$i]', '$membership_name[$i]', '$membership_type[$i]', '$reg_date[$i]', '$exp_date[$i]')";
                    else
                        break;
                }

                commitTable($conn, $memberQuery);
            }

            mysqli_close($conn);
        }
    }
?>

<html>

<link rel="stylesheet" type="text/css" href="CCBStyleSheet.css">

<script type="text/javascript">

    let historyRowCount = 2;

    function addHistory()
    {
        // A match cannot have more than 30 players
        if (historyRowCount <= 10)
        {
            let table = document.getElementById("historyTable");
            let row = table.insertRow(historyRowCount);
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
            let cell4 = row.insertCell(3);
            let cell5 = row.insertCell(4);
            let cell6 = row.insertCell(5);

            historyRowCount--;

            cell1.innerHTML = '<input type="text" name="clubPlayedFor' + historyRowCount + '" title="Club Name" required>';
            cell2.innerHTML = '<input type="text" name="transferredTo' + historyRowCount + '" title="From">';
            cell3.innerHTML = '<input type="text" name="transferredFrom' + historyRowCount + '" title="To">';
            cell4.innerHTML = '<input type="text" name="totalRuns' + historyRowCount + '" title="Total Runs">';
            cell5.innerHTML = '<input type="text" name="totalWickets' + historyRowCount + '" title="Total Wickets">';
            cell6.innerHTML = '<input type="radio" name="teamLeader' + historyRowCount + '" value="Y" title="Yes"> YES<br>' +
                                '<input type="radio" name="teamLeader' + historyRowCount + '" value="N" title="No" checked> NO';

            historyRowCount += 2;
        }
        else
            alert("Limit Reached");
    }

    let bestRowCount = 2;

    function addBest()
    {
        // A match cannot have more than 30 players
        if (bestRowCount <= 10)
        {
            let table = document.getElementById("performanceTable");
            let row = table.insertRow(bestRowCount);
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
            let cell4 = row.insertCell(3);
            let cell5 = row.insertCell(4);
            let cell6 = row.insertCell(5);

            bestRowCount--;

            cell1.innerHTML = '<input type="text" name="clubFor' + bestRowCount + '" title="Club Name" required>';
            cell2.innerHTML = '<input type="text" name="clubAgainst' + bestRowCount + '" title="Opponent club name">';
            cell3.innerHTML = '<input type="text" name="runs' + bestRowCount + '" title="Event Id">';
            cell4.innerHTML = '<input type="text" name="wickets' + bestRowCount + '" title="Match Id">';
            cell5.innerHTML = '<input type="text" name="matchID' + bestRowCount + '" title="Runs">';
            cell6.innerHTML = '<input type="text" name="eventID' + bestRowCount + '" title="Wickets">';

            bestRowCount += 2;
        }
        else
            alert("Limit Reached");
    }

    let eduRowCount = 2;

    function addEdu()
    {
        // A match cannot have more than 30 players
        if (eduRowCount <= 10)
        {
            let table = document.getElementById("EducationTable");
            let row = table.insertRow(eduRowCount);
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
            let cell4 = row.insertCell(3);
            let cell5 = row.insertCell(4);

            eduRowCount--;

            cell1.innerHTML = '<input type="text" name="degree' + eduRowCount + '" title="Name of degree" required>';
            cell2.innerHTML = '<input type="text" name="dept' + eduRowCount + '" title="Institute/Department">';
            cell3.innerHTML = '<input type="text" name="institute' + eduRowCount + '" title="Board/University">';
            cell4.innerHTML = '<input type="text" name="year' + eduRowCount + '" title="Year">';
            cell5.innerHTML = '<input type="text" name="result' + eduRowCount + '" title="Result">';

            eduRowCount += 2;
        }
        else
            alert("Limit Reached");
    }

    let memberRowCount = 2;

    function addMembership()
    {
        // A match cannot have more than 30 players
        if (memberRowCount <= 10)
        {
            let table = document.getElementById("MembershipTable");
            let row = table.insertRow(memberRowCount);
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
            let cell4 = row.insertCell(3);
            let cell5 = row.insertCell(4);

            memberRowCount--;

            cell1.innerHTML = '<input type="number" name="memID' + memberRowCount + '" title="Membership ID" required>';
            cell2.innerHTML = '<input type="text" name="memName' + memberRowCount + '" title="Membership Name">';
            cell3.innerHTML = '<input type="text" name="memType' + memberRowCount + '" title="Type">';
            cell4.innerHTML = '<input type="date" name="regDate' + memberRowCount + '" title="Registration Date">';
            cell5.innerHTML = '<input type="date" name="expDate' + memberRowCount + '" title="Expiry Date">';

            memberRowCount += 2;
        }
        else
            alert("Limit Reached");
    }

</script>

<head>
    <meta charset="UTF-8">
	<title>Player Registration Form</title>
</head>

<ul>
    <li><a href="home.php">Home</a></li>
    <li><a class="active" href="playerRegistration.php">Registration</a></li>
    <li><a href="playerProfile.php">Information</a></li>
</ul>

<div class="playerPage">
    <h1 class="pageName">Player Registration Form</h1>
</div>

<ul class="subMenu">
    <li><a class="active" href="playerRegistration.php">Player Registration</a></li>
    <li><a href="clubRegistration.php">Club Registration</a></li>
    <li><a href="contractForm.php">Contract Form</a></li>
    <li><a href="teamInfoForm.php">Team Information Form</a></li>
    <li><a href="matchInfoForm.php">Match Information Form</a></li>
</ul>

<body>

<div class="forms">
	<form class="forms" action="playerRegistration.php" method="post">

        <h4 class="headers">General Information </h4>

		First Name: <input type="text" name="firstName" title="First Name" placeholder="Your First Name" required><br><br>
		Middle Name: <input type="text" name="middleName" title="Middle Name" placeholder="Your Middle Name"><br><br>
		Last Name: <input type="text" name="lastName" title="Last Name" placeholder="Your Last Name" required><br><br>
		Father's Name: <input type="text" name="father" title="Father's Name" placeholder="Your Father's Name"><br><br>
		Mother's Name: <input type="text" name="mother" title="Mother's Name" placeholder="Your Mother's Name"><br><br>
        Date of Birth: <input type="date" name="dob" title="Date of Birth"><br><br>

        <h4 class="headers">Present Address </h4>

		Location ID: <input type="number" name="currentlID" title="Location ID" placeholder="Your Present Location ID" required><br><br>
		House: <input type="text" name="currentHouse" title="House" placeholder="Your Present House"><br><br>
		Street: <input type="text" name="currentStreet" title="Street" placeholder="Your Present Street"><br><br>
		Post Code: <input type="text" name="currentPost" title="Post Code" placeholder="Your Present Post Code"><br><br>
		Thana: <input type="text" name="currentThana" title="Thana" placeholder="Your Present Thana" required><br><br>
		District: <input type="text" name="currentDistrict" title="District" placeholder="Your Present District" required><br><br>

        <h4 class="headers">Permanent Address </h4>

        Same as present address: <input type="checkbox" name="sameAdrs" value="1" title=""><br><br>

        Location ID: <input type="number" name="permanentlID" title="Location ID" placeholder="Your Permanent Location ID"><br><br>
        House: <input type="text" name="permanentHouse" title="House" placeholder="Your Permanent House"><br><br>
        Street: <input type="text" name="permanentStreet" title="Street" placeholder="Your Permanent Street"><br><br>
        Post Code: <input type="text" name="permanentPost" title="Post Code" placeholder="Your Permanent Post Code"><br><br>
        Thana: <input type="text" name="permanentThana" title=" Thana" placeholder="Your Permanent Thana"><br><br>
        District: <input type="text" name="permanentDistrict" title="District" placeholder="Your Permanent District"><br><br>


        <table id="historyTable" border = "1" cellspacing="0" cellpadding="1">
            <caption><h4 class="headers">Previous History</h4></caption>

            <tr>
                <th>Club Name</th>
                <th>From</th>
                <th>To</th>
                <th>Total Runs</th>
                <th>Total Wickets</th>
                <th>Team leader (Y/N)</th>
            </tr>

            <tr>
                <td><input type="text" name="clubPlayedFor0" title="Club Name"></td>
                <td><input type="text" name="transferredTo0" title="From"></td>
                <td><input type="text" name="transferredFrom0" title="To"></td>
                <td><input type="text" name="totalRuns0" title="Total Runs"></td>
                <td><input type="text" name="totalWickets0" title="Total Wickets"></td>
                <td><input type="radio" name="teamLeader0" value="Y" title="Yes"> YES<br>
                    <input type="radio" name="teamLeader0" value="N" title="No" checked> NO</td>
            </tr>

        </table>


        <table id="performanceTable" border = "1" cellspacing="0" cellpadding="1">
            <caption><h4 class="headers">Best Performance</h4></caption>
            <tr>
                <th>Club Name</th>
                <th>Opponent club name</th>
                <th>Event Id</th>
                <th>Match Id</th>
                <th>Runs</th>
                <th>Wickets</th>
            </tr>

            <tr>
                <td><input type="text" name="clubFor0" title="Club Name"></td>
                <td><input type="text" name="clubAgainst0" title="Opponent club name"></td>
                <td><input type="text" name="runs0" title="Event Id"></td>
                <td><input type="text" name="wickets0" title="Match Id"></td>
                <td><input type="text" name="matchID0" title="Runs"></td>
                <td><input type="text" name="eventID0" title="Wickets"></td>
            </tr>

        </table>


        <table id="EducationTable" border = "1" cellspacing="0" cellpadding="1">
            <caption><h4 class="headers">Educational Qualifications</h4></caption>
            <tr>
                <th>Name of degree</th>
                <th>Institute/Department</th>
                <th>Board/University</th>
                <th>Year</th>
                <th>Result</th>
            </tr>

            <tr>
                <td><input type="text" name="degree0" title="Name of degree"></td>
                <td><input type="text" name="dept0" title="Institute/Department"></td>
                <td><input type="text" name="institute0" title="Board/University"></td>
                <td><input type="text" name="year0" title="Year"></td>
                <td><input type="text" name="result0" title="Result"></td>
            </tr>

        </table>

        <table id="MembershipTable" border = "1" cellspacing="0" cellpadding="1">
            <caption><h4 class="headers">Membership Details</h4></caption>
            <tr>
                <th>Membership ID</th>
                <th>Membership Name</th>
                <th>Type</th>
                <th>Registration Date</th>
                <th>Expiry Date</th>
            </tr>

            <tr>
                <td><input type="number" name="memID0" title="Membership ID"></td>
                <td><input type="text" name="memName0" title="Membership Name"></td>
                <td><input type="text" name="memType0" title="Type"></td>
                <td><input type="date" name="regDate0" title="Registration Date"></td>
                <td><input type="date" name="expDate0" title="Expiry Date"></td>
            </tr>

        </table>

        Signature of the Player:  ...................<br><br>
        Player Registration Date: <input type="date" name="dor" title="Player Registration Date"><br><br><br>

		<input type="submit" name="submit">

	</form>
</div>

<button class="button4" onclick="addMembership()">Add Member</button><br><br><br>
<button class="button3" onclick="addHistory()">Add History</button><br><br><br>
<button class="button2" onclick="addBest()">Add Best</button><br><br><br>
<button class="button" onclick="addEdu()">Add Edu</button><br><br><br>

</body>

</html>
