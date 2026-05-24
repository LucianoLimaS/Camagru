<?php
namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller {
    /**
     * Exibe a página inicial pública do Camagru
     */
    public function index() {
        $this->render('home/index', [
            'title' => 'Camagru - Compartilhe seus Momentos com Filtros Exclusivos'
        ]);
    }
}
