
<?php
    include_once("Controller/cProduct.php");
    include_once("Model/mKetNoi.php");

    $ctrl = new controlProduct();

    // Danh sách size cố định
    $dsSizeCoDinh = [36, 37, 38, 39, 40, 41, 42, 43, 44];

    $isEdit = isset($_GET['id']) ? true : false;
    $row = null;

    if ($isEdit) {
        $res = $ctrl->cGetProductById($_GET['id']);
        $row = $res ? $res->fetch_assoc() : null;
    }

    // Xử lý khi nhấn Lưu
    if (isset($_POST['btnSave'])) {

        $ten = $_POST['txtTen'];
        $gia = $_POST['txtGia'];
        $loai = $_POST['txtLoai'];
        $mota = $_POST['txtMota'];
        $hinh = $_FILES['fHinh']['name'];

        // Xử lý upload ảnh chính
        if ($hinh != "") {
            $duoiFile = pathinfo($hinh, PATHINFO_EXTENSION);
            $hinh = "sp_" . time() . "_" . rand(1000, 9999) . "." . $duoiFile;

            move_uploaded_file(
                $_FILES['fHinh']['tmp_name'],
                "img/" . $hinh
            );
        }

        // Xử lý upload nhiều ảnh phụ
        $anhPhu = [];

        if (isset($_FILES['fHinhPhu']) &&
            count($_FILES['fHinhPhu']['name']) > 0) {

            foreach ($_FILES['fHinhPhu']['name'] as $key => $tenFileGoc) {

                if ($tenFileGoc != "") {
                    $duoiFile = pathinfo($tenFileGoc, PATHINFO_EXTENSION);

                    $tenFileMoi = "sp_" . time() . "_" .
                                  rand(1000, 9999) . "_" .
                                  $key . "." . $duoiFile;

                    move_uploaded_file(
                        $_FILES['fHinhPhu']['tmp_name'][$key],
                        "img/" . $tenFileMoi
                    );

                    $anhPhu[] = $tenFileMoi;
                }
            }
        }

        // Xử lý lưu tồn kho theo size (nếu form có gửi)
        $sizeQty = [];

        if (isset($_POST['soluong_size'])) {
            foreach ($dsSizeCoDinh as $sz) {
                $sizeQty[$sz] = isset($_POST['soluong_size'][$sz])
                    ? (int)$_POST['soluong_size'][$sz]
                    : 0;
            }
        }

        // Nếu đang chỉnh sửa sản phẩm
        if ($isEdit) {

            $id = $_GET['id'];

            // Cập nhật thông tin sản phẩm
            $ctrl->cUpdateProduct(
                $id,
                $ten,
                $gia,
                $hinh,
                $loai,
                $mota
            );

            // Lưu tồn kho theo size
            if (count($sizeQty) > 0) {
                $ctrl->cSaveSizes($id, $sizeQty);
            }

            // Cập nhật ảnh phụ nếu có ảnh mới
            if (count($anhPhu) > 0) {
                $ctrl->cDeleteImagesBySP($id);

                foreach ($anhPhu as $idx => $tenAnh) {
                    $ctrl->cAddImage($id, $tenAnh, $idx);
                }
            }

            echo "<script>
                    alert('Cập nhật thành công!');
                    window.location.href='admin.php?act=product';
                  </script>";
            exit();

        } else {

            // Thêm sản phẩm mới
            $ctrl->cAddProduct(
                $ten,
                $gia,
                $hinh,
                $loai,
                $mota
            );

            // Lấy ID sản phẩm vừa thêm
            $ketNoi = new clsKetNoi();
            $conn2 = $ketNoi->mMoKetNoi();

            $idMoi = $conn2->insert_id;

            $ketNoi->mDongKetNoi($conn2);

            // Lưu tồn kho theo size nếu form có gửi
            if (count($sizeQty) > 0) {
                $ctrl->cSaveSizes($idMoi, $sizeQty);
            }

            // Lưu ảnh phụ
            foreach ($anhPhu as $idx => $tenAnh) {
                $ctrl->cAddImage($idMoi, $tenAnh, $idx);
            }

            echo "<script>
                    alert('Thêm sản phẩm thành công!');
                    window.location.href='admin.php?act=product';
                  </script>";
            exit();
        }
    }
?>

