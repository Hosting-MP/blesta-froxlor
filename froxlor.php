<?php
use Blesta\Core\Util\Validate\Server;
/**
 *
 * Froxlor Module
 *
 * @package blesta
 * @subpackage blesta.components.modules.froxlor
 * @copyright Copyright (c) 2023, EDV Service Timo Stramann
 * @link https://edvservicetimostramann.de/ Timo Stramann
 */
class Froxlor extends Module
{

    /**
     * Initializes the module
     */
    public function __construct()
    {
        // Load configuration required by this module
        $this->loadConfig(dirname(__FILE__) . DS . 'config.json');

        // Load components required by this module
        Loader::loadComponents($this, ['Input']);

        // Load the language required by this module
        Language::loadLang('froxlor', null, dirname(__FILE__) . DS . 'language' . DS);
    }

    /**
     * Returns all tabs to display to an admin when managing a service whose
     * package uses this module
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @return array An array of tabs in the format of method => title.
     *  Example: array('methodName' => "Title", 'methodName2' => "Title2")
     */
    public function getAdminTabs($package)
    {
        return [
            'tabStats' => Language::_('Froxlor.tab_stats', true)
        ];
    }

    /**
     * Returns all tabs to display to a client when managing a service whose
     * package uses this module
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @return array An array of tabs in the format of method => title.
     *  Example: array('methodName' => "Title", 'methodName2' => "Title2")
     */
    public function getClientTabs($package)
    {
        return [
            'tabClientActions' => Language::_('Froxlor.tab_client_actions', true),
            'tabClientStats' => Language::_('Froxlor.tab_client_stats', true)
        ];
    }

    /**
     * Returns an array of available service deligation order methods. The module
     * will determine how each method is defined. For example, the method "first"
     * may be implemented such that it returns the module row with the least number
     * of services assigned to it.
     *
     * @return array An array of order methods in key/value paris where the key is
     *  the type to be stored for the group and value is the name for that option
     * @see Module::selectModuleRow()
     */
    public function getGroupOrderOptions()
    {
        return [
            'roundrobin' => Language::_('Froxlor.order_options.roundrobin', true),
            'first' => Language::_('Froxlor.order_options.first', true)
        ];
    }

