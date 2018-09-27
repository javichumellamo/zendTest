<?php

namespace Bookstore\Controllers;

use Bookstore\Core\Request;
use Bookstore\Utils\DependencyInjector;

abstract class AbstractController {
    protected $request;
    protected $db;
    protected $config;
    protected $view;
    protected $log;
    protected $customerId;
    protected $di;

    public function __construct(DependencyInjector $di, Request $request) {
        $this->request = $request;
        $this->di = $di;
        $this->db = $this->di->get("PDO");
        $this->log = $this->di->get("Logger");
        $this->view = $this->di->get("Twig_Environment");
        $this->config = $this->di->get("Utils\Config");
    }

    public function setCustomerId(int $customerId) {
        $this->customerId = $customerId;
    }

    protected function render(string $template, array $params): string {
        return $this->view->loadTemplate($template)->render($params);
    }
}