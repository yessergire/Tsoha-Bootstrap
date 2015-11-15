<?php

class ItemController extends BaseController {
    public static function index() {
        $items = Item::all();
        View::make('item/index.html', array('items' => $items));
    }
    
    public static function show($id) {
        $item = Item::find($id);
        View::make('item/show.html', array('item' => $item));
    }
    
    public static function create() {
        View::make('item/new.html');
    }
    
    public static function store() {
        $params = $_POST;
        $item = new Item(array(
                'name' => $params['nimi'],
                'description' => $params['kuvaus'],
                'pictureURL' => $params['kuva'],
                'price' => $params['hinta']
                ));
        $item->save();
        Redirect::to('/items/'. $item->id, array('message' =>'Uusi tuote on lisätty tietokantaan!'));
    }
}
