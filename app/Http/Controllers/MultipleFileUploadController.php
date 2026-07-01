<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MultipleFileUploadController extends Controller
{
    //
    public function getFileUploadForm()
    {
        return view('multiple-file-upload');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'documents' => 'required',
            'documents.*' => 'required|mimes:doc,docx,xlsx,xls,pdf,zip,png,bmp,jpg|max:2048',
        ]);
 
        if ($request->documents){
            foreach($request->documents as $file) {
 
                $fileName = $file->getClientOriginalName();
                $filePath = 'uploads/' . $fileName;
 
                $path = Storage::disk('public')->put($filePath, file_get_contents($file));
                $path = Storage::disk('public')->url($path);
 
                // // Create files
                File::create([
                    'name' => $fileName
                ]);
            }
        }
 
        return back()
            ->with('success','Files have been successfully uploaded.');
    }
}

