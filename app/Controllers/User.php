<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    // Halaman register
    public function register()
    {
        $data = [
            'title' => 'Register',
            'validation' => \Config\Services::validation()
        ];
        return view('user/register', $data);
    }

    // Proses register
    public function save()
    {
        if (!$this->validate([
            'name'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Periksa input Anda.');
        }

        $this->userModel->save([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'birth_date' => $this->request->getPost('birth_date'),
            'phone'     => $this->request->getPost('phone'),
            'role'     => 'customer',
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    // Halaman login
    public function login()
    {
        return view('user/login', ['title' => 'Login']);
    }

    // Proses login
    public function auth()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user = $this->userModel->where('email', $email)->first();
        if ($user && password_verify($password, $user['password'])) {
            $this->session->set([
                'isLoggedIn' => true,
                'user_id'    => $user['id'],
                'user_name'  => $user['name'],
                'user_role'  => $user['role']
            ]);

            // Redirect berdasarkan role
            if ($user['role'] === 'admin') {
                return redirect()->to('/dashboard')->with('success', 'Login berhasil! Selamat datang Admin.');
            } else { // customer
                return redirect()->to('/')->with('success', 'Login berhasil! Selamat datang.');
            }
        } else {
            return redirect()->back()->with('error', 'Email atau password salah')->withInput();
        }
    }

    // Logout
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login')->with('success', 'Berhasil logout.');
    }

    // CRUD User
    public function index()
    {
        $data = [
            'title' => 'Daftar User',
            'users' => $this->userModel->findAll()
        ];
        return view('user/index', $data);
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit User',
            'user'  => $this->userModel->find($id),
            'validation' => \Config\Services::validation()
        ];
        return view('user/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => "required|valid_email|is_unique[users.email,id,$id]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/user')->with('success', 'User berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('/user')->with('success', 'User berhasil dihapus.');
    }
}
