<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class FileManagerController extends Controller
{



    public function renameDir(Request $request)
    {
        $path = $request->input('d', '');
        $name = basename(trim($request->input('n', '')));

        if ($name === '') {
            return $this->errorResponse('Folder name is empty');
        }

        $dirPath = $this->resolvePath($path);

        if ($dirPath === false || !File::isDirectory($dirPath)) {
            return $this->errorResponse('Invalid directory path');
        }

        $rootPath = realpath(public_path('uploads/files'));

        // Забороняємо перейменування кореневої папки
        if ($dirPath === $rootPath) {
            return $this->errorResponse('Cannot rename root directory');
        }

        $newPath = dirname($dirPath) . DIRECTORY_SEPARATOR . $name;

        // Якщо папка з таким ім'ям вже існує
        if (File::exists($newPath)) {
            return $this->errorResponse('Directory already exists');
        }

        if (rename($dirPath, $newPath)) {
            return $this->successResponse();
        }

        return $this->errorResponse('Cannot rename directory ' . basename($dirPath));
    }





    public function deleteDir(Request $request)
    {
        $path = $request->input('d', '');

        $dirPath = $this->resolvePath($path);

        if ($dirPath === false || !File::isDirectory($dirPath)) {
            return $this->errorResponse('Invalid directory path');
        }

        $rootPath = realpath(public_path('uploads/files'));

        // Забороняємо видалення кореневої папки
        if ($dirPath === $rootPath) {
            return $this->errorResponse('Cannot delete root directory');
        }

        // Перевіряємо, чи папка порожня
        if (count(File::allFiles($dirPath)) > 0 || count(File::directories($dirPath)) > 0) {
            return $this->errorResponse('Directory is not empty');
        }

        if (File::deleteDirectory($dirPath)) {
            return $this->successResponse();
        }

        return $this->errorResponse('Cannot delete directory ' . basename($dirPath));
    }



    public function deleteFile(Request $request)
    {
        $realPath = $this->resolvePath($request->input('f', ''));

        if ($realPath === false || !File::isFile($realPath)) {
            return $this->errorResponse('Invalid file path');
        }

        if (!File::exists($realPath) || !File::isFile($realPath)) {
            return $this->errorResponse('File not found');
        }

        try {
            File::delete($realPath);
            return $this->successResponse('');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }





    public function dirTree(Request $request)
    {
        $type = strtolower($request->input('type', ''));

        if (!in_array($type, ['', 'image', 'flash'])) {
            $type = '';
        }

        $basePath = realpath(public_path('uploads/files'));

        $result = [];

        $this->scanDirectories(
            $basePath,
            '',
            $type,
            $result
        );

        return response()->json($result);
    }





    public function createDir(Request $request)
    {
        $dir = $request->input('d', '');
        $name = basename(trim($request->input('n', '')));

        if ($name === '') {
            return $this->errorResponse('Folder name is empty');
        }

        $currentPath = $this->resolvePath($dir);

        if ($currentPath === false) {
            return $this->errorResponse('Invalid path');
        }

        $newDir = $currentPath . DIRECTORY_SEPARATOR . $name;

        if (File::exists($newDir)) {
            return $this->errorResponse('Folder already exists');
        }

        if (!File::makeDirectory($newDir, 0755, true)) {
            return $this->errorResponse('Unable to create folder');
        }

        return $this->successResponse('');
    }



    public function upload(Request $request)
    {
        $dir = trim($request->input('d', ''), '/');

        // Якщо Roxy передав абсолютний шлях
        $dir = preg_replace('#^uploads/files/?#', '', $dir);

        $basePath = realpath(public_path('uploads/files'));

        $currentPath = $basePath;

        if ($dir !== '') {
            $currentPath .= DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $dir);
        }

        $currentPath = realpath($currentPath);

        if (!$currentPath || !str_starts_with($currentPath, $basePath)) {
            return $this->errorResponse('Invalid path');
        }

        if (!$request->hasFile('files')) {
            return $this->errorResponse('No files uploaded');
        }

        $uploaded = [];
        $errors = [];

        foreach ($request->file('files') as $file) {

            if (!$file->isValid()) {
                $errors[] = $file->getClientOriginalName();
                continue;
            }

            // Аналог CanUploadFile()
            $allowed = [
                'jpg','jpeg','png','gif','webp','svg',
                'pdf','doc','docx','xls','xlsx','zip'
            ];

            $ext = strtolower($file->getClientOriginalExtension());

            if (!in_array($ext, $allowed)) {
                $errors[] = $file->getClientOriginalName();
                continue;
            }

            $filename = $this->makeUniqueFilename(
                $currentPath,
                $file->getClientOriginalName()
            );

            $file->move($currentPath, $filename);

            @chmod($currentPath.'/'.$filename, 0644);

            // Якщо це картинка
            if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {

                $this->resizeImage(
                    $currentPath.'/'.$filename,
                    1920,
                    1080
                );
            }

            $uploaded[] = $filename;
        }

        $result = empty($errors);

        if ($request->input('method') == 'ajax') {

            return response()->json([
                'res'   => $result ? 'ok' : 'error',
                'msg'   => $result ? '' : 'Some files were not uploaded',
                'files' => $uploaded,
            ]);

        }

        return response(
            '<script>parent.fileUploaded('.json_encode([
                'res' => $result ? 'ok' : 'error',
                'msg' => $result ? '' : 'Some files were not uploaded'
            ]).');</script>'
        )->header('Content-Type', 'text/html');
    }



    public function filesList(Request $request)
    {
        $type = strtolower($request->input('type', ''));

        if (!in_array($type, ['', 'image', 'flash'])) {
            $type = '';
        }

        // Базова папка
        $basePath = realpath(public_path('uploads/files'));

        $dir = trim($request->input('d', ''), '/');
        // прибираємо абсолютний префікс, якщо його прислав Roxy
        $dir = preg_replace('#^uploads/files/?#', '', $dir);

        // Поточна папка
        $currentPath = $basePath;

        if ($dir !== '') {
            $currentPath .= DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $dir);
        }

        $realCurrent = realpath($currentPath);

        if ($currentPath === false || !str_starts_with($currentPath, $basePath)) {
            abort(403);
        }

        $files = File::files($realCurrent);

        usort($files, function ($a, $b) {
            return strnatcasecmp($a->getFilename(), $b->getFilename());
        });

        $result = [];

        foreach ($files as $file) {

            $filename = $file->getFilename();

            // Фільтр типів
            if ($type == 'image' && !$this->isImage($filename->getExtension())) {
                continue;
            }

            if ($type == 'flash' && strtolower($file->getExtension()) != 'swf') {
                continue;
            }

            $width = 0;
            $height = 0;

            if ($this->isImage($filename)) {

                $size = @getimagesize($file->getRealPath());

                if ($size) {
                    $width = $size[0];
                    $height = $size[1];
                }
            }

            $relative = ($dir ? $dir.'/' : '') . $filename;

            $result[] = [
                'p' => '/uploads/files/' . str_replace('\\', '/', $relative),
                's' => $file->getSize(),
                't' => $file->getMTime(),
                'w' => $width,
                'h' => $height,
            ];
        }

        return response()->json($result);
    }


    protected function isImage($extension)
    {
        return in_array(
            strtolower($extension),
            [
                'jpg',
                'jpeg',
                'png',
                'gif',
                'bmp',
                'webp',
                'svg'
            ]
        );
    }


    protected function makeUniqueFilename($path, $filename)
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $i = 1;
        $newName = $filename;
        while (File::exists($path.'/'.$newName)) {
            $newName = $name.'_'.$i.'.'.$ext;
            $i++;
        }
        return $newName;
    }



    protected function resizeImage($file, $maxWidth, $maxHeight)
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
        $image->scaleDown(
            width: $maxWidth,
            height: $maxHeight
        );
        $image->save($file);
    }




    protected function scanDirectories($absolutePath, $relativePath, $type, &$result)
    {
        $counter = $this->countFilesAndDirs($absolutePath, $type);

        $result[] = [
            'p' => '/uploads/files' . ($relativePath ? '/' . str_replace('\\', '/', $relativePath) : ''),
            'f' => $counter['files'],
            'd' => $counter['dirs'],
        ];

        $dirs = File::directories($absolutePath);

        natcasesort($dirs);

        foreach ($dirs as $dir) {

            $name = basename($dir);

            $newRelative = $relativePath
                ? $relativePath . '/' . $name
                : $name;

            $this->scanDirectories(
                $dir,
                $newRelative,
                $type,
                $result
            );
        }
    }




    protected function countFilesAndDirs($path, $type)
    {
        $files = 0;

        foreach (File::files($path) as $file) {

            $ext = strtolower($file->getExtension());

            if (
                $type == 'image' &&
                !$this->isImage($ext)
            ) {
                continue;
            }

            if (
                $type == 'flash' &&
                $ext != 'swf'
            ) {
                continue;
            }

            $files++;
        }

        return [
            'files' => $files,
            'dirs' => count(File::directories($path)),
        ];
    }





    protected function resolvePath(string $path = '', bool $mustExist = true)//: string|false
    {
        $path = trim($path, '/');
        $path = preg_replace('#^uploads/files/?#', '', $path);

        $basePath = realpath(public_path('uploads/files'));

        $fullPath = $basePath;

        if ($path !== '') {
            $fullPath .= DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
        }

        if ($mustExist) {

            $realPath = realpath($fullPath);

            if (!$realPath || !str_starts_with($realPath, $basePath)) {
                return false;
            }

            return $realPath;
        }

        // Для нових файлів/папок перевіряємо тільки батьківську директорію
        $parent = realpath(dirname($fullPath));

        if (!$parent || !str_starts_with($parent, $basePath)) {
            return false;
        }

        return $fullPath;
    }



    protected function successResponse($msg = '')
    {
        return response()->json([
            'res' => 'ok',
            'msg' => $msg,
        ]);
    }



    protected function errorResponse($msg)
    {
        return response()->json([
            'res' => 'error',
            'msg' => $msg,
        ]);
    }


}
