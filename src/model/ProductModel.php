<?php
namespace Src\Model;

class ProductModel
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
                productId, name, price, mainImage, description, manufacturerId, categoryId, discount
            FROM
                product;
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
                productId, name, price, mainImage, description, manufacturerId, categoryId, discount
            FROM
                product
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
                productId, name, price, mainImage, description, manufacturerId, categoryId, discount
            FROM
                product
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
                productId, name, price, mainImage, description, manufacturerId, categoryId, discount
            FROM
                product
            WHERE productId = ?;
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
            INSERT INTO product 
                (name, price, mainImage, description, manufacturerId, categoryId, discount)
            VALUES
                (:name, :price, :mainImage, :description, :manufacturerId, :categoryId, :discount);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'name' => $input['name'],
                'price' => (double) $input['price'],
                'mainImage' => $input['mainImage'] ?? null,
                'description' => $input['description'] ?? null,
                'manufacturerId' => $input['manufacturerId'] ?? null,
                'categoryId' => $input['categoryId'] ?? null,
                'discount' => $input['discount'] ?? null,
            ));

            $lastId = $this->db->lastInsertId();
            $input['productId'] = $lastId;

            return $input;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update(array $input)
    {
        $statement = "
            UPDATE product
            SET 
                name = COALESCE(:name, name), 
                price = COALESCE(:price, price), 
                mainImage = COALESCE(:mainImage, mainImage), 
                description = COALESCE(:description, description), 
                manufacturerId = COALESCE(:manufacturerId, manufacturerId), 
                categoryId = COALESCE(:categoryId, categoryId),
                discount = COALESCE(:discount, discount)
            WHERE productId = :productId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'productId' => $input['productId'],
                'name' => $input['name']??null,
                'price' => $input['price'] ?? null,
                'mainImage' => $input['mainImage'] ?? null,
                'description' => $input['description'] ?? null,
                'manufacturerId' => $input['manufacturerId'] ?? null,
                'categoryId' => $input['categoryId'] ?? null,
                'discount' => $input['discount'] ?? null,
            ));

            return $input;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM product
            WHERE productId = :productId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('productId' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
