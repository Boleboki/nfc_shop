<?php

namespace App\Controllers;


class AdminController
{
    public function loginForm()
    {
        return view("admin/index.view.php");
    }

    public function dashboard()
    {
        return view("admin/dashboard.view.php");
    }

    public function uploadImage()
    {
        $photo = $_FILES["photo"];

        $photo_name = basename($photo["name"]);

        $photo_path = "../public/img/" . $photo_name;

        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        $ext = pathinfo($photo_name, PATHINFO_EXTENSION);


        if (in_array($ext, $allowed_ext) && $photo["size"] < 2000000) {
            move_uploaded_file($photo['tmp_name'], $photo_path);

            echo json_encode(['success' => true, "photo_path" => $photo_name]);
        } else {
            echo json_encode(['success' => false, "error" => "Invalid File"]);
        }
    }

    public function deleteImage()
    {
        // Dobijamo JSON iz POST request-a
        $data = json_decode(file_get_contents("php://input"), true);
        $path = $data['path'] ?? '';
        if (!$path) {
            echo json_encode(['success' => false, 'error' => 'No file path provided']);
            return;
        }

        // // Pretvori u punu putanju na serveru
        // $fullPath = base_path('/public' . $path);

        // if (file_exists($fullPath)) {
        //     if (unlink($fullPath)) {
        //         echo json_encode(['success' => true]);
        //     } else {
        //         echo json_encode(['success' => false, 'error' => 'Failed to delete file']);
        //     }
        // } else {
        //     echo json_encode(['success' => false, 'error' => 'File not found']);
        // }
    }
}
