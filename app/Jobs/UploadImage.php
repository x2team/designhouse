<?php

namespace App\Jobs;

use App\Models\Models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        $original_file = storage_path() . '/uploads/original' . $this->design->image;

        try {
            // Create a Large Image and save to tmp disk
            Image::make($original_file)
                ->fit(800, 600, function($constraint){
                    $constraint->espectRatio();
                })
                ->save($large = storage_path('uploads/large/' . $this->design->image));
            
            // Create the Thumnbnail Image and save to tmp disk
            Image::make($original_file)
                ->fit(250, 200, function($constraint){
                    $constraint->espectRatio();
                })
                ->save($large = storage_path('uploads/thumbnail/' . $this->design->image));

            // Save images to permanent disk
            


        } catch (\Exception $e) {
            //throw $th;
            \Log::error($e->getMessage());
        }
    }
}
