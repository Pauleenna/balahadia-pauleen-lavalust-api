<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ProductApiController extends Controller
{
    private $auth;

    public function __construct()
    {
        parent::__construct();
        $this->call->library('api');
        $this->call->model('ProductModel');
    }

    /**
     * Require a valid access token (any logged-in user).
     */
    private function authenticate()
    {
        $this->auth = $this->api->require_jwt();
    }

    /**
     * Require a valid access token AND the admin role.
     */
    private function authenticate_admin()
    {
        $this->authenticate();

        if (($this->auth['role'] ?? '') !== 'admin') {
            $this->api->respond_error('Forbidden: admin access required.', 403);
        }
    }

    /**
     * Validate product fields. Returns an array of error messages.
     */
    private function validate(array $body)
    {
        $errors = [];

        $name = trim((string) ($body['product_name'] ?? ''));
        if (strlen($name) < 2 || strlen($name) > 100) {
            $errors['product_name'] = 'product_name is required (2-100 characters).';
        }

        if (!isset($body['price']) || !is_numeric($body['price']) || $body['price'] < 0) {
            $errors['price'] = 'price is required and must be a number >= 0.';
        }

        if (!isset($body['quantity']) || !is_numeric($body['quantity']) || $body['quantity'] < 0) {
            $errors['quantity'] = 'quantity is required and must be a number >= 0.';
        }

        return $errors;
    }

    /**
     * GET /api/products
     */
    public function index()
    {
        $this->api->require_method('GET');
        $this->authenticate();

        $this->api->respond([
            'data' => $this->ProductModel->all(),
        ]);
    }

    /**
     * GET /api/products/{id}
     */
    public function show($id = null)
    {
        $this->api->require_method('GET');
        $this->authenticate();

        $product = $this->ProductModel->find((int) $id);

        if (!$product) {
            $this->api->respond_error('Product not found.', 404);
        }

        $this->api->respond(['data' => $product]);
    }

    /**
     * POST /api/products
     * Body: { "product_name": "...", "description": "...", "price": 0, "quantity": 0 }
     */
    public function store()
    {
        $this->api->require_method('POST');
        $this->authenticate_admin();

        $body   = $this->api->body();
        $errors = $this->validate($body);

        if (!empty($errors)) {
            $this->api->respond(['error' => 'Validation failed.', 'errors' => $errors], 422);
        }

        $id = $this->ProductModel->insert([
            'product_name' => trim($body['product_name']),
            'description'  => trim((string) ($body['description'] ?? '')),
            'price'        => (float) $body['price'],
            'quantity'     => (int) $body['quantity'],
        ]);

        $this->api->respond([
            'message' => 'Product created successfully.',
            'data'    => $this->ProductModel->find((int) $id),
        ], 201);
    }

    /**
     * PUT /api/products/{id}
     * Body: same fields as store()
     */
    public function update($id = null)
    {
        if (!in_array($_SERVER['REQUEST_METHOD'], ['PUT', 'PATCH'], true)) {
            $this->api->respond_error('Method Not Allowed', 405);
        }
        $this->authenticate_admin();

        $id = (int) $id;

        if (!$this->ProductModel->find($id)) {
            $this->api->respond_error('Product not found.', 404);
        }

        $body   = $this->api->body();
        $errors = $this->validate($body);

        if (!empty($errors)) {
            $this->api->respond(['error' => 'Validation failed.', 'errors' => $errors], 422);
        }

        $this->ProductModel->update($id, [
            'product_name' => trim($body['product_name']),
            'description'  => trim((string) ($body['description'] ?? '')),
            'price'        => (float) $body['price'],
            'quantity'     => (int) $body['quantity'],
        ]);

        $this->api->respond([
            'message' => 'Product updated successfully.',
            'data'    => $this->ProductModel->find($id),
        ]);
    }

    /**
     * DELETE /api/products/{id}
     */
    public function delete($id = null)
    {
        $this->api->require_method('DELETE');
        $this->authenticate_admin();

        $id = (int) $id;

        if (!$this->ProductModel->find($id)) {
            $this->api->respond_error('Product not found.', 404);
        }

        $this->ProductModel->delete($id);

        $this->api->respond(['message' => 'Product deleted successfully.']);
    }
}
