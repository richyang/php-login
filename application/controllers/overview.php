<?php

/**
 * Class Overview
 * This controller shows the (public) account data of one or all user(s)
 */
class Overview extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This method controls what happens when you move to /overview/index in your app.
     * Shows a list of all users.
     */
    public function index()
    {
        $overviewModel = $this->loadModel('Overview');
        $this->view->users = $overviewModel->getAllUsersProfiles();
        $this->view->render('overview/index');
    }

    /**
     * This method controls what happens when you move to /overview/showuserprofile in your app.
     * Shows the (public) details of the selected user.
     * @param $user_id int id the the user
     */
    public function showUserProfile($userID)
    {
        $overviewModel = $this->loadModel('Overview');
        $this->view->user = $overviewModel->getUserProfile($userID);
        $this->view->render('overview/showuserprofile');
    }
}
