<?php

namespace App\Service;

use App\Helpers\Functions;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class S3Storage
{
    public function __construct(private string $bucket, private S3Client $s3Client, private LoggerInterface $logger,
                                private ParameterBagInterface $parameterBag, private Functions $functions)
    {
    }

    public function upload(UploadedFile $file): string
    {
        try {
            $key = sprintf('%s.%s', uniqid(), $file->getClientOriginalExtension());
            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $key,
                'SourceFile' => $file->getRealPath(),
                'ACL' => 'public-read',
            ]);
            $imageName = $result['ObjectURL'];
            if($this->parameterBag->get('kernel.environment') === 'dev') {
                $imageName = $this->functions->replaceMinioUrlWithLocalhost($result['ObjectURL']);
            }
            return $imageName;

        } catch (S3Exception $e) {
            $this->logger->error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

}