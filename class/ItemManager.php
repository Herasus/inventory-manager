<?php


class ItemManager {
    private $db;

    public function __construct() {
        $this->db = DBFactory::getMySQLConnection();
    }

    public function getItems() {
        $req = $this->db->prepare('SELECT * FROM item');
        $req->execute();

        $data = $req->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function addItem($name, $description, $picture, $category, $location) {
        $req = $this->db->prepare('INSERT INTO item (name, description, picture, category, location) VALUES (:name, :description, :picture, :category, :location)');
        $req->execute([
            ':name' => $name,
            ':description' => $description,
            ':picture' => $picture,
            ':category' => $category,
            ':location' => $location
        ]);
    }

    public function editItem($id, $name, $description, $picture, $category, $location) {
        $bindings = [
            ':name' => $name,
            ':description' => $description,
            ':category' => $category,
            ':location' => $location,
            ':id' => $id
        ];
        $req = '';

        if(!empty($picture)) {
            $bindings[':picture'] = $picture;
            $req = ', picture = :picture';
        }

        $req = $this->db->prepare('UPDATE item SET name = :name, description = :description, category = :category, location = :location'.$req.' WHERE id = :id');
        $req->execute($bindings);
    }

    public function removeItem($id) {
        $req = $this->db->prepare('DELETE FROM item WHERE id = :id');
        $req->execute([
            ':id' => $id
        ]);
    }
}