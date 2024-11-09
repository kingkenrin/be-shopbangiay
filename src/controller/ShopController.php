<?php
namespace Src\Controller;

use Src\Model\BannerShopModel;
use Src\Model\ShopModel;
use Src\Config\Cloudinary;
use Src\Util\formatRes;
use Src\Util\getFormdata;

class ShopController
{
    private $db;
    private $requestMethod;
    private $shopModel;
    private $bannerShopModel;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->shopModel = new ShopModel($db);
        $this->bannerShopModel = new BannerShopModel($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->get();
                ;
                break;
            case 'POST':
                $response = $this->updateProduct();
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

    private function get()
    {
        $shop = $this->shopModel->get();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($shop);
        return $response;
    }

    private function updateProduct()
    {
        $input = $_POST;

        $input['banner'] = [];

        if(isset($_FILES['shopImage'])){
            foreach ($_FILES['shopImage']['name'] as $index => $file) {
                if (strpos($file, "logo") !== false) {
                    $upload = (new Cloudinary())->uploadImage(['tmp_name' => $_FILES['shopImage']['tmp_name'][$index], 'name' => $file]);
                    $input['logo'] = $upload['secure_url'];
                } else {
                    $upload = (new Cloudinary())->uploadImage(['tmp_name' => $_FILES['shopImage']['tmp_name'][$index], 'name' => $file]);
                    $input['banner'][] = $upload['secure_url'];
                }
            }
        }

        $shop = $this->shopModel->get();

        if (!$shop) {
            $this->shopModel->insert($input);

        } else {
            $this->shopModel->update($input);
        }

        foreach($input['banner'] as $image){
            $this->bannerShopModel->insert(['link' => $image]);
        }

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode(["success" => true, "message" => "update successfully"]);
        return $response;
    }
}