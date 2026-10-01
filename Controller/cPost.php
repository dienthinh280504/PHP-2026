<?php
include_once("Model/mPost.php");

class controlPost {
    public function cGetAllPosts() {
        $m = new modelPost();
        return $m->mGetAllPosts();
    }

    public function cGetPostById($id) {
        $m = new modelPost();
        return $m->mGetPostById($id);
    }
    public function cAddPost($tieude, $noidung, $hinh, $tacgia) {
    $m = new modelPost();
    return $m->mAddPost($tieude, $noidung, $hinh, $tacgia);
}

public function cUpdatePost($id, $tieude, $noidung, $hinh) {
    $m = new modelPost();
    return $m->mUpdatePost($id, $tieude, $noidung, $hinh);
}

public function cDeletePost($id) {
    $m = new modelPost();
    return $m->mDeletePost($id);
}
}
?>