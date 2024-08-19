<?php

namespace App\Service;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class S3Storage
{
    public function __construct(private string $bucket, private S3Client $s3Client)
    {
    }

    public function upload(UploadedFile $file): string
    {
        try {
            $key = sprintf('%s.%s', uniqid(), $file->getClientOriginalExtension());
            //dd($file->getRealPath());
            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
                'SourceFile' => $file->getRealPath(),
                'ACL' => 'public-read',
            ]);
            dump($result);
            return $result['ObjectURL'];

        } catch (S3Exception $e) {
            dd($e);
            throw new \Exception($e->getMessage());
        }
    }
}