<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatbot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bot Admin</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body style="height:1500px">

<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
  <a class="navbar-brand" href="botadmin.php">
  <img src="../static/img/profile.png" class="rounded-circle" width="40" height="40">
  </a>
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="#">Knowledge</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Unanswered Question</a>
    </li>
  </ul>
</nav>
<?php
    $sql = "SELECT pattern.pattern_id, tag.tag, pattern.pattern, tag.responses FROM tag JOIN pattern ON tag.tag_id = pattern.tag_id";
    $result = $conn->query($sql);
?>
<div class="container-fluid" style="margin-top:80px">
  <h3>Test</h3>
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tag</th>
          <th>Pattern</th>
          <th>Responses</th>
        </tr>
      </thead>
      <?php
        while($row = $result->fetch_assoc()) {
      ?>
      <tbody>
        <tr>
          <td><?php echo $row["pattern_id"];?></td>
          <td><?php echo $row["tag"];?></td>
          <td><?php echo $row["pattern"];?></td>
          <td><?php echo $row["responses"];?></td>
        </tr>
      </tbody>
      <?php
    }
    ?>
    </table>
  </div>
  <!-- Button to Open the Modal -->
  <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
    Insert Data
  </button>

  <!-- The Modal -->
  <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h1 class="modal-title">Insert New Knowledge</h1>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <form action="/action_page.php" class="needs-validation" novalidate>
            <div class="form-group">
            <label for="uname">Tag:</label>
            <input type="text" class="form-control" id="uname" placeholder="Enter username" name="uname" required>
            <div class="valid-feedback">Valid.</div>
            <div class="invalid-feedback">Please fill out this field.</div>
            </div>
            <div class="form-group">
            <label for="pwd">Pattern:</label>
            <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pswd" required>
            <div class="valid-feedback">Valid.</div>
            <div class="invalid-feedback">Please fill out this field.</div>
            </div>
            <div class="form-group form-check">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="remember" required> I agree on blabla.
                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">Check this checkbox to continue.</div>
            </label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
</div>

</body>
</html>


