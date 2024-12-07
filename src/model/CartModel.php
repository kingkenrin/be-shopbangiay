<?php
namespace Src\Model;

class CartModel
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
                cartId, userId, productId, quantity, size
            FROM
                cart;
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
                cartId, userId, productId, quantity, size
            FROM
                cart
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
                cartId, userId, productId, quantity, size
            FROM
                cart
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
                cartId, userId, productId, quantity, size
            FROM
                cart
            WHERE cartId = ?;
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
            INSERT INTO cart 
                (userId, productId, quantity, size)
            VALUES
                (:userId, :productId, :quantity, :size);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'userId' => $input['userId'],
                'productId' => $input['productId'] ?? null,
                'quantity' => $input['quantity'] ?? null,
                'size' => $input['size'] ?? null,
            ));

            return $input;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function updateByUserIdAndProductIdAndSize(array $input)
    {
        $statement = "
            UPDATE cart
            SET 
                quantity = COALESCE(:quantity, quantity)
            WHERE userId = :userId And productId = :productId And size = :size;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'userId' => $input['userId'],
                'productId' => $input['productId'] ?? null,
                'size' => $input['size'] ?? null,
                'quantity' => $input['quantity'] ?? null,
            ));

            return $input;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM cart
            WHERE cartId = :cartId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('cartId' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function deleteByProductId($input)
    {
        $statement = "
            DELETE FROM cart
            WHERE productId = :productId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'productId' => $input['productId'] ?? null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function deleteByProductIdAndSize($input)
    {
        $statement = "
            DELETE FROM cart
            WHERE productId = :productId And size = :size;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'productId' => $input['productId'] ?? null,
                'size' => $input['size'] ?? null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function deleteByUserIdAndProductIdAndSize($input)
    {
        $statement = "
            DELETE FROM cart
            WHERE userId = :userId And productId = :productId And size = :size;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'userId' => $input['userId'],
                'productId' => $input['productId'] ?? null,
                'size' => $input['size'] ?? null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
