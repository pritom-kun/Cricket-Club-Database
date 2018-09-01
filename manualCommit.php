<?php
mysqli_autocommit($conn, false);

function commitTable ($database, $query)
{
    $flag = true;

    $setTable = mysqli_query($database, $query);

    if (!$setTable)
    {
        $flag = false;
        echo "Error details: " . mysqli_error($database) . ". ";
    }

    if ($flag)
    {
        mysqli_commit($database);
        //echo "All queries were executed successfully";
    }
    else
    {
        mysqli_rollback($database);
        //echo "All queries were rolled back";
    }
}
