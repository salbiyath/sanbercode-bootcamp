<?php

namespace App\Models\Article;

use App\Models\User;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = ['title', 'slug', 'body', 'subject_id'];

    protected $with = ['subject', 'user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
