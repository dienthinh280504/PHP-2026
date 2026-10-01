<?php
session_start();

/**
 * PHÂN QUYỀN TRUY CẬP (MỤC 7)
 * IDRole = 1: Admin
 * IDRole = 2: Staff (Nhân viên)
 */
if (!isset($_SESSION['user']) || ($_SESSION['user']['IDRole'] != 1 && $_SESSION['user']['IDRole'] != 2)) {
    echo "<script>alert('Bạn không có quyền truy cập!'); window.location.href='index.php';</script>";
    exit();
}

$role = $_SESSION['user']['IDRole'];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang Quản Trị - Shoe Store</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    body {
        background-color: #f4f4f4;
    }

    /* --- BANNER HEADER --- */
    .banner {
        width: 100%;
        background-color: #dfd5ca;
        overflow: hidden;
        text-align: center;
    }

    .banner img {
        width: 100%;
        max-height: 450px;
        object-fit: cover;
        display: block;
    }

    /* --- KHUNG CHIA 2 CỘT (Đồng bộ với trang chủ) --- */
    .container {
        display: flex;
        max-width: 1400px;
        margin: 30px auto;
        padding: 0 20px;
        gap: 30px;
    }

    /* --- CỘT TRÁI: MENU --- */
    .sidebar {
        width: 260px;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        height: fit-content;
    }

    .sidebar h3 {
        font-size: 18px;
        margin-bottom: 15px;
        text-transform: uppercase;
        color: #333;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }

    .sidebar ul {
        list-style: none;
    }

    .sidebar ul li {
        margin-bottom: 10px;
    }

    .sidebar ul li a {
        text-decoration: none;
        color: #555;
        font-weight: bold;
        display: block;
        padding: 12px 15px;
        border-radius: 5px;
        transition: 0.3s;
        border-left: 3px solid transparent;
    }

    .sidebar ul li a:hover {
        background-color: #f9f9f9;
        color: #000;
        border-left: 3px solid #f15e2d;
    }

    /* --- CỘT PHẢI: NỘI DUNG CHÍNH --- */
    .main-content {
        flex: 1;
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        min-height: 500px;
    }

    /* --- CSS TABLE --- */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    table,
    th,
    td {
        border: 1px solid #eee;
    }

    th,
    td {
        padding: 15px;
        text-align: center;
    }

    th {
        background-color: #f9f9f9;
        color: #333;
        text-transform: uppercase;
        font-size: 14px;
    }

    .btn {
        padding: 8px 15px;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 13px;
        font-weight: bold;
        display: inline-block;
        transition: 0.3s;
    }

    .btn-edit {
        background: #333;
    }

    .btn-edit:hover {
        background: #000;
    }

    .btn-delete {
        background: #e74c3c;
    }

    .btn-delete:hover {
        background: #c0392b;
    }

    .btn-add {
        background: #f15e2d;
        margin-bottom: 20px;
    }

    .btn-add:hover {
        background: #d94b20;
    }
    </style>
</head>

<body>

    <div class="banner">
        <img src="img/banner/banner2.jpg" alt="Banner Ananas"
            onerror="this.src='https://placehold.co/1400x400?text=Banner+Sieu+Xinh'">
    </div>

    <div class="container">

        <div class="sidebar">
            <h3>CHỨC NĂNG</h3>
            <ul>
                <li><a href="admin.php?act=product">📦 Quản lý Sản phẩm</a></li>
                <li><a href="admin.php?act=type">🏷️ Quản lý Loại giày</a></li>
                <li><a href="admin.php?act=post">📰 Quản lý Bài viết</a></li>
                <li><a href="admin.php?act=order">🧾 Xử lý Đơn hàng</a></li>
                <li><a href="admin.php?act=thongke">📊 Thống kê Đơn hàng</a></li>

                <?php if($role == 1): ?>
                <li><a href="admin.php?act=user">👥 Quản lý Khách hàng</a></li>
                <?php endif; ?>
            </ul>

            <h3 style="margin-top: 30px;">HỆ THỐNG</h3>
            <ul>
                <li><a href="index.php">🌐 Xem Trang chủ</a></li>
                <li><a href="index.php?page=logout" style="color: #e74c3c;">🚪 Đăng xuất</a></li>
            </ul>
        </div>

        <div class="main-content">
            <?php
                if (isset($_GET['act'])) {
                    $act = $_GET['act'];
                    
                   if ($act == 'product') {
                    include("View/admin_product.php");
                    }
                    elseif ($act == 'add_product' || $act == 'edit_product') {
                        include("View/admin_form_product.php");
                    }
                    elseif ($act == 'type') {
                        include("View/admin_type.php");
                    }
                    elseif ($act == 'add_type' || $act == 'edit_type') {
                        include("View/admin_form_type.php");
                    }
                    elseif ($act == 'post') {
                        include("View/admin_post.php");
                    }
                    elseif ($act == 'add_post' || $act == 'edit_post') {
                        include("View/admin_form_post.php");
                    }
                    elseif ($act == 'order') {
                        include("View/admin_order.php");
                    }
                    elseif ($act == 'order_detail') {
                        include("View/admin_order_detail.php");
                    }
                    elseif ($act == 'thongke') {
                        include("View/admin_thongke.php");
                    }
                    elseif ($act == 'user' && $role == 1) {
                        include("View/admin_user.php");
                    }
                    else {
                        echo "<h2 style='color:red;'>Bạn không có quyền thực hiện chức năng này!</h2>";
                    }
                } else {
                    echo "<div style='text-align: center; margin-top: 80px; color: #888;'>
                            <h2 style='font-size: 28px; margin-bottom: 10px; color: #333;'>CHÀO MỪNG TRỞ LẠI!</h2>
                            <p>Xin chào <b>" . $_SESSION['user']['Username'] . "</b>. Bạn đang đăng nhập với quyền <b>" . ($role == 1 ? "Admin" : "Staff") . "</b>.</p>
                          </div>";
                }
            ?>
        </div>

    </div>

</body>

</html>