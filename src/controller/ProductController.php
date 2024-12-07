<?php
namespace Src\Controller;

use Src\Model\ProductModel;
use Src\Model\DetailProductModel;
use Src\Model\ProductOtherImageModel;
use Src\Model\ManufacturerModel;
use Src\Model\CartModel;
use Src\Model\CategoryModel;
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
    private $cartModel;
    private $categoryModel;
    private $manufacturerModel;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->productModel = new ProductModel($db);
        $this->productOtherImageModel = new ProductOtherImageModel($db);
        $this->detailProductModel = new DetailProductModel($db);
        $this->categoryModel = new CategoryModel($db);
        $this->manufacturerModel = new ManufacturerModel($db);
        $this->cartModel = new CartModel($db);
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
            case 'DELETE':
                $response = $this->deleteProduct();
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

    private function getAllProducts()
    {
        $result = $this->productModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(array_map(function ($product) {
            $types = $this->detailProductModel->find(['productId' => $product['productId']]);

            $product['type'] = array_map(function ($type) {
                return formatRes::getData(['size', 'quantity'], $type);
            }, $types);

            $otherImage = $this->productOtherImageModel->find(['productId' => $product['productId']]);

            $product['otherImages'] = [];

            foreach ($otherImage as $image) {
                $product['otherImages'][] = $image['link'];
            }

            $category = $this->categoryModel->findById($product['categoryId']);

            $product['categoryId'] = $category['name'];

            $manufacturer = $this->manufacturerModel->findById($product['manufacturerId']);

            $product['manufacturerId'] = $manufacturer['name'];

            return formatRes::getData(['productId', 'name', 'price', 'mainImage', 'otherImages', 'description', 'manufacturerId', 'categoryId', 'type', 'discount'], $product);
        }, $result));
        return $response;
    }

    private function getProduct($id)
    {
        $product = $this->productModel->findById($id);

        if (!$product) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong product"]);
            return $response;
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';

        $types = $this->detailProductModel->find(['productId' => $id]);

        $product['type'] = array_map(function ($type) {
            return formatRes::getData(['size', 'quantity'], $type);
        }, $types);

        $otherImage = $this->productOtherImageModel->find(['productId' => $product['productId']]);

        $product['otherImages'] = [];

        foreach ($otherImage as $image) {
            $product['otherImages'][] = $image['link'];
        }

        $category = $this->categoryModel->findById($product['categoryId']);

        $product['categoryId'] = $category['name'];

        $manufacturer = $this->manufacturerModel->findById($product['manufacturerId']);

        $product['manufacturerId'] = $manufacturer['name'];

        $response['body'] = json_encode(formatRes::getData(['productId', 'name', 'price', 'mainImage', 'otherImages', 'description', 'manufacturerId', 'categoryId', 'type', 'discount'], $product));
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

        $category = $this->categoryModel->findById($input['categoryId']);

        if (!$category) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong category"]);
            return $response;
        }

        $manufacturer = $this->manufacturerModel->findById($input['manufacturerId']);

        if (!$manufacturer) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong manufacturer"]);
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
            if (strpos($file, "main") !== false) {
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
        $response['body'] = json_encode(formatRes::getData(['productId', 'name', 'price', 'mainImage', 'description', 'manufacturerId', 'categoryId', 'type', 'discount'], $input));
        return $response;
    }
    private function deleteProduct()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $product = $this->productModel->findById($input['productId']);

        if (!$product) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong product"]);
            return $response;
        }

        $this->productOtherImageModel->delete($input['productId']);
        $this->detailProductModel->deleteByProductId($input['productId']);
        $this->cartModel->deleteByProductId($input['productId']);

        $this->productModel->delete($input['productId']);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["success" => true, "message" => "delete successfully"]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 NOT FOUND';
        $response['body'] = json_encode(["success" => false, "message" => "route not found"]);
        return $response;
    }
}