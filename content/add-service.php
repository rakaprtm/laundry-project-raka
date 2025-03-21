<?php
include 'koneksi.php';

$services = [
    1 => ['name' => 'Cuci Kering', 'price' => 10000],
    2 => ['name' => 'Cuci Setrika', 'price' => 15000],
    3 => ['name' => 'Setrika Saja', 'price' => 8000],
    4 => ['name' => 'Dry Cleaning', 'price' => 30000],
    5 => ['name' => 'Cuci Bedcover', 'price' => 50000]
];

$edit = isset($_GET['edit']) ? $_GET['edit'] : '';
$rowEdit = null;
$selected_service_id = '';
$service_price = '';

if ($edit) {
    $queryEdit = mysqli_query($koneksi, "SELECT * FROM services WHERE id = '$edit'");
    $rowEdit = mysqli_fetch_assoc($queryEdit);

    if ($rowEdit) {
        foreach ($services as $key => $service) {
            if ($service['name'] == $rowEdit['service_name']) {
                $selected_service_id = $key;
                $service_price = $service['price'];
                break;
            }
        }
    }
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_service_id = $_POST['service_name'];
    $service_price = $services[$selected_service_id]['price'];
}

if (isset($_POST['update'])) {
    $selected_service_id = $_POST['service_name'];
    $service_name = $services[$selected_service_id]['name'];
    $service_price = $services[$selected_service_id]['price'];
    $description = $_POST['service_desc'];

    if ($edit) {
        $update = mysqli_query($koneksi, "UPDATE services SET service_name = '$service_name', service_price = '$service_price', service_desc = '$description' WHERE id = '$edit'");

        if ($update) {
            header("Location: ?page=service&update=success");
            exit;
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    }
}
?>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3>Edit Service</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="service" class="form-label">Service Name</label>
                        <select name="service_name" class="form-control" required onchange="this.form.submit()">
                            <option value="">Select a Service</option>
                            <?php foreach ($services as $id_service => $service): ?>
                                <option value="<?php echo $id_service; ?>" <?php echo ($selected_service_id == $id_service) ? 'selected' : ''; ?>>
                                    <?php echo $service['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
                <form method="POST">
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" name="service_price" class="form-control" value="<?php echo $service_price ? 'Rp. ' . number_format($service_price, 0, ',', '.') : ''; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="service_desc" class="form-control" required><?php echo $rowEdit ? $rowEdit['service_desc'] : ''; ?></textarea>
                    </div>
                    <button type="submit" name="update" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
