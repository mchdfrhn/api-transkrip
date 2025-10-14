<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'type',
        'queue',
        'request',
    ];

    /**
     * The data type of the primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Get the user that owns the request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the response associated with the request.
     */
    public function response(): HasOne
    {
        return $this->hasOne(Response::class);
    }

    /**
     * Get the files associated with the request.
     */
    public function requestFiles()
    {
        return $this->hasMany(RequestFile::class);
    }

    /**
     * Get the ID of the response associated with the request.
     *
     * @return string
     */
    public function getResponseIdAttribute(): string
    {
        return $this->response->id;
    }
}
