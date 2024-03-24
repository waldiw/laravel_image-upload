<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageUpload;
use Illuminate\Support\Facades\Session;

class ImageUploadController extends Controller
{

    public function upload_image()
    {
        $images = ImageUpload::all();
        return view('image_upload', compact('images'));
    }

    public function store_image(Request $request)
    {
        $request->validate([
            'image'=>'required|mimes:jpg,jpeg,png,bmp',
        ]);

        $imageName = '';
        if ($image = $request->file('image')){
            $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
            //$image->move('images/uploads', $imageName);
            $imagePath = $request->file('image')->store('images', 'public');
        }
        ImageUpload::create([
            'image'=>$imagePath,
        ]);

        // image to resize
        $source = imagecreatefromjpeg('../public/storage/' . $imagePath);
        // read size
        list($width, $height) = getimagesize('../public/storage/' . $imagePath);

        $thumb_width = (int)((130 / $height) * $width);
        if($thumb_width > 160)
        {
            $tumbHeight = (int)((160 / $width) * $height);
            $thumb_width = 160;
        }
        else
        {
            $tumbHeight = 130;
        }
        // new size
        $newWidth = $thumb_width;
        $newHeight = $tumbHeight;
        // Create a new image
        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        // Resize
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        // Save the resized image
        imagejpeg($thumb, '../public/storage/' . $imagePath, 100);
        // Clear the memory of the tempory image
        ImageDestroy($thumb);

        Session::flash('message', 'New image added success.');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

}
