<?php

namespace Bookstore\Controllers;

use Bookstore\Domain\Sale;
use Bookstore\Models\SaleModel;

class SalesController extends AbstractController {
    public function add($id): string {
        $bookId = (int)$id;
        $salesModel = new SaleModel($this->db);

        $sale= new Sale();
        $sale->setCustomerId($this->customerId);
        $sale->addBook($bookId);

        try{
            $salesModel->create($sale);
        } catch(\Exception $e)
        {
            $properties = ["errorMessage" => "Error en la creación de venta"];
            return $this->render("error.twig", $properties);
        }
        return $this->getByUser();
    }

    public function getByUser(): string {
        $salesModel = new SaleModel($this->db);
        $sales = $salesModel->getByUser($this->customerID);

        //Uso de sistema template basado en TWIG
        $properties = ["sales" => $sales];
        return $this->render("sales.twig", $properties);
    }

    public function get($saleId): string {
        //TODO
    }
}