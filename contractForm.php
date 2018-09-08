<?php

    if (isset($_POST['submit']))
    {
        require "connection.php";
        require "manualCommit.php";


        // contracts Table ---------------------------------------------------------------------------------------------
        $club_ID = $_POST['clubID'];
        $player_ID = $_POST['playerID'];
        $authorized_person = $_POST['officerFirstName'] . " " . $_POST['officerMiddleName'] . " " . $_POST['officerLastName'];
        $designation = $_POST['designation'];
        $start_date = $_POST['startDate'];
        $end_date = $_POST['endDate'];
        $contract_amount = $_POST['contractAmount'];
        $witness = array($_POST['witness1'], $_POST['witness2']);

        // Check if the player has a running contract with another club
        $contractExists = false;

        $getEndDate = "SELECT contract_end_date FROM contracts WHERE playerID = '$player_ID'";

        if ($result = mysqli_query($conn, $getEndDate))
        {
            if (mysqli_num_rows($result) > 0)
            {
                while ($endDt = mysqli_fetch_assoc($result))
                {
                    $date1 = new DateTime($endDt['contract_end_date']);
                    $date2 = new DateTime($start_date);

                    if($date1 > $date2)
                        $contractExists = true;
                }
            }
        }

        // payment_schedule Table --------------------------------------------------------------------------------------
        $payment_serial = array();
        $due_date = array();
        $payment_date = array();
        $amount_paid = array();

        for ($i=0; $i<20; $i++)
        {
            if (isset($_POST["paymentDate" . $i]))
            {
                $payment_serial[$i] = $_POST["contractSerial" . $i];
                $due_date[$i] = $_POST["dueDate" . $i];
                $payment_date[$i] = $_POST["paymentDate" . $i];
                $amount_paid[$i] = $_POST["paidAmount" . $i];
            }
        }

        // The sum of the total payment of the schedule cannot be more than the fee mentioned in the contract
        $sum = 0;

        foreach ($amount_paid as $value)
        {
            $sum += $value;
        }


        if ($contractExists)
        {
            echo "<script> alert('A player cannot enroll into two clubs simultaneously'); </script>";
            mysqli_close($conn);
        }

         else if ($sum > $contract_amount)
        {
            echo "<script> alert('The sum of the total payment of the schedule cannot be more than the fee mentioned in the contract'); </script>";
            mysqli_close($conn);
        }

        else
        {
            // Insert into the contracts table
            $contractQuery = "INSERT INTO contracts (clubID, playerID, authorized_person, designation, contract_start_date, contract_end_date, contract_amount, witness1, witness2) 
                          VALUES ('$club_ID', '$player_ID', '$authorized_person', '$designation', '$start_date', '$end_date', '$contract_amount', '$witness[0]', '$witness[1]')";

            commitTable($conn, $contractQuery);

            // Get payment ID from contracts table
            $getPaymentID = "SELECT MAX(paymentID) AS LastPaymentID FROM contracts";

            if ($result = mysqli_query($conn, $getPaymentID))
                if (mysqli_num_rows($result) > 0)
                    $pID = mysqli_fetch_assoc($result);

            // Insert into the payment_schedule table
            if (!empty($payment_date[0]))
            {
                $paymentQuery = "INSERT INTO payment_schedule(paymentID, due_date, actual_payment_date, amount_paid, payment_serial)
                              VALUES ('" . $pID['LastPaymentID'] . "', '$due_date[0]', '$payment_date[0]', '$amount_paid[0]', '$payment_serial[0]')";

                for ($i = 1; $i < 15; $i++)
                {
                    if (!empty($payment_date[$i]))
                        $paymentQuery .= ", ('" . $pID['LastPaymentID'] . "', '$due_date[$i]', '$payment_date[$i]', '$amount_paid[$i]', '$payment_serial[$i]')";
                    else
                        break;
                }

                commitTable($conn, $paymentQuery);
            }

            mysqli_close($conn);
        }
    }
?>

<html>

<link rel="stylesheet" type="text/css" href="CCBStyleSheet.css">

