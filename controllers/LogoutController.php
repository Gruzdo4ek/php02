<?php
class LogoutController extends BaseDogTwigController {

    public function post(array $context) {
        // Сбрасываем значение is_logged в сессии
        $_SESSION["is_logged"] = false;
        
        // Редирект на страницу входа
        header("Location: /login");
        exit;
    }
}
?>