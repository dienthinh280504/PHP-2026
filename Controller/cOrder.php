<?php
include_once("Model/mOrder.php");

class controlOrder {
    public function cCreateOrder($username, $hoten, $sdt, $diachi, $ghichu, $tongtien) {
        $m = new modelOrder();
        return $m->mCreateOrder($username, $hoten, $sdt, $diachi, $ghichu, $tongtien);
    }

        public function cAddOrderDetail($idOrder, $idSP, $size, $soluong, $gia) {
        $m = new modelOrder();
        return $m->mAddOrderDetail($idOrder, $idSP, $size, $soluong, $gia);
    }


    public function cGetOrdersByUser($username) {
        $m = new modelOrder();
        return $m->mGetOrdersByUser($username);
    }

    public function cGetOrderDetails($idOrder) {
        $m = new modelOrder();
        return $m->mGetOrderDetails($idOrder);
    }

    public function cGetAllOrders() {
        $m = new modelOrder();
        return $m->mGetAllOrders();
    }

    public function cGetOrderById($idOrder) {
        $m = new modelOrder();
        return $m->mGetOrderById($idOrder);
    }

    public function cUpdateStatus($idOrder, $trangthai) {
        $m = new modelOrder();
        return $m->mUpdateStatus($idOrder, $trangthai);
    }
    public function cGetTongQuan() {
    $m = new modelOrder();
    return $m->mGetTongQuan();
    }

    public function cGetDoanhThuTheoThang() {
    $m = new modelOrder();
    return $m->mGetDoanhThuTheoThang();
    }
    public function cGetTongSoDoiDaMua($username) {
    $m = new modelOrder();
    return $m->mGetTongSoDoiDaMua($username);
}
}
?>