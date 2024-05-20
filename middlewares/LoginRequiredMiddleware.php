<?php

class LoginRequiredMiddleware extends BaseMiddleware
{
    public function apply(BaseController $controller, array $context)
    {
        $is_logged = isset($_SESSION['is_logged']) ? $_SESSION['is_logged'] : false;
        
        // Если пользователь не аутентифицирован, делаем редирект на страницу входа
        if (!$is_logged) {
            header("Location: /login");
            exit;
        }
    }
}

