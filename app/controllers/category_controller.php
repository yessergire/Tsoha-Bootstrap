<?php

class CategoryController extends BaseController {

    public static function index() {
        $categories = Category::all();
        View::make('category/index.html', array('categories' => $categories));
    }

    public static function show($id) {
        $category = Category::find($id);
        View::make('category/show.html', array('category' => $category));
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

    public static function edit($id) {
        $category = Category::find($id);
        View::make('category/edit.html', array('category' => $category));
    }

    public static function destroy($id) {
        $tuote = new Category(array('id' => $id));
        $tuote->destroy();
        Redirect::to('/categories', array('message' => 'Tuoteluokka on poistettu tietokannasta!'));
    }

}
