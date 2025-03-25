<?php
 // $sqlGet = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
 // $result = mysqli_fetch_all($sqlGet, MYSQLI_ASSOC);
 
 $sqlReq = mysqli_query($conn, "SELECT trans_order. * , customers.customer_name FROM trans_order LEFT JOIN customers ON customers.id = trans_order.id_customer WHERE deleted_at = 0 ORDER BY trans_order.id DESC");
 $result = mysqli_fetch_all($sqlReq, MYSQLI_ASSOC);
 
 if (empty($_SESSION['click-count'])) {
     $_SESSION['click_count'] = 0; // Initialize click_count if not set
     // $sqlGet = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
 }
 
 if (isset($_POST['simpan'])) {
     $transCode = $_POST['transcode'];
     $id_customer = $_POST['customer'];
     $transDate = $_POST['tgl_order'];
     $transEndDate = $_POST['pick_up'];
 
     $sqlInsert = mysqli_query($conn, "INSERT INTO trans_order (id_customer, order_code, order_date, order_end_date) VALUES ('$id_customer', '$transCode', '$transDate', '$transEndDate')");
 
     $idorder = mysqli_insert_id($conn);
     $qty = isset($_POST['qty']) ? $_POST['qty'] : [];
     $notes = isset($_POST['notes']) ? $_POST['notes'] : [];
     $idService = isset($_POST['service']) ? $_POST['service'] : [];
     $total = isset($_POST['total']) ? $_POST['total'] : [];
 
     $subtotal = 0;
     for ($i = 0; $i < $_POST['countDisplay']; $i++) {
         $serviceName = $idService[$i];
         $findIdSer = mysqli_query($conn, "SELECT id FROM services WHERE service_name LIKE '%$services%'");
         $rowIdSer = mysqli_fetch_assoc($findIdSer);
 
         $idService = $rowIdSer['id'];
 
         $qtyValue = $qty[$i];
         $notesValue = $notes[$i];
         $totalValue = $total[$i];
 
         $sqlInsertDetail = mysqli_query($conn, "INSERT INTO trans_order_detail (id_order, id_service, qty, subtotal, notes) VALUES ('$idorder', '$idService', '$qtyValue', '$totalValue', '$notesValue')");
 
         $subtotals += ($totalValue * $qtyValue);
     }
 
     $sqlUpdate = mysqli_query($conn, "UPDATE trans_order SET total = '$subtotals' WHERE id = '$idorder'");
 
     if ($sqlInsert) {
         echo "<script>window.location.href='?page=transaction&add=success';</script>";
     } else {
         echo "<script>window.location.href='?page=transaction&add=failed';</script>";
     }
 }
 
 if (isset($_GET['id'])) {
     $id = $_GET['id'];
 
     $sqlGet = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
     $result = mysqli_fetch_assoc($sqlGet);
     print_r($result);
     die;
 
     if (!$result) {
         die("Data tidak ditemukan!");
     }
 }
 
 if (isset($_POST['edit'])) {
     $id = $_POST['id'];
     $cust = $_POST['customer'];
     $order_code = $_POST['code'];
     $email = $_POST['email'];
 
     if ($_POST['password']) {
         $password = sha1($_POST['password']);
     } else {
         $password = $result['password'];
     }
 
     $sqlUpdate = mysqli_query($conn, "UPDATE users SET id_level = '$level', name = '$name', email = '$email', password = '$password' WHERE id = '$id'");
 
     if ($sqlUpdate) {
         echo "<script>window.location.href='?page=transaction&update=success';</script>";
     } else {
         echo "<script>window.location.href='?page=transaction&update=failed';</script>";
     }
 }
 
 // if (isset($_POST['pickup'])) {
 //     $id = $_GET['id'];
 //     $status = $_POST['status'];
 
 //     $sqlDetail = mysqli_query($conn, "UPDATE trans_order SET order_status = '$status' WHERE id = '$id'");
 
 //     if ($sqlDetail) {
 //         echo "<script>window.location.href='?page=transaction&pickup=success';</script>";
 //     } else {
 //         echo "<script>window.location.href='?page=transaction&pickup=failed';</script>";
 //     }
 // }
 
 if (isset($_GET['delete'])) {
     $id = $_GET['delete'];
 
     $sqlDelete = mysqli_query($conn, "UPDATE trans_order SET deleted_at = 1 WHERE id = '$id'");
 
     if ($sqlDelete) {
         echo "<script>window.location.href='?page=transaction&notif=success');</script>";
     }
 }
 
 // CLT-032125-01
 $sqlTrans = mysqli_query($conn, "SELECT max(id) as id_trans FROM trans_order");
 $resultTrans = mysqli_fetch_assoc($sqlTrans);
 $id_trans = $resultTrans['id_trans'];
 $id_trans++;
 
 $code_trans = "CLT-" . date("mdy") . sprintf("-%02s", $id_trans);
 
 
 
 ?>
 
 <div class="container-xxl flex-grow-1 container-p-y">
     <div class="row">
         <div class="col-lg-12 mb-4 order-0">
             <div class="card">
                 <div class="d-flex align-items-end row">
                     <div class="col-sm-12">
                         <div class="card-header">
                             <h5 class="card-title text-primary">Transaction Data</h5>
                             <!-- ALERT ERROR -->
                             <?php if (isset($_GET['add'])) : ?>
                                 <div class="alert alert-success alert-dismissible" role="alert">
                                     <i class="bx bx-bell me-2"></i>
                                     <strong>Transaction Has Added!</strong>
                                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                 </div>
                             <?php endif ?>
                             <div class="text-end">
                                 <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                     New Transaction
                                 </button>
                             </div>
 
                             <div class="modal fade" id="modalAdd" tabindex="-1" aria-hidden="true">
                                 <div class="modal-dialog modal-lg" role="document">
                                     <div class="modal-content">
 
                                         <!-- FORM ADD CUSTOMER -->
                                         <form action="" method="post">
                                             <input type="hidden" id="service_price">
 
                                             <div class="modal-header">
                                                 <h5 class="modal-title" id="modalCenterTitle">Transaction Add</h5>
                                                 <button
                                                     type="button"
                                                     class="btn-close"
                                                     data-bs-dismiss="modal"
                                                     aria-label="Close"></button>
                                             </div>
                                             <div class="modal-body">
                                                 <div class="row mb-3">
                                                     <div class="col">
                                                         <label for="nameWithTitle" class="form-label">Transaction Code</label>
                                                     </div>
                                                     <div class="col-sm-12">
                                                         <input type="text" name="transcode" id="transcode" class="form-control" value="<?= $code_trans ?>" readonly />
                                                     </div>
                                                 </div>
                                                 <div class="row mb-3">
                                                     <div class="col">
                                                         <label for="nameWithTitle" class="form-label">Services</label>
                                                     </div>
                                                     <div class="col-sm-12">
                                                         <select name="services" id="id_service" class="form-select">
                                                             <option value="-" disabled selected>Pick a Services</option>
                                                             <?php
                                                             $sql = mysqli_query($conn, "SELECT * FROM services ORDER BY id");
                                                             $resultServ = mysqli_fetch_all($sql, MYSQLI_ASSOC);
                                                             foreach ($resultServ as $row) : ?>
                                                                 <option value=<?php echo $row['id'] ?>><?php echo $row['service_name'] ?></option>
                                                             <?php endforeach; ?>
                                                         </select>
                                                     </div>
                                                 </div>
                                                 <div class="row mb-3">
                                                     <div class="col-sm-6">
                                                         <div class="col">
                                                             <label for="nameWithTitle" class="form-label">Transaction Dates</label>
                                                         </div>
                                                         <div class="col-sm-12">
                                                             <input type="date" name="tgl_order" id="tgl_order" class="form-control" />
                                                         </div>
                                                     </div>
                                                     <div class="col-sm-6">
                                                         <div class="col">
                                                             <label for="nameWithTitle" class="form-label">Pick Up Date</label>
                                                         </div>
                                                         <div class="col-sm-12">
                                                             <input type="date" name="pick_up" id="pick-up" class="form-control" />
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <div class="row">
                                                     <div class="col-sm-3">
                                                         <label for="nameWithTitle" class="form-label">Name Customer</label>
                                                     </div>
                                                     <div class="col-sm-12">
                                                         <select name="customer" id="customer" class="form-select">
                                                             <option value="-" disabled selected>Pick a Customer</option>
                                                             <?php
                                                             $sql = mysqli_query($conn, "SELECT * FROM customers ORDER BY id");
                                                             $resultCus = mysqli_fetch_all($sql, MYSQLI_ASSOC);
                                                             foreach ($resultCus as $row) : ?>
                                                                 <option value=<?php echo $row['id'] ?>><?php echo $row['customer_name'] ?></option>
                                                             <?php endforeach; ?>
                                                         </select>
                                                     </div>
                                                 </div>
                                                 <div class="row mt-3">
                                                     <div class="col">
                                                         <label for="nameWithTitle" class="form-label"></label>
                                                     </div>
                                                     <div class="col-sm-12">
                                                         <div align="right" class="mb-3">
                                                             <button type="button" class="btn btn-dark btn-sm add-row">Add Row</button>
                                                             <input type="number" name="countDisplay" id="countDisplay" value="<?= isset($_SESSION['click_count']) ? $_SESSION['click_count'] : 0; ?>">
                                                             <!-- <input type="number" name="countDisplay" id="countDisplay" value=""> -->
                                                         </div>
                                                         <div class="tabel-responsive text-nowrap">
                                                             <table class="table table-order">
                                                                 <thead>
                                                                     <tr>
                                                                         <th>No.</th>
                                                                         <th>Services</th>
                                                                         <th>Prices</th>
                                                                         <th>Qty</th>
                                                                         <th>Notes</th>
                                                                         <th>Action</th>
                                                                     </tr>
                                                                 </thead>
                                                                 <tbody></tbody>
                                                             </table>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="modal-footer">
                                                 <button type="submit" name="simpan" class="btn btn-dark">SAVE</button>
                                                 <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                                                     CLOSE
                                                 </button>
                                             </div>
                                         </form>
                                     </div>
                                 </div>
                             </div>
 
 
                             <div class="card-body">
                                 <div class="table-responsive text-nowrap">
                                     <table class="table">
                                         <caption class="ms-4">
                                             List of Transaction
                                         </caption>
                                         <thead>
                                             <tr>
                                                 <th>No.</th>
                                                 <th>Code Transaction</th>
                                                 <th>Name Customer</th>
                                                 <th>Status</th>
                                                 <th>Action</th>
                                             </tr>
                                         </thead>
                                         <?php
                                         $no = 1;
                                         foreach ($result as $row) :
                                         ?>
                                             <tbody>
                                                 <td><?php echo $no++ . '.' ?></td>
                                                 <td><?= $row['order_code'] ?></td>
                                                 <td><?= $row['customer_name'] ?></td>
                                                 <td><?= $row['order_status'] ?></td>
                                                 <td>
                                                     <!-- <button type="button" class="btn btn-dark" data-id="?page=customer&id<?= $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#modalEdit"> EDIT </button> -->
 
                                                     <?php if ($row['order_status'] == 0) { ?>
                                                         <?php
                                                         if (isset($_POST['pickup'])) {
                                                             $id = $_GET['idPick'];
                                                             $status = $_POST['status'];
                                                             $sqlDetail = mysqli_query($conn, "UPDATE trans_order SET order_status = '$status' WHERE id = '$id'");
 
                                                             echo "<script>window.location.href='?page=transaction&pickup=success';</script>";
                                                         }
                                                         ?>
 
                                                         <form action="?idPick=<?php echo $row['id'] ?>" method="post">
                                                         <form action="?page=transaction&idPick=<?php echo $row['id'] ?>" method="post">
                                                             <input type="hidden" name="status" value="1">
                                                             <button type="submit" class="btn btn-primary" name="pickup">PICKUPss</button>
                                                         </form>
                                                     <?php } elseif ($row['order_status'] == 2) { ?>
                                                         <a href="?page=payment&idPay=<?= $row['id'] ?>" class="btn btn-primary">PAYMENT</a>
                                                     <?php } else { ?>
                                                         <a href="#" class="btn btn-primary">HAS PICKUP</a>
                                                     <?php } ?>
 
                                                     <a href="?page=transaction&detail=<?= $row['id'] ?>" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>">DETAILS</a>
 
                                                     <a href="?page=transaction&delete=<?php echo $row['id'] ?>" class="btn btn-light" onclick="return confirm('Are you sure you want to delete this customer?')">DELETE</a>
 
                                                 </td>
                                             </tbody>
 
                                             <!-- <div class="modal fade" id="modalEdit<?php echo $row['id'] ?>" tabindex="-1" aria-hidden="true">
                                                 <div class="modal-dialog modal-dialog-centered" role="document">
                                                     <div class="modal-content">
 
                                                         <form action="" method="post">
                                                             <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
 
                                                             <div class="modal-header">
                                                                 <h5 class="modal-title" id="modalCenterTitle">User Edit</h5>
                                                                 <button
                                                                     type="button"
                                                                     class="btn-close"
                                                                     data-bs-dismiss="modal"
                                                                     aria-label="Close"></button>
                                                             </div>
                                                             <div class="modal-body">
                                                                 <div class="row mb-3">
                                                                     <div class="col">
                                                                         <label for="nameWithTitle" class="form-label">Level</label>
                                                                     </div>
                                                                     <div class="col-sm-12">
                                                                         <select name="level" id="level" class="form-select">
                                                                             <?php
                                                                             $sql = mysqli_query($conn, "SELECT * FROM level ORDER BY id");
                                                                             $resultSel = mysqli_fetch_all($sql, MYSQLI_ASSOC);
                                                                             foreach ($resultSel as $rows) : ?>
                                                                                 <option <?php echo ($row['id_level'] == $rows['id']) ? 'selected' : '' ?> value="<?php echo $rows['id'] ?>"><?php echo $rows['level_name'] ?></option>
                                                                             <?php endforeach; ?>
                                                                         </select>
 
                                                                     </div>
                                                                 </div>
                                                                 <div class="row mb-3">
                                                                     <div class="col">
                                                                         <label for="nameWithTitle" class="form-label">Name</label>
                                                                     </div>
                                                                     <div class="col-sm-12">
                                                                         <input type="text" name="nama" id="nama" class="form-control" value="<?php echo $row['name'] ?>" />
                                                                     </div>
                                                                 </div>
                                                                 <div class="row mb-3">
                                                                     <div class="col-sm-3">
                                                                         <label for="nameWithTitle" class="form-label">Email</label>
                                                                     </div>
                                                                     <div class="col-sm-12">
                                                                         <input type="text" name="email" id="email" class="form-control" value="<?php echo $row['email'] ?>" />
                                                                     </div>
                                                                 </div>
                                                                 <div class="row mb-3">
                                                                     <div class="col">
                                                                         <label for="nameWithTitle" class="form-label">Passwords</label>
                                                                     </div>
                                                                     <div class="col-sm-12">
                                                                         <input type="password" name="password" id="password" class="form-control" value="<?php echo $row['password'] ?>" aria-describedby="password" />
                                                                     </div>
                                                                 </div>
                                                             </div>
                                                             <div class="modal-footer">
                                                                 <button type="submit" name="edit" class="btn btn-dark">EDIT</button>
                                                                 <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                                                                     CLOSE
                                                                 </button>
                                                             </div>
                                                         </form>
                                                     </div>
                                                 </div>
                                             </div> -->
                                         <?php endforeach ?>
                                     </table>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>