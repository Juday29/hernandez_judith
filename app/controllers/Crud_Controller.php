<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Controller: User
 * 
 * Automatically generated via CLI.
 */
class Crud_Controller extends Controller {
    public function __construct()
    {
        parent::__construct();
        $this->call->model('crud_Model');
        $this->call->library('form_validation');

        // $this->call->helper('message');
    }

      public function read() 
    {
        
        $page = 1;
        if(isset($_GET['page']) && ! empty($_GET['page'])) {
            $page = $this->io->get('page');
        }

        $q = '';
        if(isset($_GET['q']) && ! empty($_GET['q'])) {
            $q = trim($this->io->get('q'));
        }

        $records_per_page = 5;

        $all = $this->crud_Model->page($q, $records_per_page, $page);
        $data['all'] = $all['records'];
        $total_rows = $all['total_rows'];
        $this->pagination->set_options([
            'first_link'     => '⏮ First',
            'last_link'      => 'Last ⏭',
            'next_link'      => 'Next →',
            'prev_link'      => '← Prev',
            'page_delimiter' => '&page='
        ]);
        $this->pagination->set_theme('bootstrap'); // or 'tailwind', or 'custom'
        $this->pagination->initialize($total_rows, $records_per_page, $page, '/?q='.$q);
        $data['page'] = $this->pagination->paginate();
        $this->call->view('index', $data);
    }

  public function createUser()
    {
        $this->form_validation
            ->name('product_name')
                ->required()
                ->max_length(50)        
            ->name('quantity')
                ->required()
                ->max_length(200);
            
           

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->get_errors();
            setErrors($errors);
            redirect('/');
        } else {
            $this->crud_Model->insert([
                'product_name' => $_POST['product_name'],
                'quantity' => $_POST['quantity'],
                'price'  => $_POST['price'],
                'created_at' => date('y-m-d H:i:s'),
                'updated_at' => date('y-m-d H:i:s')
            ]);

            setMessage('success', 'Product registered successfully!');
            redirect('/');
        }
    }



     public function updateUser($id){
        $this->crud_Model->update($id, [
            'product_name' => $_POST['product_name'],
             'quantity' => $_POST['quantity'],
            'price'  => $_POST['price'],
        ]);
        setMessage('success', 'Product updated successfully!');
        redirect('/');
    }


     public function deleteUser($id){
        $this->crud_Model->delete($id);
        setMessage('danger', 'Product deleted successfully!');
        redirect('/');
    }
}

