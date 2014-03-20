<?php

/**
 * Class View
 *
 * Provides the methods all views will have
 */
class View
{
    /**
     * simply includes (=shows) the view. this is done from the controller. In the controller, you usually say
     * $this->view->render('help/index'); to show (in this example) the view index.php in the folder help.
     * Usually the Class and the method are the same like the view, but sometimes you need to show different views.
     * @param string $filename Path of the to-be-rendered view, usually folder/file(.php)
     * @param boolean $renderWithoutHeaderAndFooter Optional: Set this to true if you don't want to include header and footer
     */
    public function render($filename, $renderWithoutHeaderAndFooter = false)
    {
        // page without header and footer, for whatever reason
        if ($renderWithoutHeaderAndFooter == true) {
            require VIEWS_PATH . $filename . '.php';
        } else {
            require VIEWS_PATH . '_templates/header.php';
            require VIEWS_PATH . $filename . '.php';
            require VIEWS_PATH . '_templates/footer.php';
        }
    }

    /**
     * renders the feedback messages into the view
     */
    public function renderFeedbackMessages()
    {
        // echo out the feedback messages (errors and success messages etc.),
        // they are in $_SESSION["feedback_positive"] and $_SESSION["feedback_negative"]
        require VIEWS_PATH . '_templates/feedback.php';

        // delete these messages (as they are not needed anymore and we want to avoid to show them twice
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }

    /**
     * Checks if the passed string is the currently active controller.
     * Useful for handling the navigation's active/non-active link.
     * @param string $filename
     * @param string $navigationController
     * @return bool Shows if the controller is used or not
     */
    private function checkForActiveController($filename, $navigationController)
    {
        $splitFilename = explode("/", $filename);
        $activeController = $splitFilename[0];

        if ($activeController == $navigationController) {
            return true;
        }
        // default return
        return false;
    }

    /**
     * Checks if the passed string is the currently active controller-action (=method).
     * Useful for handling the navigation's active/non-active link.
     * @param string $filename
     * @param string $navigationAction
     * @return bool Shows if the action/method is used or not
     */
    private function checkForActiveAction($filename, $navigationAction)
    {
        $splitFilename = explode("/", $filename);
        $activeAction = $splitFilename[1];

        if ($activeAction == $navigationAction) {
            return true;
        }
        // default return of not true
        return false;
    }

    /**
     * Checks if the passed string is the currently active controller and controller-action.
     * Useful for handling the navigation's active/non-active link.
     * @param string $filename
     * @param string $navigationControllerAndAction
     * @return bool
     */
    private function checkForActiveControllerAndAction($filename, $navigationControllerAnd_action)
    {
        $splitFilename = explode("/", $filename);
        $activeController = $splitFilename[0];
        $activeAction = $splitFilename[1];

        $splitFilename = explode("/", $navigationControllerAndAction);
        $navigationController = $splitFilename[0];
        $navigationAction = $splitFilename[1];

        if ($activeController == $navigationController AND $activeAction == $navigationAction) {
            return true;
        }
        // default return of not true
        return false;
    }
}
