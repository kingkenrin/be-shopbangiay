<?php
namespace Src\Config;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class Cloudinary
{
    private $upload = null;

    public function __construct()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => 'dxtslecpc',
                'api_key' => '378364589594615',
                'api_secret' => 'x2vmAq4Xy0EXdu3d0UpeG0eFIh4'
            ],
            'url' => [
                'secure' => true
            ]
        ]);

        $this->upload = new UploadApi();
    }

    public function uploadImage($image)
    {
        $result = $this->upload->upload($image['tmp_name'], [
            'folder' => 'shopbangiayuit',
            'public_id' => $image['name'],
            // 'allowed_formats' => ['jpg', 'png'],
            // 'overwrite' => true
        ]);

        return $result;
    }
}