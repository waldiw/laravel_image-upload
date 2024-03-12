<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImageUpload;
use Session;

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
        Session::flash('message', 'New image added success.'); 
        Session::flash('alert-class', 'alert-success'); 
        return redirect()->back();
    }

}