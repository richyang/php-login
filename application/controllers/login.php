<?php

/**
 * Login Controller
 * Controls the login processes
 */

class Login extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index, default action (shows the login form), when you do login/index
     */
    public function index()
    {
        // create a login model to perform the getFacebookLoginUrl() method
        $loginModel = $this->loadModel('Login');

        // if we use Facebook: this is necessary as we need the facebook_login_url in the login form (in the view)
        if (FACEBOOK_LOGIN == true) {
            $this->view->facebook_login_url = $loginModel->getFacebookLoginUrl();
        }

        // show the view
        $this->view->render('login/index');
    }

    /**
     * The login action, when you do login/login
     */
    public function login()
    {
        // run the login() method in the login-model, put the result in $login_successful (true or false)
        $loginModel = $this->loadModel('Login');
        // perform the login method, put result (true or false) into $login_successful
        $loginSuccessful = $loginModel->login();

        // check login status
        if ($loginSuccessful) {
            // if YES, then move user to dashboard/index (btw this is a browser-redirection, not a rendered view!)
            header('location: ' . URL . 'dashboard/index');
        } else {
            // if NO, then move user to login/index (login form) again
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * The login action, this is where the user is directed after being checked by the Facebook server by
     * clicking the facebook-login button
     */
    public function loginWithFacebook()
    {
        // run the login() method in the login-model, put the result in $login_successful (true or false)
        $loginModel = $this->loadModel('Login');
        $loginSuccessful = $loginModel->loginWithFacebook();

        // check login status
        if ($loginSuccessful) {
            // if YES, then move user to dashboard/index (this is a browser-redirection, not a rendered view)
            header('location: ' . URL . 'dashboard/index');
        } else {
            // if NO, then move user to login/index (login form) (this is a browser-redirection, not a rendered view)
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * The logout action, login/logout
     */
    public function logout()
    {
        $loginModel = $this->loadModel('Login');
        $loginModel->logout();
        // redirect user to base URL
        header('location: ' . URL);
    }

    /**
     * Login with cookie
     */
    public function loginWithCookie()
    {
        // run the loginWithCookie() method in the login-model, put the result in $login_successful (true or false)
        $loginModel = $this->loadModel('Login');
        $loginSuccessful = $loginModel->loginWithCookie();

        if ($loginSuccessful) {
            header('location: ' . URL . 'dashboard/index');
        } else {
            // delete the invalid cookie to prevent infinite login loops
            $loginModel->deleteCookie();
            // if NO, then move user to login/index (login form) (this is a browser-redirection, not a rendered view)
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * Show user's profile
     */
    public function showProfile()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $this->view->render('login/showprofile');
    }

    /**
     * Edit user name (show the view with the form)
     */
    public function editUsername()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $this->view->render('login/editusername');
    }

    /**
     * Edit user name (perform the real action after form has been submitted)
     */
    public function editUsername_action()
    {
        $loginModel = $this->loadModel('Login');
        $loginModel->editUserName();
        $this->view->render('login/editusername');
    }

    /**
     * Edit user email (show the view with the form)
     */
    public function editUserEmail()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $this->view->render('login/edituseremail');
    }

    /**
     * Edit user email (perform the real action after form has been submitted)
     */
    public function editUserEmail_action()
    {
        $loginModel = $this->loadModel('Login');
        $loginModel->editUserEmail();
        $this->view->render('login/edituseremail');
    }

    /**
     * Upload avatar
     */
    public function uploadAvatar()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $loginModel = $this->loadModel('Login');
        $this->view->avatar_file_path = $loginModel->getUserAvatarFilePath();
        $this->view->render('login/uploadavatar');
    }

    /**
     *
     */
    public function uploadAvatar_action()
    {
        $loginModel = $this->loadModel('Login');
        $loginModel->createAvatar();
        $this->view->render('login/uploadavatar');
    }

    /**
     *
     */
    public function changeAccountType()
    {
        // Auth::handleLogin() makes sure that only logged in users can use this action/method and see that page
        Auth::handleLogin();
        $this->view->render('login/changeaccounttype');
    }

    /**
     *
     */
    public function changeAccountType_action()
    {
        $loginModel = $this->loadModel('Login');
        $loginModel->changeAccountType();
        $this->view->render('login/changeaccounttype');
    }

    /**
     * Register page
     * Show the register form (with the register-with-facebook button). We need the facebook-register-URL for that.
     */
    public function register()
    {
        $loginModel = $this->loadModel('Login');

        // if we use Facebook: this is necessary as we need the facebook_register_url in the login form (in the view)
        if (FACEBOOK_LOGIN == true) {
            $this->view->facebook_register_url = $loginModel->getFacebookRegisterUrl();
        }

        $this->view->render('login/register');
    }

    /**
     * Register page action (after form submit)
     */
    public function register_action()
    {
        $loginModel = $this->loadModel('Login');
        $registrationSuccessful = $loginModel->registerNewUser();

        if ($registrationSuccessful == true) {
            header('location: ' . URL . 'login/index');
        } else {
            header('location: ' . URL . 'login/register');
        }
    }

    /**
     * Register a user via Facebook-authentication
     */
    public function registerWithFacebook()
    {
        $loginModel = $this->loadModel('Login');
        // perform the register method, put result (true or false) into $registration_successful
        $registrationSuccessful = $loginModel->registerWithFacebook();

        // check registration status
        if ($registrationSuccessful) {
            // if YES, then move user to login/index (this is a browser-redirection, not a rendered view)
            header('location: ' . URL . 'login/index');
        } else {
            // if NO, then move user to login/register (this is a browser-redirection, not a rendered view)
            header('location: ' . URL . 'login/register');
        }
    }

    /**
     * Verify user after activation mail link opened
     * @param int $user_id user's id
     * @param string $user_activation_verification_code sser's verification token
     */
    public function verify($userID, $userActivationVerificationCode)
    {
        $loginModel = $this->loadModel('Login');
        $loginModel->verifyNewUser($userID, $userActivationVerificationCode);
        $this->view->render('login/verify');
    }

    /**
     * Request password reset page
     */
    public function requestPasswordReset()
    {
        $this->view->render('login/requestpasswordreset');
    }

    /**
     * Request password reset action (after form submit)
     */
    public function requestPasswordReset_action()
    {
        $loginModel = $this->loadModel('Login');
        $loginModel->requestPasswordReset();
        $this->view->render('login/requestpasswordreset');
    }

    /**
     * Verify the verification token of that user (to show the user the password editing view or not)
     * @param string $user_name username
     * @param string $verification_code password reset verification token
     */
    public function verifyPasswordReset($username, $verificationCode)
    {
        $loginModel = $this->loadModel('Login');
        if ($loginModel->verifyPasswordReset($username, $verificationCode)) {
            // get variables for the view
            $this->view->user_name = $username;
            $this->view->user_password_reset_hash = $verificationCode;
            $this->view->render('login/changepassword');
        } else {
            header('location: ' . URL . 'login/index');
        }
    }

    /**
     * Set the new password
     */
    public function setNewPassword()
    {
        $loginModel = $this->loadModel('Login');
        // try the password reset (user identified via hidden form inputs ($user_name, $verification_code)), see
        // verifyPasswordReset() for more
        $loginModel->setNewPassword();
        // regardless of result: go to index page (user will get success/error result via feedback message)
        header('location: ' . URL . 'login/index');
    }

    /**
     * Generate a captcha, write the characters into $_SESSION['captcha'] and returns a real image which will be used
     * like this: <img src="......./login/showCaptcha" />
     * IMPORTANT: As this action is called via <img ...> AFTER the real application has finished executing (!), the
     * SESSION["captcha"] has no content when the application is loaded. The SESSION["captcha"] gets filled at the
     * moment the end-user requests the <img .. >
     * If you don't know what this means: Don't worry, simply leave everything like it is ;)
     */
    public function showCaptcha()
    {
        $loginModel = $this->loadModel('Login');
        $loginModel->generateCaptcha();
    }
}
