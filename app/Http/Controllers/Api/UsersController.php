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
            return $this->response->errorMethodNotAllowed('Token not provided');
        }
        try {
            $refreshedToken = JWTAuth::refresh($token);
        } catch (JWTException $e) {
            return $this->response->errorInternal('Not able to refresh Token');
        }
        return $this->response->withArray(['token' => $refreshedToken]);
    }


}