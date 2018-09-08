<?php

if (isset($_POST['submit']))
{
    require "connection.php";
    require "manualCommit.php";


    // events_organised Table ------------------------------------------------------------------------------------------
    $event_ID = $_POST['eventID'];
    $event_name = $_POST['eventName'];
    $event_start_date = $_POST['eventStart'];
    $event_end_date = $_POST['eventEnd'];

    // Insert into the events_organised table
    $eventQuery = "INSERT INTO events_organised (eventID, eventName, start_date, end_date) 
                    VALUES ('$event_ID', '$event_name', '$event_start_date', '$event_end_date')";

    commitTable($conn, $eventQuery);


    // matches Table ---------------------------------------------------------------------------------------------------
    $venue_ID = $_POST['venueID'];
    $match_date = $_POST['matchDate'];
    $match_ID = array();
    $mvp = array();
    $umpire = array();
    $batting_first = array();
    $bowling_first = array();

    for ($i=0; $i<50; $i++)
    {
        if (isset($_POST["matchID" . $i]))
        {
            $match_ID[$i] = $_POST["matchID" . $i];
            $mvp[$i] = $_POST["MVP" . $i];
            $umpire[$i] = $_POST["umpire" . $i];
            $batting_first[$i] = $_POST["battingFirst" . $i];
            $bowling_first[$i] = $_POST["bowlingFirst" . $i];
        }
    }

    // Insert into the matches table
    if (!empty($match_ID[0]))
    {
        $matchQuery = "INSERT INTO matches (matchID, venueID, date_of_match, man_of_the_match, umpire, team_batting_first, team_bowling_first)
                        VALUES ('$match_ID[0]', '$venue_ID', '$match_date', '$mvp[0]', '$umpire[0]', '$batting_first[0]', '$bowling_first[0]')";

        for ($i=1; $i<10; $i++)
        {
            if (!empty($match_ID[$i]))
                $matchQuery .= ", ('$match_ID[$i]', '$venue_ID', '$match_date', '$mvp[$i]', '$umpire[$i]', '$batting_first[$i]', '$bowling_first[$i]')";
            else
                break;
        }

        commitTable($conn, $matchQuery);
    }


    // match_performance Table -----------------------------------------------------------------------------------------
    $match_IDP = $_POST['matchIDPerformance'];
    $player_ID = array();
    $total_wickets = array();
    $total_runs = array();
    $outstanding_performance = array();

    for ($i=0; $i<30; $i++)
    {
        if (isset($_POST["playerID" . $i]))
        {
            $player_ID[$i] = $_POST["playerID" . $i];
            $total_wickets[$i] = $_POST["wickets" . $i];
            $total_runs[$i] = $_POST["runs" . $i];
            $outstanding_performance[$i] = $_POST["outStand" . $i];
        }
    }

    // Insert into the match_performance table
    if (!empty($player_ID[0]))
    {
        $matchPerformanceQuery = "INSERT INTO match_performance (matchID, playerID, total_wickets, total_runs, outstanding_performance)
                       VALUES ('$match_IDP', '$player_ID[0]', '$total_wickets[0]', '$total_runs[0]', '$outstanding_performance[0]')";

        for ($i=1; $i<30; $i++)
        {
            if (!empty($player_ID[$i]))
                $matchPerformanceQuery .= ", ('$match_IDP', '$player_ID[$i]', '$total_wickets[$i]', '$total_runs[$i]', '$outstanding_performance[$i]')";
            else
                break;
        }

        commitTable($conn, $matchPerformanceQuery);
    }

    mysqli_close($conn);
}
?>

<html>

<link rel="stylesheet" type="text/css" href="CCBStyleSheet.css">

