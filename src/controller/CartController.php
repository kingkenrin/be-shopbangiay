<?php
namespace Src\Controller;

use Src\Model\CartModel;
use Src\Model\UserModel;
use Src\Model\ProductModel;
use Src\Model\DetailProductModel;
use Src\Util\formatRes;

class CartController
{
    private $db;
    private $requestMethod;
    private $cartModel;
    private $productModel;
    private $userModel;
    private $detailProductModel;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->cartModel = new CartModel($db);
        $this->productModel = new ProductModel($db);
        $this->userModel = new UserModel(db: $db);
        $this->detailProductModel = new DetailProductModel($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if (isset($_GET['userId'])) {
                    $response = $this->getCartByUserId($_GET['userId']);
                } else {
                    $response = $this->getAllCarts();
                }
                ;
                break;
            case 'POST':
                $response = $this->addItemCart();
                break;
            case 'PUT':
                $response = $this->deleteItemCart();
                break;
            case 'DELETE':
                $response = $this->deleteCartByUserId();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllCarts()
    {
        $result = $this->cartModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(array_map(function ($cart) {
            return formatRes::getData(['cartId','userId', 'productId', 'size', 'quantity'], $cart);
        }, $result));
        return $response;
    }

    private function getCartByUserId($id)
    {
        $result = $this->cartModel->find(["userId" => $id]);

        if (!$result) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong cart"]);
            return $response;
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(array_map(function ($cart) {
            return formatRes::getData(['cartId','userId', 'productId', 'size', 'quantity'], $cart);
        }, $result));
        return $response;
    }

    private function addItemCart()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $cart = $this->cartModel->findOne(["userId" => $input['userId'], "productId" => $input['productId'], "size" => $input['size']]);

        $user = $this->userModel->findById($input['userId']);

        if (!$user) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong user"]);
            return $response;
        }

        $product = $this->productModel->findById($input['productId']);

        if (!$product) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong product"]);
            return $response;
        }

        $detailProduct = $this->detailProductModel->findOne(["productId" => $input['productId'], "size" => $input['size']]);

        if (!$detailProduct) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong size"]);
            return $response;
        }

        // if ((int) $input['quantity'] > $detailProduct['quantity']) {
        //     $response['status_code_header'] = 'HTTP/1.1 200 OK';
        //     $response['body'] = json_encode(["success" => false, "message" => "product out of stock"]);
        //     return $response;
        // } else {

        // $this->detailProductModel->update(["detailProductId" => $detailProduct['detailProductId'], "quantity" => $detailProduct['quantity'] - (int) $input['quantity']]);

        if (!$cart) {
            $this->cartModel->insert($input);
        } else {
            $input['quantity'] += $cart['quantity'];

            $this->cartModel->updateByUserIdAndProductIdAndSize($input);
        }
        // }

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode(["success" => true, "message" => "add successfully"]);
        return $response;
    }

    private function deleteItemCart()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $cart = $this->cartModel->findOne(["userId" => $input['userId'], "productId" => $input['productId'], "size" => $input['size']]);

        $user = $this->userModel->findById($input['userId']);

        if (!$user) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong user"]);
            return $response;
        }

        $product = $this->productModel->findById($input['productId']);

        if (!$product) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong product"]);
            return $response;
        }

        $detailProduct = $this->detailProductModel->findOne(["productId" => $input['productId'], "size" => $input['size']]);

        if (!$detailProduct) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong size"]);
            return $response;
        }

        if (!$cart) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "cant find item in cart"]);
            return $response;
        } else {
            if (isset($input['quantity'])) {
                if ($cart['quantity'] >= $input['quantity']) {

                    $input['quantity'] = $cart['quantity'] - $input['quantity'];

                    $this->cartModel->updateByUserIdAndProductIdAndSize($input);

                    $response['status_code_header'] = 'HTTP/1.1 201 Created';
                    $response['body'] = json_encode(["success" => true, "message" => "decrease successfully"]);
                    return $response;
                } else {
                    $response['status_code_header'] = 'HTTP/1.1 201 Created';
                    $response['body'] = json_encode(["success" => false, "message" => "quantity of item cart must be greater than 0"]);
                    return $response;
                }
            } else {
                $this->cartModel->deleteByUserIdAndProductIdAndSize($input);

                $response['status_code_header'] = 'HTTP/1.1 201 Created';
                $response['body'] = json_encode(["success" => true, "message" => "delete successfully"]);
                return $response;
            }
        }
    }

    private function deleteCartByUserId()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $cart = $this->cartModel->findOne(["userId" => $input['userId']]);

        if (!$cart) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "cant find cart"]);
            return $response;
        }

        $this->cartModel->delete($cart['cartId']);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["success" => true, "message" => "delete successfully"]);
        return $response;
    }

    private function notFoundResponse(){
        $response['status_code_header'] = 'HTTP/1.1 404 NOT FOUND';
        $response['body'] = json_encode(["success" => false, "message" => "route not found"]);
        return $response;
    }
}