<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

/**
 * @property int $id
 * @property string $disk
 * @property string $path
 * @property string|null $original_filename
 * @property string|null $mime
 * @property string|null $hash
 * @property string|null $creator_type
 * @property int|null $creator_id
 *
 * @property Authenticatable|null $creator
 */
class File extends Model
{
    protected $fillable = [
        'disk',
        'path',
        'original_filename',
        'mime',
    ];

    public function getTemporaryUrl(): string
    {
        return URL::temporarySignedRoute(
            'files.download',
            config('filesystems.disks.'.$this->disk.'.temporary_url', 3600),
            [$this->id]
        );
    }

    public function getUrl(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
}
