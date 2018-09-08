<?php

    if (isset($_POST['submit']))
    {
        require "connection.php";
        require "manualCommit.php";


        // Clubs Table -------------------------------------------------------------------------------------------------
        $club_name = $_POST['clubName'];
        $date_established = $_POST['dateEstablished'];
        $president_name = $_POST['president'];
        $club_locationID = $_POST['clublID'];

        // Insert into the players table
        $playerQuery = "INSERT INTO clubs (club_name, president, date_established, club_locationID) 
		VALUES ('$club_name', '$president_name', '$date_established', '$club_locationID')";

        commitTable($conn, $playerQuery);


        // Locations Table ---------------------------------------------------------------------------------------------
        $house = $_POST['clubHouse'];
        $street = $_POST['clubStreet'];
        $postCode = $_POST['clubPost'];
        $thana = $_POST['clubThana'];
        $district = $_POST['clubDistrict'];

        // Check for duplicate LocationID
        $clubLocationExists = false;

        $getLocationID = "SELECT locationID FROM locations";

        if ($result = mysqli_query($conn, $getLocationID))
        {
            if (mysqli_num_rows($result) > 0)
            {
                while ($lID = mysqli_fetch_assoc($result))
                {
                    if ($lID['locationID'] == $club_locationID)
                        $clubLocationExists = true;
                }
            }
        }

        // Insert into the locations table
        if (!$clubLocationExists)
        {
            $locationQuery = "INSERT INTO locations (locationID, house, street, postCode, thana, district) 
		    VALUES ('$club_locationID', '$house', '$street', '$postCode', '$thana', '$district')";

            commitTable($conn, $locationQuery);
        }

        mysqli_close($conn);
    }
?>

<html>

<link rel="stylesheet" type="text/css" href="CCBStyleSheet.css">

<head>
    <meta charset="UTF-8">
	<title>Club Registration Form</title>
</head>

<ul>
    <li><a href="home.php">Home</a></li>
    <li><a class="active" href="playerRegistration.php">Registration</a></li>
    <li><a href="playerSearch.php">Information</a></li>
</ul>

<div class="clubPage">
    <h1 class="pageName">Club Registration Form</h1>
</div>

<ul class="subMenu">
    <li><a href="playerRegistration.php">Player Registration</a></li>
    <li><a class="active" href="clubRegistration.php">Club Registration</a></li>
    <li><a href="contractForm.php">Contract Form</a></li>
    <li><a href="teamInfoForm.php">Team Information Form</a></li>
    <li><a href="matchInfoForm.php">Match Information Form</a></li>
</ul>

<body>

<div class="forms">
	<form class="forms" action="clubRegistration.php" method="post">

        <h4 class="headers">General Information </h4>
        Name of the club: <input type="text" name="clubName" title="Club Name" placeholder="Club Name"><br><br>
        Date of Establishment: <input type="date" name="dateEstablished" title="Date Established"><br><br>
        Name of the President: <input type="text" name="president" title="President's Name" placeholder="President's Name"><br><br>

        <h4 class="headers">CLub Address </h4>
		Location ID: <input type="number" name="clublID" title="Location ID" placeholder="Club's Location ID"><br><br>
		House: <input type="text" name="clubHouse" title="House" placeholder="Club Building"><br><br>
		Street: <input type="text" name="clubStreet" title="Street" placeholder="Club Street"><br><br>
		Post Code: <input type="text" name="clubPost" title="Post Code" placeholder="Club's Post Code"><br><br>
		Thana: <input type="text" name="clubThana" title="Thana" placeholder="Club Thana"><br><br>
		District: <input type="text" name="clubDistrict" title="District" placeholder="Club District"><br><br><br>

        <input type="submit" name="submit">

    </form>
</div>

</body>

</html>