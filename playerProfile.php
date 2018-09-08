<?php
    include_once "connection.php";
?>


<html>

<link rel="stylesheet" type="text/css" href="CCBStyleSheet.css">

<head>
    <meta charset="UTF-8">
    <title>Player Search</title>
</head>

<ul>
    <li><a href="home.php">Home</a></li>
    <li><a  href="playerRegistration.php">Registration</a></li>
    <li><a class="active" href="playerSearch.php">Information</a></li>
</ul>

<div class="playerProfile">
    <h1 class="pageName">Player Profile</h1>
</div>

<ul class="subMenu">
    <li><a class="active" href="playerSearch.php">Player Profile</a></li>
    <li><a href="clubProfile.php">Club Profile</a></li>
</ul>

<body>

<div class="forms">

    <?php
    // Players Table -----------------------------------------------------------------------------------------------

    $query = $_POST['playerSearch'];

    $query = htmlspecialchars($query);

    $playerInfo = "SELECT first_name AS firstName, middle_name AS middleName, last_name AS lastName, father_name AS father, mother_name AS mother, date_of_birth AS dob
                    FROM players WHERE playerID = '$query'";

    $history = "SELECT transferred_to AS transferredTo, transferred_from as transferredFrom, total_runs AS runs, total_wickets AS wickets, club_name AS club, team_leader AS leader
                FROM player_history WHERE playerID = '$query'";


    $result1 = mysqli_query($conn, $playerInfo);

    $info1 = mysqli_fetch_assoc($result1);

    $result2 = mysqli_query($conn, $history);

    $info2 = mysqli_fetch_assoc($result2);

    if (!empty($info1['firstName']))
    {
        $name = $info1['firstName'];
        $name .= " " . $info1['middleName'];
        $name .= " " . $info1['lastName'];
        $father = $info1['father'];
        $mother = $info1['mother'];
        $dob = $info1['dob'];


        echo "Player Name: $name <br><br>";
        echo "Father's Name: $father <br><br>";
        echo "Mother's Name: $mother <br><br>";
        echo "Date of Birth: $dob <br><br>";

        $club = $info2['club'];
        $transferred_to = $info2['transferredTo'];
        $transferred_from = $info2['transferredTo'];
        $total_runs = $info2['runs'];
        $total_wickets = $info2['wickets'];
        $team_leader = $info2['leader'];


        echo "<table id=\"historyTable\" border = \"1\" cellspacing=\"0\" cellpadding=\"1\">
            <caption><h4 class=\"headers\">Previous History</h4></caption>

            <tr>
                <th>Club Name</th>
                <th>From</th>
                <th>To</th>
                <th>Total Runs</th>
                <th>Total Wickets</th>
                <th>Team leader (Y/N)</th>
            </tr>

            <tr>
                <td>$club</td>
                <td>$transferred_to</td>
                <td>$transferred_from</td>
                <td> $total_runs</td>
                <td>$total_wickets</td>
                <td>$team_leader</td>
            </tr>

        </table>";
    }

    else
        echo "Player Not Found";
    ?>

</div>

</body>

</html>