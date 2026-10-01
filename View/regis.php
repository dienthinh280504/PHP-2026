<?php
    include_once("Controller/cUser.php");
    $ctrl = new controlUser();
    $loi = "";

    if (isset($_POST['btnRegis'])) {
        $username = trim($_POST['txtUser']);
        $pass = $_POST['txtPass'];
        $pass2 = $_POST['txtPass2'];

        if ($username == "" || $pass == "") {
            $loi = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!";
        } elseif (strlen($pass) < 6) {
            $loi = "Mật khẩu phải có ít nhất 6 ký tự!";
        } elseif ($pass != $pass2) {
            $loi = "Mật khẩu nhập lại không khớp!";
        } elseif ($ctrl->cKiemTraTonTai($username)) {
            $loi = "Tên đăng nhập này đã tồn tại, vui lòng chọn tên khác!";
        } else {
            $ctrl->cDangKy($username, $pass);
            echo "<script>alert('Đăng ký thành công! Vui lòng đăng nhập.'); window.location.href='index.php?page=login';</script>";
            exit();
        }
    }
?>

<div
    style="max-width: 450px; margin: 40px auto; padding: 40px; background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 4px solid #f15e2d;">
    <h2 style="text-align: center; margin-bottom: 30px; font-weight: 800; font-size: 24px; text-transform: uppercase;">
        ĐĂNG KÝ TÀI KHOẢN</h2>

    <?php if ($loi != ""): ?>
    <div style="background:#fdecea; color:#e74c3c; padding:12px; border-radius:5px; margin-bottom:20px; font-size:14px; text-align:center;">
        <?php echo $loi; ?>
    </div>
    <?php endif; ?>

    <form method="post" action="">
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">Tên đăng nhập:</label>
            <input type="text" name="txtUser" required placeholder="Nhập username..."
                value="<?php echo isset($_POST['txtUser']) ? htmlspecialchars($_POST['txtUser']) : ''; ?>"
                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 15px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">Mật khẩu:</label>
            <input type="password" name="txtPass" required placeholder="Ít nhất 6 ký tự..."
                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 15px;">
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-weight: bold; margin-bottom: 8px; color: #333;">Nhập lại mật khẩu:</label>
            <input type="password" name="txtPass2" required placeholder="Nhập lại mật khẩu..."
                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 15px;">
        </div>

        <button type="submit" name="btnRegis"
            style="width: 100%; padding: 14px; background: #000; color: #fff; border: none; font-weight: bold; font-size: 16px; cursor: pointer; border-radius: 5px; transition: 0.3s; text-transform: uppercase;"
            onmouseover="this.style.backgroundColor='#f15e2d'" onmouseout="this.style.backgroundColor='#000'">
            ĐĂNG KÝ
        </button>
    </form>

    <p style="text-align:center; margin-top:20px; font-size:14px; color:#666;">
        Đã có tài khoản? <a href="index.php?page=login" style="color:#f15e2d; font-weight:bold; text-decoration:none;">Đăng nhập ngay</a>
    </p>
</div>