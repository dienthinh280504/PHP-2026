<?php
include_once("Controller/cCart.php");
include_once("Controller/cOrder.php");
include_once("Controller/cProduct.php");

$cartCtrl  = new controlCart();
$orderCtrl = new controlOrder();
$prodCtrl  = new controlProduct();

if (!isset($_SESSION['user'])) {
    echo "<script>alert('Vui lòng đăng nhập để đặt hàng!'); window.location.href='index.php?page=login';</script>";
    return;
}

$username = $_SESSION['user']['Username'];

// 1. TÍNH HẠNG THÀNH VIÊN DỰA TRÊN TỔNG SỐ ĐÔI GIÀY ĐÃ MUA THÀNH CÔNG
$totalShoesBought = $orderCtrl->cGetTongSoDoiDaMua($username);

// Xác định hạng và mức giảm giá
$hangTV = "Đồng";
$iconHang = "🥉";
$tiLeGiam = 0; // %
$mienPhiShip = false;

if ($totalShoesBought >= 20) {
    $hangTV = "Kim Cương";
    $iconHang = "💎";
    $tiLeGiam = 0.15;
    $mienPhiShip = true;
} elseif ($totalShoesBought >= 10) {
    $hangTV = "Vàng";
    $iconHang = "🥇";
    $tiLeGiam = 0.10;
} elseif ($totalShoesBought >= 5) {
    $hangTV = "Bạc";
    $iconHang = "🥈";
    $tiLeGiam = 0.05;
}

// 2. LẤY GIỎ HÀNG VÀ TÍNH TỔNG TIỀN HÀNG
$list = $cartCtrl->cGetCartByUser($username);
$rows = [];
$tongTienHang = 0;
if ($list && $list->num_rows > 0) {
    while ($row = $list->fetch_assoc()) {
        $rows[] = $row;
        $giaSp = isset($row['dongia']) ? (int)$row['dongia'] : (isset($row['gia']) ? (int)$row['gia'] : 0);
        $tongTienHang += $giaSp * (int)$row['soluong'];
    }
}

if (count($rows) == 0) {
    echo "<script>alert('Giỏ hàng trống, không thể đặt hàng!'); window.location.href='index.php?page=giohang';</script>";
    return;
}

