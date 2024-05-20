<?php

class DogObjectDeleteController extends BaseController {
    public function post(array $context)
    {
        $id = $this->params['id'];
        $sql =<<<EOL
DELETE FROM dogs WHERE id = :id
EOL; // сформировали запрос

        $query = $this->pdo->prepare($sql);
        $query->bindValue(":id", $id);
        $query->execute();

        header("Location: /");
        exit;
    }
}