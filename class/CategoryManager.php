<?php

class CategoryManager
{
    private $db;

    public function __construct() {
        $this->db = DBFactory::getMySQLConnection();
    }

    public function getCategories() {
        $req = $this->db->prepare('SELECT * FROM category');
        $req->execute();

        $data = $req->fetchAll(PDO::FETCH_ASSOC);

        $categoriesData = [];
        foreach($data as $category) {
            $categoriesData[$category['id']] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'description' => $category['description'],
                'parent' => $category['parent_category']
            ];
        }

        return $categoriesData;
    }

    public function addCategory($name, $description, $parent) {
        $req = $this->db->prepare("INSERT INTO category (name, description, parent_category) VALUES (:name, :description, :parent)");
        $req->execute([
            ':name' => $name,
            ':description' => $description,
            ':parent' => $parent
        ]);
    }

    public function editCategory($id, $name, $description, $parent) {
        $req = $this->db->prepare("UPDATE category SET name = :name, description = :description, parent_category = :parent WHERE id = :id");
        $req->execute([
            ':name' => $name,
            ':description' => $description,
            ':parent' => $parent,
            ':id' => $id
        ]);
    }

    public function removeCategory($id) {
        $req = $this->db->prepare("DELETE FROM category WHERE id = :id");
        $req->execute([
            ':id' => $id
        ]);
    }

    public function getCategoryNodes($htmlButtons = false) {
        $categories = $this->getCategories();

        $categoryNodes = [];
        $refs = [];

        foreach ($categories as $category) {
            $currentNode = &$refs[$category['id']];

            $currentNode['id'] = $category['id'];
            $currentNode['text'] = $category['name'];
            if(!empty($category['description'])) {
                $currentNode['text'] .= ' (' . $category['description'] . ')';
            }
            $currentNode['selectable'] = false;

            if($htmlButtons) {
                $currentNode['text'] .= '
<div class="pull-right">
    <button class="btn btn-xs btn-primary" title="Ajouter" onclick="addCategory('.$currentNode['id'].'); event.stopPropagation();">
        <i class="far fa-plus"></i>
    </button>
    <button class="btn btn-xs btn-primary" title="Editer" onclick="editCategory('.$currentNode['id'].'); event.stopPropagation();">
        <i class="far fa-pencil"></i>
    </button>
    <button class="btn btn-xs btn-danger" title="Supprimer" onclick="deleteCategory('.$currentNode['id'].'); event.stopPropagation();">
        <i class="far fa-trash"></i>
    </button>
</div>
';
            }

            if (is_null($category['parent'])) {
                $categoryNodes[] = &$currentNode;
            } else {
                $refs[$category['parent']]['nodes'][] = &$currentNode;
            }
        }
        return $categoryNodes;
    }

    public function getCategoryCompleteList(&$categoryList) {
        $categories = $this->getCategoryNodes();

        foreach($categories as $category) {
            $this->getNodeCategoryCompleteList($categoryList, $category, '');
        }
    }

    public function getNodeCategoryCompleteList(&$categoryList, $categories, $strCategory, $parents = []) {
        $strCategory .= (($strCategory == '') ? '' : ' > ') . $categories['text'];

        $parents[] = $categories['id'];

        $categoryList[] = [
            'id' => $categories['id'],
            'text' => $strCategory,
            'name' => $categories['text'],
            'parents' => $parents
        ];

        if(!isset($categories['nodes'])) {
            return;
        }

        foreach($categories['nodes'] as $node) {
            $this->getNodeCategoryCompleteList($categoryList, $node, $strCategory, $parents);
        }
    }
}