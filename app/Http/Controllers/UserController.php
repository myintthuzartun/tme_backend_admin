<?php

    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Auth;

    class UserController extends Controller
    {
        // Register a new user
        public function register(Request $req)
        {
            $user = new User;
            $user->name = $req->input('name');
            $user->email = $req->input('email');
            $user->password = Hash::make($req->input('password'));  // Hash the password before saving

            $user->save();

            return response()->json($user);  // Return JSON response with the created user
        }

        // Log in an existing user
        public function login(Request $req)
        {
            $user = User::where('email', $req->email)->first();

            if (!$user || !Hash::check($req->password, $user->password)) {
                return response()->json(["error" => "Email & Password is not matched"], 401);
            }

            return response()->json($user);
        }

        // Retrieve all users
        public function index()
        {
            $users = User::all();  // Retrieve all user records
            return response()->json($users);  // Return the users as JSON
        }

        // Retrieve a single user by ID
        public function show($id)
        {
            $user = User::find($id);  // Retrieve the user by ID
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            return response()->json($user);
        }

        // Update user profile (only allows updating the name, email, and password)
        public function update(Request $req, $id)
        {
            \Log::info('User ID: ' . $id);
        
            $user = User::find($id);
            if (!$user) {
                \Log::error('User not found with ID: ' . $id);
                return response()->json(['error' => 'User not found'], 404);
            }
        
            // Validate current password
            if ($req->filled('current_password')) {
                if (!Hash::check($req->current_password, $user->password)) {
                    return response()->json(['error' => 'Current password is incorrect'], 400);
                }
            } else {
                return response()->json(['error' => 'Current password is required'], 400);
            }
        
            // Handle Email Update
            if ($req->has('email') && $req->email !== $user->email) {
                if (User::where('email', $req->email)->exists()) {
                    return response()->json(['error' => 'Email is already taken'], 400);
                }
                $user->email = $req->email;
            }
        
            // Handle Password Update
            if ($req->filled('password')) {
                $user->password = Hash::make($req->password);
            }
        
            $user->save();
        
            return response()->json(['message' => 'User updated successfully', 'email' => $user->email]);
        }
        
        // Delete a user by ID
        public function delete($id)
        {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Optionally, check if the logged-in user is authorized to delete this account (e.g., only the user themselves or admin can delete)
            // if (Auth::user()->id !== $user->id && !Auth::user()->is_admin) {
            //     return response()->json(['error' => 'Unauthorized'], 403);
            // }

            $user->delete();  // Delete the user

            return response()->json(['message' => 'User deleted successfully']);
        }
    }
