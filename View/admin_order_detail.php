<?php
include_once("Controller/cOrder.php");
$orderCtrl = new controlOrder();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$res = $orderCtrl->cGetOrderById($id);
$order = ($res && $res->num_rows > 0) ? $res->fetch_assoc() : null;

if (!$order) {
    echo "<div style='padding:30px; text-align:center;'>
            <p style='color:red; font-size:16px;'>Không tìm thấy đơn hàng #$id.</p>
            <a href='admin.php?act=order' style='color:#2980b9;'>← Quay lại danh sách đơn hàng</a>
          </div>";
    return;
}

$details = $orderCtrl->cGetOrderDetails($id);
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="font-size:22px; text-transform:uppercase; color:#222;">CHI TIẾT ĐƠN HÀNG #<?php echo $order['idOrder']; ?></h2>
    <a href="admin.php?act=order" style="text-decoration:none; color:#555; font-size:13px; font-weight:bold; padding:8px 14px; background:#e9ecef; border-radius:5px;">← Quay lại danh sách đơn hàng</a>
</div>

<!-- KHUNG THÔNG TIN KHÁCH HÀNG & GIAO HÀNG -->
<div style="background:#f9f9f9; padding:20px; border-radius:8px; margin-bottom:25px; line-height:2; border:1px solid #eee;">
    <p><b>Khách hàng:</b> <?php echo htmlspecialchars($order['hoten']); ?> (Tài khoản: <b><?php echo htmlspecialchars($order['Username']); ?></b>)</p>
    <p><b>Số điện thoại:</b> <?php echo htmlspecialchars($order['sdt']); ?></p>
    <p><b>Địa chỉ nhận hàng:</b> <?php echo htmlspecialchars($order['diachi']); ?></p>
    <p><b>Ghi chú:</b> <?php echo !empty($order['ghichu']) ? htmlspecialchars($order['ghichu']) : "(Không có)"; ?></p>
    <p>
        <b>Trạng thái:</b> 
        <?php 
        $tt = trim($order['trangthai']);
        if ($tt == 'Đã thanh toán') {
            echo "<span style='padding:3px 10px; background:#e8f8f5; color:#16a085; border:1px solid #a3e4d7; border-radius:15px; font-size:12px; font-weight:bold;'>✔ Đã thanh toán</span>";
        } elseif ($tt == 'Chưa thanh toán' || $tt == 'Chờ xử lý') {
            echo "<span style='padding:3px 10px; background:#fef9e7; color:#d4ac0d; border:1px solid #f9e79f; border-radius:15px; font-size:12px; font-weight:bold;'>⏳ Chưa thanh toán</span>";
        } elseif ($tt == 'Đang giao') {
            echo "<span style='padding:3px 10px; background:#ebf5fb; color:#2980b9; border:1px solid #aed6f1; border-radius:15px; font-size:12px; font-weight:bold;'>🚚 Đang giao</span>";
        } elseif ($tt == 'Hoàn tất') {
            echo "<span style='padding:3px 10px; background:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:15px; font-size:12px; font-weight:bold;'>🏆 Hoàn tất</span>";
        } elseif ($tt == 'Đã hủy') {
            echo "<span style='padding:3px 10px; background:#fbeee6; color:#c0392b; border:1px solid #f5b7b1; border-radius:15px; font-size:12px; font-weight:bold;'>✖ Đã hủy</span>";
        } else {
            echo "<span style='padding:3px 10px; background:#f2f3f4; color:#555; border-radius:15px; font-size:12px; font-weight:bold;'>" . htmlspecialchars($tt) . "</span>";
        }
        ?>
    </p>
    <p><b>Thời gian đặt:</b> <?php echo date("d/m/Y H:i:s", strtotime($order['ngaydat'])); ?></p>
</div>

<!-- BẢNG CHI TIẾT SẢN PHẨM ĐÃ ĐẶT BÊN TRONG -->
<table style="width:100%; border-collapse:collapse; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.04); border-radius:6px; overflow:hidden;">
    <thead>
        <tr style="background:#2c3e50; color:#fff;">
            <th style="width:90px; text-align:center; padding:12px;">Hình</th>
            <th style="text-align:left; padding:12px;">Tên giày</th>
            <th style="width:100px; text-align:center; padding:12px;">Size</th>
            <th style="width:130px; text-align:right; padding:12px;">Đơn giá</th>
            <th style="width:90px; text-align:center; padding:12px;">Số lượng</th>
            <th style="width:140px; text-align:right; padding:12px;">Thành tiền</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if ($details && $details->num_rows > 0): 
            while ($d = $details->fetch_assoc()): 
                $hinh   = !empty($d['hinhanh']) ? $d['hinhanh'] : '';
                $donGia = isset($d['dongia']) ? (float)$d['dongia'] : 0;
                $size   = !empty($d['size']) ? $d['size'] : '38';
        ?>
        <tr style="border-bottom:1px solid #eee;">
            <td style="text-align:center; padding:10px;">
                <img src="img/<?php echo $hinh; ?>" width="60" height="60" style="border-radius:4px; object-fit:cover;" onerror="this.src='https://placehold.co/60x60?text=Giay'">
            </td>
            <td style="font-weight:bold; color:#333; text-align:left; padding:10px;">
                <?php echo htmlspecialchars($d['tengiay']); ?>
            </td>
            <td style="text-align:center; padding:10px;">
                <span style="display:inline-block; padding:3px 10px; background:#e8f0fe; color:#1967d2; border:1px solid #c2d7fa; border-radius:4px; font-size:13px; font-weight:bold;">
                    Size: <?php echo htmlspecialchars($size); ?>
                </span>
            </td>
            <td style="text-align:right; padding:10px;">
                <?php echo number_format($donGia, 0, ',', '.'); ?>đ
            </td>
            <td style="text-align:center; font-weight:bold; padding:10px;">
                <?php echo $d['soluong']; ?>
            </td>
            <td style="color:#f15e2d; font-weight:bold; text-align:right; padding:10px;">
                <?php echo number_format($donGia * $d['soluong'], 0, ',', '.'); ?>đ
            </td>
        </tr>
        <?php endwhile; else: ?>
        <tr>
            <td colspan="6" style="padding:30px; text-align:center; color:#888;">
                Đơn hàng này chưa có chi tiết sản phẩm nào được lưu.
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<p style="text-align:right; font-size:20px; margin-top:20px;">
    Tổng tiền thanh toán: <b style="color:#f15e2d;"><?php echo number_format($order['tongtien'], 0, ',', '.'); ?>đ</b>
</p>