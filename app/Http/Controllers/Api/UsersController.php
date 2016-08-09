<?php
namespace App\Http\Controllers\Api;

/**
 * Created with love.
 * User: aseprojali
 * Date: 8/9/16
 * Time: 8:38 PM
 * Website : avew.github.io
 */

use App\Http\Controllers\ApiController;
use App\Permission;
use App\Role;
use App\Transformers\UserTransformer;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends ApiController
{


    /**
     * Get user logged in with token
     *
     * @return mixed
     */
    public function authenticated()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return $this->response->errorNotFound('User not found');
            }

        } catch (JWTException $ex) {
            return $this->response->error('Something went wrong');
        }
        return $this->response->item($user, new UserTransformer());
    }


    /**
     * Refresh the token and send back to client
     *
     */
    public function refreshToken()
    {

        $token = JWTAuth::getToken();
        if (!$token) {
            return $this->response->errorUnauthorized('Token is invalid');
        }
        try {
            $refreshedToken = JWTAuth::refresh($token);

        } catch (JWTException $ex) {
            $this->response->error('Something went wrong');
        }

        return $this->response->array(compact('refreshedToken'));
    }


}