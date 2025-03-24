<?php 
session_start();
ob_start();
include 'koneksi.php';
$queryCustomer = mysqli_query($koneksi, "SELECT * FROM customers ORDER BY id DESC");
$rowCustomer = mysqli_fetch_all($queryCustomer, MYSQLI_ASSOC);
?>


<!-- Di ambil dari Page Blank -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Components / Accordion - NiceAdmin Bootstrap Template</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
    

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
    <!-- HEADER -->
    <?php include 'inc/header.php'; ?>


    <!-- ======= Sidebar ======= -->
    <?php include 'inc/sidebar.php'; ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Pages</li>
                    <li class="breadcrumb-item active">Blank</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <?php
            if (isset($_GET['page'])) {
                if (file_exists('content/' . $_GET['page'] . ".php")) {
                    include 'content/' . $_GET['page'] . ".php";
                }
            } else {
                include "content/home.php";
            }

            ?>
    
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include 'inc/footer.php' ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script>
        $('#id_service').change(function() {
            let id_service = $(this).val();
            $.ajax({
                url: "get-service.php?id_service=" + id_service,
                method: "get",
                dataType: "json",
                success: function(res) {
                    $('#service_price').val(res.data.service_price)
                }
        });
        });
        $('.add-row').click(function () {
            let countDisplay = document.getElementById("countDispaly");
            let currentCount = parseInt(countDisplay.value) || 0;
            currentCount++;
            countDisplay.value = currentCount;
            let service_name = $('#id_service').find("option:selected").text()
            let service_price = $('#service_price').val()
            let newRow = "";
            newRow += "<tr>"
            newRow += `<td> ${currentCount} </td>`;
            newRow += `<td> ${service_name} <input type='text' name='service_name' value='${service_name}'> </td>`;
            newRow += `<td> ${service_price.toLocaleString()} </td>`;
            newRow += "<td><input class='form-control' name='qty[]' type='number' > </td>";
            newRow += "<td><input class='form-control' name='notes[]' type='text' > </td>";
            
            newRow += "<td align='center'><button type='button' class='btn btn-success btn-sm remove btn-danger'>Remove</button></td>";
            newRow += "</tr>"

            $('.table-order tbody').append(newRow);

            $('.remove').click(function () {
                $(this).closest('tr').remove();
                
            })

        });
//            alert('Testing');<?php 
// session_start();
// ob_start();
// require_once 'koneksi.php';
// $queryCustomer = mysqli_query($koneksi, "SELECT * FROM customers ORDER BY id DESC");
// $rowCustomer = mysqli_fetch_all($queryCustomer, MYSQLI_ASSOC);
// ?>
//         });
    </script>

</body>

</html>