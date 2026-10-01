<?php
include_once("Controller/cCart.php");
$cartCtrl = new controlCart();

// Bắt buộc đăng nhập mới xem được giỏ hàng
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Vui lòng đăng nhập để xem giỏ hàng!'); window.location.href='index.php?page=login';</script>";
    return;
}

$username = $_SESSION['user']['Username'];

// Xử lý xóa 1 sản phẩm khỏi giỏ
if (isset($_GET['del'])) {
    $cartCtrl->cRemoveFromCart((int)$_GET['del']);
    echo "<script>window.location.href='index.php?page=giohang';</script>";
    return;
}

// Xử lý cập nhật số lượng (khi bấm nút Cập nhật)
if (isset($_POST['btnUpdate'])) {
    foreach ($_POST['soluong'] as $idCart => $sl) {
        $sl = (int)$sl;
        if ($sl < 1) $sl = 1;
        $cartCtrl->cUpdateQuantity((int)$idCart, $sl);
    }
    echo "<script>window.location.href='index.php?page=giohang';</script>";
    return;
}

$list = $cartCtrl->cGetCartByUser($username);
$tongTien = 0;
$rows = [];
if ($list && $list->num_rows > 0) {
    while ($row = $list->fetch_assoc()) {
        $rows[] = $row;
        $tongTien += $row['gia'] * $row['soluong'];
    }
}
?>

<h2 style="margin-bottom:25px;">GIỎ HÀNG CỦA BẠN</h2>

<?php if (count($rows) == 0): ?>
    <p style="color:#888; padding:40px 0; text-align:center;">
        Giỏ hàng của bạn đang trống. <a href="index.php">Tiếp tục mua sắm</a>
    </p>
<?php else: ?>

<form method="post" action="">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="background:#f9f9f9;">
                <th style="border:1px solid #eee; padding:12px;">Hình</th>
                <th style="border:1px solid #eee; padding:12px; text-align:left;">Tên giày</th>
                <th style="border:1px solid #eee; padding:12px; width:80px; text-align:center;">Size</th>
                <th style="border:1px solid #eee; padding:12px; text-align:right;">Đơn giá</th>
                <th style="border:1px solid #eee; padding:12px; width:100px; text-align:center;">Số lượng</th>
                <th style="border:1px solid #eee; padding:12px; text-align:right;">Thành tiền</th>
                <th style="border:1px solid #eee; padding:12px; text-align:center;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
            <tr>
                <td style="border:1px solid #eee; padding:12px; text-align:center;">
                    <img src="img/<?php echo $r['hinhanh']; ?>" width="60" style="border-radius:4px;"
                         onerror="this.src='https://placehold.co/60x60?text=Giay'">
                </td>
                <td style="border:1px solid #eee; padding:12px; font-weight:500;">
                    <?php echo htmlspecialchars($r['tengiay']); ?>
                </td>
                <td style="border:1px solid #eee; padding:12px; text-align:center;">
                    <span style="display:inline-block; padding:4px 10px; background:#f0f0f0; border-radius:4px; font-weight:bold; font-size:13px; color:#333;">
                        <?php echo htmlspecialchars($r['size'] ?? '38'); ?>
                    </span>
                </td>
                <td style="border:1px solid #eee; padding:12px; text-align:right;">
                    <?php echo number_format($r['gia'], 0, ',', '.'); ?>đ
                </td>
                <td style="border:1px solid #eee; padding:12px; text-align:center;">
                    <input type="number" name="soluong[<?php echo $r['idCart']; ?>]"
                           value="<?php echo $r['soluong']; ?>" min="1"
                           style="width:60px; padding:6px; text-align:center; border:1px solid #ddd; border-radius:4px;">
                </td>
                <td style="border:1px solid #eee; padding:12px; text-align:right; color:#f15e2d; font-weight:bold;">
                    <?php echo number_format($r['gia'] * $r['soluong'], 0, ',', '.'); ?>đ
                </td>
                <td style="border:1px solid #eee; padding:12px; text-align:center;">
                    <a href="index.php?page=giohang&del=<?php echo $r['idCart']; ?>"
                       onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')"
                       style="color:#e74c3c; font-weight:bold; text-decoration:none;">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:25px; flex-wrap:wrap; gap:15px;">
        <button type="submit" name="btnUpdate"
            style="padding:12px 25px; background:#333; color:#fff; border:none; border-radius:5px; cursor:pointer; font-weight:bold;">
            Cập nhật giỏ hàng
        </button>

        <div style="text-align:right;">
            <p style="font-size:18px; margin-bottom:15px;">
                Tổng cộng: <b style="color:#f15e2d; font-size:24px;"><?php echo number_format($tongTien, 0, ',', '.'); ?>đ</b>
            </p>
         <a href="index.php?page=datHang"
        style="display:inline-block; padding:14px 35px; background:#f15e2d; color:#fff; text-decoration:none; font-weight:bold; border-radius:5px; text-transform:uppercase;">
            Tiến hành đặt hàng
        </a>
        </div>
    </div>
</form>

<?php endif; ?>