<?php
namespace App\Http\Controllers\Api;

/**
 * Created with love.
 * User: aseprojali
 * Date: 8/9/16
 * Time: 6:25 PM
 * Website : avew.github.io
 */


use App\Http\Controllers\ApiController;

class HomeController extends ApiController
{

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return response([
            'status' => 'up'
        ], 200);
    }

}