// 3. XỬ LÝ KHI BẤM "XÁC NHẬN ĐẶT HÀNG"
if (isset($_POST['btnDatHang'])) {
    $hoten = trim($_POST['txtHoTen']);
    $sdt = trim($_POST['txtSdt']);
    $diachi = trim($_POST['txtDiaChi']);
    $khuVuc = isset($_POST['khuVucShip']) ? $_POST['khuVucShip'] : 'noithanh';
    $tinhThanh = ($khuVuc == 'noithanh') ? "TP. Hồ Chí Minh" : (isset($_POST['slcTinhThanh']) ? trim($_POST['slcTinhThanh']) : "Ngoại thành");
    $ghichu = trim($_POST['txtGhiChu']);
    $phuongThucTT = isset($_POST['phuongThucTT']) ? $_POST['phuongThucTT'] : 'cod';

    if ($hoten == "" || $sdt == "" || $diachi == "") {
        echo "<script>alert('Vui lòng nhập đầy đủ Họ tên, SĐT và Địa chỉ!');</script>";
    } else {
        // Tính toán các khoản tiền
        $tienGiam = round($tongTienHang * $tiLeGiam);
        $phiShip = ($khuVuc == 'noithanh') ? 15000 : 20000;
        if ($mienPhiShip) {
            $phiShip = 0;
        }

        $tongThanhToan = ($tongTienHang - $tienGiam) + $phiShip;
        $fullDiaChi = $diachi . ", " . $tinhThanh;
        $ghiChuDon = "Hạng: $hangTV (Giảm " . ($tiLeGiam*100) . "%). Ship: " . number_format($phiShip,0,',','.') . "đ. " . $ghichu;

        // Tạo đơn hàng
        $idOrder = $orderCtrl->cCreateOrder($username, $hoten, $sdt, $fullDiaChi, $ghiChuDon, $tongThanhToan);

        // Lưu chi tiết đơn hàng (Ép kiểu chặt chẽ để khớp bảng order_details và khóa ngoại)
        foreach ($rows as $r) {
            $idSanPham = (int)$r['idSP'];
            $sizeSP    = !empty($r['size']) ? trim($r['size']) : '38';
            $sl        = (int)$r['soluong'];
            $donGia    = isset($r['dongia']) ? (int)$r['dongia'] : (isset($r['gia']) ? (int)$r['gia'] : 0);

            $orderCtrl->cAddOrderDetail((int)$idOrder, $idSanPham, $sizeSP, $sl, $donGia);
        }

        // Nhánh COD (Mặc định trạng thái đơn là "Chưa thanh toán")
        if ($phuongThucTT == 'cod') {
            // Tự động trừ số lượng tồn kho theo size cho từng món
            foreach ($rows as $r) {
                $sizeSP = !empty($r['size']) ? trim($r['size']) : '38';
                $prodCtrl->cTruTonKhoTheoSize((int)$r['idSP'], $sizeSP, (int)$r['soluong']);
            }

            // Cập nhật trạng thái đơn là Chưa thanh toán
            $orderCtrl->cUpdateStatus($idOrder, "Chưa thanh toán");

            $cartCtrl->cClearCart($username);
            echo "<script>alert('Đặt hàng thành công! Đơn hàng #$idOrder (COD - Chưa thanh toán) đã được tiếp nhận.'); window.location.href='index.php?page=donhang';</script>";
            return;
        }
        // Nhánh PayOS (VietQR)
        elseif ($phuongThucTT == 'payos') {
            try {
                include_once("PayOSHelper.php");

                $clientId    = "885eb0f0-6711-4172-b076-a5e3a6fcc2da";
                $apiKey      = "e4f78678-f575-4f80-a388-dd537401331f";
                $checksumKey = "db1ebc90cd79c569ba6eeaa49f1452ac849d5c500690b616cb0abd50f777c23a";

                $payOS = new PayOSHelper($clientId, $apiKey, $checksumKey);

                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
                // Lấy đúng tên thư mục gốc trên server
                $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . "/shoe_store_NDT/";

                $returnUrl   = $baseUrl . "return.php";
                $cancelUrl   = $baseUrl . "cancel.php";
                $description = "DH" . $idOrder;

                // Danh sách món hàng kèm Size gửi sang PayOS
                $items = array_map(function($r) {
                    $dg = isset($r['dongia']) ? (int)$r['dongia'] : (isset($r['gia']) ? (int)$r['gia'] : 0);
                    return [
                        "name" => $r['tengiay'] . " (Size " . ($r['size'] ?? '38') . ")",
                        "quantity" => (int)$r['soluong'],
                        "price" => $dg
                    ];
                }, $rows);

                $checkoutUrl = $payOS->createPaymentLink(
                    (int)$idOrder,
                    (int)$tongThanhToan,
                    $description,
                    $returnUrl,
                    $cancelUrl,
                    $items
                );

                $cartCtrl->cClearCart($username);

                header("Location: " . $checkoutUrl);
                exit();

            } catch (\Throwable $e) {
                echo "<script>alert('Lỗi khởi tạo PayOS: " . addslashes($e->getMessage()) . "'); window.location.href='index.php?page=donhang';</script>";
                exit();
            }
        }
    }
}
?>

<h2 style="margin-bottom:20px;">THÔNG TIN ĐẶT HÀNG</h2>

