<?php
namespace Src\Controller;

class PaymentController
{
    private $db;
    private $requestMethod;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->paymentMomo();
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

    private function paymentMomo()
    {
        try {
            $input = (array) json_decode(file_get_contents('php://input'), TRUE);

            // amount, $orderInfo, $items, $userId, $name, $phone, $address, $note

            $orderInfo = "Thanh toán đơn hàng";
            
            // Momo parameters
            $accessKey = 'F8BBA842ECF85';
            $secretKey = 'K951B6PE1waDMi640xX08PD3vg6EkVlz';
            $partnerCode = 'MOMO';
            $redirectUrl = 'http://localhost:5174/payment'; // Redirect URL
            $ipnUrl = 'http://localhost:5174/payment'; // IPN URL
            $requestType = "payWithMethod";

            // Order ID and Request ID (use timestamp for uniqueness)
            $orderId = $partnerCode . time();
            $requestId = $orderId;

            // Extra data to send to MoMo (items, coupon, user details)
            $extraData = json_encode([
                'items' => $input['items'],
                'userId' => $input['userId'],
                'name' => $input['name'],
                'phone' => $input['phone'],
                'address' => $input['address'],
                'note' => $input['note']
            ]);
            $extraData = urlencode($extraData);

            // Raw data string to sign
            $rawSignature = "accessKey=" . $accessKey . "&amount=" . $input['amount'] . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;

            // Generate signature using HMAC-SHA256
            $signature = hash_hmac('sha256', $rawSignature, $secretKey);

            // Request body to send to MoMo API
            $requestBody = json_encode([
                'partnerCode' => $partnerCode,
                'partnerName' => 'Test',
                'storeId' => 'MomoTestStore',
                'requestId' => $requestId,
                'amount' => $input['amount'],
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'requestType' => $requestType,
                'autoCapture' => true,
                'extraData' => $extraData,
                'orderGroupId' => '',
                'signature' => $signature
            ]);

            // cURL request to MoMo API
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://test-payment.momo.vn/v2/gateway/api/create');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($requestBody)
            ]);

            // Execute cURL and get the response
            $res = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new \Exception('Error:' . curl_error($ch));
            }

            curl_close($ch);

            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = $res;
            return $response;
        } catch (\Exception $e) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => $e->getMessage()]);
            return $response;
        }
    }
    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 NOT FOUND';
        $response['body'] = json_encode(["success" => false, "message" => "route not found"]);
        return $response;
    }
}