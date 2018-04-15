<?php

class LocationController extends Controller {



    function list() {
        $locationManager = new LocationManager();

        $locations = $locationManager->getLocationNodes();

        $locationManager->getLocationCompleteList($locationsList);

        return self::$twig->render('locations/list.html.twig', [
            'locations' => json_encode($locations),
            'locationsList' => $locationsList
        ]);
    }
}