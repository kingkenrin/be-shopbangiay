<?php
namespace Src\Model;

class DetailInvoiceModel
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
                detailInvoiceId, invoiceId, productId, size, quantity
            FROM
                detailinvoice;
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
                detailInvoiceId, invoiceId, productId, size, quantity
            FROM
                detailinvoice
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
                detailInvoiceId, invoiceId, productId, size, quantity
            FROM
                detailinvoice
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
                detailInvoiceId, invoiceId, productId, size, quantity
            FROM
                detailinvoice
            WHERE detailInvoiceId = ?;
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
            INSERT INTO detailinvoice 
                (invoiceId, productId, size, quantity)
            VALUES
                (:invoiceId, :productId, :size, :quantity);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'invoiceId' => $input['invoiceId'],
                'productId' => $input['productId'] ?? null,
                'size' => $input['size'] ?? null,
                'quantity' => $input['quantity'] ?? null,
            ));

            return $input;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    // public function updateByInvoiceId(array $input)
    // {
    //     $statement = "
    //         UPDATE detailinvoice
    //         SET 
    //             address = COALESCE(:address, address), 
    //             note = COALESCE(:note, note), 
    //             state = COALESCE(:state, state), 
    //         WHERE invoiceId = :invoiceId;
    //     ";

    //     try {
    //         $statement = $this->db->prepare($statement);
    //         $statement->execute(array(
    //             'invoiceId' => $input['invoiceId'],
    //             'address' => $input['address'] ?? null,
    //             'note' => $input['note'] ?? null,
    //             'state' => $input['state'] ?? null,
    //         ));

    //         return $input;
    //     } catch (\PDOException $e) {
    //         exit($e->getMessage());
    //     }
    // }

    public function delete($id)
    {
        $statement = "
            DELETE FROM detailinvoice
            WHERE detailInvoiceId = :detailInvoiceId;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('detailInvoiceId' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function deleteByInvoiceId($id)
    {
        $statement = "
            DELETE FROM detailinvoice
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
