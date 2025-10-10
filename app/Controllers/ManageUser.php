<?php
namespace App\Controllers;
use CodeIgniter\Controller;

class ManageUser extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Display all users
    public function index()
    {
        $builder = $this->db->table('users');
        $data['users'] = $builder->get()->getResultArray();

        // Count roles
        $data['count_admin'] = $this->db->table('users')->where('role', 'admin')->countAllResults();
        $data['count_teacher'] = $this->db->table('users')->where('role', 'teacher')->countAllResults();
        $data['count_student'] = $this->db->table('users')->where('role', 'student')->countAllResults();
        $data['count_total'] = $this->db->table('users')->countAllResults();

        $data['title'] = 'User Management';
        return view('admin/manage_users', $data);
    }

    // Delete user
    public function delete($id)
    {
        $builder = $this->db->table('users');
        $builder->where('id', $id)->delete();

        return redirect()->to('/admin/users')->with('success', 'User deleted successfully!');
    }

    // Add new user
    public function create()
    {
        return view('admin/add_user');
    }

    public function store()
    {
        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'      => $this->request->getPost('role'),
            'created_at'=> date('Y-m-d H:i:s')
        ];

        $this->db->table('users')->insert($data);
        return redirect()->to('/admin/users')->with('success', 'User added successfully!');
    }

    // Edit user
    public function edit($id)
    {
        $builder = $this->db->table('users');
        $data['user'] = $builder->where('id', $id)->get()->getRowArray();
        return view('admin/edit_user', $data);
    }

    public function update($id)
    {
        $updateData = [
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'role'      => $this->request->getPost('role'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $builder = $this->db->table('users');
        $builder->where('id', $id)->update($updateData);

        return redirect()->to('/admin/users')->with('success', 'User updated successfully!');
    }
}
