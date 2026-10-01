<?php
include_once("Model/mCart.php");

class controlCart {
    public function cAddToCart($username, $idSP, $size, $soluong) {
        $m = new modelCart();
        return $m->mAddToCart($username, $idSP, $size, $soluong);
    }

    public function cGetCartByUser($username) {
        $m = new modelCart();
        return $m->mGetCartByUser($username);
    }

    public function cUpdateQuantity($idCart, $soluong) {
        $m = new modelCart();
        return $m->mUpdateQuantity($idCart, $soluong);
    }

    public function cRemoveFromCart($idCart) {
        $m = new modelCart();
        return $m->mRemoveFromCart($idCart);
    }

    public function cClearCart($username) {
        $m = new modelCart();
        return $m->mClearCart($username);
    }
}
?>