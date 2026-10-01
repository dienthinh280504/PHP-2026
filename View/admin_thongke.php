<?php
include_once("Controller/cOrder.php");
include_once("Controller/cProduct.php");
$orderCtrl = new controlOrder();
$productCtrl = new controlProduct();

$tongQuan = $orderCtrl->cGetTongQuan();
$theoThang = $orderCtrl->cGetDoanhThuTheoThang();
$topSanPham = $productCtrl->cGetBestSeller(5);
?>

<h2 style="margin-bottom:25px;">THỐNG KÊ ĐƠN HÀNG</h2>

<div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:20px; margin-bottom:35px;">
    <div style="background:#333; color:#fff; padding:20px; border-radius:8px;">
        <p style="font-size:13px; opacity:0.8;">TỔNG SỐ ĐƠN</p>
        <p style="font-size:28px; font-weight:bold;"><?php echo $tongQuan['tongDon']; ?></p>
    </div>
    <div style="background:#f15e2d; color:#fff; padding:20px; border-radius:8px;">
        <p style="font-size:13px; opacity:0.9;">TỔNG DOANH THU</p>
        <p style="font-size:22px; font-weight:bold;"><?php echo number_format($tongQuan['tongDoanhThu'], 0, ',', '.'); ?>đ</p>
    </div>
    <div style="background:#f9f9f9; border:1px solid #eee; padding:20px; border-radius:8px;">
        <p style="font-size:13px; color:#999;">CHỜ XỬ LÝ</p>
        <p style="font-size:28px; font-weight:bold; color:#e67e22;"><?php echo $tongQuan['choXuLy']; ?></p>
    </div>
    <div style="background:#f9f9f9; border:1px solid #eee; padding:20px; border-radius:8px;">
        <p style="font-size:13px; color:#999;">HOÀN TẤT</p>
        <p style="font-size:28px; font-weight:bold; color:#27ae60;"><?php echo $tongQuan['hoanTat']; ?></p>
    </div>
</div>

<h3 style="margin-bottom:15px;">Doanh thu theo tháng</h3>
<table style="margin-bottom:35px;">
    <thead>
        <tr>
            <th>Tháng</th>
            <th>Số đơn</th>
            <th>Doanh thu</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($theoThang && $theoThang->num_rows > 0): while ($t = $theoThang->fetch_assoc()): ?>
        <tr>
            <td><?php echo $t['thang']; ?></td>
            <td><?php echo $t['soDon']; ?></td>
            <td style="color:#f15e2d; font-weight:bold;"><?php echo number_format($t['doanhThu'], 0, ',', '.'); ?>đ</td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="3" style="padding:20px; color:#888;">Chưa có dữ liệu.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<h3 style="margin-bottom:15px;">Top 5 sản phẩm bán chạy nhất</h3>
<table>
    <thead>
        <tr>
            <th style="width:80px;">Hình</th>
            <th>Tên giày</th>
            <th>Giá</th>
            <th>Đã bán</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($topSanPham && $topSanPham->num_rows > 0): while ($sp = $topSanPham->fetch_assoc()): ?>
        <tr>
            <td><img src="img/<?php echo $sp['hinhanh']; ?>" width="60" style="border-radius:4px;" onerror="this.src='https://placehold.co/60x60?text=Giay'"></td>
            <td style="font-weight:bold;"><?php echo htmlspecialchars($sp['tengiay']); ?></td>
            <td><?php echo number_format($sp['gia'], 0, ',', '.'); ?>đ</td>
            <td style="font-weight:bold; color:#f15e2d;"><?php echo $sp['tongban']; ?></td>
        </tr>
        <?php endwhile; endif; ?>
    </tbody>
</table>