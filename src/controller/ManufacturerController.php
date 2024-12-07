<?php
namespace Src\Controller;

use Src\Model\ManufacturerModel;
use Src\Util\formatRes;

class ManufacturerController
{
    private $db;
    private $requestMethod;
    private $manufacturerModel;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->manufacturerModel = new ManufacturerModel($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if (isset($_GET['manufacturerId'])) {
                    $response = $this->getManufacturer($_GET['manufacturerId']);
                } else {
                    $response = $this->getAllManufacturers();
                }
                ;
                break;
            case 'POST':
                $response = $this->addManufacturer();
                break;
            case 'PUT':
                $response = $this->updateManufacturer();
                break;
            case 'DELETE':
                $response = $this->deleteManufacturer();
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

    private function getAllManufacturers()
    {
        $result = $this->manufacturerModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(array_map(function ($manufacturer) {
            return formatRes::getData(['manufacturerId', 'name'], $manufacturer);
        }, $result));
        return $response;
    }

    private function getManufacturer($id)
    {
        $result = $this->manufacturerModel->findOne(["manufacturerId" => $id]);

        if (!$result) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong manufacturer"]);
            return $response;
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(formatRes::getData(['manufacturerId', 'name'], $result));
        return $response;
    }

    private function addManufacturer()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $this->manufacturerModel->insert($input);

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode(formatRes::getData(['manufacturerId', 'name'], $input));
        return $response;
    }

    private function updateManufacturer()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $existManufacturer = $this->manufacturerModel->findById($input['manufacturerId']);

        if (!$existManufacturer) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong manufacturer"]);
            return $response;
        }

        $manufacturer = $this->manufacturerModel->findOne(["name" => $input['name']]);

        if ($manufacturer && $existManufacturer['name'] != $manufacturer['name']) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "category exists"]);
            return $response;
        }

        $this->manufacturerModel->update($input);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(formatRes::getData(['manufacturerId', 'name'], $input));
        return $response;
    }

    private function deleteManufacturer()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $manufacturer = $this->manufacturerModel->findById($input['manufacturerId']);

        if (!$manufacturer) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong manufacturer"]);
            return $response;
        }

        $this->manufacturerModel->delete($input['manufacturerId']);

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