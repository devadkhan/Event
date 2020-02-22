<?php

namespace Drupal\custom_hooks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {

    /**
   * Attendee.
   *
   * @return string
   *   Return Hello string.
   */
    public function attendee() {



        $render_service = \Drupal::service('renderer');

        $_SESSION['otp'] = [
            //            'otp' => 1234,
            //            'mail' => $email,
            'verified' => false
        ];

        $entity = \Drupal::entityManager()
            ->getStorage('user')
            ->create(array());

        $formObject = \Drupal::entityManager()
            ->getFormObject('user', 'register')
            ->setEntity($entity);

        //        $formObject->set
        $form = \Drupal::formBuilder()->getForm($formObject);

        $form['#attached']['library'][] = 'custom_hooks/customhooks_lib2';



        //   multistep one section
        $form['groupone'] = [
            '#type' => 'fieldset',
        ]; 

        //   multistep two section
        $form['grouptwo'] = [
            '#type' => 'fieldset',
        ];
        //        kint(array_keys($form));exit;\

        $form['groupone']['info'] =[
            '#markup' => '<h3 class="custom-p-info">PERSONAL INFORMATION</h3>',
            '#weight' =>-9,


        ];

        //     \Drupal::formBuilder()->submitForm('user_register_form', $form_state);
        //kint();
        //        unset($form['actions']['submit']);

        //        $form['groupone']['name'] = [$form['name'],'#weight' =>-8,'#label' =>'adann'];
        $form['groupone']['name'] = $form['name'];
        $form['groupone']['name']['#weight'] = -8;
        //                 kint($form['groupone']['name']);
        //        $form['groupone']['name']['widget'][0]['value']['#title'] = "";
        $form['groupone']['name']['#attributes']['placeholder']= t('User Name');

        //        $form['groupone']['field_last_name'] = [$form['field_last_name'],'#weight' =>-7];
        $form['groupone']['field_last_name'] = $form['field_last_name'];
        $form['groupone']['field_last_name']['#weight'] = -7;
        $form['groupone']['field_last_name']['widget'][0]['value']['#attributes']['placeholder']= t('Last Name');

        $form['groupone']['field_last_namecnic'] = [

            '#type'=> 'select',
            '#options' =>['CNIC','Passport'],
            '#weight' =>-6,
            '#attributes' =>array('placeholder' => t('****')),

        ];



        //        $form['groupone']['field_cnic'] = [$form['field_cnic'],'#weight' =>-4];
        $form['groupone']['field_cnic'] = $form['field_cnic'];
        $form['groupone']['field_cnic']['#weight'] = -4;
        $form['groupone']['field_cnic']['widget'][0]['value']['#attributes']['placeholder']= t('xxxxxxxxxxxxxxx');

        //        $form['groupone']['field_passport'] = [$form['field_passport'],'#weight' =>-3];
        $form['groupone']['field_passport'] = $form['field_passport'];
        $form['groupone']['field_passport']['#weight'] = -3;
        $form['groupone']['field_passport']['widget'][0]['value']['#attributes']['placeholder']= t('xxxxxxxxxxxxxxx');

        $form['groupone']['mail'] = [$form['mail'],'#weight' =>-2];
        $form['groupone']['mail'] = $form['mail'];
        $form['groupone']['mail']['#weight'] = -2;
        $form['groupone']['mail']['#attributes']['placeholder']= t('example@gmail.com');

        //        $form['groupone']['field_designation'] = [$form['field_designation'],'#weight' =>-1];
        $form['groupone']['field_designation'] = $form['field_designation'];
        $form['groupone']['field_designation']['#weight'] = -1;
        //        kint($form['groupone']['field_designation']);
        $form['groupone']['field_designation']['widget'][0]['target_id']['#attributes']['placeholder']= t('Vice President BOI');

        $form['groupone']['next'] =[
            '#markup' => "<a  class='btn '>next</a>",
            '#weight' => 0,
        ];

        //  mutiple step two
        $form['grouptwo']['companyInfo'] =[
            '#markup' => '<h3 class="custom-p-info">COMPANY INFORMATION</h3>',
            '#weight' => -20,

        ];  

        $form['grouptwo']['field_company_name'] = $form['field_company_name'];   
        $form['grouptwo']['field_company_name']['#weight'] = -19;  
        $form['grouptwo']['field_company_name']['widget'][0]['value']['#title'] = "";  
        $form['grouptwo']['field_company_name']['widget'][0]['value']['#attributes']['placeholder']= t('Company Name');
        //        kint($form['grouptwo']['field_company_name']);

        //        $form['grouptwo']['otpCode'] = [
        //            '#type' => 'textfield',
        //            '#title' => "Enter OTP",
        //        ];

        //        kint($form);
        //        kint($form['#submit']);

        //        $form['grouptwo']['field_company_registration_numbe'] = [$form['field_company_registration_numbe'],'#weight' =>-18];
        $form['grouptwo']['field_company_registration_numbe'] = $form['field_company_registration_numbe'];
        $form['grouptwo']['field_company_registration_numbe']['#weight'] = -18;
        $form['grouptwo']['field_company_registration_numbe']['widget'][0]['value']['#title'] = "";
        $form['grouptwo']['field_company_registration_numbe']['widget'][0]['value']['#attributes']['placeholder']= t('Company Registration Number');
        //        kint($form['grouptwo']['field_company_registration_numbe']);
        //        unset($form['field_company_registration_numbe']);
        //        kint($form['grouptwo']['field_company_registration_numbe']);


        //email varification
        $form['grouptwo']['varifyemail'] =[
            '#markup' => "<div class='varifyemail btn btn-success'> <a>VARIFY EMAIL WITH ADDRESS</a></div>",
            '#weight' => -17,

        ]; 

        $form['grouptwo'] ['customtext'] = [
            '#markup' => "<p class='customtext'>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>",
            '#weight' => -16,
        ];
        //kint($form['groupone']['name']); exit;

        $form['grouptwo']['previous1'] =[
            '#prefix' => '<div class="custom-code">',
            '#type' => 'password',
            '#title' => t('Please enter 4-digit PIN from Email sent to <span class="custom-email"></span>'),
            '#maxlength' => 4,
            '#attributes' =>array('placeholder' => t('****')),
            '#weight' => -15,
            '#suffix' => '</div>',
        ];
        $form['grouptwo']['varifyemail2'] =[
            '#markup' => "<div class='varifyemail  resend'> <span class='custom-resent-text'>Resend Code</span><a>Click here</a></div><span class='custom-resent-seconds'></span>",
            '#weight' => -14,

        ]; 




        //        otp code 
        //        $form['a']['resend_otp'] = array(
        //            '#type' => 'button',
        //            '#value' => t('Click Here'),
        //            '#weight' => -8,
        //
        //            '#ajax' => array(
        //                'callback' => '::event_resend_otp',
        ////                'event' => 'change',
        ////                'wrapper' => 'msg-div',
        ////                'method' => 'replace',
        ////                'effect' => 'fade',
        //            ),
        //            '#prefix' => '<div id="msg-div" class="resend-otp">Resend Code', 
        //            '#suffix' => '</div>',
        //        );




        $form['grouptwo']['submit'] = [$form['actions']['submit'], '#weight' =>-14];
        $form['grouptwo']['submit']['#weight'] = -13;
        $form['grouptwo']['submit']['#title'] = 'confirm';
        $form['#submit'] = [['mycustomfunctiont'] , '#weight' =>-12];
        $form['grouptwo']['submit']['#submit'] = ['mycustomfunctiont'];
        $form['grouptwo']['submit']['#validate'] =  ['mycustomfunctiont'];
        //        kint($form['grouptwo']['submit']['#submit']);
        unset($form['actions']['submit']);

        //        $form['grouptwo']['submit'] =[
        //            '#type' => 'submit',
        //            '#value' => t('Confirm'),
        //            '#weight' => -6,
        //
        //
        //
        //        ];  

        unset($form['name']);
        unset($form['field_last_name']);
        unset($form['field_last_namecnic']);
        unset($form['field_passport']);
        unset($form['field_cnic']);
        unset($form['mail']);
        unset($form['field_designation']);
        unset($form['field_company_name']);
        unset($form['field_company_registration_numbe']);
        unset($form['next']);


        //        $form = \Drupal::formBuilder()->getForm('\Drupal\user\RegisterForm');

        $formRendered = $render_service->render($form);
        //                        kint($formRendered);
        //                                kint($form); exit;



        $output = [];
        $output['mail'] = [
            '#markup' =>  \Drupal\Core\Render\Markup::create($formRendered)
        ];


        return $output;
    }


