<?php
include_once("Controller/cProduct.php");
$ctrl = new controlProduct();
$isEdit = isset($_GET['id']) ? true : false;
$row = null;

if ($isEdit) {
    $res = $ctrl->cGetTypeById($_GET['id']);
    $row = $res->fetch_assoc();
}

if (isset($_POST['btnSave'])) {
    $tenLoai = trim($_POST['txtTenLoai']);

    if ($tenLoai == "") {
        echo "<script>alert('Vui lòng nhập tên loại giày!');</script>";
    } else {
        if ($isEdit) {
            $ctrl->cUpdateType($_GET['id'], $tenLoai);
            echo "<script>alert('Cập nhật thành công!'); window.location.href='admin.php?act=type';</script>";
        } else {
            $ctrl->cAddType($tenLoai);
            echo "<script>alert('Thêm loại giày thành công!'); window.location.href='admin.php?act=type';</script>";
        }
    }
}
?>

<h2><?php echo $isEdit ? "CHỈNH SỬA LOẠI GIÀY" : "THÊM LOẠI GIÀY MỚI"; ?></h2>
<form action="" method="post" style="margin-top:20px; line-height:2; max-width:500px;">
    Tên loại giày: <br>
    <input type="text" name="txtTenLoai" required
        value="<?php echo $isEdit ? htmlspecialchars($row['typeName']) : ''; ?>"
        style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ddd; border-radius:5px;"> <br>

    <button type="submit" name="btnSave" class="btn btn-add">
        <?php echo $isEdit ? "LƯU THAY ĐỔI" : "THÊM LOẠI"; ?>
    </button>
    <a href="admin.php?act=type" style="margin-left:10px; color:#666;">Hủy bỏ</a>
</form>