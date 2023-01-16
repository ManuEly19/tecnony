<?php

// Establecer el path del Trait
namespace App\Trait;

use App\Models\File;

// https://replit.com/@ByronLoarte/18-Traits#index.php
// https://www.php.net/manual/es/language.oop5.traits.php

trait HasFile
{
    // Función para manejar la relación polimorfica
    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    // función para agregar el archivo en la BDD
    public function attachFile(string $file_path)
    {
        // se obtiene el modelo File mediante la propiedad file
        $previous_file = $this->file;
        // si la imagen es vacía
        if (is_null($previous_file))
        {
            // se crea una nueva imagen
            $file = new File(['path' => $file_path]);
            // se registra en la BDD por medio de la relación
            // https://laravel.com/docs/9.x/eloquent-relationships#the-save-method
            $this->file()->save($file);
        }
        else
        {
            // se actualiza con el path que entra como parametro de entrada
            $previous_file->path = $file_path;
            // se actualiza en la BDD
            $previous_file->save();
        }
    }
}