<script type="text/javascript">

    let rowCount = 2;

    function addSchedule()
    {
        let table = document.getElementById("ScheduleTable");
        let row = table.insertRow(rowCount);
        let cell1 = row.insertCell(0);
        let cell2 = row.insertCell(1);
        let cell3 = row.insertCell(2);
        let cell4 = row.insertCell(3);

        rowCount--;

        cell1.innerHTML = '<input type="number" name="contractSerial' + rowCount + '" value="' + rowCount + '" title="Serial Number" required>';
        cell2.innerHTML = '<input type="date" name="dueDate' + rowCount + '" title="Due date" required>';
        cell3.innerHTML = '<input type="date" name="paymentDate' + rowCount + '" title="Payment date" required>';
        cell4.innerHTML = '<input type="text" name="paidAmount' + rowCount + '" title="Amount" required>';

        rowCount += 2;
    }

</script>

<head>
    <meta charset="UTF-8">
	<title>Contract Form</title>
</head>

<ul>
    <li><a href="home.php">Home</a></li>
    <li><a class="active" href="playerRegistration.php">Registration</a></li>
    <li><a href="playerSearch.php">Information</a></li>
</ul>

<div class="contractPage">
    <h1 class="pageName">Contract Form</h1>
</div>

<ul class="subMenu">
    <li><a href="playerRegistration.php">Player Registration</a></li>
    <li><a href="clubRegistration.php">Club Registration</a></li>
    <li><a class="active" href="contractForm.php">Contract Form</a></li>
    <li><a href="teamInfoForm.php">Team Information Form</a></li>
    <li><a href="matchInfoForm.php">Match Information Form</a></li>
</ul>

<body>

<div class="forms">
	<form class="forms" action="contractForm.php" method="post">

        <h4 class="headers">Club Information </h4>
        Club ID: <input type="number" name="clubID" title="Club ID" placeholder="Club ID"><br><br>
        Name of the club: <input type="text" name="clubName" title="Club Name" placeholder="Club Name"><br><br>

        <h4 class="headers">First Party (Player) </h4>
        First Name: <input type="text" name="playerFirstName" title="First Name" placeholder="Player's First Name"><br><br>
        Middle Name: <input type="text" name="playerMiddleName" title="Middle Name" placeholder="Player's Middle Name"><br><br>
        Last Name: <input type="text" name="playerLastName" title="Last Name" placeholder="Player's Last Name"><br><br>
        Player ID: <input type="number" name="playerID" title="PLayer ID" placeholder="Player ID"><br><br>

        <h4 class="headers">Second Party (Authorized Person) </h4>
        First Name: <input type="text" name="officerFirstName" title="First Name" placeholder="Officer's First Name"><br><br>
        Middle Name: <input type="text" name="officerMiddleName" title="Middle Name" placeholder="Officer's Middle Name"><br><br>
        Last Name: <input type="text" name="officerLastName" title="Last Name" placeholder="Officer's Last Name"><br><br>
        Designation: <input type="text" name="designation" title="Designation" placeholder="Officer's Designation"><br><br>

        <h4 class="headers">Contract Period </h4>
        Start Date : <input type="date" name="startDate" title="Start Date"><br><br>
        End Date : <input type="date" name="endDate" title="End Date"><br><br>
        Contract Amount : <input type="text" name="contractAmount" title="Contract Amount" placeholder="Contract Amount"><br><br>


        <table id="ScheduleTable" border = "1" cellspacing="0" cellpadding="1">
            <caption><h4 class="headers">Payment Schedule</h4></caption>
            <tr>
                <th>Serial Number</th>
                <th>Due date</th>
                <th>Payment date</th>
                <th>Amount</th>
            </tr>

            <tr>
                <td><input type="number" name="contractSerial0" value="0" title="Serial Number" required></td>
                <td><input type="date" name="dueDate0" title="Due date" required></td>
                <td><input type="date" name="paymentDate0" title="Payment date" required></td>
                <td><input type="text" name="paidAmount0" title="Amount" required></td>
            </tr>

        </table>
        <br><br>

        Contract Witness 1: <input type="text" name="witness1" title="Contract Witness 1" placeholder="First Witness Name"><br><br>
        Contract Witness 2: <input type="text" name="witness2" title="Contract Witness 2" placeholder="Second Witness Name"><br><br>

        <input type="submit" name="submit">

    </form>
</div>

<button class="button" onclick="addSchedule()">Add Schedule</button><br><br><br>

</body>

</html>
