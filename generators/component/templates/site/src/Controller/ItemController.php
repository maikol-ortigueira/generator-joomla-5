<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

namespace <%= vendorName %>\Component\<%= nsExtName %>\Site\Controller;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\UserFactoryAwareInterface;
use Joomla\CMS\User\UserFactoryAwareTrait;
use Joomla\CMS\Versioning\VersionableControllerTrait;
use Joomla\Utilities\ArrayHelper;

// phpcs: disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs: enable PSR1.Files.SideEffects

/**
 * Controller for single <%= lItemName %> view
 *
 * @since  1.5.19
 */
class <%= nsItemName %>Controller extends FormController implements UserFactoryAwareInterface
{
    use UserFactoryAwareTrait;
    use VersionableControllerTrait;

    /**
     * The URL view item variable.
     *
     * @var    string
     * @since  4.0.0
     */
    protected $view_item = 'form';

    /**
     * The URL view list variable.
     *
     * @var    string
     * @since  4.0.0
     */
    protected $view_list = 'categories';

    /**
     * Method to get a model object, loading it if required.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel  The model.
     *
     * @since   1.6.4
     */
    public function getModel($name = 'form', $prefix = '', $config = ['ignore_request' => true])
    {
        return parent::getModel($name, $prefix, ['ignore_request' => false]);
    }

    /**
     * Method to submit the <%= lItemName %> form and send an email.
     *
     * @return  boolean  True on success sending the email. False on failure.
     *
     * @since   1.5.19
     */
    public function submit()
    {
        // Check for request forgeries.
        $this->checkToken();

        $app    = $this->app;
        $model  = $this->getModel('<%= lItemName %>');
        $stub   = $this->input->getString('id');
        $id     = (int) $stub;

        // Get the data from POST
        $data = $this->input->post->get('jform', [], 'array');

        // Get item
        $model->setState('filter.published', 1);
        $<%= lItemName %> = $model->getItem($id);

        if ($<%= lItemName %> === false) {
            $this->setMessage($model->getError(), 'error');

            return false;
        }

        // Get item params, take menu parameters into account if necessary
        $active      = $app->getMenu()->getActive();
        $stateParams = clone $model->getState()->get('params');

        // If the current view is the active item and a <%= lItemName %> view for this <%= lItemName %>, then the menu item params take priority
        if ($active && strpos($active->link, 'view=<%= lItemName %>') && strpos($active->link, '&id=' . (int) $<%= lItemName %>->id)) {
            // $item->params are the <%= lItemName %> params, $temp are the menu item params
            // Merge so that the menu item params take priority
            $<%= lItemName %>->params->merge($stateParams);
        } else {
            // Current view is not a single <%= lItemName %>, so the <%= lItemName %> params take priority here
            $stateParams->merge($<%= lItemName %>->params);
            $<%= lItemName %>->params = $stateParams;
        }

        // ######## REVISA ESTA PARTE ########
        // Check if the <%= lItemName %> form is enabled
        if (!$<%= lItemName %>->params->get('show_email_form')) {
            $this->setRedirect(Route::_('index.php?option=com_<%= lExtName %>&view=<%= lItemName %>&id=' . $stub . '&catid=' . $<%= lItemName %>->catid, false));

            return false;
        }

        // Check for a valid session cookie
        if ($<%= lItemName %>->params->get('validate_session', 0)) {
            if (Factory::getSession()->getState() !== 'active') {
                $this->app->enqueueMessage(Text::_('JLIB_ENVIRONMENT_SESSION_INVALID'), 'warning');

                // Save the data in the session.
                $this->app->setUserState('com_<%= lExtName %>.<%= lItemName %>.data', $data);

                // Redirect back to the <%= lItemName %> form.
                $this->setRedirect(Route::_('index.php?option=com_<%= lExtName %>&view=<%= lItemName %>&id=' . $stub . '&catid=' . $<%= lItemName %>->catid, false));

                return false;
            }
        }

        // Validate the posted data.
        $form = $model->getForm();

        if (!$form) {
            throw new \Exception($model->getError(), 500);
        }

        if (!$model->validate($form, $data)) {
            $errors = $model->getErrors();

            foreach ($errors as $error) {
                $errorMessage = $error;

                if ($error instanceof \Exception) {
                    $errorMessage = $error->getMessage();
                }

                $app->enqueueMessage($errorMessage, 'error');
            }

            $app->setUserState('com_<%= lExtName %>.<%= lItemName %>.data', $data);

            $this->setRedirect(Route::_('index.php?option=com_<%= lExtName %>&view=<%= lItemName %>&id=' . $stub . '&catid=' . $<%= lItemName %>->catid, false));

            return false;
        }

        // Flush the data from the session
        $this->app->setUserState('com_<%= lExtName %>.<%= lItemName %>.data', null);

        // Redirect if it is set in the parameters, otherwise redirect back to where we came from
        if ($<%= lItemName %>->params->get('redirect')) {
            $this->setRedirect($<%= lItemName %>->params->get('redirect'), $msg);
        } else {
            $this->setRedirect(Route::_('index.php?option=com_<%= lExtName %>&view=<%= lItemName %>&id=' . $stub . '&catid=' . $<%= lItemName %>->catid, false), $msg);
        }

        return true;
    }

