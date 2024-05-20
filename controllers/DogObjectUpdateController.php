<?php
require_once "BaseDogTwigController.php";

class DogObjectUpdateController extends BaseDogTwigController {
    public $template = "dog_object_update.twig";

    public function get(array $context) 
    {
        $id = $this->params['id'];

        $sql = <<<EOL
SELECT * FROM dogs WHERE id = :id
EOL;
        $query = $this->pdo->prepare($sql);
        $query->bindValue("id", $id);
        $query->execute();

        $data = $query->fetch();

        $context['object'] = $data;

        parent::get($context);
    }

    public function post(array $context) {
        $id = $this->params['id'];
    
        if(isset($_POST['title'], $_POST['description'], $_POST['type'], $_POST['info'], $_FILES['image'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $type = $_POST['type'];
            $info = $_POST['info'];
    
            // добавил
            $tmp_name = $_FILES['image']['tmp_name'];
            $name =  $_FILES['image']['name'];
            if(move_uploaded_file($tmp_name, "../public/images/$name")) {
                $image_url = "/images/$name"; // формируем ссылку без адреса сервера
            } else {
                $context['error'] = "Ошибка загрузки изображения";
                $this->get($context);
                return;
            }
    
            $sql = <<<EOL
            UPDATE dogs 
            SET title = :title, image = :image_url, description = :description, info = :info, type = :type
            WHERE id = :id
            EOL;
        
            $query = $this->pdo->prepare($sql);
            $query->bindValue("title", $title);
            $query->bindValue("image_url", $image_url);
            $query->bindValue("description", $description);
            $query->bindValue("info", $info);
            $query->bindValue("type", $type);
            $query->bindValue("id", $id); // подвязываем id объекта
            $query->execute();
        
            // а дальше как обычно
            $context['message'] = 'Вы успешно отредактировали объект';
            $context['id'] = $this->params['id'];

            $this->get($context);
        } else {
            $context['error'] = "Не все обязательные поля заполнены";
            $this->get($context);
        }
        
    }
    
}
