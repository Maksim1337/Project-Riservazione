<?php
include 'dbConnection.php';
$adminAccess = 0; // everyone is treated as normal user, unless he's granted admin access
$CheckingString = "SELECT userNumber, userEmail FROM $UserTable WHERE userEmail='$userEmail' AND userPasswordSalt='".substr(md5($userPw), 0, 20)."'";
$CheckingQuery = mysqli_query($DBConnect, $CheckingString) ;
if(!$CheckingQuery)
{
    echo "<p>There was an error when checking if the password matched. " .mysqli_error($DBConnect) ;
}
else
{
    if (mysqli_num_rows($CheckingQuery) == 0)
    {
        echo "<span style=color:red;>The e-mail address/password " . " combination entered is not valid. </span>\n";
    } 
    else
    {
        $Row = mysqli_fetch_assoc($CheckingQuery);
        $_SESSION['userNumber'] = $Row['userNumber'];
        $_SESSION['userEmail'] = $Row['userEmail'];
    }
    /* Check if the user that just logged in, has an admin email. If true give that user permissions. */
    $adminCheckString = "SELECT userNumber
                        FROM user
                        WHERE userNumber = 
                        (SELECT userNumber
                        FROM user
                        WHERE userEmail = 'admin@stenden.com')"; // change the user email or add a new one with OR userEmail = for adding new admins
    $adminCheckQuery = mysqli_query($DBConnect, $adminCheckString);
    $adminRow = mysqli_fetch_assoc($adminCheckQuery);
    $adminRowToInt = implode($adminRow, " "); // extract the userNumber from the array
    if(isset($_SESSION['userNumber']) && $_SESSION['userNumber'] == $adminRowToInt)
    {
        $adminAccess = 1;
    }
}
?>

