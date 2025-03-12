<?php

// Classe de base pour le modèle
abstract class Model
{
    protected $_connexion;
    protected $table;

    public function __construct()
    {
        // Connexion à la base de données
        $this->_connexion = new PDO('mysql:host=localhost;dbname=mvc', 'root', 'azerty');
        $this->_connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Méthode pour récupérer tous les éléments de la table
    public function getAll()
    {
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->_connexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer un élément en fonction de son slug
    public function findBySlug($slug)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE slug = :slug";
        $stmt = $this->_connexion->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Classe de base pour le contrôleur
abstract class Controller
{
    // Affiche la vue
    public function render($view, $data = [])
    {
        extract($data);
        ob_start();
        require_once(ROOT . 'views/' . strtolower(get_class($this)) . '/' . $view . '.php');
        $content = ob_get_clean();
        require_once(ROOT . 'views/layout/default.php');
    }

    // Charge un modèle
    public function loadModel($model)
    {
        require_once(ROOT . 'models/' . $model . '.php');
        $this->$model = new $model();
    }
}
