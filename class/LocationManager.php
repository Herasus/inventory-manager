<?php

class LocationManager
{
    private $db;

    public function __construct() {
        $this->db = DBFactory::getMySQLConnection();
    }

    public function getLocations() {
        $req = $this->db->prepare('SELECT * FROM location');
        $req->execute();

        $data = $req->fetchAll(PDO::FETCH_ASSOC);

        $locationsData = [];
        foreach($data as $location) {
            $locationsData[$location['id']] = [
                'id' => $location['id'],
                'name' => $location['name'],
                'parent' => $location['parent_location']
            ];
        }

        return $locationsData;
    }

    public function getLocationNodes() {
        $locations = $this->getLocations();

        $locationNodes = [];
        $refs = [];

        foreach ($locations as $location) {
            $currentNode = &$refs[$location['id']];

            $currentNode['id'] = $location['id'];
            $currentNode['text'] = $location['name'];

            if (is_null($location['parent'])) {
                $locationNodes[] = &$currentNode;
            } else {
                $refs[$location['parent']]['nodes'][] = &$currentNode;
            }
        }
        return $locationNodes;
    }

    public function getLocationCompleteList(&$locationList) {
        $locations = $this->getLocationNodes();

        foreach($locations as $location) {
            $this->getNodeLocationCompleteList($locationList, $location, '');
        }
    }

    public function getNodeLocationCompleteList(&$locationList, $locations, $strLocation = null) {
        $strLocation .= (($strLocation == '') ? '' : ' > ') . $locations['text'];
        $locationList[] = [
            'id' => $locations['id'],
            'text' => $strLocation
        ];

        if(!isset($locations['nodes'])) {
            return;
        }

        foreach($locations['nodes'] as $node) {
            $this->getNodeLocationCompleteList($locationList, $node, $strLocation);
        }
    }
}