<?php
namespace Src\Model;

class UserModel
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
                userId, username, password, name, avatar, phone, email, address, birthday, role
            FROM
                user;
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
                userId, username, password, name, avatar, phone, email, address, birthday, role
            FROM
                user
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
                userId, username, password, name, avatar, phone, email, address, birthday, role
            FROM
                user
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
                userId, username, password, name, avatar, phone, email, address, birthday, role
            FROM
                user
            WHERE userId = ?;
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
            INSERT INTO user 
                (username, password, name, avatar, phone, email, address, birthday, role)
            VALUES
                (:username, :password, :name, :avatar, :phone, :email, :address, :birthday, :role);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'username' => $input['username'],
                'password' => $input['password'],
                'name' => $input['name'] ?? null,
                'avatar' => $input['avatar'] ?? 'https://nupet.vn/wp-content/uploads/2023/10/anh-avatar-cute-meo-nupet-2.jpg',
                'phone' => $input['phone'] ?? null,
                'email' => $input['email'] ?? null,
                'address' => $input['address'] ?? null,
                'birthday' => $input['birthday'] ?? null,
                'role' => $input['role'] ?? null,

            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update(array $input)
    {
        $statement = "
            UPDATE user
            SET 
                password = COALESCE(:password, password),
                name = COALESCE(:name, name),
                avatar = COALESCE(:avatar, avatar),
                phone = COALESCE(:phone, phone),
                email = COALESCE(:email, email),
                address = COALESCE(:address, address),
                birthday = COALESCE(:birthday, birthday),
                role = COALESCE(:role, role)
            WHERE userId = :userId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'userId' => $input['userId'],
                'password' => $input['password']??null,
                'name' => $input['name'] ?? null,
                'avatar' => $input['avatar'] ?? null,
                'phone' => $input['phone'] ?? null,
                'email' => $input['email'] ?? null,
                'address' => $input['address'] ?? null,
                'birthday' => $input['birthday'] ?? null,
                'role' => $input['role'] ?? null,

            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM user
            WHERE userId = :userId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('userId' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