    public function otpGenerate()
    {

        global $user;
        $otp = $num = str_pad(rand(0,9999),4,'0',STR_PAD_LEFT);
        $session = \Drupal::request()->getSession();
        /**** SET SESSION ****/
        $session->set('otp', 1234);

        $email =  $_POST['mail'];
        $username =  $_POST['name'];
        $_SESSION['otp'] = [
            'otp' => 1234,
            'mail' => $email,
            'verified' => false
        ];

        $session->set('mail', $email);
        $session->save();
        // $form['actions']['submit']['#attributes'] = array('class' => 'custom-save'); 
        //        $username = $arr['adnan'];

        //        $params['message'] = $text;
        $params['subject'] = 'Event Management Portal';
        $params['body'][] = $text;
        $text = '1 Dear  Please use the OTP asdf = <img src="//css-tricks.com/examples/WebsiteChangeRequestForm/images/wcrf-header.png" alt="Website Change Request" /> to Login the website"https://115.186.58.50/boi-event-portal/"'.$otp;
//        $params['body'][] = $text;
        
                $params['body'][] = ('Dear  Please use the OTP to Login the image website"'.$otp);
//                $params['body'][] = ('<p>Boi image <img src="http://115.186.58.50/boi-event-portal/image-qr-generate/Hello%20World%20For%2CTesting"/>Start Your Text Here</p>');
//        $params['body'][] = ('<p>Boi image <img src="http://115.186.58.50/boi-event-portal/image-qr-generate/Hello%20World%20For%2CTesting"/>Start Your Text Here</p>');


        $mail =  customMailSend($email, $params);


        echo 'sent';
        exit;









        //    kint($form_state);exit;

        //echo "<pre>"; print_r($session->get('otp'));exit;
        //        $name = $form_state->getValue("name");

        //        $user = user_load_by_name($name);
        //        $session->set('session-name', $name);
        //        $email = $user->getEmail();
        //        $id = $user->id(); 
        //        $session->set('login-id', $id);
        //        $session->set('login-email', $email);
        //kint($Id); exit;
        //        $arr = array('name' => $name, 'email' => $email, 'otp' => $otp);
        //sendEmail($name,$email,$otp);
        //        sendEmail($arr, 'user_login_opt');
        //        $form_state->setRedirect('multi_login.otp');
        //$submit_message = "Note Edited Successfully";
        //drupal_set_message($submit_message);
        //$form_state->setRebuild(TRUE);

    }




