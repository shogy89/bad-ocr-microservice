<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use thiagoalessio\TesseractOCR\TesseractOCR;


class FileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // Main Function, Read a file and return it's text
    public function readFile(Request $request){
        // return response()->json('random error', 400);

        Storage::putFileAs('', $request->file, 'testfile.pdf');

        $pdf = new \Spatie\PdfToImage\Pdf(Storage::path('testfile.pdf'));

        // Lets asssume the file has only 1 page
        // For future edits we should update the code for files that has more than one page
        $pdf->saveImage(Storage::path('testfile.png'));

        $text = (new TesseractOCR(Storage::path('testfile.png')))->run();

        // Delete The files
        Storage::delete(['testfile.png', 'testfile.pdf']);

        return response()->json([
            'status' => 'Success',
            'text' => $text
        ]);
    }
}