<?php
require_once 'koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID tidak ditemukan'); window.location.href='?page=trans-order';</script>";
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil data detail transaksi
$query = "SELECT trans_order.*, customers.customer_name 
          FROM trans_order 
          LEFT JOIN customers ON trans_order.id_customer = customers.id 
          WHERE trans_order.id = '$id'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) === 0) {
    echo "<script>alert('Data tidak ditemukan'); window.location.href='?page=trans-order';</script>";
    exit;
}

$row = mysqli_fetch_assoc($result);

// Proses update status ke "Sudah Pickup"
if (isset($_POST['update_pickup'])) {
    $updateQuery = "UPDATE trans_order SET status = 1 WHERE id = '$id'";
    if (mysqli_query($koneksi, $updateQuery)) {
        echo "<script>alert('Status berhasil diperbarui'); window.location.href='?page=transaction&detail=$id';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui status');</script>";
    }
}
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function printDetail() {
            let printContents = document.getElementById('print-area').innerHTML;
            let originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</head>

<div class="container mt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h2>Detail Order</h2>
                </div>
                <div class="card-body" id="print-area">
                    <table class="table table-bordered">
                        <tr>
                            <th>Transcode</th>
                            <td><?= htmlspecialchars($row['transcode']) ?></td>
                        </tr>
                        <tr>
                            <th>Customer Name</th>
                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Order</th>
                            <td><?= date('d-m-Y', strtotime($row['order_date'])) ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Selesai</th>
                            <td><?= date('d-m-Y', strtotime($row['order_end_date'])) ?></td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <th>Amount Paid</th>
                            <td>Rp <?= number_format($row['pay'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <th>Change</th>
                            <td>Rp <?= number_format($row['change_pay'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <?php
                                if ($row['status'] == 0) {
                                    echo '<span class="badge bg-warning">Belum Pickup</span>';
                                } elseif ($row['status'] == 1) {
                                    echo '<span class="badge bg-primary">Sudah Pickup</span>';
                                } elseif ($row['status'] == 2) {
                                    echo '<span class="badge bg-danger">Belum Dibayar</span>';
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <div class="text-center p-3">
                    <button onclick="printDetail()" class="btn btn-success">Print</button>
                    <a href="?page=trans-order" class="btn btn-secondary">Back</a>
                    
                    <!-- Tombol Update Status (Muncul jika status masih "Belum Pickup") -->
                    <?php if ($row['status'] == 0) { ?>
                        <form method="POST" class="d-inline">
                            <button type="submit" name="update_pickup" class="btn btn-primary">Mark as Picked Up</button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
