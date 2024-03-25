<?php

namespace App\Policies\Api;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class FilePolicy
{
    use HandlesAuthorization;

    public function download(User $user, File $file): Response {
        $file = $user->files()
            ->where('files.id', $file->id)
            ->wherePivot('allow_id', 1)
            ->first();

        if (!is_null($file)) return $this->allow();
        return $this->deny('Forbidden for you');
    }

    public function block(User $user, File $file): Response {
        $author = $file->users()
            ->where('users.id', $user->id)
            ->wherePivot('allow_id', 1)
            ->first();

        $blockUser = User::query()->firstWhere('email', request('email'));
        if (is_null($author))  return $this->deny('Forbidden for you');
        return $author->id != $blockUser->id
            ? $this->allow()
            : $this->deny('Forbidden for you');
    }

    public function add(User $user, File $file): Response {
        $author = $file->users()
            ->where('users.id', $user->id)
            ->wherePivot('allow_id', 1)
            ->first();

        return $author->id == $user->id
            ? $this->allow()
            : $this->deny('Forbidden for you');
    }

    public function edit(User $user, File $file): Response {
        $author = $file->users()
            ->where('users.id', $user->id)
            ->wherePivot('allow_id', 1)
            ->first();

        return $author->id == $user->id
            ? $this->allow()
            : $this->deny('Forbidden for you');
    }

    public function delete(User $user, File $file): Response {
        $author = $file->users()
            ->where('users.id', $user->id)
            ->wherePivot('allow_id', 1)
            ->first();

        return $author->id == $user->id
            ? $this->allow()
            : $this->deny('Forbidden for you');
    }
}
