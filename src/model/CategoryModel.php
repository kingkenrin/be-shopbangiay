<?php
namespace Src\Model;

class CategoryModel
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
                categoryId, name
            FROM
                category;
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
                categoryId, name
            FROM
                category
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
                categoryId, name
            FROM
                category
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
                categoryId, name
            FROM
                category
            WHERE categoryId = ?;
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
            INSERT INTO category 
                (name)
            VALUES
                (:name);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'name' => $input['name'],
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
            UPDATE category
            SET 
                name = COALESCE(:name, name) 
            WHERE categoryId = :categoryId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'categoryId' => $input['categoryId'],
                'name' => $input['name'] ?? null,
            ));



            return $input;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM category
            WHERE categoryId = :categoryId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('categoryId' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
