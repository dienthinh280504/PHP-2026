<?php
include_once("mKetNoi.php");

class modelCart {
    // Thêm sản phẩm vào giỏ (kiểm tra trùng cả idSP VÀ size)
    public function mAddToCart($username, $idSP, $size, $soluong) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();

        $username = mysqli_real_escape_string($conn, $username);
        $idSP = (int)$idSP;
        $size = mysqli_real_escape_string($conn, $size);
        $soluong = (int)$soluong;

        // Kiểm tra xem sản phẩm CÙNG SIZE đã có trong giỏ chưa
        $check = $conn->query("SELECT idCart, soluong FROM cart WHERE Username='$username' AND idSP=$idSP AND size='$size'");

        if ($check && $check->num_rows > 0) {
            $row = $check->fetch_assoc();
            $newSL = $row['soluong'] + $soluong;
            $res = $conn->query("UPDATE cart SET soluong=$newSL WHERE idCart={$row['idCart']}");
        } else {
            $res = $conn->query("INSERT INTO cart (Username, idSP, size, soluong) VALUES ('$username', $idSP, '$size', $soluong)");
        }

        $p->mDongKetNoi($conn);
        return $res;
    }

    // Lấy toàn bộ giỏ hàng của 1 user (kèm size và thông tin sản phẩm)
    public function mGetCartByUser($username) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $username = mysqli_real_escape_string($conn, $username);

        $sql = "SELECT c.idCart, c.size, c.soluong, p.idSP, p.tengiay, p.gia, p.hinhanh
                FROM cart c
                JOIN products p ON c.idSP = p.idSP
                WHERE c.Username = '$username'
                ORDER BY c.idCart DESC";
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }

    // Cập nhật số lượng 1 dòng trong giỏ
    public function mUpdateQuantity($idCart, $soluong) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $idCart = (int)$idCart;
        $soluong = (int)$soluong;

        $res = $conn->query("UPDATE cart SET soluong=$soluong WHERE idCart=$idCart");
        $p->mDongKetNoi($conn);
        return $res;
    }

    // Xóa 1 sản phẩm khỏi giỏ
    public function mRemoveFromCart($idCart) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $idCart = (int)$idCart;

        $res = $conn->query("DELETE FROM cart WHERE idCart=$idCart");
        $p->mDongKetNoi($conn);
        return $res;
    }

    // Xóa toàn bộ giỏ hàng của user (dùng sau khi đặt hàng thành công)
    public function mClearCart($username) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $username = mysqli_real_escape_string($conn, $username);

        $res = $conn->query("DELETE FROM cart WHERE Username='$username'");
        $p->mDongKetNoi($conn);
        return $res;
    }
}
?>