<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;

class FileController extends Controller {

	protected $disk;
	protected $dir;
	protected $shortPath;

	/**
	 * FileController constructor.
	 * initial disk path
	 *
	 */
	public function __construct ($folder = null)
	{
		$this->disk=config('filesystems.disks.local.root').'/';
		$this->get_short_path($folder);
		$this->dir = $this->fill_dir_info($folder);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('file')->with('dir', $this->dir);
	}

	private function fill_dir_info ($folder = null)
	{
		$path = $this->disk . $this->current_path($folder);
		$folders = $this->folders_info($folder);
		$files = $this->files_info($folder);
		return compact('path', 'folders', 'files');
	}

	private function current_path ($folder)
	{
		$path = '';
		if ($folder == null)
		{
			return $path;
		} else {
			$path .= $folder . '/';
			return $path;
		}
	}

	private function get_short_path ($folder)
	{
		return $this->shortPath = $this->current_path($folder);
	}

	private function files_info ($folder)
	{
		$files = Storage::files($folder);
		$num = count($files);
		$arr = [];
		for ($i = 0; $i < $num; $i++)
		{
			$arr[$i]['name'] = $files[$i];
			$arr[$i]['size'] = Storage::size($arr[$i]['name']);
			$path = $this->current_path($folder) . '/' . $files[$i];
			$arr[$i]['type'] = \File::extension($path);
		}
		return $arr;

	}

	private function folders_info ($folder)
	{
		// 获取目录下的文件夹
		$folders = Storage::directories($folder);
		// 获取文件夹数目
		$num = count($folders);
		$arr = [];
		// 存取目录信息
		for ($i = 0; $i < $num; $i++)
		{
			$arr[$i]['name'] = $folders[$i];
			$arr[$i]['size'] = $this->folder_size($arr[$i]['name']);
		}
		return $arr;
	}

	private function folder_size ($folder)
	{
		// 获取目录下的所有文件
		$files = Storage::allFiles($folder);
		// 初始化size大小
		$size = 0;
		// 计算目录总大小
		foreach ($files as $file)
		{
			$size += Storage::size($file);
		}
		return $size;
	}
	
	public function upload (Request $request)
	{
		// 获取上传文件信息
		$file = $request->file('file');
		// 获取原始文件名
		$fileName = $file->getClientOriginalName();
		// 移动文件到当前目录
		$file->move($this->disk, $fileName);
		
		return redirect()->back();
	}

	/**
	 * make new folder
	 * @param Requests\MakeFolderRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function make_folder (Requests\MakeFolderRequest $request)
	{
		// 获取文件夹名称
		$input = $request->all();
		$folderName = $input['folderName'];

		// 得到短目录
		$directory = $this->shortPath . $folderName;

		// 判断同名文件夹是否存在
		if (!Storage::exists($directory))
		{
			Storage::makeDirectory($directory);
		}

		return redirect()->back();
	}

	/**
	 * delete file
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete_folder (Request $request)
	{
		// 获取文件夹名称
		$input = $request->all();
		$folderName = $input['folderName'];

		// 得到短目录
		$directory = $this->shortPath . $folderName;

		// 判断同名文件夹是否存在
		if (Storage::exists($directory))
		{
			$directory = $this->disk . $directory;
			\File::deleteDirectory($directory);
		}

		return redirect()->back ();
	}

	public function delete_file (Request $request)
	{
		// 获取文件名
		$input = $request->all();
		$fileName = $input['fileName'];

		// 文件短目录
		$file = $this->shortPath . $fileName;

		if (Storage::exists($file))
		{
			Storage::delete($file);
		}

		return redirect()->back();
	}

}
