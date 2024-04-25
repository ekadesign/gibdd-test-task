<?php

namespace App\Services;

use App\Models\File;
use cardinalby\ContentDisposition\ContentDisposition;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Testing\MimeType;
use Illuminate\Http\UploadedFile;
use PDOException;
use Storage;
use Str;

class FileService
{
    /**
     * @param  UploadedFile  $uploadedFile
     * @param  string  $path
     * @param  string|null  $disk
     * @param  bool  $hash
     * @return File|null
     * @throws FileNotFoundException
     */
    public function upload(
        UploadedFile $uploadedFile,
        string $path,
        string $disk = null,
        bool $hash = true,
    ): ?File {
        $disk = $this->disk($disk);

        if ($hash) {
            $hashFile = md5($uploadedFile->get());
            /** @var File|null $file */
            $file = File::query()
                ->where('hash', $hashFile)
                ->first();

            if ($file) {
                return $file;
            }
        } else {
            $hashFile = null;
        }

        if ($path = Storage::disk($disk)->putFile($path, $uploadedFile)) {
            $file = new File();
            $file->disk = $disk;
            $file->path = $path;
            $file->original_filename = $uploadedFile->getClientOriginalName();
            $file->mime = $uploadedFile->getClientMimeType();
            $file->hash = $hashFile;
            if (auth()->check()) {
                $creator = auth()->user();
                $file->creator_type = $creator->getMorphClass();
                $file->creator_id = $creator->id;
            }
            try {
                $file->save();
            } catch (PDOException $exception) {
                if ($exception->getCode() != 23000) {
                    throw $exception;
                }

                $file = File::query()
                    ->where('hash', $hashFile)
                    ->first();
            }
            return $file;
        }
        return null;
    }

    public function uploadFilename(
        string $filename,
        string $path,
        string $disk = null,
        bool $hash = true,
    ): ?File {
        $disk = $this->disk($disk);
        $file = new \Illuminate\Http\File($filename);

        if ($hash) {
            $hashFile = md5($file->getContent());
            $modelFile = File::query()
                ->where('hash', $hashFile)
                ->first();

            if ($modelFile) {
                return $modelFile;
            }
        } else {
            $hashFile = null;
        }

        if ($path = Storage::disk($disk)->putFile($path, $file)) {
            $modelFile = new File();
            $modelFile->disk = $disk;
            $modelFile->path = $path;
            $modelFile->original_filename = $file->getFilename();
            $modelFile->mime = $file->getMimeType();
            $modelFile->hash = $hashFile;
            if (auth()->check()) {
                $creator = auth()->user();
                $modelFile->creator_type = $creator->getMorphClass();
                $modelFile->creator_id = $creator->id;
            }
            try {
                $modelFile->save();
            } catch (PDOException $exception) {
                if ($exception->getCode() != 23000) {
                    throw $exception;
                }

                $modelFile = File::query()
                    ->where('hash', $hashFile)
                    ->first();
            }
            return $modelFile;
        }
        return null;
    }

    /**
     * @param  Response  $response
     * @param  string  $path
     * @param  string|null  $disk
     * @param  bool  $hash
     * @return File|null
     * @throws FileNotFoundException
     */
    public function uploadResponse(Response $response, string $path, ?string $disk = null, bool $hash = true): ?File
    {
        $filename = null;
        if ($contentDisposition = $response->header('Content-Disposition')) {
            $cd = ContentDisposition::parse($contentDisposition);
            $filename = $cd->getFilename();
        }
        $mime = $response->header('Content-Type');
        if (is_null($filename)) {
            $extension = MimeType::search($mime);
            $requestPath = $response->effectiveUri()->getPath();
            if (Str::endsWith($requestPath, $extension)) {
                $filename = basename($requestPath);
            } else {
                $filename = Str::random().'.'.($extension ?? 'tmp');
            }
        }

        $tempFile = tmpfile();
        $tempFilePath = stream_get_meta_data($tempFile)['uri'];

        // Save file data in file
        file_put_contents($tempFilePath, $response->body());

        $tempFileObject = new \Illuminate\Http\File($tempFilePath);
        $file = new UploadedFile(
            $tempFileObject->getPathname(),
            Str::substr($filename ?? $tempFileObject->getFilename(), 0, 255),
            $mime ?? $tempFileObject->getMimeType(),
            0,
            true // Mark it as test, since the file isn't from real HTTP POST.
        );

        app()->terminating(function () use ($tempFile) {
            fclose($tempFile);
        });

        return $this->upload($file, $path, $disk, $hash);
    }

    protected function disk($disk): string
    {
        return $disk ?? config('filesystems.default');
    }
}
