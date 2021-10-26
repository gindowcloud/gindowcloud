<?php

namespace GindowCloud\Containers\Asset;

use GindowCloud\Kernel\Application;

class Asset extends Application
{
    public function upload($typeId, $file, $extension)
    {
        return $this->httpUpload('assets', [
            ['name' => 'type_id', 'contents' => $typeId],
            ['name' => 'file', 'contents' => fopen($file, 'r')],
            ['name' => 'extension', 'contents' => $extension],
        ]);
    }

    public function uploadImage($file, $extension = null)
    {
        $extension = $this->parseExtension($file, $extension);
        return $this->upload('IMAGE', $file, $extension ?? 'jpg');
    }

    public function uploadAvatar($file, $extension = null)
    {
        $extension = $this->parseExtension($file, $extension);
        return $this->upload('AVATAR', $file, $extension ?? 'jpg');
    }

    private function parseExtension($file, $extension)
    {
        return $extension || !is_string($file) ? $extension : pathinfo($file)['extension'] ?? null;
    }
}
