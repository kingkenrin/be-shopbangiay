<?php
namespace Src\Controller;

use Src\Model\InvoiceModel;
use Src\Model\DetailInvoiceModel;
use Src\Model\UserModel;
use Src\Model\ProductModel;
use Src\Model\DetailProductModel;
use Src\Model\CategoryModel;
use Src\Model\ManufacturerModel;
use Src\Util\formatRes;

class InvoiceController
{
    private $db;
    private $requestMethod;
    private $invoiceModel;
    private $productModel;
    private $userModel;
    private $detailProductModel;
    private $detailInvoiceModel;
    private $categoryModel;
    private $manufacturerModel;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->invoiceModel = new InvoiceModel($db);
        $this->productModel = new ProductModel($db);
        $this->userModel = new UserModel(db: $db);
        $this->detailProductModel = new DetailProductModel($db);
        $this->detailInvoiceModel = new DetailInvoiceModel($db);
        $this->categoryModel = new CategoryModel($db);
        $this->manufacturerModel = new ManufacturerModel($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if (isset($_GET['invoiceId'])) {
                    $response = $this->getInvoice($_GET['invoiceId']);
                } else {
                    if (isset($_GET['userId'])) {
                        $response = $this->getInvoiceByUserId($_GET['userId']);
                    } else {
                        $response = $this->getAllInvoices();
                    }
                }
                ;
                break;
            case 'POST':
                $response = $this->addInvoice();
                break;
            case 'PUT':
                $response = $this->updateInvoice();
                break;
            case 'DELETE':
                $response = $this->deleteInvoice();
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

    private function getAllInvoices()
    {
        $result = $this->invoiceModel->findAll();

        $response['status_code_header'] = 'HTTP/1.1 200 OK';

        $response['body'] = json_encode(array_map(function ($invoice) {
            //populate
            $user = $this->userModel->findById($invoice['userId']);

            $invoice['userId'] = $user;
            //

            $allDetailInvoice = $this->detailInvoiceModel->find(['invoiceId' => $invoice['invoiceId']]);

            $invoice['items'] = [];

            foreach ($allDetailInvoice as $detailInvoice) {
                $product = $this->productModel->findById($detailInvoice['productId']);

                //populate
                $category = $this->categoryModel->findById($product['categoryId']);

                $product['categoryId'] = $category;

                $manufacturer = $this->manufacturerModel->findById($product['manufacturerId']);

                $product['manufacturerId'] = $manufacturer;
                //

                $detailInvoice['productId'] = $product;

                $invoice['items'][] = ['productId' => $detailInvoice['productId'], 'quantity' => $detailInvoice['quantity'], 'size' => $detailInvoice['size']];
            }

            return formatRes::getData(['invoiceId', 'userId', 'address', 'note', 'state', 'orderDate', 'items', 'paymentMethod', 'totalPrice', 'name', 'phone'], $invoice);
        }, $result));
        return $response;
    }

    private function getInvoice($id)
    {
        $result = $this->invoiceModel->findById($id);

        if (!$result) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong invoice"]);
            return $response;
        }

        //populate
        $user = $this->userModel->findById($result['userId']);

        $result['userId'] = $user;
        //

        $allDetailInvoice = $this->detailInvoiceModel->find(['invoiceId' => $result['invoiceId']]);

        $result['items'] = [];

        foreach ($allDetailInvoice as $detailInvoice) {
            $product = $this->productModel->findById($detailInvoice['productId']);

            //populate
            $category = $this->categoryModel->findById($product['categoryId']);

            $product['categoryId'] = $category;

            $manufacturer = $this->manufacturerModel->findById($product['manufacturerId']);

            $product['manufacturerId'] = $manufacturer;
            //

            $detailInvoice['productId'] = $product;

            $result['items'][] = ['productId' => $detailInvoice['productId'], 'quantity' => $detailInvoice['quantity'], 'size' => $detailInvoice['size']];
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(
            formatRes::getData(['invoiceId', 'userId', 'address', 'note', 'state', 'orderDate', 'items', 'paymentMethod', 'totalPrice', 'name', 'phone'], $result)
        );
        return $response;
    }

    private function getInvoiceByUserId($userId)
    {
        $result = $this->invoiceModel->find(["userId" => $userId]);

        if (!$result) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong invoice"]);
            return $response;
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(array_map(function ($invoice) {
            //populate
            $user = $this->userModel->findById($invoice['userId']);

            $invoice['userId'] = $user;
            //

            $allDetailInvoice = $this->detailInvoiceModel->find(['invoiceId' => $invoice['invoiceId']]);

            $invoice['items'] = [];

            foreach ($allDetailInvoice as $detailInvoice) {
                $product = $this->productModel->findById($detailInvoice['productId']);

                //populate
                $category = $this->categoryModel->findById($product['categoryId']);

                $product['categoryId'] = $category;

                $manufacturer = $this->manufacturerModel->findById($product['manufacturerId']);

                $product['manufacturerId'] = $manufacturer;
                //

                $detailInvoice['productId'] = $product;

                $invoice['items'][] = ['productId' => $detailInvoice['productId'], 'quantity' => $detailInvoice['quantity'], 'size' => $detailInvoice['size']];
            }

            return formatRes::getData(['invoiceId', 'userId', 'address', 'note', 'state', 'orderDate', 'items', 'paymentMethod', 'totalPrice', 'name', 'phone'], $invoice);
        }, $result));
        return $response;
    }

    private function addInvoice()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $user = $this->userModel->findById($input['userId']);

        if (!$user) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong user"]);
            return $response;
        }

