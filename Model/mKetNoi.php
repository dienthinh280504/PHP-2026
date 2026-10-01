<?php
class clsKetNoi {
    public function mMoKetNoi() {
        $host = "localhost";
        $user = "thinh";
        $pass = "123"; 
        $db = "ananas_db";
        
        $conn = mysqli_connect($host, $user, $pass, $db);
        if (!$conn) {
            die("Lỗi kết nối CSDL: " . mysqli_connect_error());
        }
        mysqli_set_charset($conn, "utf8mb4");
        return $conn;
    }

    public function mDongKetNoi($conn) {
        mysqli_close($conn);
    }
}
?>