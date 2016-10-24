<?php
namespace App;

use Elasticsearch;
use Illuminate\Support\Facades\Session;
use \Jenssegers\Model\Model as SimpleModel;

class Items extends SimpleModel
{
    private $client;

    private $mainenu;
    private $aggregation;
    private $pathObject;
    private $content;

    private $controller;

    public function __construct(){

        //create a connection "Object"
        $this->client = Elasticsearch\ClientBuilder::create()->build();
    }
    //******************************************************************
    // PUBLIC functions ************************************************
    //******************************************************************
    //
    //GET
    //
    public function getMenu(){
        return $this->mainenu;
    }
    public function getPobject(){
        return $this->pathObject;
    }
    public function getAggregation(){
        return Session::get('aggregation' );
    }
    public function getContent(){
        return $this->content;
    }
    //
    // CHECK database
    //
    public function checkDB($index){
        //mapping...
        $this->mapping($index);
        return $this->getMenu();
    }
    //
    // CHECK aggr.
    //
    public function checkA($index){
        //session var
        return Session::get('aggregation');
        //
    }
    //
    //mapping
    //
    public function mapping($index){

        //parameters for mapping
        $params = [];
        $params['index'] = $index;
        $params['client'] = [ 'ignore' => [400, 404] ];
        //mapping
        $map = $this->client->indices()->getMapping($params);
        if ((array_key_exists ('status', $map) && $map['status'] == '404') || empty($map[$index]['mappings'])){
            //If we do not have DB or do not have data
            $this->mainenu = null;
        }else{
            //or we have..
            $defaultMenu =['brands'=>[], 'filters'=>[]];
            foreach ($map[$index]['mappings'] as $brand=>$deatils){
                $defaultMenu['brands'][]=$brand;
                foreach ($deatils['properties'] as $filter=>$fFeatils) {
                    if (array_search($filter, $defaultMenu['filters']) === false){
                        $defaultMenu['filters'][] = $filter;
                    }
                }
            }
            $this->mainenu = $defaultMenu;
        }
        //put it into the session
        Session::set('mainmenu', $this->mainenu);
    }
    //
    //create a new DB
    //
    public function createDB($index){

        //data for the content
        $brands= ['nike','adidas','puma'];
        $genders= ['man','woman'];
        $categories =['shoes','shirts','hats','pants','backpack'];
        $colors = ['black','white','blue', 'grey','red','green','pink','orange'];
        //params
        $allParams=[];
        $id=0;
        //1./a -> shuffle $brands
        shuffle($brands);
        //1./b - random num -> 2 or 3
        $rnd1 = rand(2, 3);
        //1./ -> brands cycle
        for($a=0;$a<$rnd1;$a++){
            $br = $brands[$a];
            //2. generate genders
            for($b=0;$b<count($genders);$b++){
                $ge = $genders[$b];
                //3./a -> shuffle $categories / generate categories
                shuffle($categories);
                //3./b - random num -> 3 - 5 / generate categories
                $rnd3 = rand(3, 5);
                for($c=0;$c<$rnd3;$c++) {
                    $cat = $categories[$c];
                    //4./a -> shuffle colors / generate colors
                    shuffle($colors);
                    //4./b - random num -> 4 - 8 / generate colors
                    $rnd4 = rand(4, 8);
                    for($d=0;$d<$rnd4;$d++) {
                        $co = $colors[$d];
                        //5./a -> random num how many elements... 4 - 8 / generate prices
                        $pricesNum = rand(4, 8);
                        //5./b make array
                        $prices = [];
                        for($e=0;$e<$pricesNum;$e++) {
                            $prices[] = rand(1, 20)*10;
                        }
                        for($f=0;$f<count($prices);$f++){
                            $pr = $prices[$f];
                            //id...
                            $id++;
                            //the item...
                            $allParams['body'][] = [
                                'index' => [
                                    '_index' => $index,
                                    '_type' => $br,
                                    '_id' => $id
                                ]
                            ];
                            $allParams['body'][] = [
                                'gender' => $ge,
                                'category' => $cat,
                                'color' => $co,
                                'price' => $pr
                            ];
                        }
                    }
                }
            }
        }
        //load all data into the DB
        $result = $this->client->bulk($allParams);
        //we must create the map
        $this->mapping($index);
        //we must create the main aggregation object...
        $this->aggregate($index);

        return true;
    }
    //
    // DELETE database
    //
    public function destroyDB($index){
        //delete our DB
        $this->client->indices()->delete(['index'=>$index,'client' => ['ignore' => 404]]);
        Session::forget('mainmenu');
        Session::forget('aggregation');
        Session::forget('pathObject');
        return true;
    }
    //
    //
    //
    public function checkPath($uri){

        $mainmenu = Session::get('mainmenu');
        $aggregation = Session::get('aggregation');
        $brand=null;
        //...
        $pathObject = [];
        $pathObject['error'] = false;
        //search in the aggr.
        $uriItems= explode("/",$uri);
        //search the element in the url (from 2 because the first is empty, second is "search")
        for($i=1; $i<count($uriItems); $i++){
            $item = $uriItems[$i];
            //if we put a wrong url...
            $notMach = true;
            //brand from the menu...
            foreach ($mainmenu['brands'] as $index=>$brand){
                if($item == $brand){
                    $pathObject['brand'] =$item;
                    $notMach = false;
                }
            }
            //$filter -> gender, category, color, price
            foreach ($aggregation as $filter=>$details){
                foreach ($details as $index=>$filterName){
                    if($item == $filterName){
                        $pathObject['filters'][$filter] = $item;
                        $notMach = false;
                    }
                }
            }
            //if it was a non-existing url
            if($notMach){
                $pathObject['error'] = true;
                $this->pathObject = $pathObject;
                return $pathObject;
            }
        }
        $this->pathObject = $pathObject;
        Session::set('pathObject', $pathObject);
    }
    //
    //aggregations
    //
    public function aggregate($index){

        //parameters for aggr
        $params = [];
        $params['index'] = $index;
        $params['body']['aggs'] = $this->createParams($index);
        //and the query
        $params['body']["query"]['match_all']=new \stdClass();
        //search
        $results = $this->client->search($params);
        //aggr.
        $aggregation = [];
        foreach($results['aggregations'] as $filter=>$details){
            $aggregation[$filter] = [];
            for($i=0;$i<count($details['buckets']);$i++) {
                array_push($aggregation[$filter],$details['buckets'][$i]['key']);
            }
        }
        //store
        $this->aggregation = $aggregation;
        Session::set('aggregation', $aggregation );

        return true;
    }
    //
    // SEARCH in the database
    //
    public function searchItems($index, $request){

        //check aggregation data
        if($this->getAggregation() === null){
            $this->aggregate($index);
        }
        //we have to "search" which variable is the brand, which is the gendes etc.
        $options = $this->pathObject;
        //if the url was wrong...
        if($options['error']){
            $this->content = null;
            return false;
        }
        //parameters
        $params = [];
        $params['from'] = $request->from;
        $params['size'] = 15;
        $params['index'] = $index;
        if(array_key_exists ('brand', $options)) $params['type'] = $options['brand'];
        //if no filters at all
        if(!array_key_exists ('filters', $options)) $params['body']["query"]['match_all']=new \stdClass();
        //if we have filters...
        else{
            $params['body']["query"]['bool']['must']=[];
            foreach ($options['filters'] as $filter=>$detail){
                array_push($params['body']["query"]['bool']['must'],['match'=>[$filter=>$detail]]);
            }
        }
        $params['body']["sort"]['_uid']=["order" => "asc"];
        //now we can make the aggregates...
        //parameters for aggr
        $params['body']['aggs'] = $this->createParams($index);
        //search
        $result = $this->client->search($params);
        //content
        $result['hits']['size']=$params['size'];
        $result['hits']['from']=$params['from'];
        $this->content = $result['hits'];

        return true;
    }
    //******************************************************************
    // PRIVATE functions ***********************************************
    //******************************************************************
    //
    //parameters for aggregation
    //
    private function createParams(){

        //filters from the map...
        $filters =[];
        $mainmenu = $this->getMenu();//Session::get('mainmenu');
        //we can make the aggregates...
        $params = [];
        for($i=0;$i<count($mainmenu['filters']);$i++){
            $filter = $mainmenu['filters'][$i];
            if (!array_key_exists ($filter, $params)) $params[$filter] = ['terms' => ['field' => $filter]];
        }

        return $params;
    }

    public function createData(){
        $mainmenu = $this->getMenu();
        $aggregate = $this->getAggregation();
        $pathObject = $this->getPobject();
        $content = $this->getContent();
        //
        $data = [];
        $data['brand_all'] = (array_key_exists ('brands', $mainmenu)) ?  $mainmenu['brands'] : null;
        foreach ($aggregate as $filter=>$detail){
            $varName = $filter.'_all';
            $data[$filter] = null;
            $data[$varName] = [];
            foreach ($detail as $index=>$filtername){
                array_push($data[$varName],$filtername);
            }
        }
        $data['brand'] = (array_key_exists ('brand', $pathObject)) ? $pathObject['brand'] : null;
        if(array_key_exists ('filters', $pathObject)){
            foreach ($pathObject['filters'] as $filter=>$detail){
                $data[$filter] = $detail;
            }
        }

        if($content !== null){
            $data['content'] = $content;
            $data['total'] = $content['total'];
            $data['from'] = $content['from'];
            $data['size'] = $content['size'];
            $data['itemNum'] = ceil($data['total']/$data['size']);
        }

        return $data;
    }
}