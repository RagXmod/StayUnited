<?php

namespace Modules\Core\Traits;

/**
 * Module Api: Modules\Core\Traits\UploadTrait
 *
 *
 *
 * @package    DCM
 * @author     Anthony Pillos <dev.anthonypillos@gmail.com>
 * @copyright  2018 (c) DCM
 * @version    Release: v1.0.0
 * @link       http://devcorpmanila.com
 */
use Exception;
use Illuminate\Support\Facades\Storage;

trait UploadTrait
{
    private $disk = 'user-uploads';


    public function setDisk($disk) {
        $this->disk = $disk;
        return;
    }

    /**
     * Undocumented function
     *
     * @param [type] $file
     * @return void
     */
    public function uploadFileInfo($file) {

        if(!$file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile)
			throw new Exception("Your file submitted is not an instance of Symfony\Component\HttpFoundation\File\UploadedFile Class", 400);

		return [
			'path'      => @$file->getRealPath(),
			'name'      => @$file->getClientOriginalName(),
			'extension' => @$file->getClientOriginalExtension() ?? 'png',
			'size'      => @$file->getSize() ?? 0,
			'mime'      => @$file->getMimeType()
		];
    }

    /**
     * Undocumented function
     *
     * @param [type] $srcName
     * @param [type] $dstName
     * @param integer $chunkSize
     * @param boolean $returnbytes
     * @return void
     */
    public function downloadAndSaveToFile($srcName, $dstName, $chunkSize = 5, $returnbytes = true) {

            if(!empty($dstName))
                $dstName = implode(DIRECTORY_SEPARATOR, $dstName);

            // create directory
            Storage::disk($this->disk)->makeDirectory(dirname($dstName));
            ;
			$chunksize = $chunkSize*(1024*1024); // How many bytes per chunk
			$data = '';
			$bytesCount = 0;
            $handle = fopen($srcName, 'rb');

            $dstNameFullPath = $this->getAbsolutePathBaseOnDisk($dstName);

			$fp = fopen($dstNameFullPath, 'w');
			if ($handle === false) {
				return false;
			}
			while (!feof($handle)) {
				$data = fread($handle, $chunksize);
				fwrite($fp, $data, strlen($data));
				if ($returnbytes) {
				    $bytesCount += strlen($data);
				}
			}
			$status = fclose($handle);
			fclose($fp);
			if ($returnbytes && $status) {
				return $bytesCount; // Return number of bytes delivered like readfile() does.
			}
			return $status;
    }

    public function getAbsolutePathBaseOnDisk( $dstName ) {
        return Storage::disk($this->disk)
            ->getDriver()
            ->getAdapter()
            ->applyPathPrefix($dstName);
    }

    public function processFileInfo( $filename ) {

        $path     = pathinfo($filename);
        $baseName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $path['basename']);
        $ext      = @$path['extension'];
        $basename = str_slug($baseName,'_').'.'.$ext ?? str_random(5);
        $fileName = md5($basename.time()).uniqid().'.'.$ext;
		return [
			'filename'  => $fileName,
			'basename'  => $basename,
			'extension' => $ext
		];
    }

}
