<?php
include_once("Controller/cProduct.php");
$ctrl = new controlProduct();

// Xử lý xóa
if (isset($_GET['del'])) {
    $ketQua = $ctrl->cDeleteType((int)$_GET['del']);
    if ($ketQua === "loi_con_san_pham") {
        echo "<script>alert('Không thể xóa! Vẫn còn sản phẩm thuộc loại này.'); window.location.href='admin.php?act=type';</script>";
    } else {
        echo "<script>alert('Đã xóa loại giày!'); window.location.href='admin.php?act=type';</script>";
    }
    return;
}
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2>QUẢN LÝ LOẠI GIÀY</h2>
    <a href="admin.php?act=add_type" class="btn btn-add" style="text-decoration:none;">+ Thêm loại giày</a>
</div>

<table>
    <thead>
        <tr>
            <th style="width:80px;">ID</th>
            <th>Tên loại</th>
            <th style="width:150px;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $list = $ctrl->cGetAllType();
    if ($list && $list->num_rows > 0) {
        while ($row = $list->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['idType']}</td>
                    <td style='font-weight:bold;'>{$row['typeName']}</td>
                    <td>
                        <a href='admin.php?act=edit_type&id={$row['idType']}' class='btn btn-edit'>Sửa</a>
                        <a href='admin.php?act=type&del={$row['idType']}' class='btn btn-delete' onclick='return confirm(\"Xóa loại này?\")'>Xóa</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3' style='padding:20px; color:#888;'>Chưa có loại giày nào.</td></tr>";
    }
    ?>
    </tbody>
</table>