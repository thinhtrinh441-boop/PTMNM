<?php

require_once __DIR__ . '/../models/ProductModel.php';

class CategoryApiController
{
    public function index()
    {
        header('Content-Type: application/json');

        echo json_encode(
            ProductModel::getCategories()
        );
    }
}