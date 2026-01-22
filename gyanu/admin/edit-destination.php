<?php
session_start();

// Block access if admin not logged in
if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
    header('Location: admin-login.php');
    exit;
}

include "admin-config.php";

$id = $_GET['id'];

// fetch existing data
$result = $conn->query("SELECT * FROM destinations WHERE id=$id");
$data = $result->fetch_assoc();

if(isset($_POST['update'])) {

    $name = $_POST['name'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    // check image update
    if(!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $tmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($tmp, "../images/destination/".$image);
    } else {
        $image = $data['image'];
    }

    $sql = "UPDATE destinations SET
            name='$name',
            location='$location',
            description='$description',
            image='$image'
            WHERE id=$id";

    if($conn->query($sql)) {
        header("Location: destination-crud.php");
        exit();
    } else {
        echo "Update failed";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Destination</title>
</head>
<body>

<h2>Edit Destination</h2>

<form method="POST" enctype="multipart/form-data">

    <input type="text" name="name" value="<?php echo $data['name']; ?>" required><br><br>

    <input type="text" name="location" value="<?php echo $data['location']; ?>" required><br><br>

    <textarea name="description" required><?php echo $data['description']; ?></textarea><br><br>

    <p>Current Image:</p>
    <img src="../images/destination/<?php echo $data['image']; ?>" width="150"><br><br>

    <input type="file" name="image"><br><br>

    <button type="submit" name="update">Update Destination</button>

</form>

</body>
</html>
