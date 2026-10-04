<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primary_key = 'id';

    /**
     * Only these columns can be mass-assigned via insert()/update()
     */
    protected $fillable = ['username', 'email', 'password', 'role', 'is_active'];

    /**
     * Auto-manage created_at / updated_at
     */
    protected $timestamps = true;

    /**
     * Enable soft delete for this model.
     * soft_delete() sets deleted_at instead of removing the row,
     * and all()/find()/etc. automatically exclude soft-deleted rows
     * unless $with_deleted = true is passed.
     */
    protected $has_soft_delete = true;
    protected $soft_delete_column = 'deleted_at';

    /**
     * Get only the soft-deleted (trashed) users.
     *
     * @return array
     */
    public function trashed()
    {
        return $this->db->table($this->table)
                         ->where_not_null($this->soft_delete_column)
                         ->get_all() ?: [];
    }
}
