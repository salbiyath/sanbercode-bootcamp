<?php

namespace App\Models\Article;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory, UsesUuid;

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
