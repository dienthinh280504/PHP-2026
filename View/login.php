<?php
    // Xử lý khi người dùng bấm nút Đăng nhập
    if(isset($_POST['btnLogin'])) {
        include_once("Controller/cUser.php");
        $ctrl = new controlUser();
        $ctrl->cLogin($_POST['txtUser'], $_POST['txtPass']);
    }
?>

<div
    style="max-width: 450px; margin: 40px auto; padding: 40px; background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 4px solid #f15e2d;">
    <h2 style="text-align: center; margin-bottom: 30px; font-weight: 800; font-size: 24px; text-transform: uppercase;">
        ĐĂNG NHẬP</h2>

    <form method="post" action="">
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">Tên đăng nhập:</label>
            <input type="text" name="txtUser" required placeholder="Nhập username..."
                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 15px;">
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">Mật khẩu:</label>
            <input type="password" name="txtPass" required placeholder="Nhập mật khẩu..."
                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 15px;">
        </div>

        <button type="submit" name="btnLogin"
            style="width: 100%; padding: 14px; background: #000; color: #fff; border: none; font-weight: bold; font-size: 16px; cursor: pointer; border-radius: 5px; transition: 0.3s; text-transform: uppercase;"
            onmouseover="this.style.backgroundColor='#f15e2d'" onmouseout="this.style.backgroundColor='#000'">
            ĐĂNG NHẬP
        </button>
    </form>
    <p style="text-align:center; margin-top:20px; font-size:14px; color:#666;">
    Chưa có tài khoản? <a href="index.php?page=regis" style="color:#f15e2d; font-weight:bold; text-decoration:none;">Đăng ký ngay</a>
</p>
</div>