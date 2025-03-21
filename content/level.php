<?php


$queryLevels = mysqli_query($koneksi, "SELECT * FROM levels ORDER BY id DESC");
$rowLevels = mysqli_fetch_all($queryLevels, MYSQLI_ASSOC);

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $delete = mysqli_query ($koneksi,"DELETE FROM levels WHERE id = '$id'");
    header("location:?page=level&notif=success");
}

?>



<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3>Data Level</h3>
            </div>
            <div class="card-body">
                <div align="left" class="mb-3 mt-3">
                    <a href="?page=add-level" class="btn btn-primary">Create New Level</a>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($rowLevels as $row): ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $row['level_name'] ?></td>
                                <td>
                                    <a href="?page=add-level&edit=<?php echo $row['id']?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="?page=level&delete=<?php echo $row['id'] ?>" onclick="return confirm('Are you sure??')" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>