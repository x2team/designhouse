<?php

namespace App\Jobs;

use App\Models\Design;
use Illuminate\Bus\Queueable;
// use Intervention\Image\Image;
// use Image;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $design;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Design $design)
    {
        $this->design = $design;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $disk = $this->design->disk;
        $filename = $this->design->image;
        $original_file = storage_path() . '/uploads/original/' . $filename;
        // dd($original_file);
        try {
            // Create a Large Image and save to tmp disk
            Image::make($original_file)
                ->fit(800, 600, function($constraint){
                    $constraint->aspectRatio();
                })
                ->save($large = storage_path('uploads/large/' . $filename));
            
            // Create the Thumnbnail Image and save to tmp disk
            Image::make($original_file)
                ->fit(250, 200, function($constraint){
                    $constraint->aspectRatio();
                })
                ->save($thumbnail = storage_path('uploads/thumbnail/' . $filename));

            
            
            
            // Save images to permanent disk : public, s3
            // Original image
            if(Storage::disk($disk)
                    ->put('uploads/designs/original/'. $filename, fopen($original_file, 'r+'))){
                        File::delete($original_file);
                    };
            // Large image
            if(Storage::disk($disk)
                    ->put('uploads/designs/large/'. $filename, fopen($large, 'r+'))){
                        File::delete($large); 
                    };
            // Thumbnail image
            if(Storage::disk($disk)
                    ->put('uploads/designs/thumbnail/'. $filename, fopen($thumbnail, 'r+'))){
                        File::delete($thumbnail ); 
                    };
            /**
             * Update database record with success flag
             */
            $this->design->update([
                'upload_successfuly' => true
            ]);
            


        } catch (\Exception $e) {
            //throw $th;
            \Log::error($e->getMessage());
        }
    }
}
