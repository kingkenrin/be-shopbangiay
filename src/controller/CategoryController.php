<?php
namespace Src\Controller;

use Src\Model\CategoryModel;
use Src\Model\ProductOtherImageModel;
use Src\Model\ProductModel;
use Src\Model\DetailProductModel;
use Src\Model\CartModel;
use Src\Util\formatRes;

class CategoryController
{
    private $db;
    private $requestMethod;
    private $categoryModel;
    private $productOtherImageModel;
    private $detailProductModel;
    private $cartModel;
    private $productModel;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->categoryModel = new CategoryModel($db);
        $this->productOtherImageModel = new ProductOtherImageModel($db);
        $this->detailProductModel = new DetailProductModel($db);
        $this->cartModel = new CartModel($db);
        $this->productModel = new ProductModel($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if (isset($_GET['categoryId'])) {
                    $response = $this->getCategory($_GET['categoryId']);
                } else {
                    $response = $this->getAllCategories();
                }
                ;
                break;
            case 'POST':
                $response = $this->addCategory();
                break;
            case 'PUT':
                $response = $this->updateCategory();
                break;
            case 'DELETE':
                $response = $this->deleteCategory();
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

    private function getAllCategories()
    {
        $result = $this->categoryModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(array_map(function ($category) {
            return formatRes::getData(['categoryId', 'name'], $category);
        }, $result));
        return $response;
    }

    private function getCategory($id)
    {
        $result = $this->categoryModel->findOne(["categoryId" => $id]);

        if (!$result) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong category"]);
            return $response;
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(formatRes::getData(['categoryId', 'name'], $result));
        return $response;
    }

    private function addCategory()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $category = $this->categoryModel->find(["name" => $input['name']]);

        if ($category) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "category exists"]);
            return $response;
        }

        $this->categoryModel->insert($input);

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($input);
        return $response;
    }

    private function updateCategory()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $existCategory = $this->categoryModel->findById($input['categoryId']);

        if (!$existCategory) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong category"]);
            return $response;
        }

        $category = $this->categoryModel->findOne(["name" => $input['name']]);

        if ($category) {
            if ($existCategory['name'] != $category['name']) {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(["success" => false, "message" => "category exists"]);
                return $response;
            }
        }

        $this->categoryModel->update($input);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(formatRes::getData(['categoryId', 'name'], $input));
        return $response;
    }

    private function deleteCategory()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $category = $this->categoryModel->findById($input['categoryId']);

        if (!$category) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong category"]);
            return $response;
        }

        $allProductOfCategory = $this->productModel->find(['categoryId' => $input['categoryId']]);

        foreach ($allProductOfCategory as $product) {
            $this->productOtherImageModel->deleteByProductId($product['productId']);
            $this->detailProductModel->deleteByProductId($product['productId']);
            $this->cartModel->deleteByProductId(['productId' => $product['productId']]);
            $this->productModel->delete($product['productId']);
        }

        $this->categoryModel->delete($input['categoryId']);

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