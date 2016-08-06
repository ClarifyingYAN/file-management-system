<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class FileController extends Controller {

	protected $disk = '';
	protected $files = [];
	protected $name = [];
	protected $size = [];
	protected $ext = [];
	protected $dirkTotalSize = 0;
	protected $dirkUsedSize = 0;
	protected $dirkFreeSize = 0;
	protected $fileNum = 0;

	/**
	 * FileController constructor.
	 * initial disk path
	 */
	public function __construct()
	{
		$this->disk=config('filesystems.disks.local.root').'/';
	}

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
		$fileNames = array_diff(scandir($this->disk), ['.', '..']);
		$i = 0;
		foreach ($fileNames as $fullName)
		{
			$pathinfo = pathinfo($fullName);
			$this->files[$i]['name'] = $pathinfo['filename'];
			if (!is_dir($this->disk.$fullName))
			{
				$this->files[$i]['ext'] = '.' . $pathinfo['extension'];
			} else {
				$this->files[$i]['ext'] = '';
			}
			$i++;
		}
		$this->file_size();

	}

	/**
	 * count file
	 * @return int
	 */
	private function count_files()
	{
		return $this->fileNum = count($this->files);
	}

	private function file_size()
	{
		for ($i = 0; $i < $this->count_files(); $i++)
		{
			$size = filesize($this->disk.$this->files[$i]['name'].$this->files[$i]['ext']);
			$this->files[$i]['size'] = human_size($size);
		}
	}
	
	public function upload(Request $request)
	{
		$file = $request->file('file');
		$fileName = $file->getClientOriginalName();
		$file->move($this->disk, $fileName);
		return redirect()->back();
	}

	/**
	 * make new folder
	 * @param Requests\MakeFolderRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function make_dir(Requests\MakeFolderRequest $request)
	{
		$input = $request->all();
		if (!is_dir($this->disk.$input['folderName']))
		{
			mkdir($this->disk.$input['folderName'], $mode = 0777);
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
		$file = $this->disk.$input['fileName'];
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
	

	private function dir_size()
	{

	}

}
