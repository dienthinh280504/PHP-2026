<?php
include_once("Controller/cPost.php");
$ctrl = new controlPost();

// Xử lý xóa
if (isset($_GET['del'])) {
    $ctrl->cDeletePost((int)$_GET['del']);
    echo "<script>alert('Đã xóa bài viết!'); window.location.href='admin.php?act=post';</script>";
    return;
}
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2>QUẢN LÝ BÀI VIẾT</h2>
    <a href="admin.php?act=add_post" class="btn btn-add" style="text-decoration:none;">+ Thêm bài viết</a>
</div>

<table>
    <thead>
        <tr>
            <th style="width:50px;">ID</th>
            <th style="width:80px;">Hình</th>
            <th>Tiêu đề</th>
            <th style="width:120px;">Tác giả</th>
            <th style="width:130px;">Ngày đăng</th>
            <th style="width:150px;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $list = $ctrl->cGetAllPosts();
    if ($list && $list->num_rows > 0) {
        while ($row = $list->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['idPost']}</td>
                    <td><img src='img/{$row['hinhanh']}' width='60' style='border-radius:4px;' onerror=\"this.src='https://placehold.co/60x60?text=Bai+Viet'\"></td>
                    <td style='font-weight:bold; text-align:left;'>{$row['tieude']}</td>
                    <td>{$row['tacgia']}</td>
                    <td>" . date("d/m/Y", strtotime($row['ngaydang'])) . "</td>
                    <td>
                        <a href='admin.php?act=edit_post&id={$row['idPost']}' class='btn btn-edit'>Sửa</a>
                        <a href='admin.php?act=post&del={$row['idPost']}' class='btn btn-delete' onclick='return confirm(\"Bạn có chắc muốn xóa bài viết này?\")'>Xóa</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6' style='padding:20px; color:#888;'>Chưa có bài viết nào.</td></tr>";
    }
    ?>
    </tbody>
</table>