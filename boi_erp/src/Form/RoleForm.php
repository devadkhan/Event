<?php

namespace Drupal\boi_erp\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class RoleForm.
 */
class RoleForm extends FormBase {

    /**
   * {@inheritdoc}
   */
    public function getFormId() {
        return 'role_form';
    }

    /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state, $rid=null) {

        $default_values = [];
        $rolePermissions = [];
        //edit page
        if($rid != null)
        { 
            $form_state->set('rid', $rid);
            //load role
            $role =  \Drupal\user\Entity\Role::load($rid);
            if(!$role)
            {
                throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
            }

            $rolePermissions = $role->getPermissions();

            $default_values['roleName'] = $role->label();
            //load permissions
            $default_values['rolePermissions'] = $role->getPermissions();

        }else{
            $form_state->set('rid', null);
        }

        $form['#attached']['library'][] = 'boi_erp/lider_lib';

        $form['markuptitle'] =[
            '#markup' => "<div class='role-form-custom'>ADD NEW ROLE</div>",

        ];
        $form['roleName']= [
            '#type' => 'textfield',
            '#title' => $this->t('Role Name'),
            '#default_value' => $default_values['roleName'] ?? '',
        ]; 




        //////// without role
        //    $form['markuptable'] =[
        //      '#markup' => "<div class='role-form-custom'>Select Permissions</div>",
        //
        //    ];


        //load menu items 
        $mainItems = \Drupal\boi_erp\BoiErp::getSideBarMenu();



        //    $form['conditions'] = [
        //      '#type' => 'vertical_tabs',
        //      '#title' => t('Permissions'),
        //    ];

        $form['conditions'] = [
            '#type' => 'container',
            '#tree' => true
        ];

        $form['#attributes'] = array('class' => 'custom-permission');

        foreach($mainItems as $id=>$item)
        {

            $groupKey = 'group'.$id;

            $form['conditions'][$groupKey] = [
                '#type' => count($item['subTree'])?'fieldset':'container',
                '#title' => $item['title'].' <sup class="badge">'.count($item['subTree']).'</sup>  <span class="help-block">'.$item['description'].'</span>',
                '#description' => $item['description'],
                '#open' => true,
                '#attributes' => [
                    'class' => ['custom-role']
                ]
                //        '#group' => 'conditions',
            ];


            $form['conditions'][$groupKey][$id] = [
                '#type' => 'checkboxes',
                '#title' => count($item['subTree'])?'':$item['title'],
                '#options' => ['boi_erp_'.$id.'_read'=>'Read'],
                '#default_value' => $rolePermissions,
                //        '#title' => 'Main '.$item['title'],
                '#prefix' => count($item['subTree'])?'<div class="hidden">':'<div class="dd">',
                '#suffix' => '</div>',
                '#attributes' => [
                    //          'class' => ['parent-permission-vert-tabs']
                    'class' => ['parent-permission']
                ]
            ];

            foreach($item['subTree'] as $idSub=>$itemSub)
            {
                $form['conditions'][$groupKey][$idSub] = [
                    '#prefix' => '<div class="container-inline">',
                    '#suffix' => '</div>',
                    '#type' => 'checkboxes',
                    '#title' =>$itemSub['title'],
                    '#options' => ['boi_erp_'.$idSub.'_read'=>'Read', 'boi_erp_'.$idSub.'_write'=>'Write', 'boi_erp_'.$idSub.'_delete'=>'Delete'],
                    '#default_value' => $rolePermissions,
                ];

            }

        }


        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

        return $form;
    }

    /**
   * {@inheritdoc}
   */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        //check role exists return error

        //        parent::validateForm($form, $form_state);
    }

    /**
   * {@inheritdoc}
   */
    public function submitForm(array &$form, FormStateInterface $form_state) {

        $config = \Drupal::service('config.factory')->getEditable('boi_erp.role');
        //edit case
        if($rid = $form_state->get('rid'))
        {
            $role = \Drupal\user\Entity\Role::load($rid);

            $groups = $form_state->getValue('conditions');

            foreach($groups as $groupKey=>$menuItems)
            {
                foreach($menuItems as $menuId=>$menuInfo)
                {
                    foreach($menuInfo as $perm=>$value)
                    {
                        if($value !== 0)
                        {
                            $role->grantPermission($perm);
                        }else{
                            $role->revokePermission($perm);

                        }
                    }
                }
            }

            $role->save();

            ///show message
            //drupal_set_message, \Drupal Messenger
            drupal_set_message("Role $rid has been updated.");

        }else{


            //create role programmatically
            $role_name  = $form_state->getValue('roleName');
            $role_status  = $form_state->getValue('status');
            $role_group  = $form_state->getValue('role-group');
            //        $rol_name_unberscore = preg_replace('/\s+/', '_', $role_name);
            $role_to_lower = strtolower($role_name);
            $rol_name_unberscore = preg_replace('/[^a-z0-9_]+/', '_', $role_to_lower);


            //$config = $this->config('boi_erp.role');
            //drupal get editable config


            $role = \Drupal\user\Entity\Role::create(array('id' => $rol_name_unberscore, 'label' => $role_name));

            $groups = $form_state->getValue('conditions');
            foreach($groups as $groupKey=>$menuItems)
            {
                foreach($menuItems as $menuId=>$menuInfo)
                {
                    foreach($menuInfo as $perm=>$value)
                    {
                        if($value !== 0)
                        {
                            $role->grantPermission($perm);
                        }else{
                            $role->revokePermission($perm);

                        }
                    }
                }
            }

            $role->save();



            // 
            $role_id = $role->id();

            $config->set($role_id, [
                'status'=>$role_status,
                'role-group'=>$role_group,
            ])->save();
            drupal_set_message("$role_name has been created.");

        }

    }

}
