<?php
include_once("Model/mUser.php");

class controlUser {
   public function cLogin($username, $password) {
        $p = new modelUser();
        $res = $p->mLogin($username, $password);
        
        if($res && $res->num_rows > 0) {
            $user = $res->fetch_assoc();
            $_SESSION['user'] = $user;
            
            // PHÂN QUYỀN (MỤC 7)
            if($user['IDRole'] == 1 || $user['IDRole'] == 2) {
                // Admin hoặc Staff đều vào trang quản trị
                echo "<script>alert('Xin chào " . ($user['IDRole'] == 1 ? "Admin" : "Nhân viên") . "!'); window.location.href='admin.php';</script>";
            } else {
                // Khách hàng về trang chủ
                echo "<script>alert('Đăng nhập thành công!'); window.location.href='index.php';</script>";
            }
        } else {
            echo "<script>alert('Sai tài khoản hoặc mật khẩu!'); window.history.back();</script>";
        }
    }
        public function cKiemTraTonTai($username) {
        $p = new modelUser();
        return $p->mKiemTraTonTai($username);
    }

    public function cDangKy($username, $password) {
        $p = new modelUser();
        return $p->mDangKy($username, $password);
    }
    public function cLogout() {
        session_destroy();
        echo "<script>window.location.href='index.php';</script>";
    }

    // THÊM MỚI: Lấy danh sách người dùng truyền ra View
    public function cGetAllUser() {
        $p = new modelUser();
        return $p->mGetAllUser();
    }
}
?>