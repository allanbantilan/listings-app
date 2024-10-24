<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'desc',
        'tags',
        'email',
        'link',
        'image',
        'approved',
    ];

    public function user()
    {
        return $this->belongsto(User::class);
    }

    public function scopeFilter($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . request('search') . '%');
                $q->orWhere('desc', 'like', '%' . request('search') . '%');
            });
        }
        if ($filters['user_id'] ?? false) {
            $query
                ->where('user_id', request('user_id'));
        }
        if ($filters['tag'] ?? false) {
            $query
                ->where('tags', 'like', '%' . request('tag') . '%');
        }
        if ($filters['disapproved'] ?? false) {
            $query
                ->where('approved', 'like', false);
        }
    }
}
