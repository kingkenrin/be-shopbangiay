<?php
namespace Src\Model;

// date_default_timezone_set('Asia/Ho_Chi_Minh');
class InvoiceModel
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
                invoiceId, userId, address, note, orderDate, state, totalPrice, paymentMethod, name, phone
            FROM
                invoice;
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
                invoiceId, userId, address, note, orderDate, state, totalPrice, paymentMethod, name, phone
            FROM
                invoice
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
                invoiceId, userId, address, note, orderDate, state, totalPrice, paymentMethod, name, phone
            FROM
                invoice
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
                invoiceId, userId, address, note, orderDate, state, totalPrice, paymentMethod, name, phone
            FROM
                invoice
            WHERE invoiceId = ?;
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
            INSERT INTO invoice 
                (userId, address, note, state, totalPrice, paymentMethod, name, phone)
            VALUES
                (:userId, :address, :note, :state, :totalPrice, :paymentMethod, :name, :phone);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'userId' => $input['userId'],
                'address' => $input['address'] ?? null,
                'note' => $input['note'] ?? null,
                'totalPrice' => $input['totalPrice'] ?? null,
                'paymentMethod' => $input['paymentMethod'] ?? null,
                'name' => $input['name'] ?? null,
                'phone' => $input['phone'] ?? null,
                'state' => "Pending",
                // 'orderDate' => date('j/n/Y'),
            ));

            $lastId = $this->db->lastInsertId();
            $input['invoiceId'] = $lastId;

            return $input;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update(array $input)
    {
        $statement = "
            UPDATE invoice
            SET 
                address = COALESCE(:address, address), 
                note = COALESCE(:note, note), 
                state = COALESCE(:state, state)
            WHERE invoiceId = :invoiceId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'invoiceId' => $input['invoiceId'],
                'address' => $input['address'] ?? null,
                'note' => $input['note'] ?? null,
                'state' => $input['state'] ?? null,
            ));

            return $input;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM invoice
            WHERE invoiceId = :invoiceId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('invoiceId' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
