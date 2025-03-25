<?php


$queryUser = mysqli_query($koneksi, "SELECT trans_order.*, customers.customer_name FROM trans_order 
LEFT JOIN customers on customers.id = trans_order.id_customer
WHERE deleted_at = 0
ORDER BY trans_order.id DESC");
$rowTranscode = mysqli_fetch_all($queryUser, MYSQLI_ASSOC);

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $delete = mysqli_query ($koneksi,"UPDATE FROM trans_order SET deleted_at = 1 WHERE id = '$id'");
    header("location:?page=trans-order&notif=success");
}
    
?>



<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3>Data Transorder</h3>
            </div>
            <div class="card-body">
                <div align="left" class="mb-3 mt-3">
                    <a href="?page=add-trans-order" class="btn btn-primary">Create New</a>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr align="center">
                            <th>No</th>
                            <th>Transcode</th>
                            <th>Customer Name</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody align="center">
                        <?php $no = 1;
                        foreach ($rowTranscode as $row): ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $row['transcode'] ?></td>
                                <td><?php echo $row['customer_name'] ?></td>
                                <td><?php echo $row['status'] ?></td>
                                <td>
                                     <?php if ($row['status'] == 0) { ?>
                                                        <?php
                                                        if (isset($_POST['pickup'])) {
                                                            $id = $_GET['idPick'];
                                                            $status = $_POST['status'];
                                                            $sqlDetail = mysqli_query($koneksi, "UPDATE trans_order SET status = '$status' WHERE id = '$id'");

                                                            echo "<script>window.location.href='?page=trans-order&pickup=success';</script>";
                                                        }
                                                        ?>
                                                        <form action="?page=trans-order&idPick=<?php echo $row['id'] ?>" method="post">
                                                            <input type="hidden" name="status" value="1">
                                                            <button type="submit" class="btn btn-primary btn-sm" name="pickup">PICKUP</button>
                                                        </form>
                                                    <?php } elseif ($row['status'] == 2) { ?>
                                                        <a href="?page=pay-transaction&idPay=<?= $row['id'] ?>" class="btn btn-primary btn-sm">PAYMENT</a>
                                                    <?php } else { ?>
                                                        <a href="#" class="btn btn-primary btn-sm">HAS PICKUP</a>
                                                    <?php } ?>

                                                    <?php if ($row['pay'] > 0) { ?>
        <a href="?page=detailorder&id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">DETAILS</a>
    <?php } else { ?>
        <button class="btn btn-warning btn-sm" disabled>DETAILS</button>
    <?php } ?>

                                    <a href="?page=trans-order&delete=<?php echo $row['id'] ?>" onclick="return confirm('Are you sure??')" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>