    /**
     * Method override to check if you can add a new record.
     *
     * @param   array  $data  An array of input data.
     *
     * @return  boolean
     *
     * @since   4.0.0
     */
    protected function allowAdd($data = [])
    {
        if ($categoryId = ArrayHelper::getValue($data, 'catid', $this->input->getInt('catid'), 'int')) {
            $user = $this->app->getIdentity();

            // If the category has been passed in the data or URL check it.
            return $user->authorise('core.create', 'com_<%= lExtName %>.category.' . $categoryId);
        }

        // In the absence of better information, revert to the component permissions.
        return parent::allowAdd();
    }

    /**
     * Method override to check if you can edit an existing record.
     *
     * @param   array   $data  An array of input data.
     * @param   string  $key   The name of the key for the primary key; default is id.
     *
     * @return  boolean
     *
     * @since   4.0.0
     */
    protected function allowEdit($data = [], $key = 'id')
    {
        $recordId = (int) isset($data[$key]) ? $data[$key] : 0;

        if (!$recordId) {
            return false;
        }

        // Need to do a lookup from the model.
        $record     = $this->getModel()->getItem($recordId);
        $categoryId = (int) $record->catid;

        if ($categoryId) {
            $user = $this->app->getIdentity();

            // The category has been set. Check the category permissions.
            if ($user->authorise('core.edit', $this->option . '.category.' . $categoryId)) {
                return true;
            }

            // Fallback on edit.own.
            if ($user->authorise('core.edit.own', $this->option . '.category.' . $categoryId)) {
                return ($record->created_by === $user->id);
            }

            return false;
        }

        // Since there is no asset tracking, revert to the component permissions.
        return parent::allowEdit($data, $key);
    }

    /**
     * Method to cancel an edit.
     *
     * @param   string  $key  The name of the primary key of the URL variable.
     *
     * @return  boolean  True if access level checks pass, false otherwise.
     *
     * @since   4.0.0
     */
    public function cancel($key = null)
    {
        $result = parent::cancel($key);

        $this->setRedirect(Route::_($this->getReturnPage(), false));

        return $result;
    }

    /**
     * Gets the URL arguments to append to an item redirect.
     *
     * @param   integer  $recordId  The primary key id for the item.
     * @param   string   $urlVar    The name of the URL variable for the id.
     *
     * @return  string    The arguments to append to the redirect URL.
     *
     * @since   4.0.0
     */
    protected function getRedirectToItemAppend($recordId = 0, $urlVar = 'id')
    {
        // Need to override the parent method completely.
        $tmpl = $this->input->get('tmpl');

        $append = '';

        // Setup redirect info.
        if ($tmpl) {
            $append .= '&tmpl=' . $tmpl;
        }

        $append .= '&layout=edit';

        $append .= '&' . $urlVar . '=' . (int) $recordId;

        $itemId = $this->input->getInt('Itemid');
        $return = $this->getReturnPage();
        $catId  = $this->input->getInt('catid');

        if ($itemId) {
            $append .= '&Itemid=' . $itemId;
        }

        if ($catId) {
            $append .= '&catid=' . $catId;
        }

        if ($return) {
            $append .= '&return=' . base64_encode($return);
        }

        return $append;
    }

    /**
     * Get the return URL.
     *
     * If a "return" variable has been passed in the request
     *
     * @return  string    The return URL.
     *
     * @since   4.0.0
     */
    protected function getReturnPage()
    {
        $return = $this->input->get('return', null, 'base64');

        if (empty($return) || !Uri::isInternal(base64_decode($return))) {
            return Uri::base();
        }

        return base64_decode($return);
    }
}