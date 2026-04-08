<?php
// app/controllers/AuthController.php

class AuthController extends Controller {
    private User $user;

    public function __construct() { $this->user = new User(); }

    public function loginForm(): void {
        if ($this->isLoggedIn()) $this->redirect('/');
        $flash = $this->getFlash();
        $this->view('auth.login', compact('flash'), 'public');
    }

    public function login(): void {
        $email    = $this->sanitize($this->post('email', ''));
        $password = $this->post('password', '');
        $user     = $this->user->findByEmail($email);
        if (!$user || !password_verify($password, $user->password)) {
            $this->flash('error', 'Email atau password salah.');
            $this->redirect('/auth/login');
            return;
        }
        if (!$user->is_active) {
            $this->flash('error', 'Akun Anda tidak aktif.');
            $this->redirect('/auth/login');
            return;
        }
        $_SESSION['user_id']    = $user->id;
        $_SESSION['user_name']  = $user->name;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_role']  = $user->role;
        $_SESSION['user_avatar'] = $user->avatar;
        if (in_array($user->role, ['admin', 'editor'])) {
            $this->redirect('/admin/dashboard');
        } else {
            $this->redirect('/');
        }
    }

    public function registerForm(): void {
        if ($this->isLoggedIn()) $this->redirect('/');
        $flash = $this->getFlash();
        $this->view('auth.register', compact('flash'), 'public');
    }

    public function register(): void {
        $name     = $this->sanitize($this->post('name', ''));
        $username = $this->sanitize($this->post('username', ''));
        $email    = $this->sanitize($this->post('email', ''));
        $password = $this->post('password', '');
        $confirm  = $this->post('password_confirmation', '');

        if (!$name || !$email || !$password || !$username) {
            $this->flash('error', 'Semua kolom wajib diisi.');
            $this->redirect('/auth/register'); return;
        }
        if ($password !== $confirm) {
            $this->flash('error', 'Konfirmasi password tidak cocok.');
            $this->redirect('/auth/register'); return;
        }
        if (strlen($password) < 6) {
            $this->flash('error', 'Password minimal 6 karakter.');
            $this->redirect('/auth/register'); return;
        }
        if ($this->user->emailExists($email)) {
            $this->flash('error', 'Email sudah terdaftar.');
            $this->redirect('/auth/register'); return;
        }
        if ($this->user->findByUsername($username)) {
            $this->flash('error', 'Username sudah digunakan.');
            $this->redirect('/auth/register'); return;
        }
        $this->user->create([
            'name'     => $name,
            'username' => $username,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => 'user',
        ]);
        $this->flash('success', 'Registrasi berhasil! Silakan login.');
        $this->redirect('/auth/login');
    }

    public function logout(): void {
        session_destroy();
        header("Location: " . BASE_URL);
        exit;
    }

    public function profile(): void {
        $this->requireAuth();
        $user  = $this->user->findById($_SESSION['user_id']);
        $flash = $this->getFlash();
        $this->view('auth.profile', compact('user', 'flash'), 'public');
    }

    public function updateProfile(): void {
        $this->requireAuth();
        $name = $this->sanitize($this->post('name', ''));
        $bio  = $this->sanitize($this->post('bio', ''));
        $data = ['name' => $name, 'bio' => $bio];
        $avatar = $this->uploadImage('avatar', 'avatars');
        if ($avatar) $data['avatar'] = $avatar;
        $this->user->update($data, $_SESSION['user_id']);
        $_SESSION['user_name'] = $name;
        if ($avatar) $_SESSION['user_avatar'] = $avatar;
        $this->flash('success', 'Profil berhasil diperbarui.');
        $this->redirect('/profile');
    }
}
