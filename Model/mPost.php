<?php
include_once("mKetNoi.php");

class modelPost {
    // Lấy tất cả bài viết (mới nhất trước)
    public function mGetAllPosts() {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();

        $res = $conn->query("SELECT * FROM posts ORDER BY ngaydang DESC");
        $p->mDongKetNoi($conn);
        return $res;
    }

    // Lấy 1 bài viết theo ID
    public function mGetPostById($id) {
        $p = new clsKetNoi();
        $conn = $p->mMoKetNoi();
        $id = (int)$id;

        $res = $conn->query("SELECT * FROM posts WHERE idPost = $id");
        $p->mDongKetNoi($conn);
        return $res;
    }
    // Thêm bài viết mới
public function mAddPost($tieude, $noidung, $hinh, $tacgia) {
    $p = new clsKetNoi();
    $conn = $p->mMoKetNoi();

    $tieude = mysqli_real_escape_string($conn, $tieude);
    $noidung = mysqli_real_escape_string($conn, $noidung);
    $hinh = mysqli_real_escape_string($conn, $hinh);
    $tacgia = mysqli_real_escape_string($conn, $tacgia);

    $sql = "INSERT INTO posts (tieude, noidung, hinhanh, tacgia) VALUES ('$tieude', '$noidung', '$hinh', '$tacgia')";
    $res = $conn->query($sql);
    $p->mDongKetNoi($conn);
    return $res;
}

// Cập nhật bài viết
public function mUpdatePost($id, $tieude, $noidung, $hinh) {
    $p = new clsKetNoi();
    $conn = $p->mMoKetNoi();

    $id = (int)$id;
    $tieude = mysqli_real_escape_string($conn, $tieude);
    $noidung = mysqli_real_escape_string($conn, $noidung);

    if ($hinh == "") {
        $sql = "UPDATE posts SET tieude='$tieude', noidung='$noidung' WHERE idPost=$id";
    } else {
        $hinh = mysqli_real_escape_string($conn, $hinh);
        $sql = "UPDATE posts SET tieude='$tieude', noidung='$noidung', hinhanh='$hinh' WHERE idPost=$id";
    }

    $res = $conn->query($sql);
    $p->mDongKetNoi($conn);
    return $res;
}

// Xóa bài viết
public function mDeletePost($id) {
    $p = new clsKetNoi();
    $conn = $p->mMoKetNoi();
    $id = (int)$id;

    $sql = "DELETE FROM posts WHERE idPost=$id";
    $res = $conn->query($sql);
    $p->mDongKetNoi($conn);
    return $res;
}
}
?>