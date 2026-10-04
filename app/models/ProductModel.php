<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primary_key = 'id';

    /**
     * Only these columns can be mass-assigned via insert()/update()
     */
    protected $fillable = ['product_name', 'description', 'price', 'quantity'];

    /**
     * The products table only has created_at (no updated_at column),
     * so we don't use the framework's automatic timestamp handling.
     */
    protected $timestamps = false;
}
