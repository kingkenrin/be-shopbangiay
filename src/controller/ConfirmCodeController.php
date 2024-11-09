<?php
namespace Src\Controller;
use Src\Model\UserModel;
use Src\Model\ForgetPasswordModel;

class ConfirmCodeController
{
    private $db;
    private $requestMethod;
    private $userModel;
    private $forgetPasswordModel;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->userModel = new UserModel($db);
        $this->forgetPasswordModel = new ForgetPasswordModel($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->confirmCode();
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

    private function confirmCode()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $user = $this->userModel->findOne(["email" => $input['email']]);

        $forgetPassword = $this->forgetPasswordModel->findOne(["email" => $input['email']]);

        if (!$forgetPassword) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong email"]);
            return $response;
        }

        if ($forgetPassword['code'] == $input['code']) {
            $forgetPassword = $this->forgetPasswordModel->delete(["email" => $input['email']]);

            $hashPassword = password_hash($input['newPassword'], PASSWORD_DEFAULT);

            $input['password'] = $hashPassword;

            $input['userId'] = $user['userId'];

            $this->userModel->update($input);

            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => true, "message" => "Change password successfully"]);
            return $response;
        }
        else{
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong code"]);
            return $response;
        }
    }
}