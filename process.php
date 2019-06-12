<?php
session_start();
require_once ('database.php');

$database = new Database();


if (isset($_POST)&& !empty($_POST)){
    /**
     * check $_POST có tồn tại tức là có dữ liệu được gửi đi
     * đồng thời !empty tức là nó se có dữ liệu được gửi đi
     */

    if (isset($_POST['action'])){
        switch ($_POST['action']){
            case 'add':
                if (isset($_POST['quantity']) && isset($_POST['product_id']) ) {
                    $sql = "SELECT * FROM products WHERE id=". (int)$_POST['product_id'];
                    $product = $database->runQuery($sql);
                    $product = current($product);

                    if (isset($_SESSION['cart_item']) && !empty($_SESSION['cart_item'])){
                        /**
                         * !empty($_SESSION['cart_item']  = true
                         * tức là giỏ hàng  lúc này có dữ liệu
                         *
                         */
                        if (isset($_SESSION['cart_item']['id'] )){

                            /*
                             * sản phẩm đã tồn tại trong giỏ hàng
                             */
                            $exist_cart_item = $_SESSION['cart_item']['id'];
                            $exit_quantity = $exist_cart_item['quantity'];
                            $cart_item = array();
                            $cart_item['id'] = $product['id'];
                            $cart_item['product_name'] = $product['product_name'];
                            $cart_item['product_image'] = $product['product_image'];
                            $cart_item['price'] = $product['price'];
                            $cart_item['quantity'] = $exit_quantity + $_POST['quantity'];
                            $_SESSION['cart_item']['id']=$cart_item;

                        } else {

                            /*
                             * Sản phẩm chưa tồn tại trong giỏ hàng
                             */
                            $_SESSION['cart_item'] = array();
                            $cart_item = array();
                            $cart_item['product_id'] = $product['id'];
                            $cart_item['product_name'] = $product['product_name'];
                            $cart_item['product_image'] = $product['product_image'];
                            $cart_item['price'] = $product['price'];
                            $cart_item['quantity'] = $_POST['quantity'];
                            $_SESSION['cart_item']['id'] = $cart_item;
                        }
                    }else{
                            /**
                             * !empty($_SESSION['cart_item']  == false
                             * tức là giỏ hàng  lúc này không có dữ liệu
                             */
                        $_SESSION ['cart_item'] = array();
                        $cart_item = array();
                        $cart_item['id'] = $product['id'];
                        $cart_item['product_name'] = $product['product_name'];
                        $cart_item['product_image'] = $product['product_image'];
                        $cart_item['price'] = $product['price'];
                        $cart_item['quantity'] = $_POST['quantity'];
                        $_SESSION['cart_item']['id']=$cart_item;
                        }
                    }
                    break;
                case 'remove':
                    if (isset($_POST['product_id'])){
                        $product_id= $_POST['product_id'];
                        if (isset($_SESSION['cart_item'][$product_id])){
                            unset($_SESSION['cart_item'][$product_id]);
                        }
                    }
                        break;
                default:
                    echo 'Action không tồn tại';
                    die;
            }
        }
}

header("Location: http://localhost:8080/simple-CART-/index.php");
die();

