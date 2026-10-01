<?php
    include_once("Controller/cProduct.php");
    $ctrl = new controlProduct();
    
    // 1. Sửa tham số nhận từ URL: idLoai -> idType
    $idType = isset($_GET['idType']) ? (int)$_GET['idType'] : 0;
    
    // 2. Xử lý chuỗi tìm kiếm an toàn
    $txt = "";
    if (isset($_GET['search'])) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $txt = mysqli_real_escape_string($conn, $_GET['search']);
        $p->mDongKetNoi($conn);
    }
    
    // 3. Gọi hàm lấy sản phẩm với tham số idType mới
    $list = $ctrl->cGetAllProduct($idType, $txt);
    
    // Thiết lập tiêu đề hiển thị
    $title = "SẢN PHẨM MỚI";
    if ($txt != "") {
        $title = "KẾT QUẢ TÌM KIẾM: '$txt'";
    } elseif ($idType > 0) {
        $title = "DANH MỤC SẢN PHẨM";
    }
?>

<?php
// Chỉ hiện "Sản phẩm bán chạy" ở trang chủ mặc định (không lọc, không tìm kiếm)
    if ($idType == 0 && $txt == "") {
        $bestSellers = $ctrl->cGetBestSeller(4);
        if ($bestSellers && $bestSellers->num_rows > 0) {
    ?>
    <h2 style="margin-bottom: 20px; color:#f15e2d; text-transform:uppercase;">🔥 Sản phẩm bán chạy</h2>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px;">
        <?php while ($row = $bestSellers->fetch_assoc()): 
            $conHangBC = $ctrl->cKiemTraConHang($row['idSP']);
        ?>
        <a href="index.php?page=chitiet&id=<?php echo $row['idSP']; ?>" style="text-decoration:none; color:inherit;">
            <div style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; text-align: center; padding-bottom: 10px; position:relative;">
                
                <?php if (!$conHangBC): ?>
                <span style="position:absolute; top:8px; left:8px; background:#666; color:#fff; font-size:11px; padding:3px 8px; border-radius:4px; font-weight:bold;">
                    Hết hàng
                </span>
                <?php elseif ($row['tongban'] > 0): ?>
                <span style="position:absolute; top:8px; left:8px; background:#f15e2d; color:#fff; font-size:11px; padding:3px 8px; border-radius:4px; font-weight:bold;">
                    Đã bán <?php echo $row['tongban']; ?>
                </span>
                <?php endif; ?>

                <img src="img/<?php echo $row['hinhanh']; ?>" style="width: 100%; aspect-ratio: 1/1; object-fit: cover; <?php echo !$conHangBC ? 'opacity:0.5;' : ''; ?>"
                    onerror="this.src='https://placehold.co/300x300?text=Giay+Dep'">
                
                <p style="font-size: 13px; margin: 8px 0 4px; padding:0 8px;"><?php echo htmlspecialchars($row['tengiay']); ?></p>
                <p style="color: #f15e2d; font-weight: bold; font-size: 14px;"><?php echo number_format($row['gia'], 0, ',', '.'); ?>đ</p>
            </div>
        </a>
        <?php endwhile; ?>
    </div>
    <?php
        }
    }
    ?>

<h2 style="margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 10px; text-transform: uppercase;">
    <?php echo $title; ?>
</h2>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px;">
    <?php
    $dem = 0; // Đếm thứ tự để đánh dấu "Mới" cho vài sản phẩm đầu tiên
    if ($list && $list->num_rows > 0) {
        while ($row = $list->fetch_assoc()) {
            $dem++;
            $conHang = $ctrl->cKiemTraConHang($row['idSP']);
            $daBan = $ctrl->cGetTongDaBan($row['idSP']);
            $laMoi = ($idType == 0 && $txt == "" && $dem <= 3); // Chỉ đánh dấu Mới ở trang mặc định, 3 sản phẩm đầu
    ?>
    <div style="border: 1px solid #eee; padding-bottom: 15px; text-align: center; border-radius: 8px; overflow: hidden; transition: 0.3s; background: #fff;"
        onmouseover="this.style.boxShadow='0 5px 15px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='none'">

        <!-- Khối chứa hình ảnh và các Badge -->
        <div style="position:relative;">
            <img src="img/<?php echo $row['hinhanh']; ?>" style="width: 100%; aspect-ratio: 1/1; object-fit: cover; <?php echo !$conHang ? 'opacity:0.5;' : ''; ?>"
                onerror="this.src='https://placehold.co/400x400?text=Giay+Dep'">

            <?php if (!$conHang): ?>
            <span style="position:absolute; top:8px; left:8px; background:#666; color:#fff; font-size:11px; padding:3px 10px; border-radius:4px; font-weight:bold;">
                Hết hàng
            </span>
            <?php elseif ($laMoi): ?>
            <span style="position:absolute; top:8px; left:8px; background:#2980b9; color:#fff; font-size:11px; padding:3px 10px; border-radius:4px; font-weight:bold;">
                Mới
            </span>
            <?php elseif ($daBan > 0): ?>
            <span style="position:absolute; top:8px; left:8px; background:#f15e2d; color:#fff; font-size:11px; padding:3px 10px; border-radius:4px; font-weight:bold;">
                Bán chạy
            </span>
            <?php endif; ?>
        </div>

        <div style="padding: 15px;">
            <h3 style="font-size: 14px; height: 40px; overflow: hidden; margin: 10px 0; color: #333;">
                <?php echo $row['tengiay']; ?>
            </h3>

            <p style="color: #f15e2d; font-weight: bold; font-size: 18px;">
                <?php echo number_format($row['gia'], 0, ',', '.'); ?>đ
            </p>

            <a href="index.php?page=chitiet&id=<?php echo $row['idSP']; ?>" style="text-decoration:none;">
                <button
                    style="margin-top: 15px; padding: 10px 25px; background: #000; color: #fff; border: none; cursor: pointer; font-weight: bold; font-size: 12px; border-radius: 4px; text-transform: uppercase;"
                    onmouseover="this.style.background='#f15e2d'" onmouseout="this.style.background='#000'">
                    Chi tiết
                </button>
            </a>
        </div>
    </div>
    <?php
        }
    } else {
        echo "<p style='grid-column: 1/-1; text-align: center; color: #888; padding: 80px 0; font-style: italic;'>
                Hiện chưa có sản phẩm nào trong mục này.
              </p>";
    }
    ?>
</div>