<?php
namespace Src\Controller;

use Src\Model\UserModel;
use Src\Config\Cloudinary;
use Src\Util\formatRes;
use Src\Util\getFormdata;

class UserController
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
            case 'GET':
                if (isset($_GET['userId'])) {
                    $response = $this->getUser($_GET['userId']);
                } else {
                    $response = $this->getAllUsers();
                }
                ;
                break;
            case 'POST':
                $response = $this->addUser();
                break;
            case 'PUT':
                $response = $this->updateUser();
                break;
            case 'DELETE':
                $response = $this->deleteUser();
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

    private function getAllUsers()
    {
        $result = $this->userModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(array_map(function ($user) {
            return formatRes::getData(['username', 'name', 'avatar', 'phone', 'email', 'address', 'birthday', 'role'], $user);
        }, $result));
        return $response;
    }

    private function getUser($id)
    {
        $result = $this->userModel->findById($id);

        if (!$result) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong user"]);
            return $response;
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(formatRes::getData(['username', 'name', 'avatar', 'phone', 'email', 'address', 'birthday', 'role'], $result));
        return $response;
    }

    private function addUser()
    {
        if (isset($_FILES['avatar'])) {
            $upload = (new Cloudinary())->uploadImage($_FILES['avatar']);
            $input['avatar'] = $upload['secure_url'];
        }
        $input = $_POST;


        $user = $this->userModel->find(["username" => $input['username']]);

        if ($user) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "username exists"]);
            return $response;
        }

        if (isset($input['email'])) {
            $email = $this->userModel->find(["email" => $input['email']]);

            if ($email) {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(["success" => false, "message" => "email exists"]);
                return $response;
            }
        }

        $hashPassword = password_hash($input['password'], PASSWORD_DEFAULT);

        $input['password'] = $hashPassword;

        $this->userModel->insert($input);

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode(formatRes::getData(['username', 'name', 'avatar', 'phone', 'email', 'address', 'birthday', 'role'], $input));
        return $response;
    }

    private function updateUser()
    {
        $input = getFormdata::getData();
        $file = getFormdata::getFile();

        $user = $this->userModel->findById($input['userId']);

        if (!$user) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong user"]);
            return $response;
        }

        if (isset($input['email'])) {
            $email = $this->userModel->find(["email" => $input['email']]);

            if ($email && $input['email'] != $user['email']) {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(["success" => false, "message" => "email exists"]);
                return $response;
            }
        }


        if (isset($input['oldPassword']) && isset($input['newPassword'])) {
            $check = password_verify($input['oldPassword'], $user['password']);

            if (!$check) {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(["success" => false, "message" => "wrong old password"]);
                return $response;
            } else {
                $hash = password_hash($input['newPassword'], PASSWORD_DEFAULT);
                $input['password'] = $hash;
            }
        }

        if ($file) {
            $upload = (new Cloudinary())->uploadImage($file['avatar']);
            $input['avatar'] = $upload['secure_url'];
        }

        $this->userModel->update($input);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(formatRes::getData(['username', 'name', 'avatar', 'phone', 'email', 'address', 'birthday', 'role'], $input));
        return $response;
    }

    private function deleteUser()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $user = $this->userModel->findById($input['userId']);

        if (!$user) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong user"]);
            return $response;
        }

        $this->userModel->delete($input['userId']);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(["success" => true, "message" => "delete successfully"]);
        return $response;
    }
}