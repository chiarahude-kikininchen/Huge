<?php

/**
 * RegisterController
 * Register new user
 */
class RegisterController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class. The parent::__construct thing is necessary to
     * put checkAuthentication in here to make an entire controller only usable for logged-in users (for sure not
     * needed in the RegisterController).
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Register page
     * Show the register form, but redirect to main-page if user is already logged-in
     */
    public function index()
    {
        if (!LoginModel::isUserLoggedIn()) {
            Session::add('feedback_negative', 'Please log in first.');
            Redirect::to('login/index');
            return;
        }

        if (Session::get('user_account_type') != 7) {
            Session::add('feedback_negative', 'Only admins can add users.');
            Redirect::home();
            return;
        }

        $this->View->render('register/index');
    }

    /**
     * Verify user after activation mail link opened
     * @param int $user_id user's id
     * @param string $user_activation_verification_code user's verification token
     */
    public function verify($user_id, $user_activation_verification_code)
    {
        if (isset($user_id) && isset($user_activation_verification_code)) {
            RegistrationModel::verifyNewUser($user_id, $user_activation_verification_code);
            $this->View->render('register/verify');
        } else {
            Redirect::to('login/index');
        }
    }

    /**
     * Generate a captcha, write the characters into $_SESSION['captcha'] and returns a real image which will be used
     * like this: <img src="......./login/showCaptcha" />
     * IMPORTANT: As this action is called via <img ...> AFTER the real application has finished executing (!), the
     * SESSION["captcha"] has no content when the application is loaded. The SESSION["captcha"] gets filled at the
     * moment the end-user requests the <img .. >
     * Maybe refactor this sometime.
     */
    public function showCaptcha()
    {
        CaptchaModel::generateAndShowCaptcha();
    }

    public function register_action()
    {
        if (!LoginModel::isUserLoggedIn()) {
            Session::add('feedback_negative', 'Please log in first.');
            Redirect::to('login/index');
            return;
        }

        if (Session::get('user_account_type') != 7) {
            Session::add('feedback_negative', 'Only admins can add users.');
            Redirect::home();
            return;
        }

        $recaptchaResponse = Request::post('g-recaptcha-response');

        if (empty($recaptchaResponse)) {
            Session::add('feedback_negative', 'Bitte bestätige das reCAPTCHA.');
            Redirect::to('register/index');
            return;
        }

        $secretKey = Config::get('RECAPTCHA_SECRET_KEY');

        $response = file_get_contents(
            'https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey .
            '&response=' . $recaptchaResponse
        );

        $responseData = json_decode($response);

        if (!$responseData || !$responseData->success) {
            Session::add('feedback_negative', 'reCAPTCHA wurde nicht bestätigt.');
            Redirect::to('register/index');
            return;
        }

        $registration_successful = RegistrationModel::registerNewUser();

        if ($registration_successful) {
            Session::add('feedback_positive', 'User account created.');
            Redirect::to('register/index');
        } else {
            Redirect::to('register/index');
        }
    }
}