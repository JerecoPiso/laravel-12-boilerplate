<?php

namespace App\Repositories;

use App\Models\User;

class UserRepositories
{
    public function list($filter = [])
    {
        $user = User::query();
        $user = $user->get();
        return $user->toArray();
    }

    public function searchByPid($pid)
    {
        try {
            $user = User::where('pid', $pid)->first();

            if (!$user) {
                return [];
            }

            return $user;
        } catch (\Exception $e) {
            throw new \Exception("An error has occured! " . $e->getMessage());
        }
    }

    public function update($user_id, $data)
    {
        try {
            if (!$data) {
                return null;
            }

            $user = User::findOrFail($user_id);
            $user->update($data);

            return $user;
        } catch (\Exception $e) {
            throw new \Exception("An error has occured! " . $e->getMessage());
        }
    }

    public function delete($data)
    {
        try {
            if (!$data) {
                return;
            }

            $data->delete();

            return true;
        } catch (\Exception $e) {
            throw new \Exception("An error has occured! " . $e->getMessage());
        }
    }
}