    public function verifyOtp(){
        $email = $_POST['email'];
        $otp = $_POST['otp'];
        if($email == $_SESSION['otp']['mail'] && $otp == $_SESSION['otp']['otp'])
        {
            $_SESSION['otp']['verified'] = true;
            echo json_encode(['status'=>'1', 'msg'=>'verified']);

        }else{
            echo json_encode(['status'=>'0', 'msg'=>'otp not matched']);
        }
        exit;
    }


    //Ajax otp code resent 
    public function event_resend_otp(array &$form, FormStateInterface $form_state){

        global $user;
        $otp = $num = str_pad(rand(0,9999),4,'0',STR_PAD_LEFT);
        $session = \Drupal::request()->getSession();
        /**** SET SESSION ****/
        $session->set('otp', 1234);

        $email =  $_POST['mail'];

        $session->set('login-email', $email);

        $params['subject'] = 'Event Management Portal';
        $params['body'][] = $text;
        $text = t('2 Dear  Please use the OTP to Login the image website"'.$otp.'" <img src="https://115.186.58.50/boi-event-portal/image-qr-generate/Fahadi%20Da%20Niya%20Kus" />');
        $params['body'][] = $text;
        $params['body'][] = ('2 Dear  Please use the OTP to Login the image website"'.$otp.'" <img src="https://115.186.58.50/boi-event-portal/image-qr-generate/Fahadi%20Da%20Niya%20Kus" />');

        $mail =  customMailSend($email, $params);


        echo 'sent';
        exit;


        $output = [];
        $output['a'] = ['#markup'=>"<span class='otp-resend'>OTP has been sent Successfully.</span>"];
        return $output;	
        drupal_set_message(t('Its done'), 'status', false);	
        $ajax_response = new AjaxResponse();

        //$ajax_response->addCommand(new AlertCommand("Please Enter Your Coupon Code If You have..."));
        return $ajax_response;
    }


}
