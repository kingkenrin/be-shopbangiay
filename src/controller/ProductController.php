<?php
namespace Src\Controller;

use Src\Model\ProductModel;
use Src\Model\DetailProductModel;
use Src\Model\ProductOtherImageModel;
use Src\Config\Cloudinary;
use Src\Util\formatRes;
use Src\Util\getFormdata;

class ProductController
{
    private $db;
    private $requestMethod;
    private $productModel;
    private $productOtherImageModel;
    private $detailProductModel;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->productModel = new ProductModel($db);
        $this->productOtherImageModel = new ProductOtherImageModel($db);
        $this->detailProductModel = new DetailProductModel($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if (isset($_GET['productId'])) {
                    $response = $this->getProduct($_GET['productId']);
                } else {
                    $response = $this->getAllProducts();
                }
                ;
                break;
            case 'POST':
                $response = $this->addProduct();
                break;
            case 'PUT':
                $response = $this->updateProduct();
                break;
            case 'DELETE':
                $response = $this->deleteUser();
                break;
            default:
                // $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllProducts()
    {
        $result = $this->productModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(array_map(function ($product) {
            $types = $this->detailProductModel->find(['productId' => $product['productId']]);

            $product['type'] = array_map(function ($type) {
                return formatRes::getData(['size', 'quantity'], $type);
            }, $types);

            return formatRes::getData(['productId', 'name', 'price', 'mainImage', 'description', 'manufacturer', 'category', 'type'], $product);
        }, $result));
        return $response;
    }

    private function getProduct($id)
    {
        $result = $this->productModel->findById($id);

        if (!$result) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong product"]);
            return $response;
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';

        $types = $this->detailProductModel->find(['productId' => $id]);

        $result['type'] = array_map(function ($type) {
            return formatRes::getData(['size', 'quantity'], $type);
        }, $types);

        $response['body'] = json_encode(formatRes::getData(['productId', 'name', 'price', 'mainImage', 'description', 'manufacturer', 'category', 'type'], $result));
        return $response;
    }

    private function addProduct()
    {
        $input = $_POST;

        $product = $this->productModel->find(["name" => $input['name']]);

        if ($product) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "product exists"]);
            return $response;
        }

        if (!$_FILES) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "no image upload"]);
            return $response;
        }

        $result = $this->productModel->insert($input);

        $result['otherImage'] = [];

        foreach ($_FILES['productImage']['name'] as $index => $file) {
            if (strpos($file, "main")) {
                $upload = (new Cloudinary())->uploadImage(['tmp_name' => $_FILES['productImage']['tmp_name'][$index], 'name' => $file]);
                $result['mainImage'] = $upload['secure_url'];
            } else {
                $upload = (new Cloudinary())->uploadImage(['tmp_name' => $_FILES['productImage']['tmp_name'][$index], 'name' => $file]);
                $result['otherImage'][] = $upload['secure_url'];
            }
        }

        foreach ($result['otherImage'] as $link) {
            $this->productOtherImageModel->insert(["productId" => $result['productId'], "link" => $link]);
        }

        if (isset($input['type'])) {
            $types = json_decode($input['type']);

            foreach ($types as $type) {
                $type = (array) $type;

                $type['productId'] = $result['productId'];
                $this->detailProductModel->insert($type);
            }
        }

        $result = $this->productModel->update($result);

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode(formatRes::getData(['productId', 'name', 'price', 'mainImage', 'description', 'manufacturer', 'category'], $input));
        return $response;
    }
    private function deleteUser()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $user = $this->userModel->findById($input['id']);

        if (!$user) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong user"]);
            return $response;
        }

        $this->userModel->delete($input['id']);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["success" => true, "message" => "delete successfully"]);
        return $response;
    }
}