<?php

/**
 * sfGuardUserSwitcher form.
 *
 * @package    sfGuardUserSwitcherPlugin
 * @subpackage form
 * @author     Gordon Franke <info@nevalon.de>
 * @version    SVN: $Id$
 */
class sfGuardUserSwitcherForm extends BaseForm
{
  public function setup()
  {
    $this->setWidget('user', new sfWidgetFormInputText(array('label' => 'Id/Username')));
    $this->setValidator('user', new sfValidatorCallback(
      array('callback' => array($this, 'validateUserExists')),
      array('required' => 'user_required')
    ));

    $this->getWidgetSchema()
      ->setNameFormat('user_switch[%s]')
      ->getFormFormatter()
        ->setTranslationCatalogue('form_user_switch');
  }

  public function validateUserExists($validator, $value, $arguments)
  {
    // no answers submitted
    if (empty($value))
    {
      throw new sfValidatorError($validator, 'No user given.');
    }
    
    $q = Doctrine_Core::getTable('sfGuardUser')
      ->createQuery('u')
      ->limit(1);
    
    if (is_numeric($value))
    {
      $value = (int) $value;
      $q->where('id = ?');
    }
    else
    {
      $q->where('username = ?');
    }

    $sfGuardUser = $q->fetchOne(array($value));

    // check correct answer number
    if (!$sfGuardUser)
    {
      throw new sfValidatorError($validator, 'No user found.');
    }

    $this->setOption('sfGuardUser', $sfGuardUser);
    
    return $value;
  }
}
