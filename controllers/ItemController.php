<?php

class ItemController extends Controller {


    function list() {
        $itemManager = new ItemManager();
        $categoryManager = new CategoryManager();
        $locationManager = new LocationManager();


        if(!empty($_POST) && !empty($_POST["name"])) {
            $fileName = null;

            if(!empty($_FILES) && !empty($_FILES['picture']['tmp_name'])) {
                $tempFile = $_FILES['picture']['tmp_name'];
                $targetPath = "./uploads/documents/";
                if(!is_dir($targetPath)){
                    mkdir($targetPath, 0777, true);
                }
                $ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
                $fileName = md5(uniqid(rand(), true)) . "." . $ext;
                $targetFile = $targetPath . $fileName;
                move_uploaded_file($tempFile, $targetFile);
                Thumbnail::makeThumbnails($targetPath, $targetFile, $fileName);
            }

            if(empty($_POST['id'])) {
                // add
                $itemManager->addItem($_POST['name'], $_POST['description'], $fileName ?? '', $_POST['category'], $_POST['location']);
            }
            else {
                // edit
                $itemManager->editItem($_POST['id'], $_POST['name'], $_POST['description'], $fileName ?? '', $_POST['category'], $_POST['location']);
            }

            redirectPath('inventory');
        }

        $itemsDb = $itemManager->getItems();
        $categoryManager->getCategoryCompleteList($categoriesListDb);
        $locationManager->getLocationCompleteList($locationsListDb);
        $items = [];
        foreach($itemsDb as $item) {
            $items[$item['id']] = $item;
        }
        $categories = [];
        foreach($categoriesListDb as $category) {
            $categories[$category['id']] = $category;
        }
        $locations = [];
        foreach($locationsListDb as $location) {
            $locations[$location['id']] = $location;
        }

        foreach($items as &$item) {
            $item['category'] = $categories[$item['category']] ?? null;
            $item['location'] = $locations[$item['location']] ?? null;
        }

        return self::$twig->render('item/list.html.twig', [
            'items' => $items,
            'categories' => json_encode($categoriesListDb),
            'locations' => json_encode($locationsListDb)
        ]);
    }

    public function delete() {
        if(!empty($_POST['id'])) {
            $itemManager = new ItemManager();
            $itemManager->removeItem($_POST['id']);

            redirectPath('inventory');
        }
    }
}