<?php
require_once 'koneksi.php'; 


if (!$koneksi) {
    die("Connection failed: " . mysqli_connect_error());
}

if (empty($_SESSION['click_count'])) {
    $_SESSION['click_count'] = 0;
}

if (isset($_POST['save'])) {
    $transcode = $_POST['transcode'];
    $id_customer = $_POST['id_customer']; 
    $order_date = $_POST['order_date'];
    $order_end_date = $_POST['order_end_date']; 

    $insert = mysqli_query($koneksi, "INSERT INTO trans_order (transcode, order_date, id_customer, order_end_date) 
        VALUES ('$transcode', '$order_date', '$id_customer', '$order_end_date')");

$id_order = mysqli_insert_id($koneksi);
$qty = isset($_POST['qty']) ? $_POST['qty']: 0;
$notes = isset($_POST['notes']) ? $_POST['notes']: '';
$id_service = isset($_POST['id_service']) ? $_POST['id_service']: 0;

for ($i = 0; $i < $_POST['countDispaly']; $i++) {
$service_name = $_POST['service_name']; 
$carild_service = mysqli_query($koneksi, "SELECT id FROM services WHERE service_name = '$service_name'"); 
$rowid_service = mysqli_fetch_assoc($carild_service);

$id_service = $rowid_service['id'];

$instOrderDet = mysqli_query($koneksi, "INSERT INTO trans_order_detail (id_order, id_service, qty, notes) VALUES
('$id_order', '$id_service', '$qty[$i]', '$notes[$i]')");
}
}

if (isset($_POST['edit'])) {
    $id = $_GET['edit'];
    $transcode = $_POST['transcode'];
    $order_date = $_POST['order_date'];
    $id_customer = $_POST['id_customer'];
    $order_end_date = $_POST['order_end_date'];

    $update = mysqli_query($koneksi, "UPDATE trans_order SET 
        transcode = '$transcode', 
        order_date = '$order_date', 
        id_customer = '$id_customer', 
        order_end_date = '$order_end_date'
        WHERE id = '$id'");

    if (!$update) {
        die("Error: " . mysqli_error($koneksi)); 
    } else {
        header("location:?page=trans-order&update=success");
        exit();
    }
}

$queryCustomers = mysqli_query($koneksi, "SELECT * FROM customers ORDER BY id DESC");
$rowCustomers = mysqli_fetch_all($queryCustomers, MYSQLI_ASSOC);

$queryTrans = mysqli_query($koneksi, "SELECT max(id) as id_trans FROM trans_order");
$rowTrans = mysqli_fetch_assoc($queryTrans);
$id_trans = $rowTrans["id_trans"];
$id_trans++;

$kode_transaksi = "TR" . date("mdy") . sprintf("%01s", $id_trans);

$queryServices = mysqli_query($koneksi, "SELECT * FROM services ORDER BY id DESC");
$rowServices = mysqli_fetch_all($queryServices, MYSQLI_ASSOC);
?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header mb-3">
                <h3>Trans Order</h3>
            </div>
            <div class="card-body mt-3">
                <form action="" method="POST">
                    <input type="hidden" id="service_price">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3 row">
                                <div class="col-sm-3">
                                    <label>Transaction Code</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="transcode" value="<?php echo $kode_transaksi; ?>" readonly>
                                </div>
                            </div>
                             <div class="mb-3 row">
                                <div class="col-sm-3">
                                    <label>Order Date</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="date" class="form-control" name="order_date" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-sm-3">
                                    <label>Service</label>
                                </div>
                                <div class="col-sm-5">
                                    <select name="id_service" id="id_service" class="form-control">
                                        <option value="">Choose Service</option>
                                        <?php foreach($rowServices as $row): ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['service_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3 row">
                                <div class="col-sm-3">
                                    <label>Customer Name</label>
                                </div>
                                <div class="col-sm-5">
                                    <select name="id_customer" class="form-control" required>
                                        <option value="">Choose Customer</option>
                                        <?php foreach ($rowCustomers as $row): ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['customer_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-sm-3">
                                    <label>Pickup Date</label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="date" class="form-control" name="order_end_date" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-sm-12">
                            <div align="left" class="mb-3">
                                <button type="button" class="btn btn-warning btn-sm add-row">Add Row</button>
                                <input type="number" name="countDispaly" id="countDispaly" value="<?= $_SESSION['click_count'] ?>" readonly>
                            </div>
                            <table class="table table-bordered table-order">
                                <thead>
                                    <tr>
                                        <th>No</th>
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
                        <button class="btn btn-primary" type="submit" name="<?php echo isset($_GET['edit']) ? 'edit' : 'save'; ?>">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
