<?php
include_once("Controller/cProduct.php");
include_once("Controller/cCart.php");

$ctrl = new controlProduct();
$cartCtrl = new controlCart();

// BƯỚC 1: Lấy thông tin sản phẩm
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$res = $ctrl->cGetProductById($id);
$sp = ($res && $res->num_rows > 0) ? $res->fetch_assoc() : null;

if (!$sp) {
    echo "<p style='text-align:center; color:#888; padding:60px 0;'>Không tìm thấy sản phẩm này.</p>";
    return;
}

// Kiểm tra tổng thể sản phẩm còn hàng hay không
$conHang = $ctrl->cKiemTraConHang($sp['idSP']);

// BƯỚC 2: Xử lý thêm vào giỏ hàng
if (isset($_POST['btnAddCart'])) {
    if (!isset($_SESSION['user'])) {
        echo "<script>
                alert('Vui lòng đăng nhập để mua hàng!');
                window.location.href='index.php?page=login';
              </script>";
    } elseif (!$conHang) {
        echo "<script>
                alert('Sản phẩm này hiện đã hết hàng!');
                window.location.href='index.php?page=chitiet&id=".$sp['idSP']."';
              </script>";
    } else {
        $size = isset($_POST['chonSize']) ? trim($_POST['chonSize']) : '';
        if ($size == '') {
            echo "<script>
                    alert('Vui lòng chọn kích cỡ giày!');
                    history.back();
                  </script>";
            return;
        }

        $sl = isset($_POST['txtSoLuong']) ? (int)$_POST['txtSoLuong'] : 1;
        if ($sl < 1) {
            $sl = 1;
        }

        $cartCtrl->cAddToCart(
            $_SESSION['user']['Username'],
            $sp['idSP'],
            $size,
            $sl
        );

        echo "<script>
                alert('Đã thêm sản phẩm (Size " . $size . ") vào giỏ hàng!');
                window.location.href='index.php?page=chitiet&id=" . $sp['idSP'] . "';
              </script>";
        return;
    }
}

// Lấy tên loại giày
$typeRes = $ctrl->cGetTypeById($sp['idType']);
$typeInfo = ($typeRes && $typeRes->num_rows > 0)
    ? $typeRes->fetch_assoc()
    : ['typeName' => ''];

// Danh sách size cố định
$sizeList = [36, 37, 38, 39, 40, 41, 42, 43, 44];

// Lấy tồn kho theo size
$sizeStockRes = $ctrl->cGetSizesBySP($sp['idSP']);

$sizeStock = [];
$daThietLap = false;

if ($sizeStockRes && $sizeStockRes->num_rows > 0) {
    $daThietLap = true;
    while ($rowSize = $sizeStockRes->fetch_assoc()) {
        $sizeStock[(int)$rowSize['size']] = (int)$rowSize['soluong'];
    }
}

// Chuẩn bị dữ liệu tồn kho để dùng trong JavaScript
$tonKhoJS = [];
foreach ($sizeList as $sz) {
    $tonKhoJS[$sz] = $daThietLap
        ? (isset($sizeStock[$sz]) ? (int)$sizeStock[$sz] : 0)
        : 999;
}

// Lấy danh sách ảnh phụ từ bảng product_images
$anhPhuRes = $ctrl->cGetImagesBySP($sp['idSP']);
$dsAnh = [];
if ($anhPhuRes && $anhPhuRes->num_rows > 0) {
    while ($a = $anhPhuRes->fetch_assoc()) {
        $dsAnh[] = $a['tenAnh'];
    }
}

if (count($dsAnh) == 0) {
    $dsAnh[] = $sp['hinhanh'];
}
?>

<style>
/* CSS CHO CÁC Ô CHỌN SIZE */
.size-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
}

.size-item {
    position: relative;
    user-select: none;
}

.size-item input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
    margin: 0;
    z-index: 2;
}

