<?php
namespace App\Http\Controllers\Auth;

/**
 * Created with love.
 * User: aseprojali
 * Date: 8/9/16
 * Time: 7:13 PM
 * Website : avew.github.io
 */


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class ApiAuthController extends Controller
{


    /**
     * Authenticate user to get token
     *
     * @param Request $request
     * @return mixed
     */
    public function authenticate(Request $request)
    {
        // Grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // Attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->response->errorUnauthorized();
            }
        } catch (JWTException $ex) {
            // Something went wrong whilst attempting to encode the token
            return $this->response->errorInternal($ex);
        }

        // All good so return the token
        return $this->response->array(compact('token'))->setStatusCode(200);
    }


    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
        JWTAuth::invalidate($request->input('token'));
    }

}