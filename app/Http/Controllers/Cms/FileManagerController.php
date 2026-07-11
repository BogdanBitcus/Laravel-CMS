<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use ZipArchive;

class FileManagerController extends Controller
{



    public function downloadDir(Request $request)
    {
        $directory = $this->getDirectory($request->input('d'));

        if (!class_exists(ZipArchive::class)) {
            return $this->result(false, 'ZipArchive extension is not installed');
        }

        $zipName = basename($directory) . '.zip';
        $zipPath = storage_path('app/tmp/' . uniqid() . '_' . $zipName);

        if (!File::exists(dirname($zipPath))) {
            File::makeDirectory(dirname($zipPath), 0755, true);
        }

        if (!$this->zipDirectory($directory, $zipPath)) {
            return $this->result(false, 'Unable to create archive');
        }

        return response()->download($zipPath, $zipName)->deleteFileAfterSend(true);
    }




    public function thumb(Request $request)
    {
        $filePath = $this->getImage($request->input('f'));

        $width = (int)$request->input('width', 100);
        $height = (int)$request->input('height', 0);

        $manager = new ImageManager(new Driver());

        $image = $manager->read($filePath);

        if ($width > 0 && $height > 0) {
            // Аналог CropCenter()
            $image->cover($width, $height);
        } else {
            // Аналог Resize()
            $image->scale(
                width: $width ?: null,
                height: $height ?: null
            );
        }

        return response($image->encode())
            ->header('Content-Type', File::mimeType($filePath))
            ->header('Cache-Control', 'public, max-age=3600')
            ->header('Pragma', 'cache');
    }





    public function download(Request $request)
    {
        $filePath = $this->getFile($request->input('f'));
        return response()->download($filePath, basename($filePath));
    }




    public function copyDir(Request $request)
    {
        $path = $request->input('d', '');
        $newPath = $request->input('n', '');

        // Папка-джерело
        $sourceDir = $this->resolvePath($path);

        if ($sourceDir === false || !File::isDirectory($sourceDir)) {
            return $this->result(false, 'Invalid source directory');
        }

        // Папка призначення
        $destinationParent = $this->resolvePath($newPath);

        if ($destinationParent === false || !File::isDirectory($destinationParent)) {
            return $this->result(false, 'Invalid destination directory');
        }

        // Забороняємо копіювати папку всередину самої себе
        $sourceNormalized = rtrim(str_replace('\\', '/', $sourceDir), '/');
        $destinationNormalized = rtrim(str_replace('\\', '/', $destinationParent), '/');

        if (str_starts_with($destinationNormalized, $sourceNormalized . '/')) {
            return $this->result(false, 'Cannot copy directory into its child');
        }

        // Формуємо шлях призначення
        $newDirectory = $destinationParent . DIRECTORY_SEPARATOR . basename($sourceDir);

        // Робимо ім'я унікальним (як у старому Roxy)
        if (File::exists($newDirectory)) {
            $newName = $this->makeUniqueDirectoryName(
                $destinationParent,
                basename($sourceDir)
            );
            $newDirectory = $destinationParent . DIRECTORY_SEPARATOR . $newName;
        }

        if (File::copyDirectory($sourceDir, $newDirectory)) {
            return $this->result(true);
        }

        return $this->result(false, 'Cannot copy directory');
    }



    public function moveDir(Request $request)
    {
        $path = $request->input('d', '');
        $newPath = $request->input('n', '');

        $dirPath = $this->resolvePath($path);

        if ($dirPath === false || !File::isDirectory($dirPath)) {
            return $this->result(false, 'Invalid directory path');
        }

        $destinationPath = $this->resolvePath($newPath);

        if ($destinationPath === false || !File::isDirectory($destinationPath)) {
            return $this->result(false, 'Invalid destination path');
        }

        /*
         * Забороняємо переміщення папки всередину самої себе
         */
        $dirPathNormalized = rtrim(
            str_replace('\\', '/', $dirPath),
            '/'
        );

        $destinationNormalized = rtrim(
            str_replace('\\', '/', $destinationPath),
            '/'
        );

        if ( str_starts_with($destinationNormalized, $dirPathNormalized . '/') ) {
            return $this->result(false, 'Cannot move directory into its child');
        }

        $newDirectory = $destinationPath . DIRECTORY_SEPARATOR . basename($dirPath);

        /*
         * Якщо папка з таким ім'ям вже існує
         */
        if (File::exists($newDirectory)) {
            return $this->result(false, 'Directory already exists');
        }

        if (rename($dirPath, $newDirectory)) {
            return $this->result(true);
        }

        return $this->result(false, 'Cannot move directory ' . basename($dirPath));
    }




