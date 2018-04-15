<?php

// Types de messages existants (Bootstrap)
define("INFO_SUCCESS", "success");
define("INFO_WARNING", "warning");
define("INFO_DANGER", "danger");

class SessionInfo {
    /**
     * Ajoute un message SessionInfo
     *
     * @param string $msg Contenu du message
     * @param string $type|INFO_WARNING Type du message
     *
     * @return void
     */
    static function addSessionInfo($msg, $type = INFO_WARNING){
        if(!isset($_SESSION["msg"])){
            $_SESSION["msg"] = [];
        }
        $_SESSION["msg"][] = [$msg, $type];
    }

    /**
     * Indique s'il existe au moins un message SessionInfo stocké
     *
     * @param string $type search for type
     * @return boolean true si au moins un message SessionInfo est stocké, false sinon.
     */
    static function issetSessionInfo($type = null){
        if(!isset($_SESSION["msg"])) return false;
        if($type) return in_array($type, array_column($_SESSION["msg"], 1));
        if(count($_SESSION["msg"]) == 0) return false;
        return true;
    }

    /**
     * Retourne le dernier message SessionInfo, et l'efface
     *
     * @return string[] dernier message SessionInfo
     */
    static function getSessionInfo(){
        if(self::issetSessionInfo()){
            $msg = array_pop($_SESSION["msg"]);
            return $msg;
        }
        else{
            return [];
        }
    }

    /**
     * Vide les messages de SessionInfo
     *
     * @return void
     */
    static function clearSessionInfo(){
        $_SESSION["msg"] = [];
    }

    /**
     * Retourne les messages SessionInfo au format HTML, et les efface
     *
     * @return string les messages SessionInfo
     */
    static function getHTMLSessionInfo() {
        $html = "";
        while($msg = self::getSessionInfo()) {
            $html .= '<div class="alert alert-'.$msg[1].' alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'.$msg[0].'
    </div>';
        }
        return $html;
    }

    /**
     *
     * Affiche les messages SessionInfo, et les efface
     *
     * @return void
     */
    static function printHTMLSessionInfo(){
        echo self::getHTMLSessionInfo();
    }
}
