<?php

namespace App\Traits;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

trait UserTrait
{
    public function list()
    {
        try {
            $users = $this->userRepo->list([]);
            return api_response($users, true, "Success", 200);
        } catch (\Exception $e) {
            return api_response([], false, $e->getMessage(), $code = $e->getCode() ?: 500);
        }
    }

    public function view($user_id)
    {
        try {
            $user = $this->userRepo->searchByPid($user_id);
            if (!$user) {
                return api_response([], false, "User not found", 404);
            }
            return api_response($user, true, "Success", 200);
        } catch (\Exception $e) {
            return api_response([], false, $e->getMessage(), $code = $e->getCode() ?: 500);
        }
    }

    public function update($user_pid, UserUpdateRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated["date_of_birth"] = Carbon::parse($validated["date_of_birth"])->format('Y-m-d');
            $user = $this->userRepo->searchByPid($user_pid);
            if (!$user) {
                return api_response([], false, "User not found", 404);
            }
            $user = $this->userRepo->update($user->id, $validated);
            if (!$user) {
                return api_response([], false, "User not updated", 500);
            }
            return api_response(["user" => $user], true, "Success", 200);
        } catch (\Exception $e) {
            return api_response([], false, $e->getMessage(), $code = $e->getCode() ?: 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate(); // Prevent session fixation
                return api_response(
                    [
                        "user" => Auth::user(),
                        "session_id" => session()->getId(),
                        "session" => session()->all(),
                    ],
                    true,
                    "Success",
                    200
                );
            }
            return api_response([], false, "Invalid credentials.", 401);
        } catch (\Exception $e) {
            return api_response([], false, $e->getMessage(), $code = $e->getCode() ?: 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();
            $user = User::create([
                'email' => $validated["email"],
                'firstname' => $validated["firstname"],
                'lastname' => $validated["lastname"],
                'middlename' => $validated["middlename"] ?? null,
                'suffix' => $validated["suffix"] ?? null,
                'date_of_birth' => Carbon::parse($validated["date_of_birth"])->format('Y-m-d'),
                'gender' => $validated["gender"],
                'password' => Hash::make($validated["password"])
            ]);
            return api_response(["user" => $user], true, "Success", 200);
        } catch (\Exception $e) {
            return api_response([], false, $e->getMessage(), $code = $e->getCode() ?: 500);
        }
    }

    public function delete($pid)
    {
        try {
            $data = $this->userRepo->searchByPid($pid);
            if (!$data) {
                return api_response([], false, "Invalid data. User ID not found", code: 404);
            }
            $data = $this->userRepo->delete($data);
            return api_response([], true, "Success", 200);
        } catch (\Exception $e) {
            return api_response([], false, $e->getMessage(), $code = $e->getCode() ?: 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return api_response([], true, "Logged out successfully", 200);
        } catch (\Exception $e) {
            return api_response([], false, $e->getMessage(), $code = $e->getCode() ?: 500);
        }
    }
}
