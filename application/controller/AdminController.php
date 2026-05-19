<?php

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::checkAdminAuthentication();
    }

    public function index()
    {
        $this->View->render('admin/index', array(
            'users' => UserModel::getAllUsersWithGroups()
        ));
    }

    public function actionAccountSettings()
    {
        AdminModel::setAccountSuspensionAndDeletionStatus(
            Request::post('suspension'), Request::post('softDelete'), Request::post('user_id')
        );

        Redirect::to("admin");
    }

    public function actionChangeUserGroup()
    {
        AdminModel::changeUserGroup(
            Request::post('user_id'),
            Request::post('user_account_type')
        );

        Redirect::to("admin");
    }
}