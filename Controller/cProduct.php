<?php
include_once("Model/mProduct.php");

class controlProduct {
    public function cGetAllProduct($idType = 0, $search = "") {
        $p = new modelProduct();
        return $p->mGetAllProduct($idType, $search);
    }

    public function cGetAllType() {
        $p = new modelProduct();
        return $p->mGetAllType();
    }

    public function cGetProductById($id) {
        $p = new modelProduct();
        return $p->mGetProductById($id);
    }

    public function cAddProduct($ten, $gia, $hinh, $idType, $mota) {
        $p = new modelProduct();
        return $p->mAddProduct($ten, $gia, $hinh, $idType, $mota);
    }

    public function cUpdateProduct($id, $ten, $gia, $hinh, $idType, $mota) {
        $p = new modelProduct();
        return $p->mUpdateProduct($id, $ten, $gia, $hinh, $idType, $mota);
    }

    public function cDeleteProduct($id) {
        $p = new modelProduct();
        return $p->mDeleteProduct($id);
    }

    public function cGetBestSeller($limit = 4) {
        $p = new modelProduct();
        return $p->mGetBestSeller($limit);
    }

    public function cAddType($tenLoai) {
        $p = new modelProduct();
        return $p->mAddType($tenLoai);
    }

    public function cUpdateType($id, $tenLoai) {
        $p = new modelProduct();
        return $p->mUpdateType($id, $tenLoai);
    }

    public function cDeleteType($id) {
        $p = new modelProduct();
        return $p->mDeleteType($id);
    }

    public function cGetTypeById($id) {
        $p = new modelProduct();
        return $p->mGetTypeById($id);
    }

    public function cGetImagesBySP($idSP) {
        $p = new modelProduct();
        return $p->mGetImagesBySP($idSP);
    }

    public function cAddImage($idSP, $tenAnh, $thutu) {
        $p = new modelProduct();
        return $p->mAddImage($idSP, $tenAnh, $thutu);
    }

    public function cDeleteImagesBySP($idSP) {
        $p = new modelProduct();
        return $p->mDeleteImagesBySP($idSP);
    }

    public function cGetSizesBySP($idSP) {
        $p = new modelProduct();
        return $p->mGetSizesBySP($idSP);
    }

    public function cSaveSizes($idSP, $sizeQtyArr) {
        $p = new modelProduct();
        return $p->mSaveSizes($idSP, $sizeQtyArr);
    }

    // ===== BỔ SUNG MỚI =====
    public function cGetTongDaBan($idSP) {
        $p = new modelProduct();
        return $p->mGetTongDaBan($idSP);
    }

    public function cKiemTraConHang($idSP) {
        $p = new modelProduct();
        return $p->mKiemTraConHang($idSP);
    }
    public function cTruTonKhoTheoSize($idSP, $size, $soluongMua) {
        $m = new modelProduct();
        return $m->mTruTonKhoTheoSize($idSP, $size, $soluongMua);
    }
}
?>