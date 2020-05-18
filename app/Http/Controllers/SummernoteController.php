<?php

namespace App\Http\Controllers;

use App\Summernote;
use Illuminate\Http\Request;

class SummernoteController extends Controller
{
    public function Index(){
        $summernotes = Summernote::get();
        return view('welcome')->withSummernotes($summernotes);
    }

    public function CreateSave(Request $request){
        $detail=$request->content;
        $dom = new \domdocument();
        $dom->loadHtml('<?xml encoding="UTF-8">'.$detail);
        $images = $dom->getelementsbytagname('img');
        foreach($images as $k => $img){
            $data = $img->getattribute('src');
            list($type, $data) = explode(';', $data);
            list(, $data)= explode(',', $data);
            $data = base64_decode($data);
            $image_name= time().$k.'.png';
            $path = public_path() .'/images/'. $image_name;
            file_put_contents($path, $data);
            $img->removeattribute('src');
            $img->setattribute('src', "images/".$image_name);
        }
        $detail = $dom->savehtml();
        $summernote = new Summernote;
        $summernote->content = $detail;
        $summernote->save();
        return redirect()->back();
    }
}
