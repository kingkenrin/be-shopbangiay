<?php
namespace Src\Model;

class ManufacturerModel
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
                manufacturerId, name
            FROM
                manufacturer;
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
                manufacturerId, name
            FROM
                manufacturer
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
                manufacturerId, name
            FROM
                manufacturer
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
                manufacturerId, name
            FROM
                manufacturer
            WHERE manufacturerId = ?;
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
            INSERT INTO manufacturer 
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
            $input['manufacturerId'] = $lastId;

            return $input;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update(array $input)
    {
        $statement = "
            UPDATE manufacturer
            SET 
                name = COALESCE(:name, name)
            WHERE manufacturerId = :manufacturerId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'manufacturerId' => $input['manufacturerId'],
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
            DELETE FROM manufacturer
            WHERE manufacturerId = :manufacturerId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('manufacturerId' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
