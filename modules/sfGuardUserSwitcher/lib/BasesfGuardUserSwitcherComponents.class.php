<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    sfGuardUserSwitcherPlugin
 * @subpackage module
 * @author     Gordon Franke <info@nevalon.de>
 * @version    SVN: $Id$
 */
class BasesfGuardUserSwitcherComponents extends sfComponents
{
  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   *
   * @return void
   */
  public function executeIndex(sfWebRequest $request)
  {
    $user = $this->getUser();
    if (!$this->hasPermissionToSwitch())
    {
      return sfView::NONE;
    }

    $this->form = new sfGuardUserSwitcherForm();
    if ($request->isMethod(sfRequest::POST) AND $request->hasParameter($this->form->getName()))
    {
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid())
      {
        // save original user id
        if (!$user->hasAttribute('original_id', 'sfGuardUserSwitcher'))
        {        
          $user->setAttribute('original_id', (int) $user->getAttribute('user_id', null, 'sfGuardSecurityUser'), 'sfGuardUserSwitcher');
        }

        // switch user
        $this->switchUser($this->form->getOption('sfGuardUser'));

        // redirect to actual url to reload page
        $this->getController()->redirect($this->getContext()->getRouting()->getCurrentInternalUri());
      }
    }
  }

  /**
   * check if the user is allowed to switch the user
   *
   * @return boolean
   */
  protected function hasPermissionToSwitch()
  {
    $user = $this->getUser();

    return $user->isAuthenticated() AND $user->isSuperAdmin();
    
  }

  /**
   * perform the user switch
   * 
   * @param sfGuardUser $sfGuardUser the new user object
   */
  protected function switchUser($sfGuardUser)
  {
    $user = $this->getUser();
    $user->setAttribute('user_id', $sfGuardUser->id, 'sfGuardSecurityUser');
  }
}
