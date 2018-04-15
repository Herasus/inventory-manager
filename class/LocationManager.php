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

    public function addLocation($name, $parent) {
        $req = $this->db->prepare("INSERT INTO location (name, parent_location) VALUES (:name, :parent)");
        $req->execute([
            ':name' => $name,
            ':parent' => $parent
        ]);
    }

    public function editLocation($id, $name, $parent) {
        $req = $this->db->prepare("UPDATE location SET name = :name, parent_location = :parent WHERE id = :id");
        $req->execute([
            ':name' => $name,
            ':parent' => $parent,
            ':id' => $id
        ]);
    }

    public function removeLocation($id) {
        $req = $this->db->prepare("DELETE FROM location WHERE id = :id");
        $req->execute([
            ':id' => $id
        ]);
    }

    public function getLocationNodes($htmlButtons = false) {
        $locations = $this->getLocations();

        $locationNodes = [];
        $refs = [];

        foreach ($locations as $location) {
            $currentNode = &$refs[$location['id']];

            $currentNode['id'] = $location['id'];
            $currentNode['text'] = $location['name'];
            $currentNode['selectable'] = false;

            if($htmlButtons) {
                $currentNode['text'] .= '
<div class="pull-right">
    <button class="btn btn-xs btn-primary" title="Ajouter" onclick="addLocation('.$currentNode['id'].')">
        <i class="far fa-plus"></i>
    </button>
    <button class="btn btn-xs btn-primary" title="Editer" onclick="editLocation('.$currentNode['id'].')">
        <i class="far fa-pencil"></i>
    </button>
    <button class="btn btn-xs btn-danger" title="Supprimer" onclick="deleteLocation('.$currentNode['id'].')">
        <i class="far fa-trash"></i>
    </button>
</div>
';
            }

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

    public function getNodeLocationCompleteList(&$locationList, $locations, $strLocation, $parents = []) {
        $strLocation .= (($strLocation == '') ? '' : ' > ') . $locations['text'];

        $parents[] = $locations['id'];

        $locationList[] = [
            'id' => $locations['id'],
            'text' => $strLocation,
            'parents' => $parents
        ];

        if(!isset($locations['nodes'])) {
            return;
        }

        foreach($locations['nodes'] as $node) {
            $this->getNodeLocationCompleteList($locationList, $node, $strLocation, $parents);
        }
    }
}