    public function copyFile(Request $request)
    {
        $path = $request->input('f', '');
        $newPath = $request->input('n', '');

        $filePath = $this->resolvePath($path);

        if ($filePath === false || !File::isFile($filePath)) {
            return $this->result(false, 'Invalid file path');
        }

        /*
         * Якщо папка призначення не передана,
         * копіюємо в корінь uploads/files
         */
        if (empty($newPath)) {
            $newPath = '';
        }

        $destinationDir = $this->resolvePath($newPath);

        if ($destinationDir === false || !File::isDirectory($destinationDir)) {
            return $this->result(false, 'Invalid destination path');
        }

        $filename = basename($filePath);

        // Робимо унікальне ім'я як Roxy MakeUniqueFilename()
        $newFilename = $this->makeUniqueFilename(
            $destinationDir,
            $filename
        );

        $destination = $destinationDir . DIRECTORY_SEPARATOR . $newFilename;

        if (copy($filePath, $destination)) {
            return $this->result(true);
        }

        return $this->result(false, 'Cannot copy file');
    }



    public function moveFile(Request $request)
    {
        $filePath = $this->getFile($request->input('f'));
        $newPath = $request->input('n', '');

        /*
         * Якщо новий шлях не переданий,
         * переміщуємо у корінь uploads/files
         */
        if (empty($newPath)) {
            $newPath = basename($filePath);
            //$newPath = 'uploads/files/' . basename($filePath);
        }

        // Перевірка розширення нового файлу
        if (!$this->canUploadFile(basename($newPath))) {
            return $this->result(false, 'File extension forbidden');
        }

        /*
         * Отримуємо абсолютний шлях призначення.
         * Тут файл може ще не існувати,
         * тому використовуємо окрему логіку.
         */
        $destination = $this->resolvePath( $newPath ?: basename($filePath), false );

        if ($destination === false) {
            return $this->result(false, 'Invalid destination path');
        }

        if (File::exists($destination)) {
            return $this->result(false, 'File already exists ' . basename($destination));
        }

        if (rename($filePath, $destination)) {
            return $this->result(true);
        }

        return $this->result(false, 'Cannot move file ' . basename($filePath));
    }





    public function renameFile(Request $request)
    {
        $filePath = $this->getFile($request->input('f'));

        $name = basename(trim($request->input('n', '')));

        if ($name === '') {
            return $this->result(false, 'File name is empty');
        }

        // Перевірка дозволених розширень
        if (!$this->canUploadFile($name)) {
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            return $this->result(false, 'File extension forbidden ".' . $ext . '"');
        }

        $newPath = dirname($filePath) . DIRECTORY_SEPARATOR . $name;

        // Якщо файл з таким ім'ям вже існує
        if (File::exists($newPath)) {
            return $this->result(false, 'File already exists');
        }

        if ( File::move($filePath, $newPath) ) {
            return $this->result(true);
        }

        return $this->result(false, 'Cannot rename file ' . basename($filePath));
    }





    public function renameDir(Request $request)
    {
        $path = $request->input('d', '');
        $name = basename(trim($request->input('n', '')));

        if ($name === '') {
            return $this->result(false, 'Folder name is empty');
        }

        $dirPath = $this->resolvePath($path);

        if ($dirPath === false || !File::isDirectory($dirPath)) {
            return $this->result(false, 'Invalid directory path');
        }

        $rootPath = realpath(public_path('uploads/files'));

        // Забороняємо перейменування кореневої папки
        if ($dirPath === $rootPath) {
            return $this->result(false, 'Cannot rename root directory');
        }

        $newPath = dirname($dirPath) . DIRECTORY_SEPARATOR . $name;

        // Якщо папка з таким ім'ям вже існує
        if (File::exists($newPath)) {
            return $this->result(false, 'Directory already exists');
        }

        if (rename($dirPath, $newPath)) {
            return $this->result(true);
        }

        return $this->result(false, 'Cannot rename directory ' . basename($dirPath));
    }





    public function deleteDir(Request $request)
    {
        $path = $request->input('d', '');

        $dirPath = $this->resolvePath($path);

        if ($dirPath === false || !File::isDirectory($dirPath)) {
            return $this->result(false, 'Invalid directory path');
        }

        $rootPath = realpath(public_path('uploads/files'));

        // Забороняємо видалення кореневої папки
        if ($dirPath === $rootPath) {
            return $this->result(false, 'Cannot delete root directory');
        }

        // Перевіряємо, чи папка порожня
        if (count(File::allFiles($dirPath)) > 0 || count(File::directories($dirPath)) > 0) {
            return $this->result(false, 'Directory is not empty');
        }

        if (File::deleteDirectory($dirPath)) {
            return $this->result(true);
        }

        return $this->result(false, 'Cannot delete directory ' . basename($dirPath));
    }



