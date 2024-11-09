<?php
namespace Src\Controller;

use Src\Model\BannerShopModel;
use Src\Model\ShopModel;
use Src\Config\Cloudinary;
use Src\Util\formatRes;
use Src\Util\getFormdata;

class BannerController
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
            case 'POST':
                $response = $this->addBanner();
                break;
            case 'DELETE':
                $response = $this->deleteBanner();
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

    private function addBanner()
    {
        $input['banner'] = [];

        if (isset($_FILES['shopImage'])) {
            foreach ($_FILES['shopImage']['name'] as $index => $file) {
                $upload = (new Cloudinary())->uploadImage(['tmp_name' => $_FILES['shopImage']['tmp_name'][$index], 'name' => $file]);
                $input['banner'][] = $upload['secure_url'];
            }
        }

        foreach ($input['banner'] as $image) {
            $this->bannerShopModel->insert(['link' => $image]);
        }

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode(["success" => true, "message" => "add successfully"]);
        return $response;
    }

    private function deleteBanner()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $banner = $this->bannerShopModel->findById($input['bannerId']);

        if (!$banner) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong banner"]);
            return $response;
        }

        $this->bannerShopModel->delete($input['bannerId']);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["success" => true, "message" => "delete successfully"]);
        return $response;
    }
}