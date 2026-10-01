<?php
include_once("Controller/cPost.php");
$ctrl = new controlPost();
$isEdit = isset($_GET['id']) ? true : false;
$row = null;

if ($isEdit) {
    $res = $ctrl->cGetPostById($_GET['id']);
    $row = $res->fetch_assoc();
}

if (isset($_POST['btnSave'])) {
    $tieude = $_POST['txtTieuDe'];
    $noidung = $_POST['txtNoiDung'];
    $hinh = $_FILES['fHinh']['name'];

    if ($hinh != "") {
        move_uploaded_file($_FILES['fHinh']['tmp_name'], "img/" . $hinh);
    }

    if ($isEdit) {
        $id = $_GET['id'];
        $ctrl->cUpdatePost($id, $tieude, $noidung, $hinh);
        echo "<script>alert('Cập nhật bài viết thành công!'); window.location.href='admin.php?act=post';</script>";
    } else {
        $tacgia = $_SESSION['user']['Username'];
        $ctrl->cAddPost($tieude, $noidung, $hinh, $tacgia);
        echo "<script>alert('Thêm bài viết thành công!'); window.location.href='admin.php?act=post';</script>";
    }
}
?>

<h2><?php echo $isEdit ? "CHỈNH SỬA BÀI VIẾT" : "THÊM BÀI VIẾT MỚI"; ?></h2>
<form action="" method="post" enctype="multipart/form-data" style="margin-top:20px; line-height:2;">
    Hình ảnh: <br>
    <?php if ($isEdit) echo "<img src='img/" . $row['hinhanh'] . "' width='150'><br>"; ?>
    <input type="file" name="fHinh" <?php echo $isEdit ? '' : 'required'; ?>> <br><br>

    Tiêu đề: <br>
    <input type="text" name="txtTieuDe" value="<?php echo $isEdit ? htmlspecialchars($row['tieude']) : ''; ?>" required
        style="width:100%; padding:8px; margin-bottom:10px;"> <br>

    Nội dung: <br>
    <textarea name="txtNoiDung" rows="10" required
        style="width:100%; padding:8px; margin-bottom:10px;"><?php echo $isEdit ? htmlspecialchars($row['noidung']) : ''; ?></textarea>

    <button type="submit" name="btnSave" class="btn btn-add">
        <?php echo $isEdit ? "LƯU THAY ĐỔI" : "ĐĂNG BÀI"; ?>
    </button>
    <a href="admin.php?act=post" style="margin-left:10px; color:#666;">Hủy bỏ</a>
</form>