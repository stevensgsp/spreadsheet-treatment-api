<?php

namespace App\Http\Controllers;

use App\Imports\ImportedTenderImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpreadsheetController extends Controller
{
    /**
     * # TODO: documentacion swagger
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function process(Request $request)
    {
        $file = $request->file('spreadsheet');

        DB::beginTransaction();

        (new ImportedTenderImport())->import($file);

        DB::commit();
    }
}
