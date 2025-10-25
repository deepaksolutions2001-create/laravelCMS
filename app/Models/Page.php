<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['title', 'user_id', 'html', 'css', 'slug', 'is_published'];
}