.size-btn-box {
    text-align: center;
    padding: 12px 0;
    background: #f8f9fa;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-weight: bold;
    font-size: 14px;
    color: #333;
    transition: all 0.2s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

/* Hover khi còn hàng */
.size-item:hover .size-btn-box:not(.out-of-stock) {
    border-color: #f15e2d;
    color: #f15e2d;
    background: #fff;
}

/* Size được chọn */
.size-item input[type="radio"]:checked + .size-btn-box {
    background: #000 !important;
    color: #fff !important;
    border-color: #000 !important;
}

/* Size hết hàng */
.size-btn-box.out-of-stock {
    background: #f1f3f5 !important;
    color: #adb5bd !important;
    border-color: #e9ecef !important;
    cursor: not-allowed !important;
}

/* Đường gạch chéo tinh tế qua ô size hết hàng */
.size-btn-box.out-of-stock::after {
    content: "";
    position: absolute;
    top: 50%;
    left: -15%;
    width: 130%;
    height: 1.5px;
    background-color: #ced4da;
    transform: rotate(-25deg);
    pointer-events: none;
}
</style>

<div style="display:grid; grid-template-columns:1.1fr 1fr; gap:50px; align-items:start;">

    <!-- CỘT TRÁI: HÌNH ẢNH SẢN PHẨM -->
    <div>
        <!-- Ảnh lớn -->
        <div style="background:#f5f5f5; border-radius:10px; overflow:hidden; aspect-ratio:1/1; margin-bottom:15px; position:relative;">
            <img
                id="mainImg"
                src="img/<?php echo htmlspecialchars($dsAnh[0]); ?>"
                onerror="this.src='https://placehold.co/700x700?text=Giay+Dep'"
                style="width:100%; height:100%; object-fit:cover; <?php echo !$conHang ? 'opacity:0.5;' : ''; ?>"
            >
        </div>

        <!-- Thumbnail -->
        <div style="display:grid; grid-template-columns:repeat(<?php echo count($dsAnh); ?>, 1fr); gap:12px;">
            <?php foreach ($dsAnh as $idx => $anh): ?>
                <div
                    class="thumb-box"
                    onclick="chonAnh(this)"
                    style="background:#f5f5f5; border-radius:8px; overflow:hidden; aspect-ratio:1/1; cursor:pointer; border:2px solid <?php echo $idx == 0 ? '#000' : 'transparent'; ?>; transition:0.2s;"
                >
                    <img
                        src="img/<?php echo htmlspecialchars($anh); ?>"
                        onerror="this.src='https://placehold.co/200x200?text=Giay+Dep'"
                        style="width:100%; height:100%; object-fit:cover; display:block;"
                    >
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- CỘT PHẢI: THÔNG TIN SẢN PHẨM -->
    <div>
        <p style="color:#f15e2d; font-weight:bold; font-size:12px; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
            <?php echo htmlspecialchars($typeInfo['typeName']); ?>
        </p>

        <h1 style="font-size:26px; font-weight:800; text-transform:uppercase; margin-bottom:15px;">
            <?php echo htmlspecialchars($sp['tengiay']); ?>
        </h1>

        <p style="font-size:22px; font-weight:bold; color:#111; margin-bottom:25px;">
            <?php echo number_format($sp['gia'], 0, ',', '.'); ?>đ
        </p>

        <p style="line-height:1.7; color:#555; font-size:14px; margin-bottom:30px; border-top:1px solid #eee; padding-top:20px;">
            <?php echo nl2br(htmlspecialchars($sp['mota'])); ?>
        </p>

        <?php if ($conHang): ?>
        <form method="post" action="">

            <!-- CHỌN SIZE -->
            <div style="margin-bottom:25px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <b style="font-size:14px;">Kích cỡ (VN/EU) *</b>
                    <span style="font-size:12px; color:#999; text-decoration:underline; cursor:pointer;">
                        Hướng dẫn chọn size
                    </span>
                </div>

                <div class="size-grid">
                    <?php foreach ($sizeList as $sz): ?>
                        <?php
                        $conHangSize = $daThietLap
                            ? (isset($sizeStock[$sz]) && $sizeStock[$sz] > 0)
                            : true;
                        $slTon = isset($sizeStock[$sz]) ? (int)$sizeStock[$sz] : 0;
                        ?>

                        <div class="size-item" title="<?php echo $conHangSize ? 'Còn ' . $slTon . ' đôi' : 'Size ' . $sz . ' (Tạm hết hàng)'; ?>">
                            <?php if ($conHangSize): ?>
                                <!-- SIZE CÒN HÀNG -->
                                <input
                                    type="radio"
                                    name="chonSize"
                                    value="<?php echo $sz; ?>"
                                    required
                                    onchange="capNhatGioiHanSoLuong(<?php echo $sz; ?>)"
                                >
                                <div class="size-btn-box">
                                    <?php echo $sz; ?>
                                </div>
                            <?php else: ?>
                                <!-- SIZE HẾT HÀNG: Gạch chéo thanh lịch, không chèn chữ đỏ -->
                                <div class="size-btn-box out-of-stock">
                                    <?php echo $sz; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- SỐ LƯỢNG -->
            <div style="display:flex; align-items:center; gap:15px; margin-bottom:25px;">
                <label style="font-weight:bold; font-size:14px;">
                    Số lượng:
                </label>
                <input
                    type="number"
                    name="txtSoLuong"
                    id="soLuongInput"
                    value="1"
                    min="1"
                    max="999"
                    style="width:70px; padding:10px; border:1px solid #ddd; border-radius:5px; text-align:center;"
                >
                <span id="tonKhoText" style="font-size:13px; color:#666; font-weight:500;"></span>
            </div>

            <!-- NÚT THÊM GIỎ HÀNG -->
            <button
                type="submit"
                name="btnAddCart"
                style="width:100%; padding:16px; background:#000; color:#fff; border:none; font-weight:bold; font-size:15px; cursor:pointer; border-radius:6px; text-transform:uppercase; letter-spacing:0.5px; transition:0.3s;"
                onmouseover="this.style.background='#f15e2d'"
                onmouseout="this.style.background='#000'"
            >
                🛒 Thêm vào giỏ hàng
            </button>

        </form>
        <?php else: ?>
            <div style="padding:16px; background:#f4f4f4; border-radius:6px; text-align:center; color:#999; font-weight:bold; text-transform:uppercase; margin-bottom:20px;">
                ⛔ Sản phẩm tạm hết hàng
            </div>
        <?php endif; ?>

        <br>
        <a href="index.php" style="color:#666; font-size:14px; text-decoration:none;">
            ← Quay lại danh sách sản phẩm
        </a>
    </div>

</div>

<!-- JAVASCRIPT -->
<script>
function chonAnh(el) {
    var src = el.querySelector('img').src;
    document.getElementById('mainImg').src = src;

    document.querySelectorAll('.thumb-box').forEach(function(box) {
        box.style.borderColor = 'transparent';
    });
    el.style.borderColor = '#000';
}

var tonKho = <?php echo json_encode($tonKhoJS); ?>;

function capNhatGioiHanSoLuong(size) {
    var soLuongInput = document.getElementById('soLuongInput');
    var tonKhoText = document.getElementById('tonKhoText');
    var conLai = parseInt(tonKho[size]) || 0;

    soLuongInput.setAttribute('max', conLai);

    if (parseInt(soLuongInput.value) > conLai) {
        soLuongInput.value = conLai;
    }
    if (conLai > 0 && parseInt(soLuongInput.value) < 1) {
        soLuongInput.value = 1;
    }
    tonKhoText.innerText = "(Còn " + conLai + " đôi trong kho)";
}

var soLuongInputEl = document.getElementById('soLuongInput');
if (soLuongInputEl) {
    soLuongInputEl.addEventListener('change', function() {
        var sizeDaChon = document.querySelector('input[name="chonSize"]:checked');
        if (!sizeDaChon) return;

        var size = sizeDaChon.value;
        var conLai = parseInt(tonKho[size]) || 0;
        var soLuong = parseInt(this.value) || 1;

        if (soLuong < 1) soLuong = 1;
        if (soLuong > conLai) soLuong = conLai;
        this.value = soLuong;
    });
}
</script>

<!-- SẢN PHẨM CÙNG LOẠI -->
<?php
$related = $ctrl->cGetAllProduct($sp['idType']);
?>
<h3 style="margin:50px 0 20px; border-top:2px solid #eee; padding-top:25px; text-transform:uppercase;">
    Sản phẩm cùng loại
</h3>

<div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:20px;">
    <?php
    if ($related && $related->num_rows > 0) {
        while ($r = $related->fetch_assoc()) {
            if ($r['idSP'] == $sp['idSP']) continue;
    ?>
        <a href="index.php?page=chitiet&id=<?php echo $r['idSP']; ?>" style="text-decoration:none; color:inherit;">
            <div style="border:1px solid #eee; border-radius:8px; overflow:hidden; text-align:center; padding-bottom:10px;">
                <img
                    src="img/<?php echo htmlspecialchars($r['hinhanh']); ?>"
                    style="width:100%; aspect-ratio:1/1; object-fit:cover;"
                    onerror="this.src='https://placehold.co/300x300?text=Giay+Dep'"
                >
                <p style="font-size:13px; margin:8px 0 4px; padding:0 8px;">
                    <?php echo htmlspecialchars($r['tengiay']); ?>
                </p>
                <p style="color:#f15e2d; font-weight:bold; font-size:14px;">
                    <?php echo number_format($r['gia'], 0, ',', '.'); ?>đ
                </p>
            </div>
        </a>
    <?php
        }
    }
    ?>
</div>