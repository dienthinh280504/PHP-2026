<?php
include_once("Controller/cPost.php");
$postCtrl = new controlPost();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$res = $postCtrl->cGetPostById($id);
$bv = ($res && $res->num_rows > 0) ? $res->fetch_assoc() : null;

if (!$bv) {
    echo "<p style='text-align:center; color:#888; padding:60px 0;'>Không tìm thấy bài viết này.</p>";
    return;
}
?>

<div style="max-width:800px; margin:0 auto;">
    <h2 style="margin-bottom:10px;"><?php echo htmlspecialchars($bv['tieude']); ?></h2>
    <p style="color:#999; font-size:13px; margin-bottom:20px;">
        Đăng ngày <?php echo date("d/m/Y H:i", strtotime($bv['ngaydang'])); ?> bởi <b><?php echo htmlspecialchars($bv['tacgia']); ?></b>
    </p>

    <img src="img/<?php echo $bv['hinhanh']; ?>" style="width:100%; border-radius:8px; margin-bottom:25px;"
         onerror="this.src='https://placehold.co/800x400?text=Bai+Viet'">

    <div style="line-height:1.8; color:#444; font-size:15px;">
        <?php echo nl2br(htmlspecialchars($bv['noidung'])); ?>
    </div>

    <br>
    <a href="index.php?page=baiviet" style="color:#666;">← Quay lại danh sách bài viết</a>
</div>