<?php
namespace Src\Controller;

use Src\Model\ProductModel;
use Src\Model\DetailProductModel;
use Src\Model\ProductOtherImageModel;
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
            case 'POST':
                $response = $this->updateProduct();
                break;
            default:
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

        if (isset($_FILES['productImage'])) {
            $this->productOtherImageModel->delete($input['productId']);

            $input['otherImage'] = [];

            foreach ($_FILES['productImage']['name'] as $index => $file) {
                if (strpos($file, "main")) {
                    $upload = (new Cloudinary())->uploadImage(['tmp_name' => $_FILES['productImage']['tmp_name'][$index], 'name' => $file]);
                    $input['mainImage'] = $upload['secure_url'];
                } else {
                    $upload = (new Cloudinary())->uploadImage(['tmp_name' => $_FILES['productImage']['tmp_name'][$index], 'name' => $file]);
                    $input['otherImage'][] = $upload['secure_url'];
                }
            }

            $result = $this->productModel->update($input);

            foreach ($input['otherImage'] as $link) {
                $this->productOtherImageModel->insert(["productId" => $result['productId'], "link" => $link]);
            }

        }

        if (isset($input['type'])) {
            $this->detailProductModel->delete($input['productId']);

            $types = json_decode($input['type']);

            foreach ($types as $type) {
                $type = (array) $type;

                $type['productId'] = $input['productId'];
                $this->detailProductModel->insert($type);
            }
        }

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode(formatRes::getData(['productId', 'name', 'price', 'mainImage', 'description', 'manufacturer', 'category'], $input));
        return $response;
    }
}