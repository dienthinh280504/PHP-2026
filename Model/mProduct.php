<?php
include_once("mKetNoi.php");

class modelProduct {
    public function mGetAllProduct($idType = 0, $search = "") {
        $p = new clsKetNoi(); 
        $conn = $p->mMoKetNoi();
        
        $sql = "SELECT p.*, t.typeName FROM products p LEFT JOIN typeproducts t ON p.idType = t.idType WHERE 1=1";
        
        if($idType > 0) {
            $sql .= " AND p.idType = $idType";
        }
        
        if($search != "") {
            $sql .= " AND p.tengiay LIKE '%$search%'";
        }
        
        $sql .= " ORDER BY p.idSP DESC";
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn); 
        return $res;
    }

    // Lấy danh sách loại
    public function mGetAllType() {
        $p = new clsKetNoi(); 
        $conn = $p->mMoKetNoi();
        
        $res = $conn->query("SELECT * FROM typeproducts");
        
        $p->mDongKetNoi($conn); 
        return $res;
    }

    // Thêm sản phẩm mới
    public function mAddProduct($ten, $gia, $hinh, $idType, $mota) {
        $p = new clsKetNoi(); $conn = $p->mMoKetNoi();
        $sql = "INSERT INTO products (tengiay, gia, hinhanh, idType, mota) VALUES ('$ten', $gia, '$hinh', '$idType', '$mota')";
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn); return $res;
    }

    // Sửa sản phẩm
    public function mUpdateProduct($id, $ten, $gia, $hinh, $idType, $mota) {
        $p = new clsKetNoi(); 
        $conn = $p->mMoKetNoi();
        
        if($hinh == "") {
            $sql = "UPDATE products SET tengiay='$ten', gia=$gia, idType=$idType, mota='$mota' WHERE idSP=$id";
        } else {
            $sql = "UPDATE products SET tengiay='$ten', gia=$gia, hinhanh='$hinh', idType=$idType, mota='$mota' WHERE idSP=$id";
        }
        
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn); 
        return $res;
    }
   
    // Lấy 1 sản phẩm theo ID để đổ vào Form Sửa
    public function mGetProductById($id) {
        $p = new clsKetNoi(); 
        $conn = $p->mMoKetNoi();
      
        $res = $conn->query("SELECT * FROM products WHERE idSP = $id");
        $p->mDongKetNoi($conn); 
        return $res;
    }

    // Hàm Xóa sản phẩm
    public function mDeleteProduct($idSP) {
        $p = new clsKetNoi(); 
        $conn = $p->mMoKetNoi();
        
        $sql = "DELETE FROM products WHERE idSP = $idSP";
        
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn); 
        return $res;
    }

    // Lấy sản phẩm bán chạy nhất
    public function mGetBestSeller($limit = 4) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $limit = (int)$limit;

        $sql = "SELECT p.*, COALESCE(SUM(od.soluong), 0) AS tongban
                FROM products p
                LEFT JOIN order_details od ON p.idSP = od.idSP
                GROUP BY p.idSP
                ORDER BY tongban DESC
                LIMIT $limit";

        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }

    // ===== QUẢN LÝ LOẠI GIÀY =====

    public function mAddType($tenLoai) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $tenLoai = mysqli_real_escape_string($conn, $tenLoai);

        $sql = "INSERT INTO typeproducts (typeName) VALUES ('$tenLoai')";
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }

    public function mUpdateType($id, $tenLoai) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $id = (int)$id;
        $tenLoai = mysqli_real_escape_string($conn, $tenLoai);

        $sql = "UPDATE typeproducts SET typeName='$tenLoai' WHERE idType=$id";
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }

    public function mDeleteType($id) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $id = (int)$id;

        $check = $conn->query("SELECT COUNT(*) AS soLuong FROM products WHERE idType=$id");
        $row = $check->fetch_assoc();

        if ($row['soLuong'] > 0) {
            $p->mDongKetNoi($conn);
            return "loi_con_san_pham";
        }

        $res = $conn->query("DELETE FROM typeproducts WHERE idType=$id");
        $p->mDongKetNoi($conn);
        return $res;
    }

    public function mGetTypeById($id) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $id = (int)$id;

        $res = $conn->query("SELECT * FROM typeproducts WHERE idType=$id");
        $p->mDongKetNoi($conn);
        return $res;
    }
          public function mGetImagesBySP($idSP) {
    // Kết nối trực tiếp vào CSDL ananas_db
    $conn = new mysqli("localhost", "root", "", "ananas_db");
    
    if ($conn->connect_error) {
        return false;
    }

    $conn->set_charset("utf8mb4");
    $idSP = (int)$idSP;
    $sql = "SELECT * FROM product_images WHERE idSP = $idSP ORDER BY tenAnh ASC";
    $result = $conn->query($sql);
    
    $conn->close();
    return $result;
}
    public function mAddImage($idSP, $tenAnh, $thutu) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $idSP = (int)$idSP;
        $tenAnh = mysqli_real_escape_string($conn, $tenAnh);
        $thutu = (int)$thutu;

        $sql = "INSERT INTO product_images (idSP, tenAnh, thutu) VALUES ($idSP, '$tenAnh', $thutu)";
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }

    public function mDeleteImagesBySP($idSP) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $idSP = (int)$idSP;

        $sql = "DELETE FROM product_images WHERE idSP=$idSP";
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }

    public function mGetSizesBySP($idSP) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $idSP = (int)$idSP;

        $sql = "SELECT * FROM product_sizes WHERE idSP=$idSP ORDER BY size ASC";
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }

    public function mSaveSizes($idSP, $sizeQtyArr) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $idSP = (int)$idSP;

        $conn->query("DELETE FROM product_sizes WHERE idSP=$idSP");

        foreach ($sizeQtyArr as $size => $qty) {
            $size = (int)$size;
            $qty = (int)$qty;
            $conn->query("INSERT INTO product_sizes (idSP, size, soluong) VALUES ($idSP, $size, $qty)");
        }

        $p->mDongKetNoi($conn);
    }

    // ===== BỔ SUNG MỚI =====
    // Tổng số lượng đã bán của 1 sản phẩm (dựa vào order_details)
    public function mGetTongDaBan($idSP) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $idSP = (int)$idSP;
        $sql = "SELECT COALESCE(SUM(soluong),0) AS tongban FROM order_details WHERE idSP=$idSP";
        $res = $conn->query($sql);
        $row = $res->fetch_assoc();
        $p->mDongKetNoi($conn);
        return (int)$row['tongban'];
    }

    // Kiểm tra sản phẩm còn hàng hay không (dựa vào tổng tồn kho theo size)
    public function mKiemTraConHang($idSP) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $idSP = (int)$idSP;
        $check = $conn->query("SELECT COUNT(*) AS soDong, COALESCE(SUM(soluong),0) AS tongTon FROM product_sizes WHERE idSP=$idSP");
        $row = $check->fetch_assoc();
        $p->mDongKetNoi($conn);
        
        // Nếu chưa từng thiết lập size cho sản phẩm này -> coi như còn hàng
        if ($row['soDong'] == 0) return true;
        return $row['tongTon'] > 0;
    }
    // Trừ số lượng tồn kho theo đúng sản phẩm và size
    public function mTruTonKhoTheoSize($idSP, $size, $soluongMua) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $idSP = (int)$idSP;
        $size = mysqli_real_escape_string($conn, (string)$size);
        $soluongMua = (int)$soluongMua;

        // Đổi chitietsanpham thành product_sizes
        $sql = "UPDATE product_sizes 
                SET soluong = GREATEST(0, soluong - $soluongMua) 
                WHERE idSP = $idSP AND size = '$size'";
        
        $res = $conn->query($sql);
        $p->mDongKetNoi($conn);
        return $res;
    }
}
?>