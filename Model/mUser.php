<?php
include_once("mKetNoi.php");

class modelUser {
    public function mLogin($username, $password) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $query = "SELECT * FROM userr WHERE Username = '$username' AND Password = '$password'";
        $result = $conn->query($query);
        $p->mDongKetNoi($conn);
        return $result;
    }
    // THÊM MỚI: Hàm lấy danh sách người dùng
    public function mGetAllUser() {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $query = "SELECT * FROM userr";
        $result = $conn->query($query);
        $p->mDongKetNoi($conn);
        return $result;
    }
        // Kiểm tra username đã tồn tại chưa
    public function mKiemTraTonTai($username) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $username = mysqli_real_escape_string($conn, $username);

        $sql = "SELECT * FROM userr WHERE Username = '$username'";
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return ($res && $res->num_rows > 0);
    }

    // Đăng ký tài khoản mới (mặc định IDRole = 0 - Khách hàng)
    public function mDangKy($username, $password) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $username = mysqli_real_escape_string($conn, $username);
        $password = mysqli_real_escape_string($conn, $password);

        $sql = "INSERT INTO userr (Username, Password, IDRole) VALUES ('$username', '$password', 0)";
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }
}
