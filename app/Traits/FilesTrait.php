<?php
namespace App\Traits;

use Illuminate\Http\Request;

trait FilesTrait
{
    public function uploadImage(Request $request)
    {
        if($request->hasFile('image_url'))
        {
            $file = $request->file('image_url');
            $imageName = $file->getClientOriginalName();
            $imagePath = public_path().'/files';
            $file->move($imagePath, $imageName);
        }
        return $imageName;
    }

    public function uploadVideo(Request $request)
    {
        if($request->hasFile('video_url'))
        {
            $videofile = $request->file('video_url');
            $videoName = $videofile->getClientOriginalName();
            $videoPath = public_path().'/files';
            $videofile->move($videoPath, $videoName);
        }
        return $videoName;
    }


}
