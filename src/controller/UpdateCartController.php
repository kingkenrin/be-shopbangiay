<?php
namespace Src\Controller;

use Src\Model\CartModel;
use Src\Model\UserModel;
use Src\Model\ProductModel;
use Src\Model\DetailProductModel;
use Src\Util\formatRes;

class UpdateCartController
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
            // case 'GET':
            //     if (isset($_GET['manufacturerId'])) {
            //         $response = $this->getManufacturer($_GET['manufacturerId']);
            //     } else {
            //         $response = $this->getAllManufacturers();
            //     }
            //     ;
            //     break;
            // case 'POST':
            //     $response = $this->addManufacturer();
            //     break;
            case 'PUT':
                $response = $this->updateCart();
                break;
            // case 'DELETE':
            //     $response = $this->deleteManufacturer();
            //     break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function updateCart()
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
            $response['body'] = json_encode(["success" => false, "message" => "item does not exist in cart"]);
            return $response;
        }

        $this->cartModel->updateByUserIdAndProductIdAndSize($input);

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode(["success" => true, "message" => "update successfully"]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 NOT FOUND';
        $response['body'] = json_encode(["success" => false, "message" => "route not found"]);
        return $response;
    }
}