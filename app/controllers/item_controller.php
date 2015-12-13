<?php

class ItemController extends BaseController {

    public static function index() {
        View::make('item/index.html', array('items' => Item::all()));
    }

    public static function show($id) {
        View::make('item/show.html', array('item' => Item::find($id)));
    }

    public static function create() {
        View::make('item/new.html', array('categories' => Category::all()));
    }

    public static function store() {
        $params = $_POST;
        $attributes = array(
            'name' => $params['nimi'],
            'description' => $params['kuvaus'],
            'pictureURL' => $params['kuva'],
            'price' => $params['hinta']
        );
        $item = new Item($attributes);
        $errors = $item->errors();

        if (count($errors) == 0) {
            $item->save();
            if (isset($params['categories'])) {
                $item->set_categories($item->id, $params['categories']);
            }
            Redirect::to('/items/' . $item->id, array('message' => 'Uusi tuote on lisätty tietokantaan!'));
        } else {
            View::make('item/new.html', array('errors' => $errors, 'attributes' => $attributes, 'categories' => Category::all()));
        }
    }

    public static function update($id) {
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'name' => $params['nimi'],
            'description' => $params['kuvaus'],
            'pictureURL' => $params['kuva'],
            'price' => $params['hinta']
        );
        $item = new Item($attributes);
        $errors = $item->errors();

        if (count($errors) == 0) {
            $item->update();
            if (isset($params['categories'])) {
                $item->set_categories($item->id, $params['categories']);
            } else {
                $item->destroy_category_references($item->id);
            }
            Redirect::to('/items/' . $item->id, array('message' => 'Tuotetta on muokattu onnistuneesti!'));
        } else {
            View::make('item/edit.html', array('errors' => $errors, 'item' => $item, 'categories' => Category::all()));
        }
    }

    public static function edit($id) {
        $item = Item::find($id);
        View::make('item/edit.html', array('item' => $item, 'categories' => Category::all()));
    }

    public static function destroy($id) {
        $item = new Item(array('id' => $id));
        $item->destroy();
        Redirect::to('/items', array('message' => 'Tuote on poistettu tietokannasta!'));
    }

}
