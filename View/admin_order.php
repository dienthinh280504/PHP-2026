<?php
include_once("Controller/cOrder.php");
$orderCtrl = new controlOrder();

// Xử lý Admin cập nhật trạng thái đơn hàng
if (isset($_POST['btnCapNhatTrangThai'])) {
    $idOrder = (int)$_POST['idOrder'];
    $trangThaiMoi = trim($_POST['trangthai']);
    
    $kq = $orderCtrl->cUpdateStatus($idOrder, $trangThaiMoi);
    if ($kq) {
        echo "<script>alert('Cập nhật trạng thái đơn hàng #$idOrder thành công!'); window.location.href='admin.php?act=order';</script>";
        return;
    } else {
        echo "<script>alert('Có lỗi khi cập nhật!');</script>";
    }
}

$orders = $orderCtrl->cGetAllOrders();
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2 style="font-size:22px; text-transform:uppercase; color:#222;">XỬ LÝ ĐƠN HÀNG</h2>
    <span style="font-size:13px; color:#666;">Tổng số: <b><?php echo ($orders) ? $orders->num_rows : 0; ?></b> đơn hàng</span>
</div>

<table style="width:100%; border-collapse:collapse; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.04); border-radius:6px; overflow:hidden;">
    <thead>
        <tr style="background:#2c3e50; color:#fff; text-align:left;">
            <th style="padding:12px; width:110px; text-align:center;">Mã ĐH</th>
            <th style="padding:12px; width:220px;">Khách hàng</th>
            <th style="padding:12px;">Sản phẩm đặt mua (Kèm Size)</th>
            <th style="padding:12px; width:120px; text-align:right;">Tổng tiền</th>
            <th style="padding:12px; width:150px; text-align:center;">Trạng thái</th>
            <th style="padding:12px; width:170px; text-align:center;">Cập nhật</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($orders && $orders->num_rows > 0): ?>
            <?php while ($o = $orders->fetch_assoc()): ?>
            <tr style="border-bottom:1px solid #eee; vertical-align:middle;">
                <!-- CỘT MÃ ĐƠN HÀNG & NÚT XEM CHI TIẾT -->
                <td style="padding:12px; text-align:center;">
                    <b style="font-size:16px; color:#2c3e50;">#<?php echo $o['idOrder']; ?></b>
                    <div style="font-size:11px; color:#888; margin-top:3px;">
                        <?php echo date("d/m/Y", strtotime($o['ngaydat'])); ?>
                    </div>
                    <div style="margin-top:8px;">
                        <a href="admin.php?act=order_detail&id=<?php echo $o['idOrder']; ?>" 
                           style="display:inline-block; font-size:11px; color:#1967d2; text-decoration:none; font-weight:bold; background:#e8f0fe; padding:4px 8px; border-radius:4px; border:1px solid #c2d7fa;">
                            🔍 Xem chi tiết
                        </a>
                    </div>
                </td>

                <!-- THÔNG TIN KHÁCH HÀNG -->
                <td style="padding:12px; text-align:left;">
                    <div style="font-weight:bold; color:#111;"><?php echo htmlspecialchars($o['hoten']); ?></div>
                    <div style="font-size:12px; color:#e67e22; font-weight:600;"><?php echo htmlspecialchars($o['sdt']); ?></div>
                    <div style="font-size:12px; color:#666; margin-top:2px; line-height:1.4;">
                        <?php echo htmlspecialchars($o['diachi']); ?>
                    </div>
                    <?php if (!empty($o['ghichu'])): ?>
                    <div style="font-size:11px; color:#888; font-style:italic; margin-top:3px;">
                        📝 <?php echo htmlspecialchars($o['ghichu']); ?>
                    </div>
                    <?php endif; ?>
                </td>

                <!-- DANH SÁCH MÓN & SIZE NHANH TRÊN BẢNG -->
                <td style="padding:12px; text-align:left;">
                    <?php
                    $details = $orderCtrl->cGetOrderDetails($o['idOrder']);
                    if ($details && $details->num_rows > 0) {
                        while ($d = $details->fetch_assoc()) {
                            $donGia = isset($d['dongia']) ? (float)$d['dongia'] : 0;
                            $size   = !empty($d['size']) ? $d['size'] : '38';
                    ?>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:5px; padding-bottom:5px; border-bottom:1px dashed #f0f0f0;">
                            <div>
                                <span style="font-weight:600; color:#333;"><?php echo htmlspecialchars($d['tengiay']); ?></span>
                                <span style="display:inline-block; margin-left:6px; padding:1px 6px; background:#e8f0fe; color:#1967d2; border:1px solid #c2d7fa; border-radius:3px; font-size:11px; font-weight:bold;">
                                    Size: <?php echo htmlspecialchars($size); ?>
                                </span>
                                <span style="color:#666; font-size:12px; margin-left:4px;">x<?php echo $d['soluong']; ?></span>
                            </div>
                            <span style="font-size:12px; color:#555;">
                                <?php echo number_format($donGia * $d['soluong'], 0, ',', '.'); ?>đ
                            </span>
                        </div>
                    <?php
                        }
                    } else {
                        echo "<span style='color:#999; font-size:12px;'>Chưa có món nào</span>";
                    }
                    ?>
                </td>

                <!-- TỔNG TIỀN -->
                <td style="padding:12px; text-align:right;">
                    <b style="color:#f15e2d; font-size:15px;">
                        <?php echo number_format($o['tongtien'], 0, ',', '.'); ?>đ
                    </b>
                </td>

                <!-- BADGE TRẠNG THÁI -->
                <td style="padding:12px; text-align:center;">
                    <?php 
                    $tt = trim($o['trangthai']);
                    if ($tt == 'Đã thanh toán') {
                        echo "<span style='display:inline-block; padding:4px 10px; background:#e8f8f5; color:#16a085; border:1px solid #a3e4d7; border-radius:15px; font-size:12px; font-weight:bold;'>✔ Đã thanh toán</span>";
                    } elseif ($tt == 'Chưa thanh toán' || $tt == 'Chờ xử lý') {
                        echo "<span style='display:inline-block; padding:4px 10px; background:#fef9e7; color:#d4ac0d; border:1px solid #f9e79f; border-radius:15px; font-size:12px; font-weight:bold;'>⏳ Chưa thanh toán</span>";
                    } elseif ($tt == 'Chờ xác nhận') {
                        echo "<span style='display:inline-block; padding:4px 10px; background:#f4f6f7; color:#5d6d7e; border:1px solid #d5dbdb; border-radius:15px; font-size:12px; font-weight:bold;'>📋 Chờ xác nhận</span>";
                    } elseif ($tt == 'Đang giao') {
                        echo "<span style='display:inline-block; padding:4px 10px; background:#ebf5fb; color:#2980b9; border:1px solid #aed6f1; border-radius:15px; font-size:12px; font-weight:bold;'>🚚 Đang giao</span>";
                    } elseif ($tt == 'Hoàn tất') {
                        echo "<span style='display:inline-block; padding:4px 10px; background:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:15px; font-size:12px; font-weight:bold;'>🏆 Hoàn tất</span>";
                    } elseif ($tt == 'Đã hủy') {
                        echo "<span style='display:inline-block; padding:4px 10px; background:#fbeee6; color:#c0392b; border:1px solid #f5b7b1; border-radius:15px; font-size:12px; font-weight:bold;'>✖ Đã hủy</span>";
                    } else {
                        echo "<span style='display:inline-block; padding:4px 10px; background:#f2f3f4; color:#555; border-radius:15px; font-size:12px; font-weight:bold;'>" . htmlspecialchars($tt) . "</span>";
                    }
                    ?>
                </td>

                <!-- FORM ĐỔI TRẠNG THÁI VẬN CHUYỂN -->
                <td style="padding:12px; text-align:center;">
                    <form method="post" action="" style="display:flex; gap:5px; justify-content:center; align-items:center;">
                        <input type="hidden" name="idOrder" value="<?php echo $o['idOrder']; ?>">
                        <select name="trangthai" style="padding:6px 6px; border:1px solid #ccc; border-radius:4px; font-size:12px; background:#fff; cursor:pointer;">
                            <option value="Chờ xác nhận" <?php if($tt == 'Chờ xác nhận' || $tt == 'Chờ xử lý' || $tt == 'Chưa thanh toán' || $tt == 'Đã thanh toán') echo 'selected'; ?>>Chờ xác nhận</option>
                            <option value="Đang giao" <?php if($tt == 'Đang giao') echo 'selected'; ?>>Đang giao</option>
                            <option value="Hoàn tất" <?php if($tt == 'Hoàn tất') echo 'selected'; ?>>Hoàn tất</option>
                            <option value="Đã hủy" <?php if($tt == 'Đã hủy') echo 'selected'; ?>>Đã hủy</option>
                        </select>
                        <button type="submit" name="btnCapNhatTrangThai" 
                                style="padding:6px 10px; background:#333; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:12px; font-weight:bold;"
                                onmouseover="this.style.background='#f15e2d'" onmouseout="this.style.background='#333'">
                            Lưu
                        </button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="padding:30px; text-align:center; color:#888;">Chưa có đơn hàng nào trong hệ thống.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>