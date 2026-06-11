<?php

class GalleryController extends Controller {
    public function index() {
        $this->View->render('gallery/index', array(
            'pictures' => GalleryModel::getPictures()
        ));
    }

    public function show($filename) {
        GalleryModel::showPictures($filename);
    }
    public function upload() {
        GalleryModel::uploadPicture();

        Redirect::to('gallery/index');
    }

    public function delete($filename) {
        GalleryModel::deletePicture($filename);

        Redirect::to('gallery/index');
    }
}