<script type="text/javascript">

    let performanceRowCount = 2;

    function addMatchPerformance()
    {
        // A match cannot have more than 30 players
        if (performanceRowCount <= 30)
        {
            let table = document.getElementById("performanceTable");
            let row = table.insertRow(performanceRowCount);
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
            let cell4 = row.insertCell(3);

            performanceRowCount--;

            cell1.innerHTML = '<input type="number" name="playerID' + performanceRowCount + '" title="playerID" required>';
            cell2.innerHTML = '<input type="number" name="wickets' + performanceRowCount + '" title="Total wickets">';
            cell3.innerHTML = '<input type="number" name="runs' + performanceRowCount + '" title="Total runs">';
            cell4.innerHTML = '<input type="text" name="outStand' + performanceRowCount + '" title="Outstanding performance">';

            performanceRowCount += 2;
        }
        else
            alert("A match cannot have more than 30 players");
    }

    let matchRowCount = 2;

    function addMatchInfo()
    {
        // A match cannot have more than 30 players
        if (matchRowCount <= 50)
        {
            let table = document.getElementById("playerTable");
            let row = table.insertRow(matchRowCount);
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
            let cell4 = row.insertCell(3);
            let cell5 = row.insertCell(4);

            matchRowCount--;

            cell1.innerHTML = '<input type="number" name="matchID' + matchRowCount + '" title="Match ID" required>';
            cell2.innerHTML = '<input type="text" name="MVP' + matchRowCount + '" title="Man-of-the match">';
            cell3.innerHTML = '<input type="text" name="umpire' + matchRowCount + '" title="Umpires">';
            cell4.innerHTML = '<input type="text" name="battingFirst' + matchRowCount + '" title="Team Batting First">';
            cell5.innerHTML = '<input type="text" name="bowlingFirst' + matchRowCount + '" title="Team Bowling First">';

            matchRowCount += 2;
        }
        else
            alert("Limit Reached");
    }

</script>

<head>
    <meta charset="UTF-8">
    <title>Match Information</title>
</head>

<ul>
    <li><a href="home.php">Home</a></li>
    <li><a class="active" href="playerRegistration.php">Registration</a></li>
    <li><a href="playerSearch.php">Information</a></li>
</ul>

<div class="matchPage">
    <h1 class="pageName">Match Information Form</h1>
</div>

<ul class="subMenu">
    <li><a href="playerRegistration.php">Player Registration</a></li>
    <li><a href="clubRegistration.php">Club Registration</a></li>
    <li><a href="contractForm.php">Contract Form</a></li>
    <li><a href="teamInfoForm.php">Team Information Form</a></li>
    <li><a class="active" href="matchInfoForm.php">Match Information Form</a></li>
</ul>

<body>

<div class="forms">
    <form class="forms" action="matchInfoForm.php" method="post">

        <h4 class="headers">Match Information </h4>
        Match ID: <input type="number" name="matchIDPerformance" title="Match ID" placeholder="Match ID"><br><br>
        Venue ID: <input type="number" name="venueID" title="Venue ID" placeholder="Venue ID"><br><br>
        Date of the match: <input type="date" name="matchDate" title="Date of the match"><br><br>

        <table  id="performanceTable" border = "1" cellspacing="0" cellpadding="1">
            <caption><h4 class="headers">Players Performance in Match Form</h4></caption>
            <tr>
                <th>Player ID</th>
                <th>Total wickets</th>
                <th>Total runs</th>
                <th>Outstanding performance</th>
            </tr>

            <tr>
                <td><input type="number" name="playerID0" title="Player ID" required></td>
                <td><input type="number" name="wickets0" title="Total wickets"></td>
                <td><input type="number" name="runs0" title="Total runs"></td>
                <td><input type="text" name="outStand0" title="Outstanding performance"></td>
            </tr>

        </table>
        <br><br>

        <h4 class="headers">Event Information </h4>
        Event ID: <input type="number" name="eventID" title="Event ID" placeholder="Event ID"><br><br>
        Event Name: <input type="text" name="eventName" title="Event Name" placeholder="Name of the Event"><br><br>
        Event Start Date: <input type="date" name="eventStart" title="Event Start Date"><br><br>
        Event End Date: <input type="date" name="eventEnd" title="Event End Date"><br><br>

        <table  id="matchTable" border = "1" cellspacing="0" cellpadding="1">
            <caption><h4 class="headers">Match Information Form</h4></caption>
            <tr>
                <th>Match ID</th>
                <th>Man-of-the match</th>
                <th>Umpires</th>
                <th>Team Batting First</th>
                <th>Team Bowling First</th>
            </tr>

            <tr>
                <td><input type="number" name="matchID0" title="Match ID" required></td>
                <td><input type="text" name="MVP0" title="Man-of-the match"></td>
                <td><input type="text" name="umpire0" title="Umpires"></td>
                <td><input type="text" name="battingFirst0" title="Team Batting First"></td>
                <td><input type="text" name="bowlingFirst0" title="Team Bowling First"></td>
            </tr>

        </table>
        <br><br>

        <input type="submit" name="submit">

    </form>
</div>

<button class="button2" onclick="addMatchPerformance()">Add Player</button><br><br><br>
<button class="button" onclick="addMatchInfo()">Add Match</button><br><br><br>

</body>

</html>
