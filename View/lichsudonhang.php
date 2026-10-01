<?php
include_once("Controller/cOrder.php");
$orderCtrl = new controlOrder();

if (!isset($_SESSION['user'])) {
    echo "<script>alert('Vui lòng đăng nhập!'); window.location.href='index.php?page=login';</script>";
    return;
}

$username = $_SESSION['user']['Username'];

// ===== TÍNH HẠNG THÀNH VIÊN =====
$tongDoiDaMua = $orderCtrl->cGetTongSoDoiDaMua($username);

if ($tongDoiDaMua >= 20) {
    $hang = "Kim Cương";
    $icon = "💎";
    $mauNen = "linear-gradient(135deg, #4facfe, #00f2fe)";
    $uuDai = "Giảm 15% mỗi đơn + miễn phí vận chuyển trọn đời";
} elseif ($tongDoiDaMua >= 10) {
    $hang = "Vàng";
    $icon = "🥇";
    $mauNen = "linear-gradient(135deg, #f7971e, #ffd200)";
    $uuDai = "Giảm 10% mỗi đơn + ưu tiên hỗ trợ";
} elseif ($tongDoiDaMua >= 5) {
    $hang = "Bạc";
    $icon = "🥈";
    $mauNen = "linear-gradient(135deg, #bdc3c7, #2c3e50)";
    $uuDai = "Giảm 5% mỗi đơn";
} else {
    $hang = "Đồng";
    $icon = "🥉";
    $mauNen = "linear-gradient(135deg, #7f8c8d, #95a5a6)";
    $uuDai = "Mua thêm để lên hạng và nhận ưu đãi!";
}

// Tính số đôi còn thiếu
if ($tongDoiDaMua < 5) {
    $conThieu = 5 - $tongDoiDaMua;
    $hangTiepTheo = "Bạc";
} elseif ($tongDoiDaMua < 10) {
    $conThieu = 10 - $tongDoiDaMua;
    $hangTiepTheo = "Vàng";
} elseif ($tongDoiDaMua < 20) {
    $conThieu = 20 - $tongDoiDaMua;
    $hangTiepTheo = "Kim Cương";
} else {
    $conThieu = 0;
    $hangTiepTheo = null;
}

$orders = $orderCtrl->cGetOrdersByUser($username);
?>

<!-- BANNER HẠNG THÀNH VIÊN -->
<div style="background: <?php echo $mauNen; ?>; color: #fff; padding: 25px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">
        <div>
            <span style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9;">Hạng tài khoản</span>
            <h2 style="margin: 5px 0 10px; font-size: 24px; color: #fff;"><?php echo $icon; ?> Thành viên <?php echo $hang; ?></h2>
            <p style="margin: 0; font-size: 14px; opacity: 0.95;">🎁 Ưu đãi: <?php echo $uuDai; ?></p>
        </div>
        <div style="text-align: right;">
            <p style="margin: 0; font-size: 14px;">Đã mua: <b><?php echo $tongDoiDaMua; ?></b> đôi</p>
            <?php if ($hangTiepTheo): ?>
                <p style="margin: 5px 0 0; font-size: 13px; background: rgba(0,0,0,0.2); padding: 4px 10px; border-radius: 20px;">
                    Mua thêm <b><?php echo $conThieu; ?></b> đôi để đạt hạng <b><?php echo $hangTiepTheo; ?></b>
                </p>
            <?php else: ?>
                <p style="margin: 5px 0 0; font-size: 13px; background: rgba(255,255,255,0.25); padding: 4px 10px; border-radius: 20px;">
                    👑 Bạn đã đạt hạng cao nhất!
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<h2 style="margin-bottom:25px; text-transform:uppercase;">Lịch Sử Đơn Hàng</h2>

<?php if (!$orders || $orders->num_rows == 0): ?>
    <div style="background:#fff; padding:40px; text-align:center; border-radius:8px; border:1px solid #eee;">
        <p style="color:#888; margin-bottom:15px;">Bạn chưa có đơn hàng nào.</p>
        <a href="index.php" style="display:inline-block; padding:10px 20px; background:#f15e2d; color:#fff; text-decoration:none; border-radius:5px; font-weight:bold;">Mua sắm ngay</a>
    </div>
