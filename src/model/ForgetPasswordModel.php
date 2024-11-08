<?php
namespace Src\Model;

class ForgetPasswordModel
{

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findOne($input)
    {
        $conditions = [];
        $params = [];

        foreach ($input as $key => $value) {
            $conditions[] = "$key = ?";
            $params[] = $value;
        }

        $conditions = implode(" AND ", $conditions);

        $statement = "
            SELECT
                email, code
            FROM
                forgetpassword
            WHERE " . $conditions;

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute($params);
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function findById($id)
    {
        $statement = "
            SELECT
                email, code
            FROM
                forgetpassword
            WHERE forgetPasswordId = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute([$id]);
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(array $input)
    {
        $statement = "
            INSERT INTO forgetpassword 
                (email, code)
            VALUES
                (:email, :code);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'email' => $input['email'],
                'code' => $input['code'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update(array $input)
    {
        $statement = "
            UPDATE forgetpassword
            SET 
                code = COALESCE(:code, code)
            WHERE email = :email;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'code' => $input['code']??null,
                'email' => $input['email']??null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete(array $input)
    {
        $statement = "
            DELETE FROM forgetpassword
            WHERE email = :email;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('email' => $input['email']??null));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
