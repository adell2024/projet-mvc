<?php

class MainController extends Controller {
    // Méthode pour afficher la page d'accueil
    public function index() {
        $this->render('index');
    }
}