    /**
     * Returns all fields used when adding/editing a package, including any
     * javascript to execute when the page is rendered with these fields.
     *
     * @param $vars stdClass A stdClass object representing a set of post fields
     * @return ModuleFields A ModuleFields object, containing the fields to
     *  render as well as any additional HTML markup to include
     */
    public function getPackageFields($vars = null)
    {
        Loader::loadHelpers($this, ['Html']);

        $fields = new ModuleFields();
        $fields->setHtml("
            <script type=\"text/javascript\">
                $(document).ready(function() {
                    // Set whether to show or hide the Admin limit option
                    $('[id^=froxlor_admin_limit_]').closest('li').hide();
                    if ($('#froxlor_account_type option:selected').val() == 'admin') {
                        $('#froxlor_package').closest('li').hide();
                        $('[id^=froxlor_admin_limit_]').closest('li').show();
                        $('#froxlor_sub_domains_enable').closest('li').hide();
                        $('#froxlor_domains_list').closest('li').hide();
                    }
                    $('#froxlor_account_type').change(function() {
                        if ($(this).val() == 'admin') {
                            $('#froxlor_package').closest('li').hide();
                            $('[id^=froxlor_admin_limit_]').closest('li').show();
                            $('#froxlor_sub_domains_enable').closest('li').hide();
                            $('#froxlor_domains_list').closest('li').hide();
                        } else {
                            $('#froxlor_package').closest('li').show();
							$('[id^=froxlor_admin_limit_]').closest('li').hide();
                            $('#froxlor_sub_domains_enable').closest('li').show();
                            if ($('input[name=\"meta[sub_domains]\"]:checked').val() == 'enable') {
                                $('#froxlor_domains_list').closest('li').show();
                            }
                        }
                    });
                    // Set whether to show or hide the Sub-Domains option
                    $('#froxlor_domains_list').closest('li').hide();
                    if (($('input[name=\"meta[sub_domains]\"]:checked').val() == 'enable') && ($('#froxlor_account_type option:selected').val() == 'customer')) {
                        $('#froxlor_domains_list').closest('li').show();
                    }
                    $('input[name=\"meta[sub_domains]\"]').change(function() {
                        if ($(this).val() == 'enable') {
                            $('#froxlor_domains_list').closest('li').show();
                        } else {
                            $('#froxlor_domains_list').closest('li').hide();
                        }
                    });
                });
            </script>
        ");

        // Set the account types
        $account_types = [
            'customer' => Language::_('Froxlor.package_fields.account_types_customer', true),
            'admin' => Language::_('Froxlor.package_fields.account_types_admin', true)
        ];

        // Set the account type as a selectable option
        $account_type = $fields->label(Language::_('Froxlor.package_fields.account_type', true), 'froxlor_account_type');
        $account_type->attach(
            $fields->fieldSelect(
                'meta[account_type]',
                $account_types,
				($vars->meta['account_type'] ?? null),
                ['id' => 'froxlor_account_type']
            )
        );
        $fields->setField($account_type);

        // Fetch all packages available for the given server or server group
        $module_row = null;
        if (isset($vars->module_group) && $vars->module_group == '') {
            if (isset($vars->module_row) && $vars->module_row > 0) {
                $module_row = $this->getModuleRow($vars->module_row);
            } else {
                $rows = $this->getModuleRows();
                if (isset($rows[0])) {
                    $module_row = $rows[0];
                }
                unset($rows);
            }
        } else {
            // Fetch the 1st server from the list of servers in the selected group
            $rows = $this->getModuleRows($vars->module_group);

            if (isset($rows[0])) {
                $module_row = $rows[0];
            }
            unset($rows);
        }

        // Get the Froxlor packages
        $packages = [];

        if ($module_row) {
            $packages = $this->getFroxlorPackages($module_row);
        }

        // Set the Froxlor package as a selectable option
        $package = $fields->label(Language::_('Froxlor.package_fields.package', true), 'froxlor_package');
        $package->attach(
            $fields->fieldSelect(
                'meta[package]',
                $packages,
                ($vars->meta['package'] ?? null),
                ['id' => 'froxlor_package']
            )
        );
        $fields->setField($package);

        // Admin account limits
        $admin_limits = [
            'customers',
            'diskspace',
            'traffic',
            'domains',
            'subdomains',
            'emails',
            'email_accounts',
            'email_forwarders',
            'ftps',
            'mysqls'
        ];

        foreach ($admin_limits as $admin_field) {
            $limit_field = $fields->label(Language::_('Froxlor.package_fields.admin_limit_' . $admin_field, true), 'froxlor_admin_limit_' . $admin_field);
            $limit_field->attach(
                $fields->fieldText(
                    'meta[' . $admin_field . ']',
					($vars->meta[$admin_field] ?? null),
                    ['id' => 'froxlor_admin_limit_' . $admin_field]
                )
            );
            $fields->setField($limit_field);
        }

		// Set whether to use a sub_domain
        $sub_domains = $fields->label(
            Language::_('Froxlor.package_fields.sub_domains', true),
            'froxlor_sub_domains'
        );
        $sub_domains_enable = $fields->label(
            Language::_('Froxlor.package_fields.sub_domains_enable', true),
            'froxlor_sub_domains_enable'
        );
        $sub_domains_disable = $fields->label(
            Language::_('Froxlor.package_fields.sub_domains_disable', true),
            'froxlor_sub_domains_disable'
        );
        $sub_domains->attach(
            $fields->fieldRadio(
                'meta[sub_domains]',
                'enable',
                ($vars->meta['sub_domains'] ?? null) == 'enable',
                ['id' => 'froxlor_sub_domains_enable'],
                $sub_domains_enable
            )
        );
        $sub_domains->attach(
            $fields->fieldRadio(
                'meta[sub_domains]',
                'disable',
                ($vars->meta['sub_domains'] ?? 'disable') == 'disable',
                ['id' => 'froxlor_sub_domains_disable'],
                $sub_domains_disable
            )
        );
        $fields->setField($sub_domains);

        // Set the domains to be used for sub-domains accounts
        $domains_list = $fields->label(
            Language::_('Froxlor.package_fields.domains_list', true),
            'froxlor_domains_list'
        );
        $domains_list->attach(
            $fields->fieldText(
                'meta[domains_list]',
                ($vars->meta['domains_list'] ?? null),
                ['id' => 'froxlor_domains_list']
            )
        );
        $domains_list_tooltip = $fields->tooltip(Language::_('Froxlor.package_fields.tooltip.domains_list', true));
        $domains_list->attach($domains_list_tooltip);
        $fields->setField($domains_list);

        // Set whether to allow api access on that account
        $api_allowed = $fields->label(
            Language::_('Froxlor.package_fields.api_allowed', true),
            'froxlor_api_allowed'
        );
        $api_allowed_enable = $fields->label(
            Language::_('Froxlor.package_fields.api_allowed_enable', true),
            'froxlor_api_allowed_enable'
        );
        $api_allowed_disable = $fields->label(
            Language::_('Froxlor.package_fields.api_allowed_disable', true),
            'froxlor_api_allowed_disable'
        );
        $api_allowed->attach(
            $fields->fieldRadio(
                'meta[api_allowed]',
                'enable',
                ($vars->meta['api_allowed'] ?? null) == 'enable',
                ['id' => 'froxlor_api_allowed_enable'],
                $api_allowed_enable
            )
        );
        $api_allowed->attach(
            $fields->fieldRadio(
                'meta[api_allowed]',
                'disable',
                ($vars->meta['api_allowed'] ?? 'disable') == 'disable',
                ['id' => 'froxlor_api_allowed_disable'],
                $api_allowed_disable
            )
        );
        $api_allowed_tooltip = $fields->tooltip(Language::_('Froxlor.package_fields.tooltip.api_allowed', true));
        $api_allowed->attach($api_allowed_tooltip);
        $fields->setField($api_allowed);

        return $fields;
    }

    /**
     * Validates input data when attempting to add a package, returns the meta
     * data to save when adding a package. Performs any action required to add
     * the package on the remote server. Sets Input errors on failure,
     * preventing the package from being added.
     *
     * @param array An array of key/value pairs used to add the package
     * @return array A numerically indexed array of meta fields to be stored for this package containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function addPackage(array $vars = null)
    {
        // Set rules to validate input data
        $this->Input->setRules($this->getPackageRules($vars));

        // Build meta data to return
        $meta = [];
        if ($this->Input->validates($vars)) {

            // If subdomains are not enabled, don't save anything for the domains list
            if (!isset($vars['meta']['sub_domains']) || $vars['meta']['sub_domains'] !== 'enable') {
                unset($vars['meta']['domains_list']);
            }

            // Return all package meta fields
            foreach ($vars['meta'] as $key => $value) {
                $meta[] = [
                    'key' => $key,
                    'value' => $value,
                    'encrypted' => 0
                ];
            }
        }
        return $meta;
    }

    /**
     * Validates input data when attempting to edit a package, returns the meta
     * data to save when editing a package. Performs any action required to edit
     * the package on the remote server. Sets Input errors on failure,
     * preventing the package from being edited.
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param array An array of key/value pairs used to edit the package
     * @return array A numerically indexed array of meta fields to be stored for this package containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function editPackage($package, array $vars = null)
    {
        // Set rules to validate input data
        $this->Input->setRules($this->getPackageRules($vars));

        // Build meta data to return
        $meta = [];
        if ($this->Input->validates($vars)) {

            // If subdomains are not enabled, don't save anything for the domains list
            if (!isset($vars['meta']['sub_domains']) || $vars['meta']['sub_domains'] !== 'enable') {
                unset($vars['meta']['domains_list']);
            }

            // Return all package meta fields
            foreach ($vars['meta'] as $key => $value) {
                $meta[] = [
                    'key' => $key,
                    'value' => $value,
                    'encrypted' => 0
                ];
            }
        }
        return $meta;
    }

    /**
     * Returns the rendered view of the manage module page
     *
     * @param mixed $module A stdClass object representing the module and its rows
     * @param array $vars An array of post data submitted to or on the manager module
     *  page (used to repopulate fields after an error)
     * @return string HTML content containing information to display when viewing the manager module page
     */
    public function manageModule($module, array &$vars)
    {
        // Load the view into this object, so helpers can be automatically added to the view
        $this->view = new View('manage', 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'froxlor' . DS);

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html', 'Widget']);

        $this->view->set('module', $module);

        return $this->view->fetch();
    }

    /**
     * Returns the rendered view of the add module row page
     *
     * @param array $vars An array of post data submitted to or on the add module
     *  row page (used to repopulate fields after an error)
     * @return string HTML content containing information to display when viewing the add module row page
     */
    public function manageAddRow(array &$vars)
    {
        // Load the view into this object, so helpers can be automatically added to the view
        $this->view = new View('add_row', 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'froxlor' . DS);

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html', 'Widget']);

        // Set unspecified checkboxes
        if (!empty($vars)) {
            if (empty($vars['use_ssl'])) {
                $vars['use_ssl'] = 'false';
            }
        }

        $this->view->set('vars', (object)$vars);
        return $this->view->fetch();
    }

    /**
     * Returns the rendered view of the edit module row page
     *
     * @param stdClass $module_row The stdClass representation of the existing module row
     * @param array $vars An array of post data submitted to or on the edit
     *  module row page (used to repopulate fields after an error)
     * @return string HTML content containing information to display when viewing the edit module row page
     */
    public function manageEditRow($module_row, array &$vars)
    {
        // Load the view into this object, so helpers can be automatically added to the view
        $this->view = new View('edit_row', 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'froxlor' . DS);

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html', 'Widget']);

        if (empty($vars)) {
            $vars = $module_row->meta;
        } else {
            // Set unspecified checkboxes
            if (empty($vars['use_ssl'])) {
                $vars['use_ssl'] = 'false';
            }
        }

        $this->view->set('vars', (object)$vars);
        return $this->view->fetch();
    }

    /**
     * Adds the module row on the remote server. Sets Input errors on failure,
     * preventing the row from being added. Returns a set of data, which may be
     * a subset of $vars, that is stored for this module row
     *
     * @param array $vars An array of module info to add
     * @return array A numerically indexed array of meta fields for the module row containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     */
    public function addModuleRow(array &$vars)
    {
        $meta_fields = ['server_name', 'host_name', 'api_key', 'api_secret', 'use_ssl', 'account_limit', 'notes'];
        $encrypted_fields = ['api_key', 'api_secret'];

        // Set unspecified checkboxes
        if (empty($vars['use_ssl'])) {
            $vars['use_ssl'] = 'false';
        }

        $this->Input->setRules($this->getRowRules($vars));

        // Validate module row
        if ($this->Input->validates($vars)) {
            // Build the meta data for this row
            $meta = [];
            foreach ($vars as $key => $value) {
                if (in_array($key, $meta_fields)) {
                    $meta[] = [
                        'key'=>$key,
                        'value'=>$value,
                        'encrypted'=>in_array($key, $encrypted_fields) ? 1 : 0
                    ];
                }
            }

            return $meta;
        }
    }

    /**
     * Edits the module row on the remote server. Sets Input errors on failure,
     * preventing the row from being updated. Returns a set of data, which may be
     * a subset of $vars, that is stored for this module row
     *
     * @param stdClass $module_row The stdClass representation of the existing module row
     * @param array $vars An array of module info to update
     * @return array A numerically indexed array of meta fields for the module row containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     */
    public function editModuleRow($module_row, array &$vars)
    {
        $meta_fields = ['server_name', 'host_name', 'api_key', 'api_secret', 'use_ssl', 'account_limit', 'account_count', 'notes'];
        $encrypted_fields = ['api_key', 'api_secret'];

        // Set unspecified checkboxes
        if (empty($vars['use_ssl'])) {
            $vars['use_ssl'] = 'false';
        }

        $this->Input->setRules($this->getRowRules($vars));

        // Validate module row
        if ($this->Input->validates($vars)) {
            // Build the meta data for this row
            $meta = [];
            foreach ($vars as $key => $value) {
                if (in_array($key, $meta_fields)) {
                    $meta[] = [
                        'key'=>$key,
                        'value'=>$value,
                        'encrypted'=>in_array($key, $encrypted_fields) ? 1 : 0
                    ];
                }
            }

            return $meta;
        }
    }

    /**
     * Deletes the module row on the remote server. Sets Input errors on failure,
     * preventing the row from being deleted.
     *
     * @param stdClass $module_row The stdClass representation of the existing module row
     */
    public function deleteModuleRow($module_row)
    {
    }

    /**
     * Returns the value used to identify a particular package service which has
     * not yet been made into a service. This may be used to uniquely identify
     * an uncreated services of the same package (i.e. in an order form checkout)
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param array $vars An array of user supplied info to satisfy the request
     * @return string The value used to identify this package service
     * @see Module::getServiceName()
     */
    public function getPackageServiceName($package, array $vars = null)
    {
        $domain = $this->getDomainNameFromData($package, $vars);

        return !empty($domain) ? $domain : null;
    }

    /**
     * Returns all fields to display to an admin attempting to add a service with the module
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param $vars stdClass A stdClass object representing a set of post fields
     * @return ModuleFields A ModuleFields object, containg the fields to render
     *  as well as any additional HTML markup to include
     */
    public function getAdminAddFields($package, $vars = null)
    {
        Loader::loadHelpers($this, ['Html']);

        $fields = new ModuleFields();

        if (($package->meta->account_type ?? 'customer') == 'customer') {
            // Show the subdomain fields when we are adding a service, but not when managing a pending service
            $show_subdomains = (isset($vars->froxlor_domain) && isset($vars->froxlor_sub_domain)) || !isset($vars->froxlor_domain);

            if (($package->meta->sub_domains ?? null) == 'enable' && $show_subdomains) {
                $domains = $this->getPackageAvailableDomains($package);

                // Create sub_domain label
                $sub_domain = $fields->label(Language::_('Froxlor.service_field.sub_domain', true), 'froxlor_sub_domain');
                // Create sub_domain field and attach to domain label
                $sub_domain->attach(
                    $fields->fieldText(
                        'froxlor_sub_domain',
                        ($vars->froxlor_sub_domain ?? null),
                        ['id' => 'froxlor_sub_domain']
                    )
                );
                // Set the label as a field
                $fields->setField($sub_domain);

                // Create domain label
                $domain = $fields->label(Language::_('Froxlor.service_field.domain', true), 'froxlor_domain');
                // Create domain field and attach to domain label
                $domain->attach(
                    $fields->fieldSelect(
                        'froxlor_domain',
                        $domains,
                        ($vars->froxlor_domain ?? null),
                        ['id' => 'froxlor_domain']
                    )
                );
                // Set the label as a field
                $fields->setField($domain);
            } else {
                // Create domain label
                $domain = $fields->label(Language::_('Froxlor.service_field.domain', true), 'froxlor_domain');
                // Create domain field and attach to domain label
                $domain->attach(
                    $fields->fieldText(
                        'froxlor_domain',
                        ($vars->froxlor_domain ?? null),
                        ['id' => 'froxlor_domain']
                    )
                );
                // Set the label as a field
                $fields->setField($domain);
            }
        }

        // Create username label
        $username = $fields->label(Language::_('Froxlor.service_field.username', true), 'froxlor_username');
        // Create username field and attach to username label
        $username->attach(
            $fields->fieldText('froxlor_username', ($vars->froxlor_username ?? null), ['id'=>'froxlor_username'])
        );
        // Add tooltip
        $tooltip = $fields->tooltip(Language::_('Froxlor.service_field.tooltip.username', true));
        $username->attach($tooltip);
        // Set the label as a field
        $fields->setField($username);

        // Create password label
        $password = $fields->label(Language::_('Froxlor.service_field.password', true), 'froxlor_password');
        // Create password field and attach to password label
        $password->attach(
            $fields->fieldPassword(
                'froxlor_password',
                ['id' => 'froxlor_password', 'value' => ($vars->froxlor_password ?? null)]
            )
        );
        // Add tooltip
        $tooltip = $fields->tooltip(Language::_('Froxlor.service_field.tooltip.password', true), ['class' => 'd-inline']);
        $password->attach($tooltip);
        // Set the label as a field
        $fields->setField($password);

        // Create textnotice label
        $textnotice = $fields->label(Language::_('Froxlor.service_field.textnotice', true), 'froxlor_textnotice');
        // Create textnotice field and attach to textnotice label
        $textnotice->attach(
            $fields->fieldTextarea(
                'froxlor_textnotice',
                ($vars->froxlor_textnotice ?? null),
                ['id' => 'froxlor_textnotice']
            )
        );
        // Set the label as a field
        $fields->setField($textnotice);

        return $fields;
    }

    /**
     * Returns all fields to display to a client attempting to add a service with the module
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param $vars stdClass A stdClass object representing a set of post fields
     * @return ModuleFields A ModuleFields object, containg the fields to render as well
     *  as any additional HTML markup to include
     */
    public function getClientAddFields($package, $vars = null)
    {
        Loader::loadHelpers($this, ['Html']);

        $fields = new ModuleFields();

        if (($package->meta->account_type ?? 'customer') == 'customer') {
            if (($package->meta->sub_domains ?? null) == 'enable') {
                $domains = $this->getPackageAvailableDomains($package);

                // Create sub_domain label
                $sub_domain = $fields->label(Language::_('Froxlor.service_field.sub_domain', true), 'froxlor_sub_domain');
                // Create sub_domain field and attach to domain label
                $sub_domain->attach(
                    $fields->fieldText(
                        'froxlor_sub_domain',
                        ($vars->froxlor_sub_domain ?? null),
                        ['id' => 'froxlor_sub_domain']
                    )
                );
                // Set the label as a field
                $fields->setField($sub_domain);

                // Create domain label
                $domain = $fields->label(Language::_('Froxlor.service_field.domain', true), 'froxlor_domain');
                // Create domain field and attach to domain label
                $domain->attach(
                    $fields->fieldSelect(
                        'froxlor_domain',
                        $domains,
                        ($vars->froxlor_domain ?? null),
                        ['id' => 'froxlor_domain']
                    )
                );
                // Set the label as a field
                $fields->setField($domain);
            } else {
                // Create domain label
                $domain = $fields->label(Language::_('Froxlor.service_field.domain', true), 'froxlor_domain');
                // Create domain field and attach to domain label
                $domain->attach(
                    $fields->fieldText(
                        'froxlor_domain',
                        ($vars->froxlor_domain ?? $vars->domain ?? null),
                        ['id' => 'froxlor_domain']
                    )
                );
                // Set the label as a field
                $fields->setField($domain);
            }
        }

        return $fields;
    }

    /**
     * Returns all fields to display to an admin attempting to edit a service with the module
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param $vars stdClass A stdClass object representing a set of post fields
     * @return ModuleFields A ModuleFields object, containg the fields to render as
     *  well as any additional HTML markup to include
     */
    public function getAdminEditFields($package, $vars = null)
    {
        Loader::loadHelpers($this, ['Html']);

        $fields = new ModuleFields();

        if (($package->meta->account_type ?? 'customer') == 'customer') {
            // Create domain label
            $domain = $fields->label(Language::_('Froxlor.service_field.domain', true), 'froxlor_domain');
            // Create domain field and attach to domain label
            $domain->attach(
                $fields->fieldText('froxlor_domain', ($vars->froxlor_domain ?? null), ['id'=>'froxlor_domain'])
            );
            $domain_tooltip = $fields->tooltip(Language::_('Froxlor.service_field.tooltip.domain', true));
            $domain->attach($domain_tooltip);
            // Set the label as a field
            $fields->setField($domain);
        }

        // Create username label
        $username = $fields->label(Language::_('Froxlor.service_field.username', true), 'froxlor_username');
        // Create username field and attach to username label
        $username->attach(
            $fields->fieldText('froxlor_username', ($vars->froxlor_username ?? null), ['id'=>'froxlor_username','disabled'=>'disabled'])
        );
        // Set the label as a field
        $fields->setField($username);

        // Create password label
        $password = $fields->label(Language::_('Froxlor.service_field.password', true), 'froxlor_password');
        // Create password field and attach to password label
        $password->attach(
            $fields->fieldPassword(
                'froxlor_password',
                ['id' => 'froxlor_password', 'value' => ($vars->froxlor_password ?? null)]
            )
        );
        // Set the label as a field
        $fields->setField($password);

        // Create textnotice label
        $textnotice = $fields->label(Language::_('Froxlor.service_field.textnotice', true), 'froxlor_textnotice');
        // Create textnotice field and attach to textnotice label
        $textnotice->attach(
            $fields->fieldTextarea(
                'froxlor_textnotice',
                ($vars->froxlor_textnotice ?? null),
                ['id' => 'froxlor_textnotice']
            )
        );
        // Set the label as a field
        $fields->setField($textnotice);

        return $fields;
    }

    /**
     * Attempts to validate service info. This is the top-level error checking method. Sets Input errors on failure.
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param array $vars An array of user supplied info to satisfy the request
     * @return bool True if the service validates, false otherwise. Sets Input errors when false.
     */
    public function validateService($package, array $vars = null)
    {
        $this->Input->setRules($this->getServiceRules($vars, $package));
        return $this->Input->validates($vars);
    }

    /**
     * Attempts to validate an existing service against a set of service info updates. Sets Input errors on failure.
     *
     * @param stdClass $service A stdClass object representing the service to validate for editing
     * @param array $vars An array of user-supplied info to satisfy the request
     * @return bool True if the service update validates or false otherwise. Sets Input errors when false.
     */
    public function validateServiceEdit($service, array $vars = null)
    {
        $this->Input->setRules($this->getServiceRules($vars, $service->package, true));
        return $this->Input->validates($vars);
    }

    /**
     * Returns the rule set for adding/editing a service
     *
     * @param array $vars A list of input vars
     * @param stdClass $package The service package
     * @param bool $edit True to get the edit rules, false for the add rules
     * @return array Service rules
     */
    private function getServiceRules(array $vars = null, stdClass $package = null, $edit = false)
    {
        $rules = [
            'froxlor_domain' => [
                'format' => [
                    // allow domain.com AND sub.domain.com AND (sub.)domain.com/folder
                    //'rule'=>["matches", "/^([a-z0-9]|[a-z0-9][a-z0-9\-]{0,61}[a-z0-9])(\.([a-z0-9]|[a-z0-9][a-z0-9\-]{0,61}[a-z0-9]))+(\/[a-zA-Z0-9]+)?$/i"],
                    'rule' => [[$this, 'validateHostName']],
                    'message' => Language::_('Froxlor.!error.froxlor_domain.format', true)
                ],
                'valid' => [
                    'rule' => [
                        function ($domain, $sub_domain) use ($package) {
                            // If a subdomain was provided, the domain must be one in our defined set
                            if ($sub_domain !== null) {
                                return in_array($domain, $this->getPackageAvailableDomains($package));
                            }

                            return true;
                        },
                        ['_linked' => 'froxlor_sub_domain']
                    ],
                    'message' => Language::_('Froxlor.!error.froxlor_domain.valid', true)
                ]
            ],
            'froxlor_sub_domain' => [
                'format' => [
                    'if_set' => true,
                    'rule' => ['matches', '/^((?!-)[a-z0-9-]{1,63}(?<!-))$/i'],
                    'message' => Language::_('Froxlor.!error.froxlor_sub_domain.format', true)
                ],
                'availability' => [
                    'if_set' => true,
                    'rule' => [
                        function ($sub_domain, $domain) {
                            return $this->checkSubDomainAvailability($sub_domain, $domain);
                        },
                        ['_linked' => 'froxlor_domain']
                    ],
                    'message' => Language::_('Froxlor.!error.froxlor_sub_domain.availability', true)
                ]
            ],
            'froxlor_username' => [
                'format' => [
                    'if_set' => true,
                    'rule' => ['matches', '/^[a-z]([a-z0-9])*$/i'],
                    'message' => Language::_('Froxlor.!error.froxlor_username.format', true)
                ],
                'length' => [
                    'if_set' => true,
                    'rule' => ['betweenLength', 1, 16],
                    'message' => Language::_('Froxlor.!error.froxlor_username.length', true)
                ]
            ],
            'froxlor_password' => [
                'valid' => [
                    'if_set' => true,
                    'rule' => ['isPassword', 8],
                    'message' => Language::_('Froxlor.!error.froxlor_password.valid', true),
                    'last' => true
                ],
            ],
            'froxlor_confirm_password' => [
                'matches' => [
                    'if_set' => true,
                    'rule' => ['compares', '==', ($vars['froxlor_password'] ?? '')],
                    'message' => Language::_('Froxlor.!error.froxlor_password.matches', true)
                ]
            ]
        ];

        // Set the values that may be empty
        $empty_values = ['froxlor_domain', 'froxlor_username', 'froxlor_password'];

        if ($edit) {
            // If this is an edit and no password given then don't evaluate password
            // since it won't be updated
            if (!array_key_exists('froxlor_password', $vars) || $vars['froxlor_password'] == '') {
                unset($rules['froxlor_password']);
            }

            // Validate domain if given
            $rules['froxlor_domain']['format']['if_set'] = true;
        }

        // Remove rules on empty fields
        foreach ($empty_values as $value) {
            if (empty($vars[$value])) {
                unset($rules[$value]);
            }
        }

        return $rules;
    }

     /**
     * Validates that the given hostname is valid
     *
     * @param string $host_name The host name to validate
     * @return bool True if the hostname is valid, false otherwise
     */
    public function validateHostName($host_name)
    {
        $validator = new Server();
        return $validator->isDomain($host_name) || $validator->isIp($host_name);
    }

     /**
     * Validates that the given sub-domain and domain combination is available
     *
     * @param string $sub_domain The sub domain
     * @param string $domain The main domain
     * @return bool True if the sub-domain is available, false otherwise
     */
    public function checkSubDomainAvailability($sub_domain, $domain)
    {
        return !checkdnsrr($sub_domain . '.' . $domain, 'A');
    }

     /**
     * Retrieves the domain name from the given vars for this package
     *
     * @param stdClass $package An stdClass object representing the package
     * @param array $vars An array of input data including:
     *  - froxlor_domain The froxlor domain name
     *  - froxlor_sub_domain The froxlor sub domain (optional)
     * @return string The name of the domain name
     */
    private function getDomainNameFromData(stdClass $package, array $vars = null)
    {
        Loader::loadHelpers($this, ['Html']);

        $name = $this->formatDomain(($vars['froxlor_domain'] ?? null));
        if ((($package->meta->sub_domains ?? null) == 'enable') && ($vars['froxlor_sub_domain'] ?? null))
        {
            $name = $this->formatDomain($vars['froxlor_sub_domain'] . '.' . $vars['froxlor_domain']);
        }

        return $name;
    }

    /**
     * Adds the service to the remote server. Sets Input errors on failure,
     * preventing the service from being added.
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param array $vars An array of user supplied info to satisfy the request
     * @param stdClass $parent_package A stdClass object representing the parent
     *  service's selected package (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent
     *  service of the service being added (if the current service is an addon service
     *  service and parent service has already been provisioned)
     * @param string $status The status of the service being added. These include:
     *  - active
     *  - canceled
     *  - pending
     *  - suspended
     * @return array A numerically indexed array of meta fields to be stored for this service containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function addService(
        $package,
        array $vars = null,
        $parent_package = null,
        $parent_service = null,
        $status = 'pending'
    ) {
        // Get module row
        $row = $this->getModuleRow();

        if (!$row) {
            $this->Input->setErrors(
                ['module_row' => ['missing' => Language::_('Froxlor.!error.module_row.missing', true)]]
            );
            return;
        }

        $api = $this->getApi($row->meta->host_name, $row->meta->api_key, $row->meta->api_secret, $row->meta->use_ssl);

        // Generate username/password
        if (array_key_exists('froxlor_domain', $vars) || (($package->meta->account_type ?? 'customer') == 'admin')) {
            // Load helpders to get the client info from Blesta
            Loader::loadModels($this, ['Clients', 'Contacts']);

            // Format domain in case of subdomain
            if (array_key_exists('froxlor_domain', $vars)) {
                $vars['froxlor_domain'] = $this->formatDomain($vars['froxlor_domain']);
            }

            // Generate a username
            if (empty($vars['froxlor_username'])) {
                if (($package->meta->account_type ?? 'customer') == 'customer') {
                    $vars['froxlor_username'] = $this->generateUsername();
                } else {
                    $vars['froxlor_username'] = $this->generateUsername(true);
                }
            }

            // Generate a password
            if (empty($vars['froxlor_password'])) {
                $vars['froxlor_password'] = $this->generatePassword();
            }

            // Set client's information
            if (isset($vars['client_id']) && ($client = $this->Clients->get($vars['client_id'], false))) {
                $vars['froxlor_first_name'] = $client->first_name;
                $vars['froxlor_last_name'] = $client->last_name;
                $vars['froxlor_company'] = $client->company;
                $vars['froxlor_email'] = $client->email;
                $vars['froxlor_street'] = $client->address1;
                $vars['froxlor_city'] = $client->city;
                $vars['froxlor_zipcode'] = $client->zip;
                $vars['froxlor_customernumber'] = $client->id_code;

                // The phone number can be retrieved from the Contacts model and not the Clients model
                if ($contact_numbers = $this->Contacts->getNumbers($client->contact_id)) {					
                    if(isset($contact_numbers[0]) ? $primary_phone_number = $contact_numbers[0]->number : false) {
                        $vars['froxlor_phone'] = $primary_phone_number;
                    }
                }
            }
        }

        $params = $this->getFieldsFromInput((array)$vars, $package);

        $this->validateService($package, $vars);

        if ($this->Input->errors()) {
            return;
        }

        // Only provision the service if 'use_module' is true
        if ($vars['use_module'] == 'true') {
            $masked_params = $params;
            $masked_params['new_customer_password'] = '***';

            if (($package->meta->account_type ?? 'customer') == 'customer') {
                $this->log($row->meta->host_name . '|Customers.add', serialize($masked_params), 'input', true);
                unset($masked_params);
                if(!($new_account = $this->parseResponse($api->request('Customers.add', $params), $api->getLastStatusCode()))){ return; }
            } else {
                $this->log($row->meta->host_name . '|Admins.add', serialize($masked_params), 'input', true);
                unset($masked_params);
               if(!($new_account = $this->parseResponse($api->request('Admins.add', $params), $api->getLastStatusCode()))){ return; }
            }

            if ($this->Input->errors()) {
                return;
            }

            // Add domain to customer
            if (($package->meta->account_type ?? 'customer') == 'customer') {
                $params = [
                    'domain' => $this->getDomainNameFromData($package, $vars),
                    'customerid' => $new_account['customerid']
                ];
                $this->log($row->meta->host_name . '|Domains.add', serialize($params), 'input', true);
                $this->parseResponse($api->request('Domains.add', $params), $api->getLastStatusCode());

                if ($this->Input->errors()) {
                    return;
                }
            }

            // Update the number of accounts on the server
            $this->updateAccountCount($row);
        }

        // Return service fields
        if (($package->meta->account_type ?? 'customer') == 'customer') {
            return [
                [
                    'key' => 'froxlor_domain',
                    'value' => $this->getDomainNameFromData($package, $vars),
                    'encrypted' => 0
                ],
                [
                    'key' => 'froxlor_username',
                    'value' => $vars['froxlor_username'],
                    'encrypted' => 0
                ],
                [
                    'key' => 'froxlor_password',
                    'value' => $vars['froxlor_password'],
                    'encrypted' => 1
                ],
                [
                    'key' => 'froxlor_textnotice',
                    'value' => $vars['froxlor_textnotice'],
                    'encrypted' => 1
                ]
            ];
        } else {
			return [
                [
                    'key' => 'froxlor_username',
                    'value' => $vars['froxlor_username'],
                    'encrypted' => 0
                ],
                [
                    'key' => 'froxlor_password',
                    'value' => $vars['froxlor_password'],
                    'encrypted' => 1
                ],
                [
                    'key' => 'froxlor_textnotice',
                    'value' => $vars['froxlor_textnotice'],
                    'encrypted' => 1
                ]
            ];
		}
    }

    /**
     * Edits the service on the remote server. Sets Input errors on failure,
     * preventing the service from being edited.
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $vars An array of user supplied info to satisfy the request
     * @param stdClass $parent_package A stdClass object representing the parent
     *  service's selected package (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent
     *  service of the service being edited (if the current service is an addon service)
     * @return array A numerically indexed array of meta fields to be stored for this service containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function editService($package, $service, array $vars = null, $parent_package = null, $parent_service = null)
    {
        // Get module row
        $row = $this->getModuleRow();

        // Get Froxlor API
        $api = $this->getApi($row->meta->host_name, $row->meta->api_key, $row->meta->api_secret, $row->meta->use_ssl);

        // Validate service
        $this->validateServiceEdit($service, $vars);

        if ($this->Input->errors()) {
            return;
        }

        $service_fields = $this->serviceFieldsToObject($service->fields);

        // Remove password if not being updated
        if (isset($vars['froxlor_password']) && $vars['froxlor_password'] == '') {
            unset($vars['froxlor_password']);
        }

        $delta = [];
		$fields_changed = [];
        // Only update the service if 'use_module' is true
        if ($vars['use_module'] == 'true') {
            // Check for fields that changed
            foreach ($vars as $key => $value) {
                if (!array_key_exists($key, (array) $service_fields) || $vars[$key] != $service_fields->$key) {
                    $delta[$key] = $value;
                }
            }

            $params['loginname'] = $service_fields->froxlor_username;
            if (isset($delta['froxlor_textnotice'])) {
                // If notes are empty disable them to not show an empty notes field
                if($delta['froxlor_textnotice'] !== ''){
                    $params['custom_notes_show'] = true;
                } else {
                    $params['custom_notes_show'] = false;
                }
                $params['custom_notes'] = $delta['froxlor_textnotice'];
                $fields_changed[] = 'froxlor_textnotice';
            }

            if (($package->meta->account_type ?? 'customer') == 'customer') {
                // Do not put password in log so obfuscate it here
                if (isset($delta['froxlor_password'])) {
                    $params['new_customer_password'] = $delta['froxlor_password'];
                    $fields_changed[] = 'froxlor_password';
                    $this->log($row->meta->host_name . '|Customers.update', '***', 'input', true);
                } else {
                    $this->log(
                        $row->meta->host_name . '|Customers.update',
                        serialize($params),
                        'input',
                        true
                    );
                }
                if(!$this->parseResponse($api->request('Customers.update', $params), $api->getLastStatusCode())){ return; }

                // Update domain too if changed
                if (isset($delta['froxlor_domain'])) {
                    // Get old domain to delete
                    $params_oldDomain = [
                        'domainname' => $service_fields->froxlor_domain
                    ];
                    $this->log($row->meta->host_name . '|Domains.delete', serialize($params_oldDomain), 'input', true);
                    if(!$this->parseResponse($api->request('Domains.delete', $params_oldDomain), $api->getLastStatusCode())){ return; }
                    // Get new domain to create
                    $params_newDomain = [
                        'loginname' => $service_fields->froxlor_username,
                        'domain' => $this->formatDomain(($delta['froxlor_domain'] ?? null))
                    ];
                    $this->log($row->meta->host_name . '|Domains.add', serialize($params_newDomain), 'input', true);
                    if(!$this->parseResponse($api->request('Domains.add', $params_newDomain), $api->getLastStatusCode())){ return; }
                    $fields_changed[] = 'froxlor_domain';
                }

            } else {
                // Do not put password in log so obfuscate it here
                if (isset($delta['froxlor_password'])) {
                    $params['admin_password'] = $delta['froxlor_password'];
                    $fields_changed[] = 'froxlor_password';
                    $this->log($row->meta->host_name . '|Admins.update', '***', 'input', true);
                } else {
                    $this->log($row->meta->host_name . '|Admins.update', serialize($params), 'input', true);
                }
                if(!$this->parseResponse($api->request('Admins.update', $params), $api->getLastStatusCode())){ return; }
            }
        }

        // Set fields to update locally
        foreach ($fields_changed as $field) {
            if (property_exists($service_fields, $field) && isset($vars[$field])) {
                $service_fields->{$field} = $delta[$field];
            }
        }

        // Return all the service fields
        $fields = [];
        $encrypted_fields = ['froxlor_password'];
        foreach ($service_fields as $key => $value) {
            $fields[] = ['key' => $key, 'value' => $value, 'encrypted' => (in_array($key, $encrypted_fields) ? 1 : 0)];
        }

        return $fields;
    }

    /**
     * Suspends the service on the remote server. Sets Input errors on failure,
     * preventing the service from being suspended.
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param stdClass $parent_package A stdClass object representing the parent
     *  service's selected package (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent
     *  service of the service being suspended (if the current service is an addon service)
     * @return mixed null to maintain the existing meta fields or a numerically
     *  indexed array of meta fields to be stored for this service containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function suspendService($package, $service, $parent_package = null, $parent_service = null)
    {
        if ($row = $this->getModuleRow()) {
            $api = $this->getApi($row->meta->host_name, $row->meta->api_key, $row->meta->api_secret, $row->meta->use_ssl);

            $service_fields = $this->serviceFieldsToObject($service->fields);

            $params = [
                'loginname' => $service_fields->froxlor_username,
                'deactivated' => 1
            ];

            if (($package->meta->account_type ?? 'customer') == 'customer') {
                $this->log(
                    $row->meta->host_name . '|Customers.update',
                    serialize($params),
                    'input',
                    true
                );
                $this->parseResponse($api->request('Customers.update', $params), $api->getLastStatusCode());
            } else {
                $this->log(
                    $row->meta->host_name . '|Admins.update',
                    serialize($params),
                    'input',
                    true
                );
                $this->parseResponse($api->request('Admins.update', $params), $api->getLastStatusCode());
            }
        }

        return null;
    }

    /**
     * Unsuspends the service on the remote server. Sets Input errors on failure,
     * preventing the service from being unsuspended.
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param stdClass $parent_package A stdClass object representing the parent
     *  service's selected package (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent
     *  service of the service being unsuspended (if the current service is an addon service)
     * @return mixed null to maintain the existing meta fields or a numerically
     *  indexed array of meta fields to be stored for this service containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function unsuspendService($package, $service, $parent_package = null, $parent_service = null)
    {
        if ($row = $this->getModuleRow()) {
            $api = $this->getApi($row->meta->host_name, $row->meta->api_key, $row->meta->api_secret, $row->meta->use_ssl);

            $service_fields = $this->serviceFieldsToObject($service->fields);

            $params = [
                'loginname' => $service_fields->froxlor_username,
                'deactivated' => 0
            ];

            if (($package->meta->account_type ?? 'customer') == 'customer') {
                $this->log(
                    $row->meta->host_name . '|Customers.update',
                    serialize($params),
                    'input',
                    true
                );
                $this->parseResponse($api->request('Customers.update', $params), $api->getLastStatusCode());
            } else {
                $this->log(
                    $row->meta->host_name . '|Admins.update',
                    serialize($params),
                    'input',
                    true
                );
                $this->parseResponse($api->request('Admins.update', $params), $api->getLastStatusCode());
            }
        }

        return null;
    }

    /**
     * Cancels the service on the remote server. Sets Input errors on failure,
     * preventing the service from being canceled.
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param stdClass $parent_package A stdClass object representing the parent
     *  service's selected package (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent
     *  service of the service being canceled (if the current service is an addon service)
     * @return mixed null to maintain the existing meta fields or a numerically
     *  indexed array of meta fields to be stored for this service containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function cancelService($package, $service, $parent_package = null, $parent_service = null)
    {
        if ($row = $this->getModuleRow()) {
            // Connect to API
            $api = $this->getApi(
                $row->meta->host_name,
                $row->meta->api_key,
                $row->meta->api_secret,
                $row->meta->use_ssl
            );

            $service_fields = $this->serviceFieldsToObject($service->fields);

            if (($package->meta->account_type ?? 'customer') == 'customer') {
                // Set the account name to delete and to erase all user data
                $params = [
                    'loginname' => $service_fields->froxlor_username,
                    'delete_userfiles' => 1
                ];
                $this->log(
                    $row->meta->host_name . '|Customers.delete',
                    serialize($params),
                    'input',
                    true
                );
                $this->parseResponse($api->request('Customers.delete', $params), $api->getLastStatusCode());
            } else {
                $params = [
                    'loginname' => $service_fields->froxlor_username
                ];
                $this->log(
                    $row->meta->host_name . '|Admins.delete',
                    serialize($params),
                    'input',
                    true
                );
                $this->parseResponse($api->request('Admins.delete', $params), $api->getLastStatusCode());
            }
        }

        return null;
    }

    /**
     * Updates the package for the service on the remote server. Sets Input
     * errors on failure, preventing the service's package from being changed.
     *
     * @param stdClass $package_from A stdClass object representing the current package
     * @param stdClass $package_to A stdClass object representing the new package
     * @param stdClass $service A stdClass object representing the current service
     * @param stdClass $parent_package A stdClass object representing the parent
     *  service's selected package (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent
     *  service of the service being changed (if the current service is an addon service)
     * @return mixed null to maintain the existing meta fields or a numerically
     *  indexed array of meta fields to be stored for this service containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function changeServicePackage(
        $package_from,
        $package_to,
        $service,
        $parent_package = null,
        $parent_service = null
    ) {
        // Nothing to do

        return null;
    }

    /**
     * Fetches the HTML content to display when viewing the service info in the
     * admin interface.
     *
     * @param stdClass $service A stdClass object representing the service
     * @param stdClass $package A stdClass object representing the service's package
     * @return string HTML content containing information to display when viewing the service info
     */
    public function getAdminServiceInfo($service, $package)
    {
        // Get module row
        $module_row = $this->getModuleRow();

        // Load the view into this object, so helpers can be automatically added to the view
        $this->view = new View('admin_service_info', 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'froxlor' . DS);

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html']);

        // Fetch service fields
        $service_fields = $this->serviceFieldsToObject($service->fields);

        $this->view->set('module_row', $module_row);
        $this->view->set('package', $package);
        $this->view->set('service', $service);
        $this->view->set('service_fields', $service_fields);

        return $this->view->fetch();
    }

    /**
     * Fetches the HTML content to display when viewing the service info in the
     * client interface.
     *
     * @param stdClass $service A stdClass object representing the service
     * @param stdClass $package A stdClass object representing the service's package
     * @return string HTML content containing information to display when viewing the service info
     */
    public function getClientServiceInfo($service, $package)
    {
        // Get module row
        $module_row = $this->getModuleRow();

        // Load the view into this object, so helpers can be automatically added to the view
        $this->view = new View('client_service_info', 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'froxlor' . DS);

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html']);

        // Fetch service fields
        $service_fields = $this->serviceFieldsToObject($service->fields);

        $this->view->set('module_row', $module_row);
        $this->view->set('package', $package);
        $this->view->set('service', $service);
        $this->view->set('service_fields', $service_fields);

        return $this->view->fetch();
    }

    /**
     * Statistics tab (bandwidth/disk usage)
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     */
    public function tabStats($package, $service, array $get = null, array $post = null, array $files = null)
    {
        $this->view = new View('tab_stats', 'default');

        // Get module row
        $row = $this->getModuleRow();

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html']);

        // Get service fields
        $service_fields = $this->serviceFieldsToObject($service->fields);

        // Initialize API
        $api = $this->getApi(
            $row->meta->host_name,
            $row->meta->api_key,
            $row->meta->api_secret,
            $row->meta->use_ssl
        );

        // Get client information
        if (($package->meta->account_type ?? 'customer') == 'customer') {
            $this->log($row->meta->host_name . '|Customers.get', serialize(['loginname' => $service_fields->froxlor_username]), 'input', true);
            $stats = $this->parseResponse($api->request('Customers.get', ['loginname' => $service_fields->froxlor_username]), $api->getLastStatusCode());
        } else {
            $this->log($row->meta->host_name . '|Admins.get', serialize(['loginname' => $service_fields->froxlor_username]), 'input', true);
            $stats = $this->parseResponse($api->request('Admins.get', ['loginname' => $service_fields->froxlor_username]), $api->getLastStatusCode());
        }

        $this->view->set('stats', $stats);

        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'froxlor' . DS);
        return $this->view->fetch();
    }

