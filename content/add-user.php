<?php
// session_start();
// include '../koneksi.php';

if(isset($_POST['save'])){
    $id_level = $_POST['id_level'];
    $name = $_POST['name'];
    $email= $_POST['email'];
    $password= sha1($_POST['password']);

    $insert = mysqli_query($koneksi, "INSERT INTO users (id_level, name, email, password) VALUES ('$id_level', '$name', '$email', '$password')");
    if ($insert) {
        header("location:?page=user&add=success");
    }
}

if(isset($_POST['edit'])){
    $id = $_GET['edit'];
    $id_level = $_POST['id_level'];
    $name = $_POST['name'];
    $email= $_POST['email'];
    $password= $_POST['password'];

    if(isset ($_POST['password'])){
        $password = sha1($_POST['password']);
    } else {
        $password = $rowEdit['password'];
    }

    $update = mysqli_query($koneksi, "UPDATE users SET id_level = '$id_level', name = '$name', email = '$email', password = '$password' WHERE id = '$id'");
    if($update) {
        header("location:?page=user&update=success");
    }
}


$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$queryEdit = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id'");
$rowEdit = mysqli_fetch_assoc($queryEdit);

$queryLevels = mysqli_query($koneksi, "SELECT * FROM levels ORDER BY id DESC");
$rowLevels = mysqli_fetch_all($queryLevels, MYSQLI_ASSOC);



?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header mb-3" >
                <h3>User</h3>
            </div>
            <div class="card-body">
              <form action="" method="POST">
                <div class="mb-3">
                    <label for="">Id Level :</label>
                    <select name="id_level" id="" class="form-control">
                        <option value="">Pilih Level</option>
                        <?php foreach ($rowLevels as $item) : ?>
                            <option <?php echo isset($_GET['edit']) ? ($rowEdit['id_level'] == $item['id']) ? 'selected' : '':'' ?> value="<?php echo $item['id'] ?>"><?php echo $item['level_name']?></option>
                            <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="">Name :</label>
                    <input value="<?php echo isset($_GET['edit']) ? $rowEdit['name'] : '' ?>" type="text" class="form-control" name="name" required placeholder="Enter Name">
                </div>
                <div class="mb-3">
                    <label for="">Email :</label>
                    <input value="<?php echo isset($_GET['edit']) ? $rowEdit['email'] : '' ?>" type="text" class="form-control" name="email" required placeholder="Enter email">
                </div>
                <div class="mb-3">
                    <label for="">password :</label>
                    <input type="text" class="form-control" name="password" required placeholder="Enter password">
                    
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary" type="submit" name="<?php echo isset($_GET['edit']) ? 'edit' : 'save' ?>">Save</button>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>
