<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    use HasFactory;
    use Sluggable;
    protected $guarded = [];


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'englishTitle'
            ]
        ];
    }

    public function categories() {
        return $this->belongsToMany(Category::class, 'category_posts');
    }

    public function likes() : MorphMany {
        return $this->morphMany(Like::class, 'likeable');
    }

}