<!-- Banner Hạng thành viên -->
<div style="background:linear-gradient(90deg, #111, #333); color:#fff; padding:15px 20px; border-radius:8px; margin-bottom:25px; display:flex; justify-content:space-between; align-items:center;">
    <div>
        <div style="font-size:13px; color:#aaa;">Hạng thành viên hiện tại:</div>
        <div style="font-size:18px; font-weight:bold; color:#f1c40f;">
            <?php echo $iconHang . " " . $hangTV; ?> 
            <span style="font-size:13px; color:#fff; font-weight:normal;">(Đã mua <?php echo $totalShoesBought; ?> đôi)</span>
        </div>
    </div>
    <div style="text-align:right;">
        <span style="background:#f15e2d; color:#fff; padding:5px 12px; border-radius:20px; font-size:13px; font-weight:bold;">
            <?php 
                if ($tiLeGiam > 0) echo "Ưu đãi giảm " . ($tiLeGiam * 100) . "%";
                else echo "Hạng chuẩn";
                if ($mienPhiShip) echo " + FreeShip";
            ?>
        </span>
    </div>
</div>

<div style="display:flex; gap:40px; flex-wrap:wrap;">

    <!-- CỘT TRÁI: FORM ĐẶT HÀNG -->
    <div style="flex:1; min-width:320px;">
        <form method="post" action="">
            <label style="display:block; font-weight:bold; margin-bottom:6px;">Họ và tên: *</label>
            <input type="text" name="txtHoTen" required
                   value="<?php echo isset($_SESSION['user']['HoTen']) ? htmlspecialchars($_SESSION['user']['HoTen']) : ''; ?>"
                   style="width:100%; padding:11px; margin-bottom:15px; border:1px solid #ddd; border-radius:5px;">

            <label style="display:block; font-weight:bold; margin-bottom:6px;">Số điện thoại: *</label>
            <input type="text" name="txtSdt" required
                   value="<?php echo isset($_POST['txtSdt']) ? htmlspecialchars($_POST['txtSdt']) : ''; ?>"
                   style="width:100%; padding:11px; margin-bottom:15px; border:1px solid #ddd; border-radius:5px;">

            <!-- KHU VỰC GIAO HÀNG -->
            <label style="display:block; font-weight:bold; margin-bottom:6px;">Khu vực giao hàng: *</label>
            <div style="display:flex; gap:15px; margin-bottom:12px;">
                <label style="flex:1; border:1px solid #ddd; padding:10px 12px; border-radius:5px; cursor:pointer; display:flex; align-items:center; gap:8px;">
                    <input type="radio" name="khuVucShip" value="noithanh" checked onchange="thayDoiKhuVuc()">
                    <div>
                        <strong>Nội thành (15.000đ)</strong>
                        <div style="font-size:11px; color:#666;">TP. Hồ Chí Minh</div>
                    </div>
                </label>
                <label style="flex:1; border:1px solid #ddd; padding:10px 12px; border-radius:5px; cursor:pointer; display:flex; align-items:center; gap:8px;">
                    <input type="radio" name="khuVucShip" value="ngoaithanh" onchange="thayDoiKhuVuc()">
                    <div>
                        <strong>Ngoại thành (20.000đ)</strong>
                        <div style="font-size:11px; color:#666;">Các tỉnh/thành phố khác</div>
                    </div>
                </label>
            </div>

            <!-- CHỌN TỈNH/THÀNH PHỐ KHI CHỌN NGOẠI THÀNH -->
            <div id="boxNgoaiThanh" style="display:none; margin-bottom:15px;">
                <label style="display:block; font-weight:bold; margin-bottom:6px;">Tỉnh / Thành phố nhận hàng: *</label>
                <select name="slcTinhThanh" id="slcTinhThanh" style="width:100%; padding:11px; border:1px solid #ddd; border-radius:5px; background:#fff; cursor:pointer;">
                    <option value="Hà Nội">Hà Nội</option>
                    <option value="Đà Nẵng">Đà Nẵng</option>
                    <option value="Hải Phòng">Hải Phòng</option>
                    <option value="Cần Thơ">Cần Thơ</option>
                    <option value="Bình Dương">Bình Dương</option>
                    <option value="Đồng Nai">Đồng Nai</option>
                    <option value="Bà Rịa - Vũng Tàu">Bà Rịa - Vũng Tàu</option>
                    <option value="Long An">Long An</option>
                    <option value="Tiền Giang">Tiền Giang</option>
                    <option value="Lâm Đồng">Lâm Đồng</option>
                    <option value="Khánh Hòa">Khánh Hòa</option>
                    <option value="Thừa Thiên Huế">Thừa Thiên Huế</option>
                    <option value="Quảng Nam">Quảng Nam</option>
                    <option value="Bình Định">Bình Định</option>
                    <option value="Nghệ An">Nghệ An</option>
                    <option value="Thanh Hóa">Thanh Hóa</option>
                    <option value="Quảng Ninh">Quảng Ninh</option>
                    <option value="Bắc Ninh">Bắc Ninh</option>
                    <option value="Tỉnh khác">Tỉnh khác</option>
                </select>
            </div>

            <!-- ĐỊA CHỈ CHI TIẾT -->
            <label style="display:block; font-weight:bold; margin-bottom:6px;">Địa chỉ nhận hàng (Số nhà, tên đường, phường/xã): *</label>
            <input type="text" name="txtDiaChi" required
                   placeholder="Ví dụ: 123 Nguyễn Huệ, Phường Bến Nghé, Quận 1"
                   style="width:100%; padding:11px; margin-bottom:15px; border:1px solid #ddd; border-radius:5px;">

            <label style="display:block; font-weight:bold; margin-bottom:6px;">Ghi chú giao hàng:</label>
            <textarea name="txtGhiChu" rows="2"
                      placeholder="Lưu ý khi giao hàng..."
                      style="width:100%; padding:11px; margin-bottom:20px; border:1px solid #ddd; border-radius:5px;"></textarea>

            <!-- PHƯƠNG THỨC THANH TOÁN -->
            <label style="display:block; font-weight:bold; margin-bottom:10px;">Phương thức thanh toán: *</label>
            <div style="margin-bottom:25px; display:flex; flex-direction:column; gap:10px;">
                <label style="display:flex; align-items:center; gap:12px; padding:12px 15px; border:1px solid #ddd; border-radius:6px; cursor:pointer; background:#fff;">
                    <input type="radio" name="phuongThucTT" value="cod" checked style="accent-color:#f15e2d; width:18px; height:18px;">
                    <div>
                        <strong style="font-size:14px;">Thanh toán khi nhận hàng (COD)</strong>
                        <div style="font-size:12px; color:#666;">Thanh toán tiền mặt cho shipper khi nhận được giày.</div>
                    </div>
                </label>

                <label style="display:flex; align-items:center; gap:12px; padding:12px 15px; border:1px solid #ddd; border-radius:6px; cursor:pointer; background:#fff;">
                    <input type="radio" name="phuongThucTT" value="payos" style="accent-color:#f15e2d; width:18px; height:18px;">
                    <div>
                        <strong style="font-size:14px;">Thanh toán trực tuyến VietQR (PayOS)</strong>
                        <div style="font-size:12px; color:#666;">Quét mã QR qua app ngân hàng, chuyển khoản tức thì không cần nhập số tài khoản.</div>
                    </div>
                </label>
            </div>

            <button type="submit" name="btnDatHang"
                style="width:100%; padding:15px; background:#f15e2d; color:#fff; border:none; font-weight:bold; font-size:16px; cursor:pointer; border-radius:6px; text-transform:uppercase;">
                Xác nhận đặt hàng
            </button>
        </form>
    </div>

    <!-- CỘT PHẢI: CHI TIẾT TÍNH TIỀN CÓ SIZE & HẠNG THÀNH VIÊN -->
    <div style="flex:1; min-width:320px; background:#fbfbfb; padding:25px; border-radius:8px; height:fit-content; border:1px solid #eee;">
        <h3 style="margin-bottom:15px; border-bottom:1px solid #ddd; padding-bottom:10px;">Đơn hàng của bạn</h3>
        
        <?php foreach ($rows as $r): 
            $dg = isset($r['dongia']) ? (int)$r['dongia'] : (isset($r['gia']) ? (int)$r['gia'] : 0);
        ?>
        <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid #eee;">
            <div>
                <span style="font-weight:500;"><?php echo htmlspecialchars($r['tengiay']); ?></span>
                <span style="font-size:12px; background:#eee; padding:2px 7px; border-radius:4px; margin-left:6px; font-weight:bold; color:#333;">
                    Size: <?php echo htmlspecialchars($r['size'] ?? '38'); ?>
                </span>
                <span style="color:#777; font-size:13px; margin-left:4px;">x<?php echo $r['soluong']; ?></span>
            </div>
            <span style="font-weight:bold;"><?php echo number_format($dg * $r['soluong'], 0, ',', '.'); ?>đ</span>
        </div>
        <?php endforeach; ?>

        <!-- TÍNH TOÁN CÁC KHOẢN -->
        <div style="margin-top:15px; font-size:14px;">
            <div style="display:flex; justify-content:space-between; padding:6px 0; color:#555;">
                <span>Tiền hàng:</span>
                <span><?php echo number_format($tongTienHang, 0, ',', '.'); ?>đ</span>
            </div>

            <div style="display:flex; justify-content:space-between; padding:6px 0; color:#27ae60;">
                <span>Ưu đãi thành viên (<?php echo $hangTV; ?> - <?php echo ($tiLeGiam*100); ?>%):</span>
                <span id="lblGiamGia">-0đ</span>
            </div>

            <div style="display:flex; justify-content:space-between; padding:6px 0; color:#555;">
                <span id="lblKhuVucText">Phí vận chuyển (TP. Hồ Chí Minh):</span>
                <span id="lblPhiShip">15.000đ</span>
            </div>

            <div style="display:flex; justify-content:space-between; padding-top:15px; margin-top:10px; border-top:2px solid #ddd; font-weight:bold; font-size:18px;">
                <span>Tổng cộng:</span>
                <span style="color:#f15e2d;" id="lblTongCong">0đ</span>
            </div>
        </div>
    </div>

