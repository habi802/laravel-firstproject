<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\Post;

class AttachmentService
{
    public function store(array $data, Post $post)
    {
        foreach ($data['attachments'] as $attachment) {
            $attachment->storePublicly('attachments', 'public');

            $post->attachments()->create([
                'original_name' => $attachment->getClientOriginalName(),
                'name' => $attachment->hashName('attachments'),
            ]);
        }
    }

    public function destroy(Attachment $attachment)
    {
        $attachment->delete();
    }
}