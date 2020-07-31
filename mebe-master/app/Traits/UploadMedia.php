<?php


namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Auth;

trait UploadMedia
{
    public function upload($request)
    {
        if(Auth::check()){
            $request->user_id = Auth::user()->getId();
        }
        else
            $request->request->set('user_id', '0');
        $user = $request->user_id;
        $file_address = $request->file_address;
        $file_name = $request->file_name;
        $data_image = $request->image_base64;
        $data = $this->getInfoMedia($user,$data_image,$file_address,$file_name);
        if($data!=null)
        {
            $this->mediaSave($data);
            $this->storeInfoMedia();
            return $data->path;
        }
        else
            return null;
    }
    protected function storeInfoMedia(){
        // this function to save info media to table medias
    }
    public function getInfoMedia($user, $image_base64, $filepath, $filename){
        if ($filepath == null) {
            $path = str_replace(' ', '', $user);
        } else {
            $path = $filepath. '/' .str_replace(' ', '', $user)  ;
        }

        @list($type, $image_base64) = explode(';', $image_base64);
        @list(, $image_base64) = explode(',', $image_base64);

        $tmpExtension = explode('/', $type);

        $file_name = $this->getFileName($filename,$tmpExtension);
        $data = new \stdClass();
        if ($image_base64 != "") { // storing image in storage/app/public Folder
            $data->path = $path . '/' . $file_name;
            $data->base64 = ($image_base64);
            return $data;
        }
        return null;
    }
    function mediaSave($data){
        Storage::disk('local')->put($data->path, base64_decode($data->base64));

    }
    function getFileName( $file_name,$tmpExtension){
        $file_name = ($file_name == "") ? uniqid() : $file_name;
        return $file_name.'.'.$tmpExtension[1];
    }
}