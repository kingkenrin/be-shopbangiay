<?php
namespace Src\Controller;
use Src\Model\UserModel;

class SignUpController
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
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->signUp();
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

    private function signUp()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $user = $this->userModel->findOne(["username" => $input['username']]);

        if ($user) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "username has been used"]);
            return $response;
        }

        $hashPassword = password_hash($input['password'], PASSWORD_DEFAULT);

        $input['password'] = $hashPassword;

        $this->userModel->insert($input);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["success" => true, "message" => "create account successfully"]);
        return $response;
    }
}