        foreach ($input['items'] as $item) {
            $detailProduct = $this->detailProductModel->findOne(["productId" => $item['productId'], "size" => $item['size']]);

            if (!$detailProduct) {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(["success" => false, "message" => "wrong item"]);
                return $response;
            }

            if ((int) $item['quantity'] > $detailProduct['quantity']) {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(["success" => false, "message" => "product out of stock"]);
                return $response;
            }
        }

        $total = 0;

        foreach ($input['items'] as $item) {
            $detailProduct = $this->detailProductModel->findOne(["productId" => $item['productId'], "size" => $item['size']]);
            $this->detailProductModel->update(["detailProductId" => $detailProduct['detailProductId'], "quantity" => $detailProduct['quantity'] - (int) $item['quantity']]);

            $product = $this->productModel->findById($item['productId']);

            $total += (1 - $product['discount'] / 100) * $product['price'] * $item['quantity'];
        }

        $input['totalPrice'] = $total;
        $input = $this->invoiceModel->insert($input);

        foreach ($input['items'] as $item) {
            $item['invoiceId'] = $input['invoiceId'];

            $this->detailInvoiceModel->insert($item);
        }

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode(["success" => true, "message" => "add successfully"]);
        return $response;
    }

    private function updateInvoice()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $invoice = $this->invoiceModel->findOne(['invoiceId' => $input['invoiceId']]);

        if (!$invoice) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong invoice"]);
            return $response;
        }

        if (isset($input['state'])) {
            if ($input['state'] == "Cancel") {
                if ($invoice['state'] != "Pending") {
                    $response['status_code_header'] = 'HTTP/1.1 200 OK';
                    $response['body'] = json_encode(["success" => false, "message" => "can only cancel in pending state"]);
                    return $response;
                }
            }
        }

        $this->invoiceModel->update($input);

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode(["success" => true, "message" => "update successfully"]);
        return $response;
    }

    private function deleteInvoice()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $invoice = $this->invoiceModel->findOne(['invoiceId' => $input['invoiceId']]);

        if (!$invoice) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong invoice"]);
            return $response;
        }

        $this->invoiceModel->delete($input['invoiceId']);
        $this->detailInvoiceModel->deleteByInvoiceId($input['invoiceId']);

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