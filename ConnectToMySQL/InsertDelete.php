<?php
require_once "login.php";
$conn = new mysqli($hn,$un,$pw,$db);
if ($conn->connect_errno) die("Error");
if (isset($_POST['delete']) && isset($_POST['isbn'])){
    $isbn = get_post($conn,'isbn');
    $query = "Delete from classics where isbn = '$isbn'";
    $result = $conn->query($query);
    if (!$result) echo "Delete failed";
}
if (isset($_POST['author']) && isset($_POST['title']) && isset($_POST['category']) && isset($_POST['year']) && isset($_POST['isbn'])){
    $author = get_post($conn,'author');
    $title = get_post($conn,'title');
    $category = get_post($conn,'category');
    $year = get_post($conn,'year');
    $isbn = get_post($conn,'isbn');
    $query = "Insert into classics values ('$author','$title','$category','$year','$isbn')";
    $result = $conn->query($query);
    if (!$result) echo "Insert failed";
}
echo <<<_END
<form action="InsertDelete.php" method="post">
<pre>
    Author:  <input type="text" name="author">
    Title:   <input type="text" name="title">
    Category:<input type="text" name="category">
    Year:    <input type="text" name="year">
    ISBN:    <input type="text" name="isbn">
             <input type="submit" name="submit" value="Add Record">
</pre></form>
_END;
$query = "select * from classics";
$result = $conn->query($query);
if (!$result) die("Database access failed ");
$rows = $result->num_rows;
for ($j = 0;$j<$rows;$j++){
    $row = $result->fetch_array(MYSQLI_NUM);
    $r0 = $row[0];
    $r1 = $row[1];
    $r2 = $row[2];
    $r3 = $row[3];
    $r4 = $row[4];
    echo <<<_END
<pre>
Author: $r0
Title: $r1
Category: $r2
Year: $r3
Isbn: $r4
</pre>
<form action="InsertDelete.php" method="post">
<input type="hidden" name="delete" value="Yes">
<input type="hidden" name="isbn" value="$r4">
<input type="submit" value="Delete Record">
</form>
_END;
}
$result->close();
$conn->close();
function get_post($conn,$var){
    return $conn->real_escape_string($_POST[$var]);
}