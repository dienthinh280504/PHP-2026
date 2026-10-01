<?php
session_start();
include_once("Model/mOrder.php");
include_once("Controller/cOrder.php");

$orderCtrl = new controlOrder();

$idOrder = isset($_GET['orderCode']) ? (int)$_GET['orderCode'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);

if ($idOrder > 0) {
    $orderCtrl->cUpdateStatus($idOrder, "Đã hủy");
}

echo "<script>
        alert('Giao dịch thanh toán PayOS đã bị hủy.');
        window.location.href = 'index.php?page=giohang';
      </script>";
exit();
?>