<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Shoe Store - NĐT</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    body {
        background: #f4f4f4;
    }

    .banner img {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        display: block;
    }

    .container {
        display: flex;
        max-width: 1400px;
        margin: 30px auto;
        padding: 0 20px;
        gap: 30px;
    }

    .sidebar {
        width: 260px;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        height: fit-content;
    }

    .sidebar h3 {
        font-size: 16px;
        margin: 20px 0 10px;
        border-bottom: 2px solid #000;
        padding-bottom: 5px;
        text-transform: uppercase;
    }

    .sidebar ul {
        list-style: none;
    }

    .sidebar ul li a {
        text-decoration: none;
        color: #555;
        font-weight: bold;
        display: block;
        padding: 10px;
        transition: 0.3s;
    }

    .sidebar ul li a:hover {
        color: #f15e2d;
        background: #f9f9f9;
    }

    .main-content {
        flex: 1;
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        min-height: 600px;
    }

    .search-box {
        display: flex;
        margin-bottom: 10px;
    }

    .search-box input {
        flex: 1;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px 0 0 4px;
    }

    .search-box button {
        padding: 8px 12px;
        background: #333;
        color: #fff;
        border: none;
        cursor: pointer;
        border-radius: 0 4px 4px 0;
    }
    </style>
</head>

<body>
    <div class="banner">
    <img src="img/banner/banner2.jpg" 
         alt="Banner cửa hàng giày"
         onerror="this.onerror=null; this.src='https://placehold.co/1400x400?text=Banner+Shoe+Store';">
</div>
    <div class="container">
        <div class="sidebar">
            <h3>TÌM KIẾM</h3>
            <form action="index.php" method="get" class="search-box">
                <input type="text" name="search" placeholder="Nhập tên giày...">
                <button type="submit">🔍</button>
            </form>

           <h3>DANH MỤC</h3>
                <ul>
                    <li><a href="index.php">👟 Tất cả sản phẩm</a></li>

                    <?php
                    include_once("Controller/cProduct.php");

                    $ctrl = new controlProduct();
                    $types = $ctrl->cGetAllType();

                    if ($types && $types->num_rows > 0) {
                        while ($t = $types->fetch_assoc()) {
                            echo "<li><a href='index.php?idType=" . $t['idType'] . "'>» " . $t['typeName'] . "</a></li>";
                        }
                    }
                    ?>
                </ul>

               <h3>THÔNG TIN</h3>
                <ul>
                    <li><a href="index.php?page=baiviet">📰 Bài viết & Giới thiệu</a></li>
                    <li><a href="index.php?page=chinhsach">📋 Chính sách</a></li>
                </ul>

            <h3>TÀI KHOẢN</h3>
                <ul>
    <?php if(!isset($_SESSION['user'])): ?>
    <li><a href="index.php?page=login">🔑 Đăng nhập</a></li>
    <?php else: ?>
    <li><a href="index.php?page=giohang">🛒 Giỏ hàng</a></li>
    <li><a href="index.php?page=donhang">📦 Đơn hàng của tôi</a></li>
    <?php if($_SESSION['user']['IDRole'] == 1 || $_SESSION['user']['IDRole'] == 2): ?>
    <li><a href="admin.php" style="color: #2980b9;">⚙️ TRANG QUẢN TRỊ</a></li>
    <?php endif; ?>
    <li><a href="index.php?page=logout" style="color: red;">🚪 Đăng xuất
            (<?php echo $_SESSION['user']['Username']; ?>)</a></li>
    <?php endif; ?>
            </ul>
        </div>

        <div class="main-content">
            <?php
            $page = isset($_GET['page']) ? $_GET['page'] : '';
            if($page == 'login') include("View/login.php");
            elseif($page == 'logout') include("View/logout.php");
            elseif($page == 'regis') include("View/regis.php");
            elseif($page == 'chitiet') include("View/chitietsp.php");
            elseif($page == 'giohang') include("View/giohang.php");
            elseif($page == 'datHang' || $page == 'checkout') include("View/datHang.php");
            elseif($page == 'donhang') include("View/lichsudonhang.php");
            elseif($page == 'baiviet') include("View/baiviet.php");
            elseif($page == 'chitietbaiviet') include("View/chitietbaiviet.php");
            elseif($page == 'chinhsach') include("View/chinhsach.php");
            else include("View/dssp.php");
            ?>
        </div>
    </div>
</body>

</html>