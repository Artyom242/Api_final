<?php

namespace App\Http\Controllers\Api\File;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\File\AddAccessFileRequest;
use App\Http\Requests\Api\File\DeleteFileRequest;
use App\Http\Requests\Api\File\EditFileRequest;
use App\Http\Requests\Api\File\UploadFileRequest;
use App\Http\Resources\Api\File\AccessFileResource;
use App\Http\Resources\Api\File\ErrorUploadFileResource;
use App\Http\Resources\Api\File\GetFilesResource;
use App\Http\Resources\Api\File\UploadFileResource;
use App\Models\File as FileModel;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function uploadFile(UploadFileRequest $request)
    {
        $result = [];
        foreach ($request->validated('files') as $file) {
            $validator = Validator::make([
                'files' => $file
            ], [
                'files' => File::types(['doc', 'pdf', 'docx', 'zip', 'jpeg', 'jpg', 'png'])->max(2048)
            ]);

            if ($validator->fails()) {
                $result[] = new  ErrorUploadFileResource($file);
            }
            /**
             * @var UploadedFile $file
             */
            $path = Storage::putFileAs('/uploads/', $file, $file->getFilename());
            /**
             * @var FileModel $fileModel
             */
            $fileModel = FileModel::query()->create([
                'path' => $path,
                'name' => $file->getClientOriginalName(),
                'file_id' => Str::random(10)
            ]);
            $fileModel->users()->attach(auth()->user()->id, [
                'allow_id' => 1
            ]);
            $result[] = new UploadFileResource($fileModel);
        }
        return response()->json([$result]);
    }

    public function editFile(EditFileRequest $request, FileModel $file): JsonResponse
    {
        $data = $request->validated();
        $file->update(['name' => $data['name']]);
        return response()->json([
            'success' => true,
            'message' => 'Renamed',
        ]);
    }

    public function downloadFile(FileModel $file): StreamedResponse
    {
        return Storage::download($file->path, $file->name);
    }

    public function deleteFile(FileModel $file): JsonResponse
    {
        $usersFile = $file->users()->get();
        /**
         * @var User $user
         */
        foreach ($usersFile as $user) {
            $user->files()->detach($file->id);
        }

        $file->delete();
        return response()->json([
            'success' => true,
            'message' => 'File already deleted'
        ]);
    }

    public function addAccessFile(AddAccessFileRequest $request, FileModel $file): JsonResponse
    {
        $result = [];
        $data = $request->validated();
        $addUser = User::query()->firstWhere('email', $data['email']);
        /**
         * @var User $addUser
         */
        $addUser->files()->attach($file->id, [
            'allow_id' => 2
        ]);

        $userFiles = $file->users()->withPivot('allow_id')->get();
        foreach ($userFiles as $user) {
            $result[] = new AccessFileResource($user);
        }

        return response()->json($result);
    }

    public function blockAccessFile(AddAccessFileRequest $request, FileModel $file): JsonResponse
    {
        $result = [];
        $data = $request->validated();
        $blockUser = User::query()->firstWhere('email', $data['email']);
        /**
         * @var User $blockUser
         */
        $blockUser->files()->detach($file->id);

        $userFiles = $file->users()->withPivot('allow_id')->get();
        foreach ($userFiles as $user) {
            $result[] = new AccessFileResource($user);
        }

        return response()->json($result);
    }

    public function getAllFiles()
    {
        $result = [];
        $author = auth()->user();
        /**
         * @var User $author
         */
        $filesAuthor = $author->files()->wherePivot('allow_id',1)->get();
        /** @var FileModel $file
         *
         */
        foreach ($filesAuthor as $file) {
            $usersResult = [];

            $filesUser = $file->users()->withPivot('allow_id')->get();
            foreach ($filesUser as $user) {
                $usersResult[] = new AccessFileResource($user);
            }

            $result[] = [
                'file_id' => $file->file_id,
                'name' => $file->name,
                'url' => asset('/files/' . $file->file_id),
                'accesses' => $usersResult,
            ];
        }

        return response()->json($result);
    }

    public function getUserFiles()
    {
        $result = [];
        $author = auth()->user();
        /**
         * @var User $author
         */
        $filesAuthor = $author->files()->withPivot('allow_id')->wherePivot('allow_id', 2)->get();
        foreach ($filesAuthor as $file) {
            $result[] = new GetFilesResource($file);
        }
        return response()->json($result);
    }
}
