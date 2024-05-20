<?php
class LoginController extends BaseDogTwigController {

    public $template = "login.twig";
    public $title = "Вход";

    public function get(array $context) // добавили параметр
    {
        parent::get($context); // пробросили параметр
    }
    public function post(array $context) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Проверяем существование введенных данных в БД
        $query = $this->pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $query->bindValue("username", $username);
        $query->bindValue("password", $password);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Вход успешен, устанавливаем флаг is_logged в сессии
            $_SESSION["is_logged"] = true;
            
            // Редирект на главную страницу, если нет информации о перенаправлении
            header("Location: /");
            exit;
        } else {
            $_SESSION["is_logged"] = false;
            
            // Редирект на главную страницу, если нет информации о перенаправлении
            header("Location: /login");
            exit;
        }

    }
}

