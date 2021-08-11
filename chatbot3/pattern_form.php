<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatbot";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">
{% load static %}
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body style="height:1500px">

<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
  <a class="navbar-brand" href="#"><img src="{% static 'img/profile.png' %}" class="rounded" width="30" height="30"></img></a>
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="#">Link</a>
    </li>
  </ul>
</nav>

<div class="container-fluid" style="margin-top:80px">
    <h3>Knowledge Management</h3>
    <div class="table-responsive">
    <table class="table table-bordered">
    <?php
      $sql = "SELECT a.tag_id, a.tag, a.responses, b.pattern FROM tag a JOIN pattern b ON a.tag_id = b.tag_id";
      $result = mysqli_query($conn, $sql);
    ?>
        <thead>
        <tr>
            <th>Tag ID</th>
            <th>Tag</th>
            <th>Responses</th>
            <th>Pattern</th>
        </tr>
        </thead>
        <?php
          while($row = mysqli_fetch_assoc($result)) {
        ?>
        <tbody>
        <tr>
            <td><?php echo $row["tag_id"];?></td>
            <td><?php echo $row["tag"];?></td>
            <td><?php echo $row["responses"];?></td>
            <td><?php echo $row["pattern"];?></td>
        </tr>
        </tbody>
        <?php
          }
          mysqli_close($conn);
        ?>


    </table>
    </div>
</div>

</body>
</html>


