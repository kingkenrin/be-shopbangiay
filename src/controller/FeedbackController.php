<?php
namespace Src\Controller;

use Src\Model\FeedbackModel;
use Src\Model\UserModel;
use Src\Util\formatRes;

class FeedbackController
{
    private $db;
    private $requestMethod;
    private $feedbackModel;
    private $userModel;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->feedbackModel = new FeedbackModel($db);
        $this->userModel = new UserModel($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if (isset($_GET['feedbackId'])) {
                    $response = $this->getFeedback($_GET['feedbackId']);
                } else {
                    if (isset($_GET['userId'])) {
                        $response = $this->getFeedbackByUserId($_GET['userId']);
                    } else {
                        $response = $this->getAllFeedbacks();
                    }
                }

                ;
                break;
            case 'POST':
                $response = $this->addFeedback();
                break;
            case 'PUT':
                $response = $this->updateFeedback();
                break;
            case 'DELETE':
                $response = $this->deleteFeedback();
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

    private function getAllFeedbacks()
    {
        $result = $this->feedbackModel->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(array_map(function ($feedback) {
            return formatRes::getData(['feedbackId', 'userId', 'name', 'email', 'phone', 'address', 'content'], $feedback);
        }, $result));
        return $response;
    }

    private function getFeedback($id)
    {
        $result = $this->feedbackModel->findOne(["feedbackId" => $id]);

        if (!$result) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong feedback"]);
            return $response;
        }

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(
            formatRes::getData(['feedbackId', 'userId', 'name', 'email', 'phone', 'address', 'content'], $result)
        );
        return $response;
    }

    private function getFeedbackByUserId($userId)
    {
        $user = $this->userModel->findById($userId);

        if (!$user) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong user"]);
            return $response;
        }

        $result = $this->feedbackModel->find(['userId' => $userId]);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(array_map(function ($feedback) {
            return formatRes::getData(['feedbackId', 'userId', 'name', 'email', 'phone', 'address', 'content'], $feedback);
        }, $result));
        return $response;
    }

    private function addFeedback()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $this->feedbackModel->insert($input);

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($input);
        return $response;
    }

    private function updateFeedback()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $feedback = $this->feedbackModel->findById($input['feedbackId']);

        if (!$feedback) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong feedback"]);
            return $response;
        }

        $this->feedbackModel->update($input);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode(formatRes::getData(['feedbackId', 'userId', 'name', 'email', 'phone', 'address', 'content'], $input));
        return $response;
    }

    private function deleteFeedback()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $feedback = $this->feedbackModel->findById($input['feedbackId']);

        if (!$feedback) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong feedback"]);
            return $response;
        }

        $this->feedbackModel->delete($input['feedbackId']);

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