</div>

<!-- JAVASCRIPT ĐIỀU KHIỂN KHU VỰC VÀ TÍNH TIỀN -->
<script>
var tongTienHang = <?php echo $tongTienHang; ?>;
var tiLeGiam = <?php echo $tiLeGiam; ?>;
var mienPhiShip = <?php echo $mienPhiShip ? 'true' : 'false'; ?>;

function thayDoiKhuVuc() {
    var khuVuc = document.querySelector('input[name="khuVucShip"]:checked').value;
    var boxNgoaiThanh = document.getElementById('boxNgoaiThanh');
    var lblKhuVucText = document.getElementById('lblKhuVucText');

    if (khuVuc === 'noithanh') {
        boxNgoaiThanh.style.display = 'none';
        lblKhuVucText.innerText = "Phí vận chuyển (TP. Hồ Chí Minh):";
    } else {
        boxNgoaiThanh.style.display = 'block';
        lblKhuVucText.innerText = "Phí vận chuyển (Ngoại thành):";
    }

    capNhatGia();
}

function capNhatGia() {
    var khuVuc = document.querySelector('input[name="khuVucShip"]:checked').value;
    var phiShip = (khuVuc === 'noithanh') ? 15000 : 20000;
    
    if (mienPhiShip) {
        phiShip = 0;
    }

    var giamGia = Math.round(tongTienHang * tiLeGiam);
    var tongCong = (tongTienHang - giamGia) + phiShip;

    document.getElementById('lblGiamGia').innerText = "-" + giamGia.toLocaleString('vi-VN') + "đ";
    
    if (mienPhiShip) {
        document.getElementById('lblPhiShip').innerHTML = "<span style='text-decoration:line-through; color:#999;'>" + (khuVuc === 'noithanh' ? '15.000đ' : '20.000đ') + "</span> <b style='color:#27ae60;'>Miễn phí</b>";
    } else {
        document.getElementById('lblPhiShip').innerText = phiShip.toLocaleString('vi-VN') + "đ";
    }

    document.getElementById('lblTongCong').innerText = tongCong.toLocaleString('vi-VN') + "đ";
}

// Khởi chạy tính toán khi vừa vào trang
thayDoiKhuVuc();
</script>