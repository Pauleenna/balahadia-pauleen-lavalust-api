<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->model('ProductModel');
        $this->call->library('form_validation');
    }

    /**
     * Runs automatically before every action in this controller
     * (see Controller::__construct()). This is our authentication
     * guard: only logged-in users may reach any /products/* route.
     */
    public function before_action()
    {
        if (empty($_SESSION['auth_user_id'])) {
            $_SESSION['auth_message'] = 'Please log in to access product management.';
            redirect('login');
            exit;
        }
    }

    /**
     * Read - display all products
     */
    public function index()
    {
        $data['page_title'] = 'Product Management';
        $data['products']   = $this->ProductModel->all();
        $data['flash']      = $this->get_flash();
        $data['username']   = $_SESSION['auth_username'] ?? '';

        $this->call->view('products', $data);
    }

    /**
     * Show the create-product form
     */
    public function create()
    {
        $data['page_title'] = 'Add Product';
        $data['mode']       = 'create';
        $data['product']    = null;
        $data['errors']     = [];

        $this->call->view('product_form', $data);
    }

    /**
     * Create - handle create-product form submission
     */
    public function store()
    {
        $rules = [
            'product_name' => 'required|min_length[2]|max_length[100]',
            'price'        => 'required|numeric|greater_than_equal_to[0]',
            'quantity'     => 'required|numeric|greater_than_equal_to[0]',
        ];

        if ($this->form_validation->validate($rules)) {

            $this->ProductModel->insert([
                'product_name' => trim($this->io->post('product_name')),
                'description'  => trim((string) $this->io->post('description')),
                'price'        => (float) $this->io->post('price'),
                'quantity'     => (int) $this->io->post('quantity'),
            ]);

            $this->set_flash('Product created successfully.');
            redirect('products');
            exit;
        }

        $data['errors']     = $this->form_validation->get_errors();
        $data['page_title'] = 'Add Product';
        $data['mode']       = 'create';
        $data['product']    = $this->io->post();

        $this->call->view('product_form', $data);
    }

    /**
     * Show the edit-product form
     */
    public function edit($id)
    {
        $product = $this->ProductModel->find((int) $id);

        if (!$product) {
            $this->set_flash('Product not found.');
            redirect('products');
            exit;
        }

        $data['page_title'] = 'Edit Product';
        $data['mode']       = 'edit';
        $data['product']    = $product;
        $data['errors']     = [];

        $this->call->view('product_form', $data);
    }

    /**
     * Update - handle edit-product form submission
     */
    public function update($id)
    {
        $id      = (int) $id;
        $product = $this->ProductModel->find($id);

        if (!$product) {
            $this->set_flash('Product not found.');
            redirect('products');
            exit;
        }

        $rules = [
            'product_name' => 'required|min_length[2]|max_length[100]',
            'price'        => 'required|numeric|greater_than_equal_to[0]',
            'quantity'     => 'required|numeric|greater_than_equal_to[0]',
        ];

        if ($this->form_validation->validate($rules)) {

            $this->ProductModel->update($id, [
                'product_name' => trim($this->io->post('product_name')),
                'description'  => trim((string) $this->io->post('description')),
                'price'        => (float) $this->io->post('price'),
                'quantity'     => (int) $this->io->post('quantity'),
            ]);

            $this->set_flash('Product updated successfully.');
            redirect('products');
            exit;
        }

        $data['errors']     = $this->form_validation->get_errors();
        $data['page_title'] = 'Edit Product';
        $data['mode']       = 'edit';
        $data['product']    = array_merge($product, $this->io->post() ?: []);
        $data['product']['id'] = $id;

        $this->call->view('product_form', $data);
    }

    /**
     * Delete - remove a product
     */
    public function delete($id)
    {
        $id = (int) $id;

        if ($this->ProductModel->find($id)) {
            $this->ProductModel->delete($id);
            $this->set_flash('Product deleted successfully.');
        }

        redirect('products');
    }

    /**
     * Small session-based flash message helper (same pattern as UsersController)
     */
    private function set_flash($message)
    {
        $_SESSION['flash_message'] = $message;
    }

    private function get_flash()
    {
        $message = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);
        return $message;
    }
}
