<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Bug extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'description',
        'photo_path',
        'urgent',
    ];

    /**
     * Update the bug photo.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return void
     */
    public function updateBugPhoto(UploadedFile $photo)
    {
        tap($this->photo_path, function ($previous) use ($photo) {
            $this->forceFill([
                'photo_path' => $photo->storePublicly('bug-photos'),
            ])->save();

            if ($previous) {
                Storage::delete($previous);
            }
        });
    }

    /**
     * Delete the bug photo.
     *
     * @return void
     */
    public function deletePhoto()
    {
        if (is_null($this->photo_path)) {
            return;
        }

        Storage::delete($this->photo_path);

        $this->forceFill([
            'photo_path' => null,
        ])->save();
    }

    /**
     * Get the URL to the bug photo.
     *
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        return $this->photo_path
                    ? Storage::url($this->photo_path)
                    : null;
    }
}
