<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CloudinaryService
{
    /**
     * Subir una imagen a Cloudinary y devolver la URL.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @return string
     */
    public function uploadPhoto($file, $folder = 'profile_photos')
    {
        // Subir la imagen a Cloudinary
        $result = Cloudinary::upload($file->getRealPath(), [
            'folder' => $folder,
        ]);

        // Devolver la URL de la imagen
        return $result->getSecurePath();
    }

    /**
     * Eliminar una imagen de Cloudinary.
     *
     * @param string $publicId
     * @return void
     */
    public function deletePhoto($publicId)
    {
        Cloudinary::destroy($publicId);
    }
}
