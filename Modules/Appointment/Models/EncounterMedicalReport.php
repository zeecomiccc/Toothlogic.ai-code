<?php

namespace Modules\Appointment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Appointment\Database\factories\EncounterMedicalReportFactory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class EncounterMedicalReport extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */

     protected $table = 'encounter_medical_report';
    
     protected $fillable = ['encounter_id','user_id','name','date','radiographs'];

     protected $appends = ['file_url'];

     protected $casts = [
         'radiographs' => 'array',
     ];

    
    protected static function newFactory(): EncounterMedicalReportFactory
    {
        //return EncounterMedicalReportFactory::new();
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
                'type' => 'medical_report'
            ];
        }
        
        return $files;
    }

    public function getAllIntraoralScans()
    {
        $mediaItems = $this->getMedia('intraoral_scans');
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
                'type' => 'intraoral_scan'
            ];
        }
        
        return $files;
    }

    public function getAllOralPics()
    {
        $mediaItems = $this->getMedia('oral_pics');
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
                'type' => 'oral_pic'
            ];
        }
        
        return $files;
    }

    public function getAllAdditionalAttachments()
    {
        $mediaItems = $this->getMedia('additional_attachments');
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
                'type' => 'additional_attachment'
            ];
        }
        
        return $files;
    }

    public function getAllRadiographFiles()
    {
        $mediaItems = $this->getMedia('radiograph_files');
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
                'type' => 'radiograph'
            ];
        }
        
        return $files;
    }

    public function getAllFilesWithType()
    {
        $medicalFiles = $this->getAllFiles();
        $intraoralScans = $this->getAllIntraoralScans();
        $oralPics = $this->getAllOralPics();
        $additionalAttachments = $this->getAllAdditionalAttachments();
        $radiographFiles = $this->getAllRadiographFiles();
        
        return array_merge($medicalFiles, $intraoralScans, $oralPics, $additionalAttachments, $radiographFiles);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }



}
