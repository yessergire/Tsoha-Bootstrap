<?php

class CategoryController extends BaseController {

    public static function index() {
        View::make('category/index.html', array('categories' => Category::all()));
    }

    public static function show($id) {
        View::make('category/show.html', array('category' => Category::find($id)));
    }

    public static function create() {
        View::make('category/new.html');
    }

    public static function store() {
        $params = $_POST;
        $attributes = array(
            'name' => $params['nimi'],
            'description' => $params['kuvaus']
        );
        $category = new Category($attributes);
        $errors = $category->errors();

        if (count($errors) == 0) {
            $category->save();
            Redirect::to('/categories/' . $category->id, array('message' => 'Uusi tuoteluokka on lisätty tietokantaan!'));
        } else {
            View::make('category/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function edit($id) {
        View::make('category/edit.html', array('category' => Category::find($id)));
    }
    
    public static function update($id) {
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'name' => $params['nimi'],
            'description' => $params['kuvaus']
        );
        $category = new Category($attributes);
        $errors = $category->errors();

        if (count($errors) == 0) {
            $category->update();
            Redirect::to('/categories/' . $category->id, array('message' => 'Tuoteluokkaa on muokattu onnistuneesti!'));
        } else {
            View::make('category/edit.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function destroy($id) {
        $category = new Category(array('id' => $id));
        $category->destroy();
        Redirect::to('/categories', array('message' => 'Tuoteluokka on poistettu tietokannasta!'));
    }

}
