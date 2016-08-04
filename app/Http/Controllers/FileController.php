<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class FileController extends Controller {

	protected $dir = 'E:/wamp64/www/file-management-system/management/';
	protected $files = [];
	protected $name = [];
	protected $size = [];
	protected $ext = [];
	protected $dirkTotalSize = 0;
	protected $dirkUsedSize = 0;
	protected $dirkFreeSize = 0;
	protected $fileNum = 0;

//	public function __construct($dir)
//	{
//		$this->dir = Config::get();
//	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->get_filename();
		return view('file')->with('files', $this->files);
	}

	private function get_filename()
	{
		$fileNames = array_diff(scandir($this->dir), ['.', '..']);
		$i = 0;
		foreach ($fileNames as $fullName)
		{
//			echo $fullName . '<br>';
			$pathinfo = pathinfo($fullName);
			$this->files[$i]['name'] = $pathinfo['filename'];
			if (!is_dir($this->dir.$fullName))
			{
				$this->files[$i]['ext'] = '.' . $pathinfo['extension'];
			} else {
				$this->files[$i]['ext'] = '';
			}
			$i++;
		}
		$this->file_size();

	}

	private function count_files()
	{
		return $this->fileNum = count($this->files);
	}

	private function file_size()
	{
		for ($i = 0; $i < $this->count_files(); $i++)
		{
			$this->files[$i]['size'] = $this->size(filesize($this->dir.$this->files[$i]['name'].$this->files[$i]['ext']));
		}
	}

	/**
	 * make new folder
	 * @param Requests\MakeFolderRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function make_dir(Requests\MakeFolderRequest $request)
	{
		$input = $request->all();
		if (!is_dir($input['folderName']))
		{
			mkdir($this->dir.$input['folderName'], $mode = 0777);
		}
		return redirect()->back();
	}

	/**
	 * delete file
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete_file(Request $request)
	{
		$input = $request->all();
		$file = $this->dir.$input['fileName'];
		if (is_dir($file))
		{
			rmdir($file);
		} else {
			if (file_exists($file))
			{
				unlink($file);
			}
		}

		return redirect()->back();
	}

	/**
	 * show size better
	 *
	 * @param $bytes
	 * @param int $decimals
	 * @return string
	 */
	private function size($bytes, $decimals = 2)
	{
		$size = $bytes;
		$unit = ['Byte', 'KB', 'MB', 'GB'];
		$i = 0;
		while($size > 1024)
		{
			$size = $size / 1024;
			$i++;
		}
		$size = round($size, 2);
		return $size . $unit[$i];
	}

	private function dir_size()
	{

	}

}
