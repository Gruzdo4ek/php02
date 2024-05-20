<?php
require_once "BaseDogTwigController.php";

class DogObjectCreateController extends BaseDogTwigController {
    public $template = "dog_object_create.twig";
    public function get(array $context) // добавили параметр
    {
        parent::get($context); // пробросили параметр
    }
    public function post(array $context) {
        // получаем значения полей с формы
        $title = $_POST['title'];
        $description = $_POST['description'];
        $type = $_POST['type'];
        $info = $_POST['info'];

        // добавил
        $tmp_name = $_FILES['image']['tmp_name'];
        $name =  $_FILES['image']['name'];
        move_uploaded_file($tmp_name, "../public/images/$name");
        $image_url = "/images/$name"; // формируем ссылку без адреса сервера

        $sql = <<<EOL
INSERT INTO dogs(title, description, type, info, image)
VALUES(:title, :description, :type, :info, :image_url) -- передаем переменную в запрос
EOL;


        $query = $this->pdo->prepare($sql);
        $query->bindValue("title", $title);
        $query->bindValue("description", $description);
        $query->bindValue("type", $type);
        $query->bindValue("info", $info);
        $query->bindValue("image_url", $image_url); // подвязываем значение ссылки к переменной  image_url
        $query->execute();
        
        // а дальше как обычно
        $context['message'] = 'Вы успешно создали объект';
        $context['id'] = $this->pdo->lastInsertId();

        $this->get($context);
    }
}