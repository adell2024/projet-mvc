<?php

class ArticlesController extends Controller
{
    // Afficher la liste des articles
    public function index()
    {
        $this->loadModel('Article');

        if (!isset($this->Article)) {
            $this->notFound("Le modèle Article est introuvable.");
            return;
        }

        $articles = $this->Article->getAll();
        $this->render('index', compact('articles'));
    }

    // Afficher un article spécifique à partir de son slug
    public function read($slug)
    {
        $this->loadModel('Article');

        if (!isset($this->Article)) {
            $this->notFound("Le modèle Article est introuvable.");
            return;
        }

        $article = $this->Article->findBySlug($slug);

        if ($article) {
            $this->render('read', compact('article'));
        } else {
            $this->notFound("L'article demandé n'existe pas.");
        }
    }

    // Gestion des erreurs 404
    private function notFound($message = "Page introuvable.")
    {
        http_response_code(404);
        $this->render('error404', ['message' => $message]);
    }
}

