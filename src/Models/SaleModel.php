<?php

namespace Bookstore\Models;

use Bookstore\Domain\Sale;
use Bookstore\Exceptions\DbException;
use Bookstore\Exceptions\NotFoundException;
use PDO;

class SaleModel extends AbstractModel {
    const CLASSNAME = '\Bookstore\Domain\Sale';

    public function getByUser(int $userId): array {
        $query = 'SELECT * FROM sale WHERE customer_id = :user';
        $sth = $this->db->prepare($query);
        $sth->execute(['user' => $userId]);

        return $sth->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
 
    }

    public function get(int $saleId): Sale {
        //TODO
    }

    public function create(Sale $sale) {
        $this->db->beginTransaction();
        $query =<<<SQL
INSERT INTO sale(customer_id, date)
VALUES (:id, NOW())
SQL;
       
        $sth = $this->db->prepare($query);

        if (!$sth->execute(["id" => $sale->getCustomerId()])){
            $this->db->rollback();
            throw new DbException($sth->erorInfo()[2]);
        }

        $saleId = $this->db->lastInsertId();

        $query =<<<SQL
INSERT INTO sale_book(sale_id, book_id, amount)
VALUES (:sale, :book, :amount)
SQL;

        $sth=$this->db->prepare($query);
        $sth->bindValue("sale",$saleId);

        foreach($sale->getBooks() as $bookId => $amount)
        {
            $sth->bindValue("book", $bookId);
            $sth->bindValue("amount", $amount);
            if(!$sth->execute())
            {
                $this->db->rollback();
                throw new DbException($sth->errorInfo()[2]);
            }
        }

        $this->db->commit(); 
    }
}