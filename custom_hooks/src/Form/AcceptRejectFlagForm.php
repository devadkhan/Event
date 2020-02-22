<?php

namespace Drupal\custom_hooks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;

/**
 * Class AcceptRejectFlagForm.
 */
class AcceptRejectFlagForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'accept_reject_flag_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $flagId=null, $status=null) {





    //load flag

    $flag = \Drupal\flag\Entity\Flagging::load($flagId);


    if(!$flag || !in_array($status, ['Approved', 'Rejected']))
    {

      $form['msg'] = [
        '#markup' => 'Flag Not found or status incorrect'
      ];
      return $form;
    }



    $flagStatus = $flag->field_status->getValue();

    //if value is set or it is not equal to not selected
    if( !(!isset($flagStatus[0]['value']) || $flagStatus[0]['value'] == "Not selected") )
    {
      $form['msg'] = [
        '#markup' => 'Already '.$flagStatus[0]['value']
      ];
      return $form;
    }


    $form_state->set("flagId", $flagId);
    $form_state->set("status", $status);


    $form['header'] = [
      '#markup' => '<div class="request-modal-header">Confirmation</div><div class="request-modal-quest">Are you sure to <span class=custom-status-appr-rej>'.$status.'</span> his request?</div>',
    ];


    $form['msg'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Comments (if any)'),
      '#placeholder' => $this->t('Type here...'),
//      '#maxlength' => 64,
//      '#size' => 64,
      '#weight' => '0',
    ];

    $form['cancel'] = [
      '#markup' => '<a class="cancel-dialoge btn btn-default btn-lg request-modal-no"  href="#" aria-label="Close" data-dismiss="modal">No</a>'
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Yes'),
      '#ajax' => [
        'callback' => '::save',
      ],
      '#attributes' => [
        'class' => ['btn btn-primary btn-lg request-modal-yes']
      ]
    ];

    return $form;
  }

  function save(array &$form, FormStateInterface $form_state)
  {
    $response = new AjaxResponse();
    $response->addCommand(new CloseModalDialogCommand());
    $mailSent = $form_state->get("emailSent");
    $mailSentMsg = $mailSent?"Mail sent":"Mail sending failed";
    if( $form_state->get('flagUpdate') )
    {
      $response->addCommand(new OpenModalDialogCommand("Success", '<div class="info info-success">Updated </div>'."<div>$mailSentMsg</div>", ["dialogClass"=>'updateviews event-request-modal']));
    }else{
      $response->addCommand(new OpenModalDialogCommand("Failure", '<div class="info info-danger">Not Updated</div>'));
    }
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->set('msgsgs', 'set');
    $status = $form_state->get('status');
    $flagId = $form_state->get('flagId');
    $msg = $form_state->getValue("msg");
    $flag = \Drupal\flag\Entity\Flagging::load($flagId);
    $ownerEmail = $flag->getOwner()->getEmail();

    $ownerEmail = $flag->getOwner()->getEmail();

    $ownerEmail = 'adnan.drupl@gmail.com';

    $arr = array('name' => 'adnan khan kabari', 'email' => $ownerEmail, 'otp' => '0000333');

    //    mail($ownerEmail, "My subject",'your OTP code is '.'123'.' email is '.$ownerEmail);



    $to = 'adnan.drupl@gmail.com';
    $text = t('asdf');
    $params['subject'] = 'Request '.$flagId;
    $params['body'][] = t($msg);
    $params['body'][] = t("Status ". $status);
    $mail =  customMailSend($to, $params);


    $form_state->set("emailSent", $mail['result']);



    $flag->field_status->setValue($status);
    $flag->field_message->setValue($msg);
    $flag->save();
    $form_state->set("flagUpdate", true);
  }

}
