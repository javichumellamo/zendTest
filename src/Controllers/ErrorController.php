<?php

namespace Bookstore\Controllers;

class ErrorController extends AbstractController {
    public function notFound(): string
    {
        $properties = ["errorMessage" => "Página no encontrada"];
        return $this->render("error.twig",$properties);
    }
}