<?php
namespace Src\Controller;

use Src\Model\ProductModel;
use Src\Model\CartModel;
use Src\Model\DetailProductModel;
use Src\Model\ProductOtherImageModel;
use Src\Model\ManufacturerModel;
use Src\Model\CategoryModel;
use Src\Config\Cloudinary;
use Src\Util\formatRes;
use Src\Util\getFormdata;

class UpdateProductController
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
        $this->cartModel = new CartModel($db);
        $this->categoryModel = new CategoryModel($db);
        $this->manufacturerModel = new ManufacturerModel($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->updateProduct();
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

    private function updateProduct()
    {
        $input = $_POST;

        $product = $this->productModel->findById($input['productId']);

        if (!$product) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong product"]);
            return $response;
        }

        if (isset($input['categoryId'])) {
            $category = $this->categoryModel->findById($input['categoryId']);

            if (!$category) {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(["success" => false, "message" => "wrong category"]);
                return $response;
            }
        }

        if (isset($input['manufacturerId'])) {
            $manufacturer = $this->manufacturerModel->findById($input['manufacturerId']);

            if (!$manufacturer) {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(["success" => false, "message" => "wrong manufacturer"]);
                return $response;
            }
        }

        if (isset($_FILES['productImage'])) {
            $this->productOtherImageModel->deleteByProductId($input['productId']);

            $input['otherImage'] = [];

            foreach ($_FILES['productImage']['name'] as $index => $file) {
                if (strpos(strtolower($file), "main") !== false) {
                    $upload = (new Cloudinary())->uploadImage(['tmp_name' => $_FILES['productImage']['tmp_name'][$index], 'name' => $file]);
                    $input['mainImage'] = $upload['secure_url'];
                } else {
                    $upload = (new Cloudinary())->uploadImage(['tmp_name' => $_FILES['productImage']['tmp_name'][$index], 'name' => $file]);
                    $input['otherImage'][] = $upload['secure_url'];
                }
            }

            if (isset($_POST['productImage'])) {
                $input['otherImage'] += $_POST['productImage'];
            }

            foreach ($input['otherImage'] as $link) {
                $this->productOtherImageModel->insert(["productId" => $input['productId'], "link" => $link]);
            }

        }

        if (isset($input['type'])) {
            $oldDetail = $this->detailProductModel->find(['productId' => $input['productId']]);
            $oldDetailType = [];

            foreach ($oldDetail as $detail) {
                $oldDetailType[] = $detail['size'];
            }

            $types = json_decode($input['type']);

            $newDetailType = [];

            foreach ($types as $type) {
                $type = (array) $type;

                $newDetailType[] = $type['size'];
            }

            $diff = array_diff($oldDetailType, $newDetailType);


            $this->detailProductModel->deleteByProductId($input['productId']);


            foreach ($types as $type) {
                $type = (array) $type;

                $type['productId'] = $input['productId'];
                $this->detailProductModel->insert($type);
            }

            foreach ($diff as $size) {
                $this->cartModel->deleteByProductIdAndSize(['productId' => $input['productId'], 'size' => $size]);
            }
        }

        $result = $this->productModel->update($input);

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode(formatRes::getData(['productId', 'name', 'price', 'mainImage', 'description', 'manufacturerId', 'categoryId', 'discount'], $input));
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 NOT FOUND';
        $response['body'] = json_encode(["success" => false, "message" => "route not found"]);
        return $response;
    }
}