<?php

function getBasePath()
{
    return BASE_PATH;
}

// Transforme une chaîne en une date formatée
function stringToFormatDate($str, $mysql = false){
    return formatDate(strtotime(str_replace("/","-",$str)), $mysql);
}

// Transforme une chaîne en une date et heure formatée
function stringToFormatDateTime($str, $mysql = false){
    return formatDateTime(strtotime(str_replace("/","-",$str)), $mysql);
}

// Formate une date
function formatDate($date, $mysql = false){
    if($mysql)
        $strformat = "Y-m-d";
    else
        $strformat = "d/m/Y";
    return date($strformat, $date);
}

// Formate une date et heure
function formatDateTime($date, $mysql = false){
    if($mysql)
        $strformat = "Y-m-d H:i:s";
    else
        $strformat = "d/m/Y H:i:s";
    return date($strformat, $date);
}

function getHTMLSessionInfo() {
    return SessionInfo::getHTMLSessionInfo();
}