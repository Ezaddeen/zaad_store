<?php

namespace App\Trait;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FileHandler
{
    // هذه الدالة لا تحتاج تعديل لأنها تستخدم storage_path()
    public function uploader($file, $path, $width, $height)
    {
        $file_name = time() . "_" . uniqid() . "_" . $file->getClientOriginalName();
        $storingPath = storage_path("app" . $path) . "/" . $file_name;

        if (!file_exists(storage_path('app' . $path))) {
            Storage::makeDirectory($path);
        }

        Image::make($file->getRealPath())->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save($storingPath);

        return substr($path . "/" . $file_name, 8);
    }

    // [مصححة]
    public function uploadToPublic($file, $path = "/assets/images")
    {
        $file_name = time() . "_" . uniqid() . "_" . $file->getClientOriginalName();
        
        $correctPublicPath = base_path('public_html');
        $storingPath = $correctPublicPath . $path . "/" . $file_name;
        
        $directoryPath = $correctPublicPath . $path;
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0775, true);
        }

        Image::make($file->getRealPath())->resize(null, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->save($storingPath);

        return $path . "/" . $file_name;
    }

    // [مصححة]
    public function securePublicUnlink($path)
    {
        $absolute_path = base_path('public_html') . '/' . ltrim($path, '/');

        if (file_exists($absolute_path) && is_file($absolute_path)) {
            unlink($absolute_path);
            return true;
        }
        return false;
    }

    // هذه الدالة لا تحتاج تعديل
    public function secureUnlink($path)
    {
        $absolute_path = storage_path('app/public/') . $path;

        if (file_exists($absolute_path) && is_file($absolute_path)) {
            unlink($absolute_path);
            return true;
        }
        return false;
    }

    // [!!! تم تصحيح هذه الدالة بالكامل !!!]
    // هذه هي الدالة التي كانت تسبب مشكلة صور المنتجات
    public function fileUploadAndGetPath($file, $path = "/media/products") // تم تغيير المسار الافتراضي
    {
        $file_name = time() . "_" . $file->getClientOriginalName();

        // استخدام الطريقة الصحيحة للرفع إلى المجلد العام
        // move() هي الدالة المناسبة هنا
        $file->move(base_path('public_html') . $path, $file_name);

        // إرجاع المسار النسبي الصحيح الذي سيتم حفظه في قاعدة البيانات
        return $path . "/" . $file_name;
    }
}
