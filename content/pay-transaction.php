<?php
require_once 'koneksi.php';

// Inisialisasi variabel $row untuk menghindari error
$row = [
    'id' => '',
    'customer_name' => 'Data tidak ditemukan',
    'total' => 0,
    'status' => '',
    'pay' => 0,
    'change_pay' => 0
];

// Cek apakah `idPay` ada di URL
if (isset($_GET['idPay']) && !empty($_GET['idPay'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['idPay']);

    $query = "SELECT trans_order.*, customers.customer_name 
              FROM trans_order 
              LEFT JOIN customers ON trans_order.id_customer = customers.id 
              WHERE trans_order.id = '$id'";

    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "<div class='alert alert-danger'>Data transaksi tidak ditemukan!</div>";
        exit;
    }
}

// Proses pembayaran
if (isset($_POST['bayar'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['idPay']);
    $pay = mysqli_real_escape_string($koneksi, $_POST['pay']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    $changepay = mysqli_real_escape_string($koneksi, $_POST['changepay']);
    $total = mysqli_real_escape_string($koneksi, $_POST['total']);

    // Update pembayaran di database
    $updateQuery = "UPDATE trans_order SET pay = '$pay', status = '$status', total = '$total', change_pay = '$changepay' WHERE id = '$id'";

    if (mysqli_query($koneksi, $updateQuery)) {
        echo "<script>alert('Pembayaran berhasil!'); window.location.href='?page=trans-order';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal melakukan pembayaran. Coba lagi!');</script>";
    }
}
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function printPayment() {
            let printContents = document.getElementById('print-area').innerHTML;
            let originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        function payment() {
            let pay = document.getElementById('orderPay').value;
            let total = document.getElementById('total').value;
            let hitung = pay - total;
            document.getElementById('changePay').value = hitung;
        }
    </script>
</head>

<div class="container mt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">
                    <h2>PAYMENT</h2>
                </div>
                <div class="card-body" id="print-area">
                    <div class="mb-3">
                        <label for="customerName" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="customerName" value="<?= htmlspecialchars($row['customer_name']) ?>" readonly>
                    </div>
                    <form action="" method="post">
                        <div class="mb-3">
                            <label class="form-label">Order Status</label><br>
                            <input type="radio" name="status" value="0" <?= isset($row['status']) && $row['status'] == 0 ? 'checked' : '' ?>> Transaksi Selesai <br>
                            <input type="radio" name="status" value="1" <?= isset($row['status']) && $row['status'] == 1 ? 'checked' : '' ?>> Status Sudah Diambil
                        </div>
                        <div class="mb-3">
                            <label for="orderPay" class="form-label">Order Pay</label>
                            <input type="number" class="form-control" id="orderPay" name="pay" oninput="payment()" required>
                        </div>
                        <div class="mb-3">
                            <label for="changePay" class="form-label">Change Pay</label>
                            <input type="number" class="form-control" id="changePay" name="changepay" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="total" class="form-label">Total</label>
                            <input type="number" class="form-control" id="total" name="total" value="<?= $row['total'] ?>" required>
                        </div>
                        <div class="mb-3 text-center">
                            <button class="btn btn-primary" type="submit" name="bayar">Bayar</button>
                            <a href="?page=pay-transaction&idpay=<?= $row['id'] ?>" class="btn btn-warning">Cancel</a>
                            <?php if ($row['pay'] > 0) { ?>
                                <button onclick="printPayment()" class="btn btn-danger">Print</button>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
