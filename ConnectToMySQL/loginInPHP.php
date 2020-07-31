<?php

require_once "login.php";
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_errno) die("Fatal Error");

if (isset($_POST['submit'])) {

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = mysql_entitis_fix_string($conn, $_POST['username']);
        $password = mysql_entitis_fix_string($conn, $_POST['password']);

        $query = "select * from logins where username = '$username'";
        $result = $conn->query($query);
        if (!$result) echo "Doesn't Exist";
        elseif ($result->num_rows) {
            $row = $result->fetch_array(MYSQLI_NUM);
            $result->close();

            if ($username === $row[1] && $password === $row[2]) {
                echo "You are Login<br>";
                echo "<a href='login.html'>Comeback</a>";
            } elseif ($username === $row[1] && $password !== $row[2]) {
                echo "Your password doesn't exist";
            }
        } else die("Invalid username/password combination");
    } else {
        header("www-Authenticate: Basic realm='Restricted Area'");
        header("HTTP/1.0 401 Unauthorized");
        echo "Please enter your username and password";
    }
}

function mysql_entitis_fix_string($conn, $string)
{
    return htmlentities(mysql_fix_string($conn, $string));
}

function mysql_fix_string($conn, $string)
{
    if (get_magic_quotes_gpc()) $string = stripcslashes($conn, $string);
    return $conn->real_escape_string($string);
}