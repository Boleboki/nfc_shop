<?php

namespace App\Controllers;


class AdminController{
    public function loginForm(){
        return view("admin/index.view.php");
    }
    
    public function dashboard(){
        return view("admin/dashboard.view.php");
    }   
}