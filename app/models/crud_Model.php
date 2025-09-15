<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Model: User_Model
 * 
 * Automatically generated via CLI.
 */
class crud_Model extends Model {
    protected $table = 'product';
    protected $primary_key = 'id';

    protected $fillable = [
        'product_name',
        'quantity',
        'Price',
        'created_at',
        'updated_at'

    ];  
    
            public function page($q, $records_per_page = null, $page = null) {
            if (is_null($page)) {
                return $this->db->table($this->table)->get_all();
            } else {
                $query = $this->db->table($this->table);
                
                // Build LIKE conditions
                $query->like('id', '%'.$q.'%')
                    ->or_like('product_name', '%'.$q.'%')
                    ->or_like('quantity', '%'.$q.'%')
                    ->or_like('price', '%'.$q.'%');

                // Clone before pagination
                $countQuery = clone $query;

                $data['total_rows'] = $countQuery->select_count('*', 'count')
                                                ->get()['count'];

                $data['records'] = $query->pagination($records_per_page, $page)
                                        ->get_all();

                return $data;
            }
        }


}