<?php else: ?>
    <?php while ($o = $orders->fetch_assoc()): ?>
    <div style="border:1px solid #e0e0e0; border-radius:8px; padding:20px; margin-bottom:20px; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,0.03);">
        
        <!-- HEADER ĐƠN HÀNG -->
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #f0f0f0; padding-bottom:12px; margin-bottom:12px;">
            <div>
                <b style="font-size:16px; color:#2c3e50;">Đơn hàng #<?php echo $o['idOrder']; ?></b>
                <span style="color:#888; font-size:13px; margin-left:10px;">Ngày đặt: <?php echo date("d/m/Y H:i", strtotime($o['ngaydat'])); ?></span>
            </div>
            <div>
                <?php 
                $tt = trim($o['trangthai']);
                if ($tt == 'Đã thanh toán') {
                    echo "<span style='padding:4px 12px; background:#e8f8f5; color:#16a085; border:1px solid #a3e4d7; border-radius:20px; font-size:12px; font-weight:bold;'>✔ Đã thanh toán</span>";
                } elseif ($tt == 'Chưa thanh toán' || $tt == 'Chờ xử lý') {
                    echo "<span style='padding:4px 12px; background:#fef9e7; color:#d4ac0d; border:1px solid #f9e79f; border-radius:20px; font-size:12px; font-weight:bold;'>⏳ Chưa thanh toán</span>";
                } elseif ($tt == 'Đang giao') {
                    echo "<span style='padding:4px 12px; background:#ebf5fb; color:#2980b9; border:1px solid #aed6f1; border-radius:20px; font-size:12px; font-weight:bold;'>🚚 Đang giao</span>";
                } elseif ($tt == 'Hoàn tất') {
                    echo "<span style='padding:4px 12px; background:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:20px; font-size:12px; font-weight:bold;'>🏆 Hoàn tất</span>";
                } elseif ($tt == 'Đã hủy') {
                    echo "<span style='padding:4px 12px; background:#fbeee6; color:#c0392b; border:1px solid #f5b7b1; border-radius:20px; font-size:12px; font-weight:bold;'>✖ Đã hủy</span>";
                } else {
                    echo "<span style='padding:4px 12px; background:#f2f3f4; color:#555; border-radius:20px; font-size:12px; font-weight:bold;'>" . htmlspecialchars($tt) . "</span>";
                }
                ?>
            </div>
        </div>

        <!-- THÔNG TIN NGƯỜI NHẬN -->
        <div style="font-size:14px; color:#555; margin-bottom:15px; line-height:1.6;">
            <div>👤 <b>Người nhận:</b> <?php echo htmlspecialchars($o['hoten']); ?> - <b>SĐT:</b> <?php echo htmlspecialchars($o['sdt']); ?></div>
            <div>📍 <b>Địa chỉ:</b> <?php echo htmlspecialchars($o['diachi']); ?></div>
            <?php if (!empty($o['ghichu'])): ?>
                <div>📝 <b>Ghi chú:</b> <?php echo htmlspecialchars($o['ghichu']); ?></div>
            <?php endif; ?>
        </div>

        <!-- DANH SÁCH CHI TIẾT SẢN PHẨM & SIZE ĐÃ ĐẶT BÊN TRONG -->
        <div style="background:#fafafa; border-radius:6px; padding:12px 15px; border:1px solid #f0f0f0; margin-top:12px;">
            <div style="font-size:13px; font-weight:bold; color:#555; margin-bottom:10px; text-transform:uppercase;">
                Danh sách sản phẩm đã đặt:
            </div>
            <?php
            $details = $orderCtrl->cGetOrderDetails((int)$o['idOrder']);
            if ($details && $details->num_rows > 0):
                while ($d = $details->fetch_assoc()):
                    $donGia = (int)$d['dongia'];
                    $size = !empty($d['size']) ? $d['size'] : '38';
                    $hinh = !empty($d['hinhanh']) ? $d['hinhanh'] : '';
            ?>
            <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px dashed #e8e8e8;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <?php if (!empty($hinh)): ?>
                        <img src="img/<?php echo $hinh; ?>" width="55" height="55" style="border-radius:4px; object-fit:cover; border:1px solid #ddd;" onerror="this.src='https://placehold.co/55x55?text=Giay'">
                    <?php else: ?>
                        <div style="width:55px; height:55px; background:#eee; border-radius:4px; display:flex; align-items:center; justify-content:center; font-size:10px; color:#888;">No IMG</div>
                    <?php endif; ?>
                    <div>
                        <div style="font-weight:600; color:#333; font-size:14px;"><?php echo htmlspecialchars($d['tengiay']); ?></div>
                        <div style="margin-top:4px;">
                            <span style="display:inline-block; padding:2px 8px; background:#e8f0fe; color:#1967d2; border:1px solid #c2d7fa; border-radius:4px; font-size:12px; font-weight:bold;">
                                Size: <?php echo htmlspecialchars($size); ?>
                            </span>
                            <span style="color:#777; font-size:13px; margin-left:8px;">Số lượng: <b><?php echo $d['soluong']; ?></b></span>
                        </div>
                    </div>
                </div>
                <div style="text-align:right;">
                    <div style="font-weight:bold; color:#333; font-size:15px;">
                        <?php echo number_format($donGia * (int)$d['soluong'], 0, ',', '.'); ?>đ
                    </div>
                    <div style="font-size:12px; color:#888;">
                        (<?php echo number_format($donGia, 0, ',', '.'); ?>đ / đôi)
                    </div>
                </div>
            </div>
            <?php 
                endwhile;
            else:
            ?>
                <p style="color:#e74c3c; font-size:13px; margin:5px 0;">
                    ⚠️ Đơn hàng #<?php echo $o['idOrder']; ?> chưa có sản phẩm chi tiết trong bảng <code>order_details</code>.
                </p>
            <?php endif; ?>
        </div>

        <!-- TỔNG THANH TOÁN -->
        <div style="text-align:right; margin-top:12px; font-size:16px;">
            Tổng tiền: <b style="color:#f15e2d; font-size:18px;"><?php echo number_format($o['tongtien'], 0, ',', '.'); ?>đ</b>
        </div>

    </div>
    <?php endwhile; ?>
<?php endif; ?>