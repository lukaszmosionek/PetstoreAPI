<?php

namespace App\Services;

use App\Models\Tag;

class TagService
{
    public function syncTags(array $tags): array
    {
        $tagIds = [];

        foreach ($tags as $tagData) {
            $tag = Tag::updateOrCreate(
                ['id' => $tagData['id']],
                ['name' => $tagData['name']]
            );
            $tagIds[] = $tag->id;
        }

        return $tagIds;
    }
}
