<?php
namespace Src\Controller;
use Src\Model\UserModel;
use Src\Model\ForgetPasswordModel;

use PHPMailer\PHPMailer\PHPMailer;

class ForgetPasswordController
{
    private $db;
    private $requestMethod;
    private $userModel;

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
                $response = $this->forgetPassWord();
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

    private function forgetPassWord()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        $user = $this->userModel->findOne(["email" => $input['email']]);

        $code = json_decode(rand(1001, 9999));

        if (!$user) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(["success" => false, "message" => "wrong email"]);
            return $response;
        }

        $forgetPassword = $this->forgetPasswordModel->findOne(["email" => $input['email']]);

        if ($forgetPassword) {
            $this->forgetPasswordModel->update(["email" => $user['email'], "code" => $code]);

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'shopcaulonguit@gmail.com';
            $mail->Password = 'znpf vyru qrdo xvhv';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('shopcaulonguit@gmail.com', 'Shop bán giày');
            $mail->addAddress($user['email'], 'Me');
            $mail->Subject = 'Mã xác nhận';
            $mail->isHTML(TRUE);
            $mail->Body = "<html> Chào " . $user['username'] . " ,<br>

Để hoàn tất quy trình, vui lòng nhập mã xác nhận dưới đây:<br>

<h1>Mã xác nhận của bạn là: $code</h1>

Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này hoặc liên hệ với chúng tôi để được hỗ trợ.
<br>
Trân trọng,<br>
Shop bán giày uit
</html>";

            if (!$mail->send()) {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(["success" => false, "message" => "cant send email"]);
                return $response;
            } else {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(["success" => true, "message" => "confirmation code sent"]);
                return $response;
            }
        } else {
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'shopcaulonguit@gmail.com';
            $mail->Password = 'znpf vyru qrdo xvhv';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('shopcaulonguit@gmail.com', 'Shop bán giày');
            $mail->addAddress($user['email'], 'Me');
            $mail->Subject = 'Mã xác nhận';
            $mail->isHTML(TRUE);
            $mail->Body = "<html> Chào " . $user['username'] . " ,<br>

Để hoàn tất quy trình, vui lòng nhập mã xác nhận dưới đây:<br>

<h1>Mã xác nhận của bạn là: $code</h1>

Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này hoặc liên hệ với chúng tôi để được hỗ trợ.
<br>
Trân trọng,<br>
Shop bán giày uit
</html>";

            if (!$mail->send()) {
                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(["success" => false, "message" => "cant send email"]);
                return $response;
            } else {
                $this->forgetPasswordModel->insert(["email" => $user['email'], "code" => $code]);

                $response['status_code_header'] = 'HTTP/1.1 200 OK';
                $response['body'] = json_encode(["success" => true, "message" => "confirmation code sent"]);
                return $response;
            }

        }
    }
}