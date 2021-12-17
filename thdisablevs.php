<?php
/**
 * 2006-2021 THECON SRL
 *
 * NOTICE OF LICENSE
 *
 * DISCLAIMER
 *
 * YOU ARE NOT ALLOWED TO REDISTRIBUTE OR RESELL THIS FILE OR ANY OTHER FILE
 * USED BY THIS MODULE.
 *
 * @author    THECON SRL <contact@thecon.ro>
 * @copyright 2006-2021 THECON SRL
 * @license   Commercial
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Thdisablevs extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'thdisablevs';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'Presta Maniacs';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Disable Inspect Element Shortcuts Access');
        $this->description = $this->l('Disable View Source and Inspect Element shortcuts');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('THDISABLEVS_LIVE_MODE', false);

        if (!parent::install()) {
            return false;
        }

        if (!$this->registerHooks()) {
            return false;
        }

        return true;
    }

    public function registerHooks()
    {
        if (!$this->registerHook('backOfficeHeader')) {
            return false;
        }

        if ($this->getPsVersion() == '7') {
            if (!$this->registerHook('displayBeforeBodyClosingTag')) {
                return false;
            }
        } else {
            if (!$this->registerHook('displayFooter')) {
                return false;
            }
        }

        return true;
    }

    public function uninstall()
    {
        Configuration::deleteByName('THDISABLEVS_LIVE_MODE');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitThdisablevsModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitThdisablevsModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'THDISABLEVS_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'th_title',
                        'label' => '',
                        'name' => $this->l('Shortcuts Disabled'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Right Click'),
                        'name' => 'THDISABLEVS_RIGHT_CLICK',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('F12'),
                        'name' => 'THDISABLEVS_F12',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Ctrl + Shift + I'),
                        'name' => 'THDISABLEVS_CTRL_SHIFT_I',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Ctrl + Shift + C'),
                        'name' => 'THDISABLEVS_CTRL_SHIFT_C',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Ctrl + Shift + J'),
                        'name' => 'THDISABLEVS_CTRL_SHIFT_J',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Ctrl + U'),
                        'name' => 'THDISABLEVS_CTRL_U',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Ctrl + S'),
                        'name' => 'THDISABLEVS_CTRL_S',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'THDISABLEVS_LIVE_MODE' => Tools::getValue('THDISABLEVS_LIVE_MODE', Configuration::get('THDISABLEVS_LIVE_MODE')),
            'THDISABLEVS_RIGHT_CLICK' => Tools::getValue('THDISABLEVS_RIGHT_CLICK', Configuration::get('THDISABLEVS_RIGHT_CLICK')),
            'THDISABLEVS_F12' => Tools::getValue('THDISABLEVS_F12', Configuration::get('THDISABLEVS_F12')),
            'THDISABLEVS_CTRL_SHIFT_I' => Tools::getValue('THDISABLEVS_CTRL_SHIFT_I', Configuration::get('THDISABLEVS_CTRL_SHIFT_I')),
            'THDISABLEVS_CTRL_SHIFT_C' => Tools::getValue('THDISABLEVS_CTRL_SHIFT_C', Configuration::get('THDISABLEVS_CTRL_SHIFT_C')),
            'THDISABLEVS_CTRL_SHIFT_J' => Tools::getValue('THDISABLEVS_CTRL_SHIFT_J', Configuration::get('THDISABLEVS_CTRL_SHIFT_J')),
            'THDISABLEVS_CTRL_U' => Tools::getValue('THDISABLEVS_CTRL_U', Configuration::get('THDISABLEVS_CTRL_U')),
            'THDISABLEVS_CTRL_S' => Tools::getValue('THDISABLEVS_CTRL_S', Configuration::get('THDISABLEVS_CTRL_S')),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function assignFrontVariables()
    {
        $data = array(
            'THDISABLEVS_RIGHT_CLICK' => Configuration::get('THDISABLEVS_RIGHT_CLICK'),
            'THDISABLEVS_F12' => Configuration::get('THDISABLEVS_F12'),
            'THDISABLEVS_CTRL_SHIFT_I' => Configuration::get('THDISABLEVS_CTRL_SHIFT_I'),
            'THDISABLEVS_CTRL_SHIFT_C' => Configuration::get('THDISABLEVS_CTRL_SHIFT_C'),
            'THDISABLEVS_CTRL_SHIFT_J' => Configuration::get('THDISABLEVS_CTRL_SHIFT_J'),
            'THDISABLEVS_CTRL_U' => Configuration::get('THDISABLEVS_CTRL_U'),
            'THDISABLEVS_CTRL_S' => Configuration::get('THDISABLEVS_CTRL_S'),
        );

        $this->context->smarty->assign($data);
    }

    public function hookDisplayFooter($params)
    {
        if (!Configuration::get('THDISABLEVS_LIVE_MODE') || $this->getPsVersion() == '7') {
            return false;
        }

        $this->assignFrontVariables();
        return $this->display(__FILE__, 'footer.tpl');
    }

    public function hookDisplayBeforeBodyClosingTag($params)
    {
        if (!Configuration::get('THDISABLEVS_LIVE_MODE') || $this->getPsVersion() == '6') {
            return false;
        }

        $this->assignFrontVariables();
        return $this->display(__FILE__, 'footer.tpl');
    }

    public function getPsVersion()
    {
        $full_version = _PS_VERSION_;
        return explode(".", $full_version)[1];
    }
}
