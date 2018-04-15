<?php

class CategoryController extends Controller {

    function list() {
        $categoryManager = new CategoryManager();

        if(!empty($_POST)) {
            $parent = empty($_POST['parent']) ? null : $_POST['parent'];
            if(empty($_POST['id'])) {
                // add
                $categoryManager->addCategory($_POST['name'], $_POST['description'], $parent);
            }
            else {
                // edit
                $categoryManager->editCategory($_POST['id'], $_POST['name'], $_POST['description'], $parent);
            }

            redirectPath('categories');
        }

        $categories = $categoryManager->getCategories();
        $nodesCategories = $categoryManager->getCategoryNodes(true);

        $categoryManager->getCategoryCompleteList($categoriesList);

        return self::$twig->render('categories/list.html.twig', [
            'categories' => json_encode($categories),
            'nodesCategories' => json_encode($nodesCategories),
            'categoriesList' => json_encode($categoriesList)
        ]);
    }

    public function delete() {
        if(!empty($_POST['id'])) {
            $categoryManager = new CategoryManager();
            $categoryManager->removeCategory($_POST['id']);

            redirectPath('categories');
        }
    }
}