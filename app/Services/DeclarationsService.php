<?php

namespace App\Services;

use App\Declaration;
use App\Member;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;

class DeclarationsService
{
    private const BASE_DIR = "uploads/declarations/";

    /** @var Filesystem */
    private $storage;
    
    public function __construct()
	{
        $this->storage = Storage::disk("local");
    }

    public function store(Member $member, UploadedFile $file): string
    {
        $filepath = $this->getNewFilePath(
            $member, 
            $file
        );
        
        $this->storage->putFileAs(
            dirname($filepath),
            $file,
            basename($filepath)
        );

        return $filepath;
    }

    public function deleteFileFor(Declaration $declaration) 
    {
        $this->storage->delete($declaration->filename);
    }

    private function getFilePath(Member $member, string $fileName)
    {
		return self::BASE_DIR . $member->id . '/' . $fileName;
	}

    private function getNewFilePath(Member $member, UploadedFile $file)
    {
		return $this->getFilePath(
			$member,  
			date('YmdHis') . '_' . random_int(0, 9999) . '.' . $file->extension()
		);
	}
}