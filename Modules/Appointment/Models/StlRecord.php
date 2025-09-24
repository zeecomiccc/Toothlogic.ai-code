<?php

namespace Modules\Appointment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class StlRecord extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'stl_records';

    protected $fillable = [
        'user_id',
        'encounter_id',
        'stl_files',
        'date',
    ];

    protected $casts = [
        'stl_files' => 'array',
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

 
    protected function getFileUrlAttribute()
    {
        $media = $this->getFirstMediaUrl('file_url');

        return isset($media) && ! empty($media) ? $media : default_file_url();
    }

    
    public function getAllFiles()
    {
        $mediaItems = $this->getMedia('file_url');
        $files = [];
        foreach ($mediaItems as $media) {
            $files[] = [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'name' => $media->name,
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'is_image' => strpos($media->mime_type, 'image/') === 0,
                'is_pdf' => $media->mime_type === 'application/pdf',
                'type' => 'stl_file',
            ];
        }
        return $files;
    }
} 