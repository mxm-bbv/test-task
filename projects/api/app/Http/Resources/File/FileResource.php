<?php

namespace App\Http\Resources\File;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin File
 */
class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $size = round($this->size / 1024 / 1024, 2);

        return [
            'id' => $this->id,
            'user' => $this->user_id,
            'name' => $this->name,
            'original_name' => $this->original_name,
            'url' => $this->url,
            'size' => $size . ' MB',
            'mime' => $this->mime,
            'date' => $this->created_at->format('d.m.Y'),
        ];
    }
}
