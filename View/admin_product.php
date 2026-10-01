<?php
    include_once("Controller/cProduct.php");
    $ctrl = new controlProduct();

    // Xử lý Xóa
    if(isset($_GET['del'])){
        $ctrl->cDeleteProduct($_GET['del']);
        echo "<script>alert('Đã xóa thành công!'); window.location.href='admin.php?act=product';</script>";
    }
?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>DANH SÁCH SẢN PHẨM</h2>
    <a href="admin.php?act=add_product" class="btn btn-add" style="text-decoration:none;">+ Thêm giày mới</a>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 50px;">ID</th>
            <th style="width: 80px;">Hình</th>
            <th>Tên giày</th>
            <th style="width: 120px;">Giá</th>
            <th style="width: 100px;">Loại</th>
            <th>Mô tả</th>
            <th style="width: 150px;">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $list = $ctrl->cGetAllProduct();
        if ($list && $list->num_rows > 0) {
            while($row = $list->fetch_assoc()){
                // Xử lý cắt bớt mô tả cho gọn bảng
                $mota = $row['mota'];
                $mota_hien_thi = (mb_strlen($mota, 'UTF-8') > 50) ? mb_substr($mota, 0, 50, 'UTF-8') . "..." : $mota;

                echo "<tr>
                        <td>{$row['idSP']}</td>
                        <td><img src='img/{$row['hinhanh']}' width='60' style='border-radius:4px;'></td>
                        <td style='font-weight:bold;'>{$row['tengiay']}</td>
                        <td style='color:#f15e2d;'>".number_format($row['gia'], 0, ',', '.')."đ</td>
                        <td>".($row['idType'] == 1 ? "Sneaker" : "Boots")."</td>
                        <td style='text-align:left; font-size:13px; color:#666;'>{$mota_hien_thi}</td>
                        <td>
                            <a href='admin.php?act=edit_product&id={$row['idSP']}' class='btn btn-edit'>Sửa</a>
                            <a href='admin.php?act=product&del={$row['idSP']}' class='btn btn-delete' onclick='return confirm(\"Bạn có chắc muốn xóa không?\")'>Xóa</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='padding: 20px; color: #888;'>Chưa có sản phẩm nào trong hệ thống.</td></tr>";
        }
        ?>
    </tbody>
</table>