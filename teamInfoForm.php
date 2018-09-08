<?php

    if (isset($_POST['submit']))
    {
        require "connection.php";
        require "manualCommit.php";


        // teams Table -------------------------------------------------------------------------------------------------
        $club_ID = $_POST['clubID'];
        $team_formation_date = $_POST['formationDate'];
        $event_ID = $_POST['eventID'];
        $leader_ID = $_POST['leaderID'];
        $coach_ID = $_POST['coachID'];
        $coach_name = $_POST['coachName'];

        // Insert into the teams table
        $teamQuery = "INSERT INTO teams (clubID, formation_date, eventID, team_leaderID, coachID, coach_name) 
                      VALUES ('$club_ID', '$team_formation_date', '$event_ID', '$leader_ID', '$coach_ID', '$coach_name')";

        commitTable($conn, $teamQuery);


        // Get teamID from teams table
        $getTeamID = "SELECT MAX(teamID) AS LastTeamID FROM teams";

        if ($result = mysqli_query($conn, $getTeamID))
            if (mysqli_num_rows($result) > 0)
                $tID = mysqli_fetch_assoc($result);


        // team_playerList Table ---------------------------------------------------------------------------------------
        $player_ID = array();
        $player_name = array();

        for ($i=0; $i<15; $i++)
        {
            if (isset($_POST["playerID" . $i]))
            {
                $player_ID[$i] = $_POST["playerID" . $i];
                $player_name[$i] = $_POST["playerName" . $i];
            }
        }

        // Insert into the team_playerList table
        if (!empty($player_ID[0]))
        {
            $playerListQuery = "INSERT INTO team_playerlist (teamID, playerID, player_name) 
                                VALUES ('" . $tID['LastTeamID'] . "', '$player_ID[0]', '$player_name[0]')";

            for ($i=1; $i<15; $i++)
            {
                if (!empty($player_ID[$i]))
                    $playerListQuery .= ", ('" . $tID['LastTeamID'] . "', '$player_ID[$i]', '$player_name[$i]')";
                else
                    break;
            }

            commitTable($conn, $playerListQuery);
        }

        mysqli_close($conn);
    }
?>

<html>

<link rel="stylesheet" type="text/css" href="CCBStyleSheet.css">

<script type="text/javascript">

    let rowCount = 2;

    function addPlayer()
    {
        // A team cannot be formed with more than 15 players
        if (rowCount <= 15)
        {
            let table = document.getElementById("playerTable");
            let row = table.insertRow(rowCount);
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);

            rowCount--;

            cell1.innerHTML = '<input type="number" name="playerID' + rowCount + '" title="playerID" required>';
            cell2.innerHTML = '<input type="text" name="playerName' + rowCount + '" title="playerName" required>';

            rowCount += 2;
        }
        else
            alert("A team cannot be formed with more than 15 players");
    }

</script>

<head>
    <meta charset="UTF-8">
    <title>Team Information Form</title>
</head>

<ul>
    <li><a href="home.php">Home</a></li>
    <li><a class="active" href="playerRegistration.php">Registration</a></li>
    <li><a href="playerSearch.php">Information</a></li>
</ul>

<div class="teamPage">
    <h1 class="pageName">Team Information Form</h1>
</div>

<ul class="subMenu">
    <li><a href="playerRegistration.php">Player Registration</a></li>
    <li><a href="clubRegistration.php">Club Registration</a></li>
    <li><a href="contractForm.php">Contract Form</a></li>
    <li><a class="active" href="teamInfoForm.php">Team Information Form</a></li>
    <li><a href="matchInfoForm.php">Match Information Form</a></li>
</ul>

<body>

<div class="forms">
    <form class="forms" action="teamInfoForm.php" method="post">

        <h4 class="headers">Club Information</h4>
        Club ID: <input type="number" name="clubID" title="Club ID" placeholder="Club ID"><br><br>
        Club Name: <input type="text" name="clubName" title="Club Name" placeholder="Name of the Club"><br><br>
        Team Formation Date: <input type="date" name="formationDate" title="Team Formation Date"><br><br>

        <h4 class="headers">Event Information</h4>
        Event ID: <input type="number" name="eventID" title="Event ID" placeholder="Event ID"><br><br>
        Event Name: <input type="text" name="eventName" title="Event Name" placeholder="Name of the Event"><br><br>

        <h4 class="headers">Team Leader's Information</h4>
        Team Leader's Player ID: <input type="number" name="leaderID" title="Team Leader's Player ID" placeholder="Team Leader's Player ID"><br><br>
        Team Leader Name: <input type="text" name="leaderName" title="Team Leader Name" placeholder="Team Leader's Name"><br><br>

        <h4 class="headers">Coach Information</h4>
        Coach ID: <input type="number" name="coachID" title="Coach ID" placeholder="Coach ID"><br><br>
        Coach Name: <input type="text" name="coachName" title="Coach Name" placeholder="Coach Name"><br><br>

        <table id="playerTable" border = "1" cellspacing="0" cellpadding="1">
            <caption><h4 class="headers">Player List</h4></caption>

            <tr>
                <th>Player ID</th>
                <th>Player Name</th>
            </tr>

            <tr>
                <td><input type="number" name="playerID0" title="Club Name" required></td>
                <td><input type="text" name="playerName0" title="Opponent club name" required></td>
            </tr>

        </table>

        <input type="submit" name="submit">

    </form>
</div>

<button class="button" onclick="addPlayer()">Add Player</button><br><br><br>

</body>

</html>
