<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUpload extends Model
{
    use HasFactory;
    protected $fillable = ['image'];

    // pobieranie linka do zdjęcia - w widoku dzięki getPhotoAtribute pobieramy zdjęcie $article->photo
    public function getPhotoAttribute(): string
    {
        return Str::startsWith($this->image, 'http') ? $this->image : Storage::url($this->image); // zapis zdjęć w storage
        //return 'uploads/' . $this->image; // zapis zdjęć w katalogu public v
    }
}


