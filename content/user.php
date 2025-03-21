<?php


$queryUser = mysqli_query($koneksi, "SELECT users.*, levels.level_name FROM users 
LEFT JOIN levels on levels.id = users.id_level
ORDER BY users.id DESC");
$rowUser = mysqli_fetch_all($queryUser, MYSQLI_ASSOC);

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $delete = mysqli_query ($koneksi,"DELETE FROM users WHERE id = '$id'");
    header("location:?page=user&notif=success");
}

?>



<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3>Data User</h3>
            </div>
            <div class="card-body">
                <div align="left" class="mb-3 mt-3">
                    <a href="?page=add-user" class="btn btn-primary">Create New User</a>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>id Level</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($rowUser as $row): ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $row['id_level'] ?></td>
                                <td><?php echo $row['name'] ?></td>
                                <td><?php echo $row['email'] ?></td>
                                <td><?php echo $row['password'] ?></td>
                                <td>
                                    <a href="?page=add-user&edit=<?php echo $row['id']?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="?page=user&delete=<?php echo $row['id'] ?>" onclick="return confirm('Are you sure??')" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>