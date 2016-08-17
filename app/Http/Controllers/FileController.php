<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;

/**
 * file class
 *
 * Class FileController
 * @package App\Http\Controllers
 */
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
		$this->middleware('auth');
		$this->disk=config('filesystems.disks.local.root').'/';
	}

	/**
	 * initial path info,
	 * show file list.
	 *
	 * @param Request $request
	 * @return $this
	 */
	public function index (Request $request)
	{
		// set current folder.
		$input = $request->all();
		$this->setCurrentFolder($input);

		// set short path.
		$this->getShortPath($this->folder);

		// fill dir's information.
		$this->dir = $this->fillDirInfo($this->folder);
		
		return view('file')->with('dir', $this->dir);
	}

	/**
	 * set current folder's path
	 *
	 * @param $input
	 * @return string
	 */
	private function setCurrentFolder ($input)
	{
		if (!array_key_exists('folder', $input))
		{
			$input['folder'] = '';
		}

		return $this->folder = $input['folder'];
	}

	/**
	 * get current short path.
	 *
	 * @param $folder
	 * @return string
	 */
	private function currentPath ($folder)
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
	 * fill dir's information.
	 *
	 * @param mixed $folder
	 * @return array
	 */
	private function fillDirInfo ($folder)
	{
		$folders = $this->foldersInfo($folder);
		$files = $this->filesInfo($folder);
		$shortPath = $this->shortPath;
		$shortPathArr = splitShortPath($shortPath);

		// return a all information together in an array.
		return compact('shortPath', 'folders', 'files', 'shortPathArr');
	}

	/**
	 * get files' information.
	 *
	 * @param string $folder folder's name
	 * @return array
	 */
	private function filesInfo ($folder)
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
			$arr[$i]['lastModified'] = Storage::lastModified($files[$i]);
			$path = $this->currentPath($folder) . '/' . $files[$i];
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
	private function foldersInfo ($folder)
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
			$arr[$i]['lastModified'] = Storage::lastModified($folders[$i]);
			$arr[$i]['size'] = $this->folderSize($folders[$i]);
		}

		return $arr;
	}

	/**
	 * get short path.
	 *
	 * @param  string $folder
	 * @return string
	 */
	private function getShortPath ($folder)
	{
		return $this->shortPath = $this->currentPath($folder);
	}

	/**
	 * get folder's size
	 *
	 * @param string $folder
	 * @return int
	 */
	private function folderSize ($folder)
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
	 * @param Requests\UploadFileRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function upload (Requests\UploadFileRequest $request)
	{
		// get upload file's information.
		$file = $request->file('file');

		// if doesn't choose file return back.
		if ($file == '')
		{
			return redirect()->back();
		}

		$input = $request->all();
		$shortPath = $input['shortPath'];

		// get file's original name.
		$fileName = $file->getClientOriginalName();

		// if have the same name file, return back.
		if (Storage::exists($shortPath.$fileName))
		{
			return redirect()->back();
		}

		// move file.
		$file->move($this->disk . $shortPath, $fileName);

		return redirect()->back();
	}

	/**
	 * make new folder
	 *
	 * @param Requests\MakeFolderRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function makeFolder (Requests\MakeFolderRequest $request)
	{
		// get folder's name
		$input = $request->all();

		// get short path
		$directory = $input['shortPath'] . $input['folderName'];

		// if the folder not exists, then make the folder.
		if (!Storage::exists($directory))
		{
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
	public function deleteFolder (Request $request)
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
	public function deleteFile (Request $request)
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

	/**
	 * download
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
	 */
	public function download (Request $request)
	{
		$input = $request->all();
		$pathName = $input['pathName'];

		if (Storage::exists($pathName))
		{
			$path = $this->disk . $pathName;
			return response()->download($path);
		}

		return redirect()->back();
	}

}
