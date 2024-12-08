<?php
namespace Src\Model;

class FeedbackModel
{

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function findAll()
    {
        $statement = "
            SELECT 
                feedbackId, userId, name, email, phone, address, content, isHandle, createdAt
            FROM
                feedback;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($input)
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
                feedbackId, userId, name, email, phone, address, content, isHandle, createdAt
            FROM
                feedback
            WHERE " . $conditions;

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute($params);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
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
                feedbackId, userId, name, email, phone, address, content, isHandle, createdAt
            FROM
                feedback
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
                feedbackId, userId, name, email, phone, address, content, isHandle, createdAt
            FROM
                feedback
            WHERE feedbackId = ?;
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
            INSERT INTO feedback 
                (userId, name, email, phone, address, content)
            VALUES
                (:userId, :name, :email, :phone, :address, :content);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'userId' => $input['userId'],
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'address' => $input['address'],
                'content' => $input['content'],
                // 'createdAt' => date('j/n/Y'),
            ));

            $lastId = $this->db->lastInsertId();
            $input['categoryId'] = $lastId;

            return $input;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update(array $input)
    {
        $statement = "
            UPDATE feedback
            SET 
                userId = COALESCE(:userId, userId),
                name = COALESCE(:name, name),
                email = COALESCE(:email, email),
                phone = COALESCE(:phone, phone),
                address = COALESCE(:address, address),
                content = COALESCE(:content, content),
                isHandle = COALESCE(:isHandle, isHandle)
            WHERE feedbackId = :feedbackId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'feedbackId' => $input['feedbackId'],
                'userId' => $input['userId'] ?? null,
                'name' => $input['name'] ?? null,
                'email' => $input['email'] ?? null,
                'phone' => $input['phone'] ?? null,
                'address' => $input['address'] ?? null,
                'content' => $input['content'] ?? null,
                'isHandle' => $input['isHandle'] ?? null,
            ));



            return $input;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM feedback
            WHERE feedbackId = :feedbackId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('feedbackId' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
