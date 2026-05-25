<?php
namespace App\Controllers;

use Core\Controller;

class AuthController extends Controller {
    /**
     * Exibe a tela de login
     */
    public function login() {
        $this->render('auth/index', [
            'title' => 'Camagru - Entrar na sua Conta',
            'action' => 'login'
        ], 'auth_layout');
    }

    /**
     * Exibe a tela de cadastro
     */
    public function register() {
        $this->render('auth/index', [
            'title' => 'Camagru - Criar uma Conta',
            'action' => 'register'
        ], 'auth_layout');
    }
}
