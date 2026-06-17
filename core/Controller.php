<?php
class Controller {
    protected function render($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . '/../app/views/' . $view . '.php';
        require_once $viewFile;
    }
}