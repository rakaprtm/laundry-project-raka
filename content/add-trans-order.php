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

$queryCustomers = mysqli_query($koneksi, "SELECT * FROM customers ORDER BY id DESC");
$rowCustomers = mysqli_fetch_all($queryCustomers, MYSQLI_ASSOC);


// TR032125001
$queryTrans = mysqli_query($koneksi, "SELECT max(id) as id_trans FROM trans_order");
$rowTrans = mysqli_fetch_assoc($queryTrans);
$id_trans = $rowTrans["id_trans"];
$id_trans++;

$kode_transaksi = "TR" . date("mdy") . sprintf("%01s", $id_trans);

// Service
$queryServices = mysqli_query($koneksi, "SELECT * FROM services ORDER BY id DESC");
$rowServices = mysqli_fetch_all($queryServices, MYSQLI_ASSOC); 



?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header mb-3" >
                <h3>Trans Order</h3>
            </div>
            <div class="card-body mt-3">
              <form action="" method="POST">
                <input type="hidden" id="service_price">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-3 row">
                            <div class="col-sm-3">
                                <label for="">Transaction Code</label>
                            </div>
                            <div class="col-sm-5">
                            <input type="text" class="form-control" name="trans_code" value="<?php echo $kode_transaksi; ?>" readonly>

                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-3">
                                <label for="">Order Date</label>
                            </div>
                            <div class="col-sm-5">
                                <input type="date" class="form-control" name="order_date">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-3">
                                <label for="">Service</label>
                            </div>
                            <div class="col-sm-5">
                            <select name="" id="id_service" class="form-control">
                                <option value="">Choose Service</option>
                                <?php foreach($rowServices as $row): ?>
                                    <option value="<?php echo $row['id'] ?>"><?php echo $row['service_name'] ?></option>
                                <?php endforeach ?>
                            </select>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                    <div class="mb-3 row">
                            <div class="col-sm-3">
                                <label for="">Customer Name</label>
                            </div>
                            <div class="col-sm-5">
                                <select name="id_customer" class="form-control" id="">
                                    <option value="">Choose Customer</option>
                                    <?php foreach  ($rowCustomers as $row): ?>
                                    <option value="><?php echo $row['id'] ?>"><?= $row['customer_name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-3">
                                <label for="">Pickup Date</label>
                            </div>
                            <div class="col-sm-5">
                                <input type="date" class="form-control" name="order_date" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-sm-12">
                        <div align="left" class="mb-3">
                            <button type="button" class="btn btn-success btn-sm add-row btn-warning">Add Row</button>
                        </div>
                        <table class="table table-bordered table-order">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Notes</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div align="center" class="mb-3">
                    <button class="btn btn-primary" type="submit" name="<?php echo isset($_GET['edit']) ? 'edit' : 'save' ?>">Save</button>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>
