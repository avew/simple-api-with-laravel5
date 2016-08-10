<?php
namespace App\Http\Controllers\Auth;

/**
 * Created with love.
 * User: aseprojali
 * Date: 8/9/16
 * Time: 7:13 PM
 * Website : avew.github.io
 */


use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class ApiAuthController extends ApiController
{


    /**
     * Authenticate user to get token
     *
     * @param Request $request
     * @return mixed
     */
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:4',
        ]);


        // Grab credentials from the request
        $credentials = $request->only('email', 'password');


        try {
            // Attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->error('Invalid credentials', 401);
            }
        } catch (JWTException $ex) {
            // Something went wrong whilst attempting to encode the token
            return response()->error('Could not create token', 500);

        }

        // All good so return user and token
        return response()->json(compact('token'), 200);
    }

    /**
     * Register User
     *
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = new User;
        $user->name = trim($request->name);
        $user->email = trim(strtolower($request->email));
        $user->password = bcrypt($request->password);
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'),200);
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