<?php
session_start();
include_once("Model/mKetNoi.php");
include_once("Model/mOrder.php");
include_once("Controller/cOrder.php");
include_once("Controller/cProduct.php");

$orderCtrl = new controlOrder();
$prodCtrl  = new controlProduct();

// 1. PayOS trả mã đơn qua tham số orderCode
$idOrder = 0;
if (isset($_GET['orderCode'])) {
    $idOrder = (int)$_GET['orderCode'];
} elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idOrder = (int)$_GET['id'];
}

// 2. Kiểm tra trạng thái báo về từ cổng PayOS
$status = isset($_GET['status']) ? strtoupper(trim($_GET['status'])) : '';
$code   = isset($_GET['code']) ? trim($_GET['code']) : '';
$cancel = isset($_GET['cancel']) ? trim($_GET['cancel']) : '';

// Khách trả tiền thành công khi status=PAID hoặc code=00 hoặc cancel=false
if ($idOrder > 0 && ($status === 'PAID' || $code === '00' || $cancel === 'false')) {
    
    // TỰ ĐỘNG 1: Đổi trạng thái sang "Đã thanh toán"
    $orderCtrl->cUpdateStatus($idOrder, "Đã thanh toán");

    // TỰ ĐỘNG 2: Trừ tồn kho theo đúng Size trong kho
    $details = $orderCtrl->cGetOrderDetails($idOrder);
    if ($details && $details->num_rows > 0) {
        while ($d = $details->fetch_assoc()) {
            $sz = !empty($d['size']) ? trim($d['size']) : '38';
            $prodCtrl->cTruTonKhoTheoSize((int)$d['idSP'], $sz, (int)$d['soluong']);
        }
    }

    // TỰ ĐỘNG 3: Điều hướng ngay về trang Đơn hàng của khách
    echo "<script>
            alert('🎉 Quý khách đã thanh toán thành công đơn hàng #" . $idOrder . "!');
            window.location.href = 'index.php?page=donhang';
          </script>";
    exit();
} else {
    // Nếu khách hủy hoặc thanh toán lỗi
    echo "<script>
            alert('Thanh toán chưa hoàn tất.');
            window.location.href = 'index.php?page=donhang';
          </script>";
    exit();
}
?>