    public function deleteFile(Request $request)
    {
        $realPath = $this->getFile($request->input('f'));

        if (!File::delete($realPath)) {
            return $this->result(false, 'Unable to delete file');
        }

        return $this->result(true);
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
            return $this->result(false, 'Folder name is empty');
        }

        $currentPath = $this->resolvePath($dir);

        if ($currentPath === false) {
            return $this->result(false, 'Invalid path');
        }

        $newDir = $currentPath . DIRECTORY_SEPARATOR . $name;

        if (File::exists($newDir)) {
            return $this->result(false, 'Folder already exists');
        }

        if (!File::makeDirectory($newDir, 0755, true)) {
            return $this->result(false, 'Unable to create folder');
        }

        return $this->result(true);
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
            return $this->result(false, 'Invalid path');
        }

        if (!$request->hasFile('files')) {
            return $this->result(false, 'No files uploaded');
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

        if ($realCurrent === false || !str_starts_with($realCurrent, $basePath)) {
            abort(403);
        }

        $files = File::files($realCurrent);

        usort($files, function ($a, $b) {
            return strnatcasecmp($a->getFilename(), $b->getFilename());
        });

        $result = [];

        foreach ($files as $file) {

            $filename = $file->getFilename();
            $extension = strtolower($file->getExtension());
            $mime = File::mimeType($file->getRealPath());

            // Фільтр типів
            if ($type === 'image' && !str_starts_with($mime, 'image/')) {
                continue;
            }

            if ($type === 'flash' && $extension !== 'swf') {
                continue;
            }

            $width = 0;
            $height = 0;

            if (str_starts_with($mime, 'image/')) {
                if ($size = @getimagesize($file->getRealPath())) {
                    $width  = $size[0];
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
        return in_array(strtolower($extension), [ 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg' ]);
    }


    protected function makeUniqueFilename($directory, $filename)
    {
        if (!File::exists($directory . DIRECTORY_SEPARATOR . $filename)) {
            return $filename;
        }

        $name = pathinfo($filename, PATHINFO_FILENAME);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        $i = 1;
        do {
            $newName = $name . '_' . $i;
            if ($ext) {
                $newName .= '.' . $ext;
            }
            $fullPath = $directory . DIRECTORY_SEPARATOR . $newName;
            $i++;
        } while (File::exists($fullPath));

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
        $path = str_replace('\\', '/', $path);
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


    protected function result(bool $ok, string $message = '')
    {
        return response()->json([
            'res' => $ok ? 'ok' : 'error',
            'msg' => $message,
        ]);
    }



    protected function canUploadFile($filename)
    {
        $forbidden = ['zip', 'js', 'jsp', 'php', 'php3', 'php4', 'php5', 'phtml', 'exe', 'sh', 'py', 'cgi', 'dll', 'bat', 'cmd', 'com', 'vbs', 'htaccess',];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return !in_array($ext, $forbidden);
    }




    protected function makeUniqueDirectoryName(string $parentDir, string $dirName)//: string
    {
        if (!File::exists($parentDir . DIRECTORY_SEPARATOR . $dirName)) {
            return $dirName;
        }
        $i = 1;
        do {
            $newName = $dirName . '_' . $i;
            $i++;
        } while (File::exists($parentDir . DIRECTORY_SEPARATOR . $newName));

        return $newName;
    }






    protected function getFile(string $path)//: string
    {
        $file = $this->resolvePath($path);
        if ($file === false || !File::isFile($file)) {
            abort(404);
        }

        if (!@getimagesize($file) && request()->route()->getActionMethod() === 'thumb') {
            abort(404);
        }

        return $file;
    }



    protected function getImage(string $path)//: string
    {
        $file = $this->getFile($path);
        if (!str_starts_with(File::mimeType($file), 'image/')) {
            abort(404);
        }
        return $file;
    }



    protected function getDirectory(string $path)//: string
    {
        $directory = $this->resolvePath($path);
        if ($directory === false || !File::isDirectory($directory)) {
            abort(404);
        }
        return $directory;
    }




    protected function zipDirectory(string $source, string $zipFile): bool
    {
        $zip = new ZipArchive();

        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return false;
        }

        $source = realpath($source);

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $source,
                RecursiveDirectoryIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($files as $file) {
            $file = $file->getRealPath();
            $relativePath = substr($file, strlen($source) + 1);
            if (is_dir($file)) {
                $zip->addEmptyDir($relativePath);
            } else {
                $zip->addFile($file, $relativePath);
            }
        }
        $zip->close();

        return true;
    }




}
