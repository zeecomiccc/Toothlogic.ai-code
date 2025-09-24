<?php

namespace Modules\Lab\Models;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Lab extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'case_type',
        'notes',
        'case_status',
        'delivery_date_estimate',
        'clear_aligner_impression_type',
        'prosthodontic_impression_type',
        'treatment_plan_link',
        'margin_location',
        'contact_tightness',
        'occlusal_scheme',
        'temporary_placed',
        'teeth_treatment_type',
        'shade_selection',
    ];

    protected $casts = [
        'delivery_date_estimate' => 'datetime',
        'temporary_placed' => 'boolean',
        'teeth_treatment_type' => 'array',
    ];

    protected $appends = ['file_url'];

    /**
     * Register media collections for the model
     */
    public function registerMediaCollections(): void
    {
        // Call parent method if it exists
        if (method_exists(parent::class, 'registerMediaCollections')) {
            parent::registerMediaCollections();
        }
        
        // Register all media collections explicitly
        $this->addMediaCollection('file_url');
        $this->addMediaCollection('clear_aligner_intraoral');
        $this->addMediaCollection('clear_aligner_pics');
        $this->addMediaCollection('clear_aligner_others');
        $this->addMediaCollection('prostho_prep_scans');
        $this->addMediaCollection('prostho_bite_scans');
        $this->addMediaCollection('prostho_preop_pictures');
        $this->addMediaCollection('prostho_others');
        $this->addMediaCollection('rx_prep_scan');
        $this->addMediaCollection('rx_bite_scan');
        $this->addMediaCollection('rx_preop_images');
    }

    // Relationships
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get file URL attribute - same as EncounterMedicalReport
     */
    protected function getFileUrlAttribute()
    {
        $media = $this->getFirstMediaUrl('file_url');
        return isset($media) && ! empty($media) ? $media : default_file_url();
    }

    /**
     * Get all files - same as EncounterMedicalReport
     */
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
                'type' => 'lab_file'
            ];
        }
        
        return $files;
    }

    /**
     * Get all Clear Aligner Intraoral files
     */
    public function getAllClearAlignerIntraoral()
    {
        $mediaItems = $this->getMedia('clear_aligner_intraoral');
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
                'type' => 'clear_aligner_intraoral'
            ];
        }
        
        return $files;
    }

    /**
     * Get all Clear Aligner Pics files
     */
    public function getAllClearAlignerPics()
    {
        $mediaItems = $this->getMedia('clear_aligner_pics');
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
                'type' => 'clear_aligner_pics'
            ];
        }
        
        return $files;
    }

    /**
     * Get all Clear Aligner Others files
     */
    public function getAllClearAlignerOthers()
    {
        $mediaItems = $this->getMedia('clear_aligner_others');
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
                'type' => 'clear_aligner_others'
            ];
        }
        
        return $files;
    }

    /**
     * Get all Prostho Prep Scans files
     */
    public function getAllProsthoPrepScans()
    {
        $mediaItems = $this->getMedia('prostho_prep_scans');
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
                'type' => 'prostho_prep_scans'
            ];
        }
        
        return $files;
    }

    /**
     * Get all Prostho Bite Scans files
     */
    public function getAllProsthoBiteScans()
    {
        $mediaItems = $this->getMedia('prostho_bite_scans');
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
                'type' => 'prostho_bite_scans'
            ];
        }
        
        return $files;
    }

    /**
     * Get all Prostho Preop Pictures files
     */
    public function getAllProsthoPreopPictures()
    {
        $mediaItems = $this->getMedia('prostho_preop_pictures');
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
                'type' => 'prostho_preop_pictures'
            ];
        }
        
        return $files;
    }

    /**
     * Get all Prostho Others files
     */
    public function getAllProsthoOthers()
    {
        $mediaItems = $this->getMedia('prostho_others');
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
                'type' => 'prostho_others'
            ];
        }
        
        return $files;
    }

    /**
     * Get all Rx Prep Scan files
     */
    public function getAllRxPrepScan()
    {
        $mediaItems = $this->getMedia('rx_prep_scan');
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
                'type' => 'rx_prep_scan'
            ];
        }
        
        return $files;
    }

    /**
     * Get all Rx Bite Scan files
     */
    public function getAllRxBiteScan()
    {
        $mediaItems = $this->getMedia('rx_bite_scan');
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
                'type' => 'rx_bite_scan'
            ];
        }
        
        return $files;
    }

    /**
     * Get all Rx Preop Images files
     */
    public function getAllRxPreopImages()
    {
        $mediaItems = $this->getMedia('rx_preop_images');
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
                'type' => 'rx_preop_images'
            ];
        }
        
        return $files;
    }

    /**
     * Get all files with their types
     */
    public function getAllFilesWithType()
    {
        $defaultFiles = $this->getAllFiles();
        $clearAlignerIntraoral = $this->getAllClearAlignerIntraoral();
        $clearAlignerPics = $this->getAllClearAlignerPics();
        $clearAlignerOthers = $this->getAllClearAlignerOthers();
        $prosthoPrepScans = $this->getAllProsthoPrepScans();
        $prosthoBiteScans = $this->getAllProsthoBiteScans();
        $prosthoPreopPictures = $this->getAllProsthoPreopPictures();
        $prosthoOthers = $this->getAllProsthoOthers();
        $rxPrepScan = $this->getAllRxPrepScan();
        $rxBiteScan = $this->getAllRxBiteScan();
        $rxPreopImages = $this->getAllRxPreopImages();
        
        return array_merge(
            $defaultFiles,
            $clearAlignerIntraoral, 
            $clearAlignerPics, 
            $clearAlignerOthers,
            $prosthoPrepScans,
            $prosthoBiteScans,
            $prosthoPreopPictures,
            $prosthoOthers,
            $rxPrepScan,
            $rxBiteScan,
            $rxPreopImages
        );
    }
}
