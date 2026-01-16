<?php
// Include authentication check - redirects if not logged in
require_once 'auth_check.php';
require 'config.php';

$q = $_GET['q'];

$stmt = $conn->prepare(
 "SELECT * FROM destinations 
  WHERE name LIKE ? OR location LIKE ?"
);
$search = "%$q%";
$stmt->bind_param("ss", $search, $search);
$stmt->execute();

$result = $stmt->get_result();
?>

<h2>Search Results</h2>

<?php while($r = $result->fetch_assoc()) { ?>
<p>
<b><?=$r['name']?></b><br>
<?=$r['location']?><br>
<?=$r['description']?>
</p>
<hr>
<?php } ?> -->


<?php
include "config.php";

$query = $_GET['query'];

$sql = "SELECT * FROM destinations 
        WHERE name LIKE '%$query%' 
        OR location LIKE '%$query%'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Search Results</title>
</head>
<body>

<h2>Search Results for "<?php echo $query; ?>"</h2>

<?php if($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div style="margin-bottom:20px;">
            <h3><?php echo $row['name']; ?></h3>
            <img src="./images/destination/<?php echo $row['image']; ?>" width="200">
            <p><?php echo $row['location']; ?></p>
            <a href="destination-details.php?id=<?php echo $row['id']; ?>">View</a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No destination found.</p>
<?php endif; ?>

</body>
</html>
