<?php
namespace App\Http\Controllers;

use App\Items;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    //DB name
    const INDEX = 'testdbelastic';

    public $aggregation;

    public function __construct(){

        //create Items Model
        $this->items = new Items($this);
    }
    //******************************************************************
    // PUBLIC functions ************************************************
    //******************************************************************
    //
    // MAIN function (routing...)
    //
    public function navigate(Request $request){

        //check the database -> if we do not have -> we can create
        $mainmenu = $this->items->checkDB(self::INDEX);
        if($mainmenu === null)return redirect()->route('create');
        //check the aggregation
        $aggregation = $this->items->checkA(self::INDEX);
        //if it is empty or null -> make
        if($aggregation === null || empty(array_filter($aggregation))) $this->items->aggregate(self::INDEX);
        //check the path
        $this->items->checkPath($request->path());
        //search
        $this->items->searchItems(self::INDEX,$request);
        //show
        return View::make('pages.index')->with(['data'=>$this->items->createData()]);
    }
//
    // MAIN function for post data(routing...)
    //
    public function navigatePost(Request $request){

        //pagination starting point
        $options['path'] = $request['path'];
        $options['from'] = $request['from'];
        $options['priceMin'] = $request['priceMin'];
        $options['priceMax'] = $request['priceMax'];
        //show
        return view('pages.index', $options);
    }
    //
    // DELETE database
    //
    public function destroyDB(){

        if($this->items->destroyDB(self::INDEX)) return View::make('pages.create')->with(['id'=>'info']);
    }
    //
    // MAKE a new database
    //
    public function checkDB(){

        //if we write "/create" directly, maybe we have DB, so we have to check first
        $this->items->checkDB(self::INDEX);
        if($this->items->getMenu() === null) return View::make('pages.create')->with(['id'=>'danger']);
        else return redirect()->route('search');
    }
    public function createDB(){

        //if we write "/create/doit" directly, maybe we have DB, so we have to check first
        $this->items->checkDB(self::INDEX);
        if($this->items->getMenu() === null){
            if($this->items->createDB(self::INDEX)) return View::make('pages.create')->with(['id'=>'success']);
        }
        else return redirect()->route('search');
    }
}