<?php
class load 
{
    public function __construct()
    {
        
        $module = isset($_REQUEST['module'])  && $_REQUEST['module'] != null ? $_REQUEST['module'] : "admin";
        $controller = isset($_REQUEST['module'])  && $_REQUEST['controller'] != null ? $_REQUEST['controller'] : "sinhvienController";
        $action = isset($_REQUEST['action'])  && $_REQUEST['action'] != null ? $_REQUEST['action'] : "indexAction";
        $url = "application/modules/$module/controllers/$controller";
        // echo $url;
        // echo $action;
        require("$url.php");
        // echo "load() function is call";
        $obj = new $controller;
        // echo "$action()";
        $obj->$action();
    }
}