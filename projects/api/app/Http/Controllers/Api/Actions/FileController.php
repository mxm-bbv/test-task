<?php

namespace App\Http\Controllers\Api\Actions;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\File\FileCollection;
use App\Http\Resources\File\FileResource;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends ApiController
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $file = File::query()
            ->cursorPaginate(5)
            ->withQueryString();

        return $this->success(new FileCollection($file));
    }

    /**
     * @param File $file
     * @return JsonResponse
     */
    public function show(File $file): JsonResponse
    {
        User::query()->where('id', $file->user_id);

        return $this->success([
            'file' => new FileResource($file)
        ]);
    }

    /**
     * @param File $file
     * @return JsonResponse|StreamedResponse
     */
    public function download(File $file): JsonResponse|StreamedResponse
    {
        if (!$file->exists) {
            return $this->fail(['File not found']);
        }

        $fileName = $file->name ? $file->name : $file->original_name;

        return Storage::disk('public')->download($file->hash_name, $fileName, [
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Access-Control-Expose-Headers' => 'Content-Disposition, Content-Length, Content-Type'
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function check(Request $request): JsonResponse
    {
        if ($request->file('file')) {
            $request->validate([
                'file' => 'required|file|max:8000'
            ]);

            $file = $request->file('file');

            /** @var File $fileExists */
            $fileExists = File::where('original_name', $file->getClientOriginalName())->first();

            if ($fileExists) {
                return $this->success(data: [
                    'file' => new FileResource($fileExists),
                    'exists' => true,
                ]);
            }

            return $this->success(data: [
                'exists' => false,
            ]);
        }

        $user = User::query()
            ->where('ip', $request->ip())
            ->first();

        if (!$user) {
            return $this->fail(['User not found']);
        }

        $perPage = $request->input('per_page', 5);

        $file = File::query()
            ->where('user_id', $user->id)
            ->paginate($perPage);

        return $this->success([
            'file' => new FileCollection($file),
            'pagination' => [
                'current_page' => $file->currentPage(),
                'total_pages' => $file->lastPage(),
                'total_items' => $file->total(),
                'per_page' => $file->perPage(),
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = User::where('ip', $request->ip())->first();

        if (!$user) {
            return $this->fail([], message: 'You are not allowed to upload file.');
        }

        $request->validate([
            'file' => 'required|file|max:8000'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $name = $request->get('name', $file->hashName()) . "." . $file->getClientOriginalExtension();
            $path = $file->store('', 'public');

            $json = [
                'user_id' => $user->id,
                'name' => $name,
                'hash_name' => $file->hashName(),
                'original_name' => $file->getClientOriginalName(),
                'url' => env('APP_URL') . '/storage/' . $path,
                'size' => $file->getSize(),
                'mime' => $file->getClientOriginalExtension(),
            ];

            File::query()->create($json);

            return $this->success($json, message: 'File uploaded successfully.');
        }

        return $this->fail([], message: 'File not uploaded.');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = User::where('ip', $request->ip())->first();

        if (!$user) {
            return $this->fail([], message: 'You are not allowed to upload file.');
        }
        $request->validate([
            'file' => 'required|file|max:8000'
        ]);

        $file = $request->file('file');

        $name = $request->get('name', $file->hashName()) . '.' . $file->getClientOriginalExtension();
        $fileRecord = File::where('original_name', $file->getClientOriginalName())
            ->first();

        if ($fileRecord) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $fileRecord->url));

            $newPath = $file->storeAs('', $name, 'public');

            $fileRecord->update([
                'name' => $name,
                'hash_name' => $file->hashName(),
                'original_name' => $file->getClientOriginalName(),
                'url' => env('APP_URL') . '/storage/' . $newPath,
                'size' => $file->getSize(),
                'mime' => $file->getClientOriginalExtension(),
                'updated_at' => now(),
            ]);

            return $this->success([], message: 'File updated successfully.');
        }

        return $this->success([], 404, 'File not updated.');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $file = $request->file('file');

        /** @var File $fileRecord */
        $fileRecord = File::where('original_name', $file->getClientOriginalName())->first();

        if ($fileRecord) {
            Storage::disk('public')->delete($fileRecord->hash_name);
            $fileRecord->delete();

            return $this->success([], message: 'File deleted successfully.');
        }

        return $this->fail([], message: 'File not deleted.');
    }
}
