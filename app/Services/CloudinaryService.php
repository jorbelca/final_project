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
    public static function uploadPhoto($file, $folder = 'profile_photos')
    {
        try {
            // Subir la imagen a Cloudinary
            $result = Cloudinary::upload($file->getRealPath(), [
                'folder' => $folder,
                'transformation' => [
                    'quality' => 'auto:good',  // Optimiza la calidad automáticamente
                    'fetch_format' => 'auto',  // Usa el mejor formato (WebP, JPEG, PNG)
                    'width' => 200,
                    'crop' => 'limit',  // Asegura que la imagen no se deforme
                ],
            ]);

            // Devolver la URL de la imagen
            return $result->getSecurePath();
        } catch (\Throwable $th) {
            return $th;
        }
    }

    /**
     * Eliminar una imagen de Cloudinary.
     *
     * @param string $publicId
     * @return void
     */
    public static function deletePhoto($publicId, $folder = 'profile_photos')
    {
        // Obtener el ID de la imagen
        $publicId = explode('/', $publicId);
        $publicId = end($publicId); // Obtiene la última parte después de la última barra "/"
        $imageId = pathinfo($publicId, PATHINFO_FILENAME); // Elimina la extensión del archivo

        try {
            Cloudinary::destroy($folder . '/' . $imageId);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
