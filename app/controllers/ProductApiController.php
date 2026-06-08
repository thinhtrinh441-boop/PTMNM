<?php

require_once __DIR__ . '/../models/ProductModel.php';

class ProductApiController
{
    public function index()
    {
        header('Content-Type: application/json');

        $products = ProductModel::getAll();

        $result = [];

        foreach ($products as $p) {

            $result[] = [
                'id' => $p->getID(),
                'name' => $p->getName(),
                'description' => $p->getDescription(),
                'price' => $p->getPrice(),
                'image' => $p->getImage(),
                'category_id' => $p->getCategoryId(),
                'category_name' => $p->getCategoryName()
            ];
        }

        echo json_encode($result);
    }

    public function show($id)
    {
        header('Content-Type: application/json');

        $p = ProductModel::getById($id);

        if (!$p) {

            http_response_code(404);

            echo json_encode([
                'message' => 'Product not found'
            ]);

            return;
        }

        echo json_encode([
            'id' => $p->getID(),
            'name' => $p->getName(),
            'description' => $p->getDescription(),
            'price' => $p->getPrice(),
            'image' => $p->getImage(),
            'category_id' => $p->getCategoryId(),
            'category_name' => $p->getCategoryName()
        ]);
    }

    public function store()
    {
        header('Content-Type: application/json');

        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        $product = new ProductModel(
            null,
            $data['name'] ?? '',
            $data['description'] ?? '',
            $data['price'] ?? 0,
            $data['image'] ?? '',
            $data['category_id'] ?? null
        );

        if ($product->insert()) {

            http_response_code(201);

            echo json_encode([
                'message' => 'Product created successfully'
            ]);

        } else {

            http_response_code(400);

            echo json_encode([
                'message' => 'Create failed'
            ]);
        }
    }

    public function update($id)
    {
        header('Content-Type: application/json');

        $oldProduct = ProductModel::getById($id);

        if (!$oldProduct) {

            http_response_code(404);

            echo json_encode([
                'message' => 'Product not found'
            ]);

            return;
        }

        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        $product = new ProductModel(
            $id,
            $data['name'] ?? $oldProduct->getName(),
            $data['description'] ?? $oldProduct->getDescription(),
            $data['price'] ?? $oldProduct->getPrice(),
            $data['image'] ?? $oldProduct->getImage(),
            $data['category_id'] ?? $oldProduct->getCategoryId()
        );

        if ($product->update()) {

            echo json_encode([
                'message' => 'Product updated successfully'
            ]);

        } else {

            http_response_code(400);

            echo json_encode([
                'message' => 'Update failed'
            ]);
        }
    }

    public function destroy($id)
    {
        header('Content-Type: application/json');

        if (ProductModel::delete($id)) {

            echo json_encode([
                'message' => 'Product deleted successfully'
            ]);

        } else {

            http_response_code(400);

            echo json_encode([
                'message' => 'Delete failed'
            ]);
        }
    }
}