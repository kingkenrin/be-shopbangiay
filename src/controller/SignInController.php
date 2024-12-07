<?php
namespace Src\Controller;
use Src\Model\UserModel;

class SignInController
{
    private $db;
    private $requestMethod;
    private $userModel;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->userModel = new UserModel($db);
    }

    public function processRequest()
    {
        // $this->setCorsHeaders();

        // Xử lý yêu cầu OPTIONS (Preflight)
        // if ($this->requestMethod === 'OPTIONS') {
        //     header("HTTP/1.1 204 No Content");
        //     exit;
        // }

        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->signIn();
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

    private function signIn()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $user = $this->userModel->findOne(["username" => $input['username']]);

        if (!$user) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong user"]);
            return $response;
        }

        $check = password_verify($input['password'], $user['password']);

        if (!$check) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong password"]);
            return $response;
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["success" => true, "message" => "login successfully", "userId" => $user['userId']]);
        return $response;
    }

    private function notFoundResponse(){
        $response['status_code_header'] = 'HTTP/1.1 404 NOT FOUND';
        $response['body'] = json_encode(["success" => false, "message" => "route not found"]);
        return $response;
    }
}