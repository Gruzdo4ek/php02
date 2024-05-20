<?php
require_once "BaseDogTwigController.php";

class MainController extends BaseDogTwigController {
    public $template = "main.twig";
    public $title = "Главная";
    public function getContext(): array
    {
        $context = parent::getContext();

        if (isset($_GET['type'])){
            $query = $this->pdo->prepare("SELECT * FROM dogs WHERE type = :type");
            $query->bindValue("type", $_GET['type']);
            $query->execute();
        }

        else {
            $query = $this->pdo->query("SELECT * FROM dogs");
        }
        $context['dogs'] = $query->fetchAll();
        return $context;
    }
}

