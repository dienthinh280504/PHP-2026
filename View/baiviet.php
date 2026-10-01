<?php
include_once("Controller/cPost.php");
$postCtrl = new controlPost();
$posts = $postCtrl->cGetAllPosts();
?>

<h2 style="margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 10px; text-transform: uppercase;">
    BÀI VIẾT & GIỚI THIỆU
</h2>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px;">
<?php
if ($posts && $posts->num_rows > 0) {
    while ($row = $posts->fetch_assoc()) {
        $noidung_hienthi = (mb_strlen($row['noidung'], 'UTF-8') > 100)
            ? mb_substr($row['noidung'], 0, 100, 'UTF-8') . "..."
            : $row['noidung'];
?>
    <a href="index.php?page=chitietbaiviet&id=<?php echo $row['idPost']; ?>" style="text-decoration:none; color:inherit;">
        <div style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; background:#fff; transition:0.3s;"
             onmouseover="this.style.boxShadow='0 5px 15px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='none'">
            <img src="img/<?php echo $row['hinhanh']; ?>" style="width:100%; height:180px; object-fit:cover;"
                 onerror="this.src='https://placehold.co/400x250?text=Bai+Viet'">
            <div style="padding:15px;">
                <h3 style="font-size:16px; margin-bottom:10px; color:#333;"><?php echo htmlspecialchars($row['tieude']); ?></h3>
                <p style="font-size:13px; color:#777; line-height:1.5; margin-bottom:10px;"><?php echo htmlspecialchars($noidung_hienthi); ?></p>
                <p style="font-size:12px; color:#aaa;"><?php echo date("d/m/Y", strtotime($row['ngaydang'])); ?> - <?php echo htmlspecialchars($row['tacgia']); ?></p>
            </div>
        </div>
    </a>
<?php
    }
} else {
    echo "<p style='grid-column:1/-1; text-align:center; color:#888; padding:80px 0; font-style:italic;'>Chưa có bài viết nào.</p>";
}
?>
</div>