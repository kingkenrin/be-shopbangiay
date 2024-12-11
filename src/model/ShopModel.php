<?php
namespace Src\Model;

class ShopModel
{

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }
    // public function findAll()
    // {
    //     $statement = "
    //         SELECT 
    //             shopId, logo, name, about, address, email, phone, phone
    //         FROM
    //             shop;
    //     ";

    //     try {
    //         $statement = $this->db->query($statement);
    //         $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
    //         return $result;
    //     } catch (\PDOException $e) {
    //         exit($e->getMessage());
    //     }
    // }

    // public function find($input)
    // {
    //     $conditions = [];
    //     $params = [];

    //     foreach ($input as $key => $value) {
    //         $conditions[] = "$key = ?";
    //         $params[] = $value;
    //     }

    //     $conditions = implode(" AND ", $conditions);

    //     $statement = "
    //         SELECT
    //             userId, username, password, name, avatar, phone, email, address, birthday, role
    //         FROM
    //             user
    //         WHERE " . $conditions;

    //     try {
    //         $statement = $this->db->prepare($statement);
    //         $statement->execute($params);
    //         $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
    //         return $result;
    //     } catch (\PDOException $e) {
    //         exit($e->getMessage());
    //     }
    // }

    public function get()
    {
        $statement = "
            SELECT 
                shopId, logo, logodark, name, about, address, email, phone, hotline
            FROM
                shop;";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    // public function findById($id)
    // {
    //     $statement = "
    //         SELECT
    //             userId, username, password, name, avatar, phone, email, address, birthday, role
    //         FROM
    //             user
    //         WHERE userId = ?;
    //     ";

    //     try {
    //         $statement = $this->db->prepare($statement);
    //         $statement->execute([$id]);
    //         $result = $statement->fetch(\PDO::FETCH_ASSOC);
    //         return $result;
    //     } catch (\PDOException $e) {
    //         exit($e->getMessage());
    //     }
    // }

    public function insert(array $input)
    {
        $statement = "
            INSERT INTO shop 
                (logo, name, about, address, email, phone, hotline, logodark)
            VALUES
                (:logo, :name, :about, :address, :email, :phone, :hotline, :logodark);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'logo' => $input['logo'] ?? null,
                'logodark' => $input['logodark'] ?? null,
                'name' => $input['name'] ?? null,
                'about' => $input['about'] ?? null,
                'address' => $input['address'] ?? null,
                'email' => $input['email'] ?? null,
                'phone' => $input['phone'] ?? null,
                'hotline' => $input['hotline'] ?? null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update(array $input)
    {
        $statement = "
            UPDATE shop
            SET 
                logo = COALESCE(:logo, logo), 
                logodark = COALESCE(:logodark, logodark), 
                name = COALESCE(:name, name), 
                about = COALESCE(:about, about), 
                address = COALESCE(:address, address), 
                email = COALESCE(:email, email), 
                phone = COALESCE(:phone, phone), 
                hotline = COALESCE(:hotline, hotline) 
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'logo' => $input['logo'] ?? null,
                'logodark' => $input['logodark'] ?? null,
                'name' => $input['name'] ?? null,
                'about' => $input['about'] ?? null,
                'address' => $input['address'] ?? null,
                'email' => $input['email'] ?? null,
                'phone' => $input['phone'] ?? null,
                'hotline' => $input['hotline'] ?? null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    // public function delete($id)
    // {
    //     $statement = "
    //         DELETE FROM user
    //         WHERE userId = :userId;
    //     ";

    //     try {
    //         $statement = $this->db->prepare($statement);
    //         $statement->execute(array('userId' => $id));
    //         return $statement->rowCount();
    //     } catch (\PDOException $e) {
    //         exit($e->getMessage());
    //     }
    // }
}
