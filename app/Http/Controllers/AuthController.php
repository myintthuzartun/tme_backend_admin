<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTFactory;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        $request->validate([

            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([

            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level'=>1,
            'type'=>'vendor',
        ]);

        return response()->json(['message' => 'User created successfully']);
    }



public function login(Request $request)
{
    // Validate input
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    // Retrieve credentials
    $credentials = $request->only('email', 'password');

    // Find user by email
    $user = User::where('email', $credentials['email'])->first();

    // Check if user exists and password matches
    if (!$user || !Hash::check($credentials['password'], $user->password)) {
        return response()->json(['error' => 'Unauthorized: Invalid credentials'], 401);
    }

    // Ensure the user has the correct type (e.g., 'vendor')
    if ($user->type !== 'vendor') {
        return response()->json(['error' => 'Unauthorized: Invalid user type'], 401);
    }

    // Generate JWT token
    if (!$token = Auth::guard('api')->login($user)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Return token response
    return response()->json([
        'message' => 'Login successful',
        'token' => $token,
        'user' => $user
    ]);
}


    // Logout and clear the cookie
    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json(['message' => 'Successfully logged out'])
            ->withCookie(cookie()->forget('jwt_token'));
    }

    // Refresh the JWT token
    public function refresh()
    {
        return $this->respondWithToken(Auth::guard('api')->refresh());
    }

    // Send JWT Token in HttpOnly Cookie
    protected function respondWithToken($token)
    {
        return response()->json(['message' => 'Logged in successfully'])
            ->withCookie(cookie('jwt_token', $token, 60, '/', null, false, true));
    }

    // Get Authenticated User
    public function me()
    {
        return response()->json(Auth::guard('api')->user());
    }
    // Login and set HttpOnly Cookie -Admin
    public function admin_register(Request $request)
    {

        $user = User::create([

            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level'=>0,
            'type'=>'admin',
        ]);

        return response()->json(['message' => 'Admin created successfully']);
    }
    public function admin_login(Request $request)
    {
        // Validate incoming request for email and password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Retrieve the credentials from the request
        $credentials = $request->only('email', 'password');

        // Attempt to authenticate the admin
        $admin = User::where('email', $credentials['email'])->first();

        // Check if the admin exists and the password is correct
        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if the admin's level is 'admin'
        if ($admin->type !== 'admin') {
            return response()->json(['error' => 'Unauthorized: Insufficient level'], 401);
        }

        // Generate a token for the admin
        if (!$token = Auth::guard('api')->login($admin)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Return the token in the response
        return $this->respondWithToken($token);
    }

}

