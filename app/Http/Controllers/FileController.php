<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;

class FileController extends Controller {

	// root path.
	protected $disk;

	// current dir's information.
	protected $dir;

	// current folder.
	protected $folder;

	// current short path.
	protected $shortPath;

	/**
	 * FileController constructor.
	 *
	 * return void
	 */
	public function __construct ()
	{
//		parent::__construct();
		// set root path.
		$this->disk=config('filesystems.disks.local.root').'/';
	}

	/**
	 * initial path info,
	 * show file list.
	 *
	 * @param Request $request
	 * @return $this
	 */
	public function index(Request $request)
	{
		// set current folder.
		$input = $request->all();
		$this->set_current_folder($input);

		// set short path.
		$this->get_short_path($this->folder);

		// fill dir's information.
		$this->dir = $this->fill_dir_info($this->folder);

		return view('file')->with('dir', $this->dir);
	}

	/**
	 * set current folder's path
	 *
	 * @param $input
	 * @return string
	 */
	private function set_current_folder($input)
	{
		if (!array_key_exists('folder', $input))
		{
			$input['folder'] = '';
		}
		return $this->folder = $input['folder'];
	}

	/**
	 * fill dir's information.
	 *
	 * @param mixed $folder
	 * @return array
	 */
	private function fill_dir_info ($folder)
	{
		$path = $this->disk . $this->current_path($folder);
		$folders = $this->folders_info($folder);
		$files = $this->files_info($folder);

		// return a all information together in an array.
		return compact('path', 'folders', 'files');
	}

	/**
	 * get files' information.
	 *
	 * @param string $folder folder's name
	 * @return array
	 */
	private function files_info ($folder)
	{
		// get all files.
		$files = Storage::files($folder);

		// count files.
		$num = count($files);
		$arr = [];

		// set files information.
		for ($i = 0; $i < $num; $i++)
		{
			// get file's name and trim the short path.
			$arr[$i]['name'] = str_replace($this->shortPath, '', $files[$i]);
			$arr[$i]['pathName'] = $files[$i];
			$arr[$i]['size'] = Storage::size($files[$i]);
			$path = $this->current_path($folder) . '/' . $files[$i];
			$arr[$i]['type'] = \File::extension($path);
		}

		return $arr;
	}

	/**
	 * get folders' information.
	 *
	 * @param $folder
	 * @return array
	 */
	private function folders_info ($folder)
	{
		// get all folders.
		$folders = Storage::directories($folder);

		// count folders.
		$num = count($folders);
		$arr = [];

		// set folders information.
		for ($i = 0; $i < $num; $i++)
		{
			// get the folder's name and trim the short path.
			$arr[$i]['name'] = str_replace($this->shortPath, '', $folders[$i]);
			$arr[$i]['pathName'] = $folders[$i];
			$arr[$i]['size'] = $this->folder_size($folders[$i]);
		}

		return $arr;
	}

	/**
	 * get current short path.
	 *
	 * @param $folder
	 * @return string
	 */
	private function current_path ($folder)
	{
		$path = '';

		if ($folder == '')
		{
			return $path;
		} else {
			$path .= $folder . '/';
			return $path;
		}
	}

	/**
	 * get short path.
	 *
	 * @param  string $folder
	 * @return string
	 */
	private function get_short_path ($folder)
	{
		return $this->shortPath = $this->current_path($folder);
	}

	/**
	 * get folder's size
	 *
	 * @param string $folder
	 * @return int
	 */
	private function folder_size ($folder)
	{
		// get all files.
		// Recursive.
		$files = Storage::allFiles($folder);

		// initial size.
		$size = 0;
		
		// get folder size.
		foreach ($files as $file)
		{
			$size += Storage::size($file);
		}

		return $size;
	}

	/**
	 * upload file.
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function upload (Request $request)
	{
		// get upload file's information.
		$file = $request->file('file');

		// get file's original name.
		$fileName = $file->getClientOriginalName();

		// move file.
		$file->move($this->disk, $fileName);

		return redirect()->back();
	}

	/**
	 * make new folder
	 *
	 * @param Requests\MakeFolderRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function make_folder (Requests\MakeFolderRequest $request)
	{
		// get folder's name
		$input = $request->all();
		$folderName = $input['folderName'];

		// get short path
		$directory = $this->shortPath . $folderName;

		// if the folder not exists, then make the folder.
		if (!Storage::exists($directory))
		{
			echo $directory;
			Storage::makeDirectory($directory);
		}

		return redirect()->back();
	}

	/**
	 * delete folder
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete_folder (Request $request)
	{
		// get folder's name.
		$input = $request->all();
		$folderName = $input['folderName'];

		// get short path.
		$directory = $folderName;

		// if the file exists, then delete the folder.
		if (Storage::exists($directory))
		{
			$directory = $this->disk . $directory;
			\File::deleteDirectory($directory);
		}

		return redirect()->back ();
	}

	/**
	 * delete file / folder
	 *
	 * @param Request $request get upload file info
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete_file (Request $request)
	{
		// get file's name.
		$input = $request->all();
		$fileName = $input['fileName'];

		// get file's short path.
		$file = $fileName;

		// if the file exists, then delete the file.
		if (Storage::exists($file))
		{
			Storage::delete($file);
		}
		return redirect()->back();
	}

}
