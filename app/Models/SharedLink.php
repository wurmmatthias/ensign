<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedLink extends Model
{

    public function document()
{
    return $this->belongsTo(Document::class);
}

    protected $fillable = [
    'token',
    'document_id',
    'expires_at',
];

}
