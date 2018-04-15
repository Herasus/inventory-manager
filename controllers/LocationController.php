<?php

class LocationController extends Controller {

    function list() {
        $locationManager = new LocationManager();

        if(!empty($_POST)) {
            $parent = empty($_POST['parent']) ? null : $_POST['parent'];
            if(empty($_POST['id'])) {
                // add
                $locationManager->addLocation($_POST['name'], $parent);
            }
            else {
                // edit
                $locationManager->editLocation($_POST['id'], $_POST['name'], $parent);
            }

            redirectPath('locations');
        }

        $locations = $locationManager->getLocations();
        $nodesLocations = $locationManager->getLocationNodes(true);

        $locationManager->getLocationCompleteList($locationsList);

        return self::$twig->render('locations/list.html.twig', [
            'locations' => json_encode($locations),
            'nodesLocations' => json_encode($nodesLocations),
            'locationsList' => json_encode($locationsList)
        ]);
    }

    public function delete() {
        if(!empty($_POST['id'])) {
            $locationManager = new LocationManager();
            $locationManager->removeLocation($_POST['id']);

            redirectPath('locations');
        }
    }
}