    /**
     * Client Statistics tab (bandwidth/disk usage)
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     */
    public function tabClientStats($package, $service, array $get = null, array $post = null, array $files = null)
    {
        $this->view = new View('tab_client_stats', 'default');

        // Get module row
        $row = $this->getModuleRow();

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html']);

        // Get service fields
        $service_fields = $this->serviceFieldsToObject($service->fields);

        // Initialize API
        $api = $this->getApi(
            $row->meta->host_name,
            $row->meta->api_key,
            $row->meta->api_secret,
            $row->meta->use_ssl
        );

        // Get client information
        if (($package->meta->account_type ?? 'customer') == 'customer') {
            $this->log($row->meta->host_name . '|Customers.get', serialize(['loginname' => $service_fields->froxlor_username]), 'input', true);
            $stats = $this->parseResponse($api->request('Customers.get', ['loginname' => $service_fields->froxlor_username]), $api->getLastStatusCode());
        } else {
            $this->log($row->meta->host_name . '|Admins.get', serialize(['loginname' => $service_fields->froxlor_username]), 'input', true);
            $stats = $this->parseResponse($api->request('Admins.get', ['loginname' => $service_fields->froxlor_username]), $api->getLastStatusCode());
        }

        $this->view->set('stats', $stats);

        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'froxlor' . DS);
        return $this->view->fetch();
    }

    /**
     * Client Actions (reset password)
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     */
    public function tabClientActions($package, $service, array $get = null, array $post = null, array $files = null)
    {
        $this->view = new View('tab_client_actions', 'default');
        $this->view->base_uri = $this->base_uri;
        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html']);

        $service_fields = $this->serviceFieldsToObject($service->fields);

        // Perform the password reset
        if (!empty($post)) {
            Loader::loadModels($this, ['Services']);
            $data = [
                'froxlor_password' => ($post['froxlor_password'] ?? null),
                'froxlor_confirm_password' => ($post['froxlor_confirm_password'] ?? null)
            ];
            $this->Services->edit($service->id, $data);

            if ($this->Services->errors()) {
                $this->Input->setErrors($this->Services->errors());
            }

            $vars = (object)$post;
        }

        $this->view->set('service_fields', $service_fields);
        $this->view->set('service_id', $service->id);
        $this->view->set('vars', ($vars ?? new stdClass()));

        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'froxlor' . DS);
        return $this->view->fetch();
    }

    /**
     * Retrieves the accounts on the server
     *
     * @param stdClass $api The Froxlor API
     * @return mixed The number of Froxlor accounts on the server, or false on error
     */
    private function getAccountCount($api)
    {
        $accounts = null;

        try {
            $this->log('non-module-row-function|Customers.listing', serialize(""), 'input', true);
            $output = $this->parseResponse($api->request('Customers.listing'), $api->getLastStatusCode());

            if (isset($output['count'])) {
                $accounts = $output['count'];
            }
        } catch (Exception $e) {
            // Nothing to do
        }

        return $accounts;
    }

    /**
     * Updates the module row meta number of accounts
     *
     * @param stdClass $module_row A stdClass object representing a single server
     */
    private function updateAccountCount($module_row)
    {
        $api = $this->getApi(
            $module_row->meta->host_name,
            $module_row->meta->api_key,
            $module_row->meta->api_secret,
            $module_row->meta->use_ssl
        );

        // Get the number of accounts on the server
        $count = $this->getAccountCount($api);

        if (!is_null($count)) {
            // Update the module row account list
            Loader::loadModels($this, ['ModuleManager']);
            $vars = $this->ModuleManager->getRowMeta($module_row->id);

            if ($vars) {
                $vars->account_count = $count;
                $vars = (array)$vars;

                $this->ModuleManager->editRow($module_row->id, $vars);
            }
        }
    }

    /**
     * Validates whether or not the connection details are valid by attempting to fetch
     * the number of accounts that currently reside on the server
     *
     * @return bool True if the connection is valid, false otherwise
     */
    public function validateConnection($api_secret, $host_name, $api_key, $use_ssl, &$account_count)
    {
        try {
            $api = $this->getApi(
                $host_name,
                $api_key,
                $api_secret,
                $use_ssl
            );

            // Get customers listing count
            $this->log($host_name . '|Customers.listingCount', serialize(""), 'input', true);
            $count = $this->parseResponse($api->request('Customers.listingCount'), $api->getLastStatusCode());

            if (is_numeric($count)) {
                $account_count = $count;
                return true;
            }
        } catch (Exception $e) {
            // Trap any errors encountered, could not validate connection
        }

        return false;
    }

    /**
     * Test if an account with this username already exists
     *
     * @param string $accountName Name of the account to be checked
     * @return boolean true if account exists and false if no account with that name could be found
     */
    private function checkAccountExists($accountName)
    {

        if($accountName === ''){
            // do not allow empty value so mark this account as existant to force a regeneration
            $accountExists = true;
        } else {
            // Get module row
            $module_row = $this->getModuleRow();

            $api = $this->getApi(
                $module_row->meta->host_name,
                $module_row->meta->api_key,
                $module_row->meta->api_secret,
                $module_row->meta->use_ssl
            );

            $accountExists = false;

            try {
                $this->log($module_row->meta->host_name . '|Customers.get', serialize(['loginname' => $accountName]), 'input', true);
                $output = $this->parseResponse($api->request('Customers.get', ['loginname' => $accountName]), $api->getLastStatusCode());

                $accountExists = isset($output['id']) ? true : false;

            } catch (Exception $e) {
                // Nothing to do
            }
        }

        return $accountExists;
    }

    /**
     * Generates a username from the given host name
     *
     * @param string $host_name The host name to use to generate the username
     * @return string The username generated from the given hostname
     */
    private function generateLoginUsername($host_name, $max_length = 8)
    {
        $username = '';
        while($this->checkAccountExists($username)){
            // Remove everything except letters and numbers from the domain
            $username = preg_replace('/[^a-z0-9]/i', '', $host_name);

            // Remove the 'test' string if it appears in the beginning
            if (strpos($username, 'test') === 0) {
                $username = substr($username, 4);
            }

            // Ensure no number appears in the beginning
            $username = ltrim($username, '0123456789');

            // All customer accounts start with a "c" char
            $username = substr_replace($username, 'c', 0, 0);

            $length = strlen($username);
            $pool = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $pool_size = strlen($pool);

            if ($length < $max_length) {
                for ($i=$length; $i<$max_length; $i++) {
                    $username .= substr($pool, mt_rand(0, $pool_size-1), 1);
                }
                $length = strlen($username);
            }

            $username = substr($username, 0, min($length, $max_length));
        }

        return $username;
    }

    /**
    * Generate an username, using a cryptographically secure
    * pseudorandom number generator (random_int)
    *
    * @param int $max_length  Maximum characters we want
    * @param string $keyspace A string of all possible characters
    *                         to select from
    * @return string          The generated username
    */
    function generateUsername(bool $isAdminUsername = false, int $max_length = 8, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string {
        $username = '';
        while($this->checkAccountExists($username)){
            if ($max_length < 1) {
                throw new \RangeException("Length must be a positive integer");
            }
            $pieces = [];
            // All accounts start with an "a" char
			if($isAdminUsername){
                $pieces []= "a";
			} else {
                $pieces []= "c";
			}
            $max = mb_strlen($keyspace, '8bit') - 1;
            for ($i = 1; $i < $max_length; ++$i) {
                $pieces []= $keyspace[random_int(0, $max)];
            }
            $username = implode('', $pieces);
        }
        return $username;
    }

     /**
     * Retrieves all of the available domains for subdomain provisioning for a specific package
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @return mixed A key/value array of available domains
     */
    private function getPackageAvailableDomains(stdClass $package)
    {
        if (!empty($package->meta->domains_list)) {
            return $this->parseElementsFromCsv($package->meta->domains_list);
        }

        return [];
    }

    /**
     * Parses out the given elements from a CSV
     *
     * @param string $csv The CSV list
     * @return array An array of elements from the list
     */
    private function parseElementsFromCsv($csv)
    {
        $items = [];

        foreach (explode(',', $csv) as $item) {
            $item = strtolower(trim($item));

            // Skip any blank items
            if (empty($item)) {
                continue;
            }

            $items[$item] = $item;
        }

        return $items;
    }

    /**
     * Generates a password based on Froxlor settings for min-length, included characters, etc.
     *
     * @return string The generated password
     */
    private function generatePassword()
    {
        // Get module row
        $module_row = $this->getModuleRow();

        // Initialize API
        $api = $this->getApi(
            $module_row->meta->host_name,
            $module_row->meta->api_key,
            $module_row->meta->api_secret,
            $module_row->meta->use_ssl
        );

        // Generate random password
        $password = null;

        try {
            $this->log($module_row->meta->host_name . '|Froxlor.generatePassword', serialize($module_row->meta->host_name), 'input', true);
            $password = $this->parseResponse($api->request('Froxlor.generatePassword'), $api->getLastStatusCode());
            //$this->log($module_row->meta->host_name . '|Froxlor.generatePassword', serialize($password), 'output', !empty($password));
        } catch (Exception $e) {
            // API request failed
        }

        return $password;
    }

    /**
     * Returns an array of service field to set for the service using the given input
     *
     * @param array $vars An array of key/value input pairs
     * @param stdClass $package A stdClass object representing the package for the service
     * @return array An array of key/value pairs representing service fields
     */
    private function getFieldsFromInput(array $vars, $package)
    {

        if (($package->meta->account_type ?? 'customer') == 'customer') {
            $fields = [
                'new_loginname' => $vars['froxlor_username'] ?? null,
                'new_customer_password' => $vars['froxlor_password'] ?? null,
                'api_allowed' => isset($package->meta->api_allowed) ? (($package->meta->api_allowed == 'enable') ? true : false) : null,
                'firstname' => $vars['froxlor_first_name'] ?? null,
                'name' => $vars['froxlor_last_name'] ?? null,
                'company' => $vars['froxlor_company'] ?? 'NA',
                'customernumber' => $vars['froxlor_customernumber'] ?? null,
                'hosting_plan_id' => $package->meta->package,
                'email' => $vars['froxlor_email'] ?? null,
                'street' => $vars['froxlor_street'] ?? null,
                'city' => $vars['froxlor_city'] ?? null,
                'zipcode' => $vars['froxlor_zipcode'] ?? null,
                'phone' => $vars['froxlor_phone'] ?? null,
                'custom_notes' => $vars['froxlor_textnotice'] ?? null,
                'custom_notes_show' => isset($vars['froxlor_textnotice']) ? (($vars['froxlor_textnotice'] !== '') ? true : false) : null
            ];
        } else {
			$fields = [
                'name' => (isset($vars['froxlor_first_name']) && isset($vars['froxlor_last_name'])) ? $vars['froxlor_first_name'] . ' ' . $vars['froxlor_last_name'] : null,
                'email' => $vars['froxlor_email'] ?? null,
                'new_loginname' => $vars['froxlor_username'] ?? null,
                'admin_password' => $vars['froxlor_password'] ?? null,
                'api_allowed' => isset($package->meta->api_allowed) ? (($package->meta->api_allowed == 'enable') ? true : false) : null,
                'custom_notes' => $vars['froxlor_textnotice'] ?? null,
                'custom_notes_show' => isset($vars['froxlor_textnotice']) ? (($vars['froxlor_textnotice'] !== '') ? true : false) : null
            ];
            // package options which can be unlimited (specified as -1 in blesta)
            $ul_list = [
                'customers',
                'diskspace',
                'traffic',
                'domains',
                'subdomains',
                'emails',
                'email_accounts',
                'email_forwarders',
                'ftps',
                'mysqls'
            ];
            foreach ($ul_list as $ul) {
                if(($package->meta->$ul ?? null) == -1){
                    $fields[$ul . '_ul'] = true;
                } else {
                    $fields[$ul] = $package->meta->$ul;
                }
			}
		}

        return $fields;
    }

    /**
     * Parses the response from the API into a stdClass object
     *
     * @param string $response The response from the API
     * @return stdClass A stdClass object representing the response, void if the response was an error
     */
    private function parseResponse($response, $status)
    {
        // Get module row
        if($row = $this->getModuleRow()){
            $hostname = $row->meta->host_name;
        } else {
            $hostname = 'could not get hostname (must not mean error)';
        }

        if($status != 200){
            // Set internal error on no response
            // Only some API requests return status message, so only use it if its available
            if (empty($response['message'])) {
                $this->Input->setErrors(['api' => ['internal' => Language::_('Froxlor.!error.api.internal', true)]]);
            } else {
                $this->Input->setErrors(['api' => ['internal' => $response['message']]]);
            }

            // Log the response
            $this->log($hostname, serialize($response), 'output', false);
            return;
        } else {
            // Log the response
            $this->log($hostname, serialize($response), 'output', true);
            return $response['data'];
        }

    }

    /**
     * Initializes the FroxlorAPI and returns an instance of that object with the given $host, $api_key, and $api_secret set
     *
     * @param string $host The host to the Froxlor server
     * @param string $api_key The Froxlor server API key
     * @param string $api_secret The Froxlor server API secret
     * @param bool $use_ssl Use an SSL connection
     * @return FroxlorApi The FroxlorApi instance
     */
    private function getApi($host, $api_key, $api_secret, $use_ssl = 'false')
    {
        Loader::load(dirname(__FILE__) . DS . 'apis' . DS . 'FroxlorAPI.php');

        // Format API url
        $api_url = ($use_ssl == 'true' ? 'https' : 'http') . '://' . $host . '/api.php';
        $api = new FroxlorAPI($api_url, $api_key, $api_secret);

        return $api;
    }

    /**
     * Fetches a listing of all packages configured in Froxlor for the given server
     *
     * @param stdClass $module_row A stdClass object representing a single server
     * @return array An array of packages in key/value pair
     */
    private function getFroxlorPackages($module_row)
    {
        // Initialize API
        $api = $this->getApi(
            $module_row->meta->host_name,
            $module_row->meta->api_key,
            $module_row->meta->api_secret,
            $module_row->meta->use_ssl
        );
        $packages = [];

        try {
            $this->log($module_row->meta->host_name . '|HostingPlans.listing', null, 'input', true);
            $response = $this->parseResponse($api->request('HostingPlans.listing'), $api->getLastStatusCode());

            foreach ($response['list'] as $package) {
                $packages[$package['id']] = $package['name'];
            }
        } catch (Exception $e) {
            // API request failed
        }

        return $packages;
    }

    /**
     * Removes the www. from a domain name
     *
     * @param string $domain A domain name
     * @return string The domain name after the www. has been removed
     */
    private function formatDomain($domain)
    {
        return strtolower(preg_replace('/^\s*www\./i', '', $domain));
    }

    /**
     * Builds and returns the rules required to add/edit a module row (e.g. server)
     *
     * @param array $vars An array of key/value data pairs
     * @return array An array of Input rules suitable for Input::setRules()
     */
    private function getRowRules(&$vars)
    {
        $rules = [
            'server_name'=>[
                'valid'=>[
                    'rule'=>'isEmpty',
                    'negate'=>true,
                    'message'=>Language::_('Froxlor.!error.server_name_valid', true)
                ]
            ],
            'host_name'=>[
                'empty'=>[
                    'rule'=>'isEmpty',
                    'negate'=>true,
                    'message'=>Language::_('Froxlor.!error.host_name_empty', true)
                ],
                'length'=>[
                    'rule'=>["betweenLength", 1, 128],
                    'message'=>Language::_('Froxlor.!error.host_name_length', true)
                ],
                'valid'=>[
                    // allow domain.com AND sub.domain.com AND (sub.)domain.com/folder
                    'rule'=>["matches", "/^([a-z0-9]|[a-z0-9][a-z0-9\-]{0,61}[a-z0-9])(\.([a-z0-9]|[a-z0-9][a-z0-9\-]{0,61}[a-z0-9]))+(\/[a-zA-Z0-9]+)?$/i"],
                    //'rule'=>[[$this, 'validateHostName']],
                    'message'=>Language::_('Froxlor.!error.host_name_valid', true)
                ]
            ],
            'api_key'=>[
                'valid'=>[
                    'rule'=>'isEmpty',
                    'negate'=>true,
                    'message'=>Language::_('Froxlor.!error.api_key_valid', true)
                ]
            ],
            'api_secret'=>[
                'valid'=>[
                    'last'=>true,
                    'rule'=>'isEmpty',
                    'negate'=>true,
                    'message'=>Language::_('Froxlor.!error.api_secret_valid', true)
                ],
                'valid_connection'=>[
                    'rule' => [
                        [$this, 'validateConnection'],
                        $vars['host_name'],
                        $vars['api_key'],
                        $vars['use_ssl'],
                        &$vars['account_count']
                    ],
                    'message'=>Language::_('Froxlor.!error.api_secret_valid_connection', true)
                ]
            ],
            'account_limit'=>[
                'valid'=>[
                    'rule'=>['matches', '/^([0-9]+)?$/'],
                    'message'=>Language::_('Froxlor.!error.account_limit_valid', true)
                ]
            ]
        ];

        return $rules;
    }

    /**
     * Builds and returns rules required to be validated when adding/editing a package
     *
     * @param array $vars An array of key/value data pairs
     * @return array An array of Input rules suitable for Input::setRules()
     */
    private function getPackageRules($vars)
    {
        $rules = [
            'meta[account_type]' => [
                'valid' => [
                    'rule' => ['matches', '/^(customer|admin)$/'],
                    // type must be customer or admin
                    'message' => Language::_('Froxlor.!error.meta[account_type].valid', true),
                ]
            ],
            'meta[sub_domains]' => [
                'valid' => [
                    'rule' => ['matches', '/^(disable|enable)$/'],
                    'message' => Language::_('Froxlor.!error.meta[sub_domains].valid', true),
                ]
            ],
            'meta[domains_list]' => [
                'valid' => [
                    'rule' => [
                        function ($domains_csv, $enable_subdomains) {
                            // We only validate the domains if the sub domains are enabled
                            if ($enable_subdomains !== 'enable') {
                                return true;
                            }

                            $domains = $this->parseElementsFromCsv($domains_csv);

                            // At least one domain must be set
                            if (empty($domains)) {
                                return false;
                            }

                            // The domains must be valid host names
                            foreach ($domains as $domain) {
                                if (!$this->validateHostName($domain)) {
                                    return false;
                                }
                            }

                            return true;
                        },
                        ['_linked' => 'meta[sub_domains]']
                    ],
                    'message' => Language::_('Froxlor.!error.meta[domains_list].valid', true),
                    'post_format' => function ($domains_csv) {
                        // Create a new CSV list that we've formatted
                        return implode(',', $this->parseElementsFromCsv($domains_csv));
                    }
                ]
            ],
            'meta[account_limit]' => [
                'valid' => [
                    'rule' => ['matches', '/^([0-9]+)?$/'],
                    'message' => Language::_('Froxlor.!error.meta[account_limit].valid', true),
                ]
            ]
        ];

        if (($vars['meta']['account_type'] ?? 'customer') == 'customer') {
            $rules['meta[package]'] = [
                'empty' => [
                    'rule' => 'isEmpty',
                    'negate' => true,
                    'message' => Language::_('Froxlor.!error.meta[package].empty', true)
                ]
            ];
            $rules['meta[api_allowed]'] = [
                'valid' => [
                    'rule' => ['matches', '/^(disable|enable)$/'],
                    'message' => Language::_('Froxlor.!error.meta[api_allowed].valid', true),
                ]
            ];
        } else {
            $rules['meta[customers]'] = [
                'format' => [
                    'rule' => ["between", "-1", "999999", true],
                    'message' => Language::_('Froxlor.!error.meta[customers].format', true)
                ]
            ];
            $rules['meta[diskspace]'] = [
                'format' => [
                    'rule' => ["between", "-1", "104857600", true],
                    'message' => Language::_('Froxlor.!error.meta[diskspace].format', true)
                ]
            ];
            $rules['meta[traffic]'] = [
                'format' => [
                    'rule' => ["between", "-1", "102400", true],
                    'message' => Language::_('Froxlor.!error.meta[traffic].format', true)
                ]
            ];
            $rules['meta[domains]'] = [
                'format' => [
                    'rule' => ["between", "-1", "999999", true],
                    'message' => Language::_('Froxlor.!error.meta[domains].format', true)
                ]
            ];
            $rules['meta[subdomains]'] = [
                'format' => [
                    'rule' => ["between", "-1", "999999", true],
                    'message' => Language::_('Froxlor.!error.meta[subdomains].format', true)
                ]
            ];
            $rules['meta[emails]'] = [
                'format' => [
                    'rule' => ["between", "-1", "999999", true],
                    'message' => Language::_('Froxlor.!error.meta[emails].format', true)
                ]
            ];
            $rules['meta[email_accounts]'] = [
                'format' => [
                    'rule' => ["between", "-1", "999999", true],
                    'message' => Language::_('Froxlor.!error.meta[email_accounts].format', true)
                ]
            ];
            $rules['meta[email_forwarders]'] = [
                'format' => [
                    'rule' => ["between", "-1", "999999", true],
                    'message' => Language::_('Froxlor.!error.meta[email_forwarders].format', true)
                ]
            ];
            $rules['meta[ftps]'] = [
                'format' => [
                    'rule' => ["between", "-1", "999999", true],
                    'message' => Language::_('Froxlor.!error.meta[ftps].format', true)
                ]
            ];
            $rules['meta[mysqls]'] = [
                'format' => [
                    'rule' => ["between", "-1", "999999", true],
                    'message' => Language::_('Froxlor.!error.meta[mysqls].format', true)
                ]
            ];
		}

        return $rules;
    }
}
