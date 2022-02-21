<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;


class TransformerController extends Controller
{

    /**
     * Transform the incoming data
     */
    public static function transform($data, $fields)
    {
        $transformedData = [];
        foreach ($data as $item) {
            //return json object for each fields
            foreach ($fields as $val) {
                dd($val);
                $transformedData[] = [$val => $item->$val];
            }
        }
        // dd($transformedData);
        return $transformedData;
    }
}
