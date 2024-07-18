<?php
header("Access-Control-Allow-Origin: http://localhost:3001"); // Your React app URL
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
require_once 'DAL.php';
$dal = new DAL();

// Function to handle file upload
function handleFileUpload() {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (max 5MB)
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        return false;
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            return $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
            return false;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $profileImage = "";

    // Handle file upload
    if (isset($_FILES["fileToUpload"])) {
        $profileImage = handleFileUpload();
        if ($profileImage === false) {
            $profileImage = ""; // Reset profileImage if upload fails
        }
    }
    
    // Handle form submissions
    if (isset($_POST['create'])) {
        $dal->createStudent($_POST['name'], $_POST['age'], $_POST['email'], $profileImage);
    } elseif (isset($_POST['update'])) {
        $dal->updateStudent($_POST['id'], $_POST['name'], $_POST['age'], $_POST['email'], $profileImage);
    } elseif (isset($_POST['delete'])) {
        $dal->deleteStudent($_POST['id']);
    }
}

$students = $dal->getStudents();

include 'header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h2>Create Student</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" name="age" id="age" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="fileToUpload">Profile Image:</label>
                    <input type="file" name="fileToUpload" id="fileToUpload" class="form-control">
                </div>
                <input type="submit" name="create" value="Create" class="btn btn-primary">
            </form>
        </div>

        <div class="col-md-4">
            <h2>Update Student</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="id">ID:</label>
                    <input type="number" name="id" id="id" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" name="age" id="age" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="fileToUpload">Profile Image:</label>
                    <input type="file" name="fileToUpload" id="fileToUpload" class="form-control">
                </div>
                <input type="submit" name="update" value="Update" class="btn btn-primary">
            </form>
        </div>

        <div class="col-md-4">
            <h2>Delete Student</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="id">ID:</label>
                    <input type="number" name="id" id="id" class="form-control" required>
                </div>
                <input type="submit" name="delete" value="Delete" class="btn btn-danger">
            </form>
        </div>
    </div>

    <h2>Student List</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Email</th>
                <th>Profile Image</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo $student['id']; ?></td>
                    <td><?php echo $student['name']; ?></td>
                    <td><?php echo $student['age']; ?></td>
                    <td><?php echo $student['email']; ?></td>
                    <td>
                    <img src="<?php echo $student['profile_image']; ?>" alt="Profile Image" width="50">
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
