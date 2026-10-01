<?php
include_once("mKetNoi.php");

class modelOrder {
    // Tạo đơn hàng mới, trả về idOrder vừa tạo
    public function mCreateOrder($username, $hoten, $sdt, $diachi, $ghichu, $tongtien) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();

        $username = mysqli_real_escape_string($conn, $username);
        $hoten    = mysqli_real_escape_string($conn, $hoten);
        $sdt      = mysqli_real_escape_string($conn, $sdt);
        $diachi   = mysqli_real_escape_string($conn, $diachi);
        $ghichu   = mysqli_real_escape_string($conn, $ghichu);
        $tongtien = (int)$tongtien;

        $sql = "INSERT INTO orders (Username, hoten, sdt, diachi, ghichu, tongtien)
                VALUES ('$username', '$hoten', '$sdt', '$diachi', '$ghichu', $tongtien)";
        $conn->query($sql);
        $idOrder = $conn->insert_id;

        $p->mDongKetNoi($conn);
        return $idOrder;
    }

    // Thêm từng dòng chi tiết đơn hàng (khớp cột idDetail, idOrder, idSP, size, soluong, dongia)
    public function mAddOrderDetail($idOrder, $idSP, $size, $soluong, $gia) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $idOrder = (int)$idOrder;
        $idSP    = (int)$idSP;
        $size    = mysqli_real_escape_string($conn, (string)$size);
        $soluong = (int)$soluong;
        $dongia  = (int)$gia;

        $sql = "INSERT INTO order_details (idOrder, idSP, size, soluong, dongia) 
                VALUES ($idOrder, $idSP, '$size', $soluong, $dongia)";
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }

    // Lấy chi tiết đơn hàng an toàn (kết nối với bảng products lấy tên và hình ảnh)
    public function mGetOrderDetails($idOrder) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $idOrder = (int)$idOrder;

        $sql = "SELECT od.idDetail, od.idOrder, od.idSP, od.size, od.soluong, od.dongia,
                       COALESCE(p.tengiay, 'Sản phẩm giày') AS tengiay, 
                       p.hinhanh
                FROM order_details od
                LEFT JOIN products p ON od.idSP = p.idSP
                WHERE od.idOrder = $idOrder";
                
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }

    // Lấy danh sách đơn hàng của 1 khách hàng
    public function mGetOrdersByUser($username) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $username = mysqli_real_escape_string($conn, $username);

        $sql = "SELECT * FROM orders WHERE Username='$username' ORDER BY idOrder DESC";
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }

    // Lấy tất cả đơn hàng cho trang admin
    public function mGetAllOrders() {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();

        $sql = "SELECT * FROM orders ORDER BY idOrder DESC";
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }

    // Lấy 1 đơn hàng theo ID
    public function mGetOrderById($idOrder) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $idOrder = (int)$idOrder;

        $sql = "SELECT * FROM orders WHERE idOrder=$idOrder";
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }

    // Cập nhật trạng thái đơn hàng
    public function mUpdateStatus($idOrder, $trangthai) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $idOrder   = (int)$idOrder;
        $trangthai = mysqli_real_escape_string($conn, $trangthai);

        $sql = "UPDATE orders SET trangthai='$trangthai' WHERE idOrder=$idOrder";
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }

    // Thống kê tổng quan
    public function mGetTongQuan() {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();

        $sql = "SELECT
                    COUNT(*) AS tongDon,
                    COALESCE(SUM(CASE WHEN trangthai != 'Đã hủy' THEN tongtien ELSE 0 END), 0) AS tongDoanhThu,
                    COALESCE(SUM(CASE WHEN trangthai = 'Chờ xử lý' OR trangthai = 'Chưa thanh toán' THEN 1 ELSE 0 END), 0) AS choXuLy,
                    COALESCE(SUM(CASE WHEN trangthai = 'Hoàn tất' OR trangthai = 'Đã thanh toán' THEN 1 ELSE 0 END), 0) AS hoanTat,
                    COALESCE(SUM(CASE WHEN trangthai = 'Đã hủy' THEN 1 ELSE 0 END), 0) AS daHuy
                FROM orders";

        $res = $conn->query($sql);
        $row = $res->fetch_assoc();
        $p->mDongKetNoi($conn);
        return $row;
    }

    // Doanh thu theo tháng
    public function mGetDoanhThuTheoThang() {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();

        $sql = "SELECT DATE_FORMAT(ngaydat, '%m/%Y') AS thang,
                    COUNT(*) AS soDon,
                    SUM(tongtien) AS doanhThu
                FROM orders
                WHERE trangthai != 'Đã hủy'
                GROUP BY DATE_FORMAT(ngaydat, '%Y-%m')
                ORDER BY DATE_FORMAT(ngaydat, '%Y-%m') DESC";

        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }

    // Tổng số đôi giày 1 khách đã mua (chỉ tính đơn không bị hủy)
    public function mGetTongSoDoiDaMua($username) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $username = mysqli_real_escape_string($conn, $username);

        $sql = "SELECT COALESCE(SUM(od.soluong), 0) AS tongDoi
                FROM order_details od
                JOIN orders o ON od.idOrder = o.idOrder
                WHERE o.Username = '$username' AND o.trangthai != 'Đã hủy'";

        $res = $conn->query($sql);
        $row = $res->fetch_assoc();
        $p->mDongKetNoi($conn);
        return (int)$row['tongDoi'];
    }
}
?>