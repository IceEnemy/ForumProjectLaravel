<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Storage;

class FirebaseService
{
    protected $storage;

    public function __construct()
    {
        // Initialize Firebase using the service account credentials
        $factory = (new Factory)
            ->withServiceAccount(storage_path('firebase/service-account.json'));

        // Create a Storage instance
        $this->storage = $factory->createStorage();
    }

    /**
     * Upload a file to Firebase Storage and return the public URL.
     *
     * @param \Illuminate\Http\UploadedFile $file The file to upload
     * @param string $path The path to save the file in the storage bucket
     * @return string The public URL of the uploaded file
     */
    public function uploadFile($file, $path)
    {
        $bucket = $this->storage->getBucket(); // Retrieve the storage bucket

        // Upload the file to the given path in the storage bucket
        $object = $bucket->upload(
            fopen($file->getRealPath(), 'r'),
            ['name' => $path]
        );

        // Make the file publicly readable
        $object->update(['acl' => []], ['predefinedAcl' => 'publicRead']);

        // Return the public URL of the uploaded file
        return "https://storage.googleapis.com/{$bucket->name()}/{$path}";
    }

    /**
     * Delete a file from Firebase Storage.
     *
     * @param string $path The path of the file to delete in the storage bucket
     * @return void
     * @throws \Kreait\Firebase\Exception\StorageException
     */
    public function deleteFile($path)
    {
        $bucket = $this->storage->getBucket(); // Retrieve the storage bucket
        $object = $bucket->object($path); // Get the object from the bucket

        if ($object->exists()) {
            $object->delete(); // Delete the object if it exists
        }
    }
}