<h2>
    <?php echo $isEdit ? "CHỈNH SỬA SẢN PHẨM" : "THÊM SẢN PHẨM MỚI"; ?>
</h2>

<form action="" method="post" enctype="multipart/form-data"
      style="margin-top:20px; line-height:2;">

    <label>Hình ảnh chính:</label><br>

    <?php
        if ($isEdit && !empty($row['hinhanh'])) {
            echo "<img src='img/" . htmlspecialchars($row['hinhanh']) .
                 "' width='100'><br>";
        }
    ?>

    <input type="file" name="fHinh"
           <?php echo $isEdit ? '' : 'required'; ?>>

    <br><br>

    <label>Hình ảnh phụ (các góc khác, có thể chọn nhiều ảnh cùng lúc):</label><br>

    <input type="file" name="fHinhPhu[]" multiple accept="image/*">

    <br><br>

    <label>Tên giày:</label><br>

    <input type="text" name="txtTen"
        value="<?php echo $isEdit ? htmlspecialchars($row['tengiay']) : ''; ?>"
        required
        style="width:100%; padding:8px; margin-bottom:10px;">

    <br>

    <label>Giá bán:</label><br>

    <input type="number" name="txtGia"
        value="<?php echo $isEdit ? $row['gia'] : ''; ?>"
        required
        style="width:100%; padding:8px; margin-bottom:10px;">

    <br>

    <label>Loại:</label><br>

    <select name="txtLoai"
            style="width:100%; padding:8px; margin-bottom:10px;">

        <?php
            $types = $ctrl->cGetAllType();

            if ($types && $types->num_rows > 0) {
                while ($t = $types->fetch_assoc()) {

                    $selected = (
                        $isEdit &&
                        $row['idType'] == $t['idType']
                    ) ? 'selected' : '';

                    echo "<option value='" . htmlspecialchars($t['idType']) .
                         "' $selected>" .
                         htmlspecialchars($t['typeName']) .
                         "</option>";
                }
            } else {
                echo "<option value=''>-- Chưa có loại giày, vui lòng thêm loại trước --</option>";
            }
        ?>

    </select>

    <br>

    <label>Mô tả sản phẩm:</label><br>

    <textarea name="txtMota" id="txtmota" rows="5"
        style="width:100%; padding:8px; margin-bottom:10px;"><?php
            echo $isEdit ? htmlspecialchars($row['mota']) : '';
        ?></textarea>

    <br>

    <!-- Quản lý tồn kho theo size: chỉ hiển thị khi chỉnh sửa -->
    <?php if ($isEdit): ?>

        <label>Tồn kho theo size:</label><br>

        <?php
            $sizeData = [];

            $resSize = $ctrl->cGetSizesBySP($_GET['id']);

            if ($resSize && $resSize->num_rows > 0) {
                while ($s = $resSize->fetch_assoc()) {
                    $sizeData[$s['size']] = $s['soluong'];
                }
            }
        ?>

        <table style="width:100%; margin-bottom:15px;">

            <tr>
                <?php foreach ($dsSizeCoDinh as $sz): ?>
                    <th style="padding:6px; font-size:13px;">
                        Size <?php echo $sz; ?>
                    </th>
                <?php endforeach; ?>
            </tr>

            <tr>
                <?php foreach ($dsSizeCoDinh as $sz): ?>
                    <td style="padding:6px;">

                        <input type="number"
                               name="soluong_size[<?php echo $sz; ?>]"
                               min="0"
                               value="<?php
                                   echo isset($sizeData[$sz])
                                       ? (int)$sizeData[$sz]
                                       : 0;
                               ?>"
                               style="width:100%; padding:6px;
                                      text-align:center;
                                      border:1px solid #ddd;
                                      border-radius:4px;">

                    </td>
                <?php endforeach; ?>
            </tr>

        </table>

        <p style="font-size:12px; color:#888; margin-bottom:20px;">
            Nhập số lượng còn lại cho từng size. Để 0 nếu hết hàng.
        </p>

    <?php endif; ?>

    <button type="submit" name="btnSave" class="btn btn-add">
        <?php echo $isEdit ? "LƯU THAY ĐỔI" : "THÊM SẢN PHẨM"; ?>
    </button>

    <a href="admin.php?act=product"
       style="margin-left:10px; color:#666;">
        Hủy bỏ
    </a>

</form>