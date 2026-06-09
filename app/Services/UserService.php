<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function register(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);
    }
    public function login(array $data)
    {
        if (!auth()->attempt($data)) {
            return null;
        }
        return auth()->user();
    }
    public function update(array $data)
    {
        $user = auth()->user();
        $avatarPath = $user->avatar;
        if($data['avatar'] ?? null){
            if($user->avatar && $user->avatar !== 'avatars/default-user-avatar.png'){
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $data['avatar']->store('avatars', 'public');
        }

        $user->update([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'avatar' => $avatarPath,
        ]);
        return $user->fresh();
    }
    public function updatePassword(array $data){
        $user = auth()->user();
        $hashedPassword = $user->password;
        $password = $data['password'];
        if(Hash::check($password, $hashedPassword)){
            $user->update([
                'password'=>$data['new_password']
            ]);
            return $user->fresh();
        }
        return null;
        

    }
}
