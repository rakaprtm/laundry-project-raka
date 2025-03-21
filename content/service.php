<?php
$queryservice = mysqli_query($koneksi, "
    SELECT s.id, l.name AS service_name, s.service_price, s.service_desc 
    FROM services s 
    JOIN layanan l ON s.service_name = l.id 
    ORDER BY s.id DESC
");

$queryservice = mysqli_query($koneksi, "SELECT * FROM services ORDER BY id DESC");
$service = mysqli_fetch_all($queryservice, MYSQLI_ASSOC);

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $delete = mysqli_query($koneksi, "DELETE FROM services WHERE id = '$id'");
    header("location:?page=service&notif=success");
}
?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3>Data Service</h3>
            </div>
            <div class="card-body">
                <div align="right" class="mb-3 mt-3">
                    <a href="?page=add-service" class="btn btn-warning">Create New</a>
                </div>
                <table class="table table-bordered">
                    <thead align="center">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($service as $row): ?>
                            <tr align="center">
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $row['service_name'] ?: 'Kosong'; ?></td>
                                <td>Rp. <?php echo number_format($row['service_price'], 0, ',', '.'); ?></td>
                                <td><?php echo $row['service_desc']; ?></td>
                                <td>
                                    <a href="?page=add-service&edit=<?php echo $row['id']?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="?page=service&delete=<?php echo $row['id'] ?>" onclick="return confirm('Are you sure??')" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>