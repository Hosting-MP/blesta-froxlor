<?php
/**
 * en_us language for the froxlor module
 */
// Basics
$lang['Froxlor.name'] = 'Froxlor';
$lang['Froxlor.description'] = 'Froxlor Server Manager';
$lang['Froxlor.module_row'] = 'Server';
$lang['Froxlor.module_row_plural'] = 'Servers';
$lang['Froxlor.module_group'] = 'Server Group';
$lang['Froxlor.tab_stats'] = 'Statistics';
$lang['Froxlor.tab_client_stats'] = 'Statistics';
$lang['Froxlor.tab_client_actions'] = 'Actions';

// Module management
$lang['Froxlor.add_module_row'] = 'Add Server';
$lang['Froxlor.add_module_group'] = 'Add Server Group';
$lang['Froxlor.manage.module_rows_title'] = 'Servers';
$lang['Froxlor.manage.module_groups_title'] = 'Server Groups';
$lang['Froxlor.manage.module_rows_heading.name'] = 'Server Label';
$lang['Froxlor.manage.module_rows_heading.hostname'] = 'Hostname';
$lang['Froxlor.manage.module_rows_heading.accounts'] = 'Accounts';
$lang['Froxlor.manage.module_rows_heading.options'] = 'Options';
$lang['Froxlor.manage.module_groups_heading.name'] = 'Group Name';
$lang['Froxlor.manage.module_groups_heading.servers'] = 'Server Count';
$lang['Froxlor.manage.module_groups_heading.options'] = 'Options';
$lang['Froxlor.manage.module_rows.count'] = '%1$s / %2$s'; // %1$s is the current number of accounts, %2$s is the total number of accounts available
$lang['Froxlor.manage.module_rows.edit'] = 'Edit';
$lang['Froxlor.manage.module_groups.edit'] = 'Edit';
$lang['Froxlor.manage.module_rows.delete'] = 'Delete';
$lang['Froxlor.manage.module_groups.delete'] = 'Delete';
$lang['Froxlor.manage.module_rows.confirm_delete'] = 'Are you sure you want to delete this server?';
$lang['Froxlor.manage.module_groups.confirm_delete'] = 'Are you sure you want to delete this server group?';
$lang['Froxlor.manage.module_rows_no_results'] = 'There are no servers.';
$lang['Froxlor.manage.module_groups_no_results'] = 'There are no server groups.';


$lang['Froxlor.order_options.first'] = 'First Non-full Server';
$lang['Froxlor.order_options.roundrobin'] = 'Evenly Distribute Among Servers';

// Add row
$lang['Froxlor.add_row.box_title'] = 'Add Froxlor Server';
$lang['Froxlor.add_row.basic_title'] = 'Basic Settings';
$lang['Froxlor.add_row.notes_title'] = 'Notes';
$lang['Froxlor.add_row.name_server_btn'] = 'Add Additional Name Server';
$lang['Froxlor.add_row.name_server_col'] = 'Name Server';
$lang['Froxlor.add_row.name_server_host_col'] = 'Hostname';
$lang['Froxlor.add_row.name_server'] = 'Name server %1$s'; // %1$s is the name server number (e.g. 3)
$lang['Froxlor.add_row.remove_name_server'] = 'Remove';
$lang['Froxlor.add_row.add_btn'] = 'Add Server';

$lang['Froxlor.edit_row.box_title'] = 'Edit Froxlor Server';
$lang['Froxlor.edit_row.basic_title'] = 'Basic Settings';
$lang['Froxlor.edit_row.notes_title'] = 'Notes';
$lang['Froxlor.edit_row.name_server_btn'] = 'Add Additional Name Server';
$lang['Froxlor.edit_row.name_server_col'] = 'Name Server';
$lang['Froxlor.edit_row.name_server_host_col'] = 'Hostname';
$lang['Froxlor.edit_row.name_server'] = 'Name server %1$s'; // %1$s is the name server number (e.g. 3)
$lang['Froxlor.edit_row.remove_name_server'] = 'Remove';
$lang['Froxlor.edit_row.add_btn'] = 'Edit Server';

$lang['Froxlor.row_meta.server_name'] = 'Server Label';
$lang['Froxlor.row_meta.host_name'] = 'Hostname';
$lang['Froxlor.row_meta.api_key'] = 'API Key';
$lang['Froxlor.row_meta.api_secret'] = 'API Secret';
$lang['Froxlor.row_meta.use_ssl'] = 'Use SSL when connecting to the API (recommended)';
$lang['Froxlor.row_meta.allow_direct_login'] = 'Allow direct login (special configuration required)';
$lang['Froxlor.row_meta.account_limit'] = 'Account Limit';

// Package fields
$lang['Froxlor.package_fields.package'] = 'Froxlor Package';
$lang['Froxlor.package_fields.account_type'] = 'Account Type';
$lang['Froxlor.package_fields.account_types_customer'] = 'Customer';
$lang['Froxlor.package_fields.account_types_admin'] = 'Admin';
$lang['Froxlor.package_fields.admin_limit_customers'] = 'Customer Limit (-1 for unlimited)';
$lang['Froxlor.package_fields.admin_limit_diskspace'] = 'Disk Space Limit in MiB (-1 for unlimited)';
$lang['Froxlor.package_fields.admin_limit_traffic'] = 'Traffic Limit in GiB (-1 for unlimited)';
$lang['Froxlor.package_fields.admin_limit_domains'] = 'Domains Limit (-1 for unlimited)';
$lang['Froxlor.package_fields.admin_limit_subdomains'] = 'Subdomains Limit (-1 for unlimited)';
$lang['Froxlor.package_fields.admin_limit_emails'] = 'Email Address Limit (-1 for unlimited)';
$lang['Froxlor.package_fields.admin_limit_email_accounts'] = 'Email Accounts Limit (-1 for unlimited)';
$lang['Froxlor.package_fields.admin_limit_email_forwarders'] = 'Email Forwarders Limit (-1 for unlimited)';
$lang['Froxlor.package_fields.admin_limit_ftps'] = 'FTP Accounts Limit (-1 for unlimited)';
$lang['Froxlor.package_fields.admin_limit_mysqls'] = 'MySql Databases Limit (-1 for unlimited)';
$lang['Froxlor.package_fields.sub_domains'] = 'Activate selling of Sub-Domains';
$lang['Froxlor.package_fields.sub_domains_enable'] = 'Yes';
$lang['Froxlor.package_fields.sub_domains_disable'] = 'No';
$lang['Froxlor.package_fields.domains_list'] = 'Available Domains';
$lang['Froxlor.package_fields.tooltip.domains_list'] = 'Enter a CSV list of domains that will be available to provision sub-domains for, e.g. "domain1.com,domain2.com,domain3.com"';
$lang['Froxlor.package_fields.api_allowed'] = 'Grand API access';
$lang['Froxlor.package_fields.api_allowed_enable'] = 'Yes';
$lang['Froxlor.package_fields.api_allowed_disable'] = 'No';
$lang['Froxlor.package_fields.tooltip.api_allowed'] = 'Allowing the customer to access the API remotely. If set to false, the customer can interact with his account through the webinterface only.';

// Service fields
$lang['Froxlor.service_field.domain'] = 'Domain';
$lang['Froxlor.service_field.tooltip.domain'] = 'Changing the domain name will delete the current domain including the domain configs. The changed domain will be added with default configs.';
$lang['Froxlor.service_field.sub_domain'] = 'Sub-Domain';
$lang['Froxlor.service_field.username'] = 'Username';
$lang['Froxlor.service_field.password'] = 'Password';
$lang['Froxlor.service_field.confirm_password'] = 'Confirm Password';
$lang['Froxlor.service_field.textnotice'] = 'Notice to customer';

// Service management
$lang['Froxlor.tab_stats.info_title'] = 'Information';
$lang['Froxlor.tab_stats.info_heading.field'] = 'Field';
$lang['Froxlor.tab_stats.info_heading.value'] = 'Value';
$lang['Froxlor.tab_stats.info.customers'] = 'Customers';
$lang['Froxlor.tab_stats.info.diskspace'] = 'Disk Space';
$lang['Froxlor.tab_stats.info.traffic'] = 'Bandwidth';
$lang['Froxlor.tab_stats.info.domains'] = 'Domains';
$lang['Froxlor.tab_stats.info.subdomains'] = 'Subdomains';
$lang['Froxlor.tab_stats.info.emails'] = 'E-mail Addresses';
$lang['Froxlor.tab_stats.info.email_accounts'] = 'E-mail Accounts';
$lang['Froxlor.tab_stats.info.email_forwarders'] = 'E-mail Forwarders';
$lang['Froxlor.tab_stats.info.ftps'] = 'FTP Accounts';
$lang['Froxlor.tab_stats.info.mysqls'] = 'MySQL Databases';
$lang['Froxlor.tab_stats.info.documentroot'] = 'Document Root';
$lang['Froxlor.tab_stats.info.api_allowed'] = 'API access';
$lang['Froxlor.tab_stats.info.enabled'] = 'Enabled';
$lang['Froxlor.tab_stats.info.disabled'] = 'Disabled';
$lang['Froxlor.tab_stats.bandwidth_title'] = 'Bandwidth';
$lang['Froxlor.tab_stats.bandwidth_heading.used'] = 'Used';
$lang['Froxlor.tab_stats.bandwidth_heading.limit'] = 'Limit';
$lang['Froxlor.tab_stats.bandwidth_value'] = '%1$s'; // %1$s is the amount of bandwidth in MB
$lang['Froxlor.tab_stats.bandwidth_unlimited'] = 'unlimited';
$lang['Froxlor.tab_stats.disk_title'] = 'Disk';
$lang['Froxlor.tab_stats.disk_heading.used'] = 'Used';
$lang['Froxlor.tab_stats.disk_heading.limit'] = 'Limit';
$lang['Froxlor.tab_stats.disk_value'] = '%1$s'; // %1$s is the amount of disk in MB
$lang['Froxlor.tab_stats.disk_unlimited'] = 'unlimited';


// Client actions
$lang['Froxlor.tab_client_actions.change_password'] = 'Change Password';
$lang['Froxlor.tab_client_actions.field_froxlor_password'] = 'Password';
$lang['Froxlor.tab_client_actions.field_froxlor_confirm_password'] = 'Confirm Password';
$lang['Froxlor.tab_client_actions.field_password_submit'] = 'Update Password';


// Client Service management
$lang['Froxlor.tab_client_stats.info_title'] = 'Information';
$lang['Froxlor.tab_client_stats.info_heading.field'] = 'Field';
$lang['Froxlor.tab_client_stats.info_heading.value'] = 'Value';
$lang['Froxlor.tab_client_stats.info.customers'] = 'Customers';
$lang['Froxlor.tab_client_stats.info.diskspace'] = 'Disk Space (in MB)';
$lang['Froxlor.tab_client_stats.info.traffic'] = 'Bandwidth (in MB)';
$lang['Froxlor.tab_client_stats.info.domains'] = 'Domains';
$lang['Froxlor.tab_client_stats.info.subdomains'] = 'Subdomains';
$lang['Froxlor.tab_client_stats.info.emails'] = 'E-mail Addresses';
$lang['Froxlor.tab_client_stats.info.email_accounts'] = 'E-mail Accounts';
$lang['Froxlor.tab_client_stats.info.email_forwarders'] = 'E-mail Forwaders';
$lang['Froxlor.tab_client_stats.info.ftps'] = 'FTP Accounts';
$lang['Froxlor.tab_client_stats.info.mysqls'] = 'MySQL Databases';
$lang['Froxlor.tab_client_stats.info.documentroot'] = 'Document Root';
$lang['Froxlor.tab_client_stats.bandwidth_title'] = 'Bandwidth Usage (Month to Date)';
$lang['Froxlor.tab_client_stats.disk_title'] = 'Disk Usage';
$lang['Froxlor.tab_client_stats.usage'] = '(%1$s / %2$s)'; // %1$s is the amount of resource usage, %2$s is the resource usage limit
$lang['Froxlor.tab_client_stats.usage_unlimited'] = '(%1$s / ∞)'; // %1$s is the amount of resource usage


// Service info
$lang['Froxlor.service_info.username'] = 'Username';
$lang['Froxlor.service_info.password'] = 'Password';
$lang['Froxlor.service_info.server'] = 'Server';
$lang['Froxlor.service_info.options'] = 'Options';
$lang['Froxlor.service_info.option_login'] = 'Log in';


// Tooltips
$lang['Froxlor.service_field.tooltip.username'] = 'You may leave the username blank to automatically generate one.';
$lang['Froxlor.service_field.tooltip.password'] = 'You may leave the password blank to automatically generate one.';


// Errors
$lang['Froxlor.!error.server_name_valid'] = 'You must enter a Server Label.';
$lang['Froxlor.!error.host_name_empty'] = 'The hostname may not be an empty value.';
$lang['Froxlor.!error.host_name_length'] = 'The hostname must be between 1 und 128 characters long.';
$lang['Froxlor.!error.host_name_valid'] = 'The Hostname appears to be invalid.';
$lang['Froxlor.!error.api_key_valid'] = 'The API Key appears to be invalid.';
$lang['Froxlor.!error.api_secret_valid'] = 'The API Secret appears to be invalid.';
$lang['Froxlor.!error.api_secret_valid_connection'] = 'A connection to the server could not be established. Please check to ensure that the Hostname, API Key and API Secret are correct.';
$lang['Froxlor.!error.account_limit_valid'] = 'Account Limit must be left blank (for unlimited accounts) or set to some integer value.';
$lang['Froxlor.!error.api.internal'] = 'An internal error occurred, or the server did not respond to the request.';
$lang['Froxlor.!error.module_row.missing'] = 'An internal error occurred. The module row is unavailable.';

$lang['Froxlor.!error.froxlor_domain.format'] = 'Please enter a valid domain name, e.g. domain.com.';
$lang['Froxlor.!error.froxlor_domain.valid'] = 'Invalid domain name';
$lang['Froxlor.!error.froxlor_sub_domain.format'] = 'Please insert a valid sub-domain name, e.g. subdomain1.';
$lang['Froxlor.!error.froxlor_sub_domain.availability'] = 'This sub-domain is not available. Please choose a different one.';
$lang['Froxlor.!error.froxlor_username.format'] = 'The username may contain only letters and numbers and may not start with a number.';
$lang['Froxlor.!error.froxlor_username.length'] = 'The username must be between 1 and 16 characters in length.';
$lang['Froxlor.!error.froxlor_password.valid'] = 'Password must be at least 8 characters in length.';
$lang['Froxlor.!error.froxlor_password.matches'] = 'Password and Confirm Password do not match.';

// general meta package error messages
$lang['Froxlor.!error.meta[account_type].valid'] = 'Account type must be either customer or admin.';
$lang['Froxlor.!error.meta[sub_domains].valid'] = 'Enable Sub-Domains must be set to either enable or disable.';
$lang['Froxlor.!error.meta[domains_list].valid'] = 'At least one available domain must be set and they must all represent a valid host name.';
$lang['Froxlor.!error.meta[account_limit].valid'] = 'Account limit must be a number.';
$lang['Froxlor.!error.meta[api_allowed].valid'] = 'Grand API access must be set to either enable or disable.';
// admin meta package error messages
$lang['Froxlor.!error.meta[customers].format'] = 'Admin customer limit must be a number between -1 and 999999.';
$lang['Froxlor.!error.meta[diskspace].format'] = 'Admin diskspace limit must be a number between -1 and 104857600.';
$lang['Froxlor.!error.meta[traffic].format'] = 'Admin traffic limit must be a number between -1 and 102400.';
$lang['Froxlor.!error.meta[domains].format'] = 'Admin domains limit must be a number between -1 and 999999.';
$lang['Froxlor.!error.meta[subdomains].format'] = 'Admin subdomains limit must be a number between -1 and 999999.';
$lang['Froxlor.!error.meta[emails].format'] = 'Admin email address limit must be a number between -1 and 999999.';
$lang['Froxlor.!error.meta[email_accounts].format'] = 'Admin email accounts limit must be a number between -1 and 999999.';
$lang['Froxlor.!error.meta[email_forwarders].format'] = 'Admin email forwarders limit must be a number between -1 and 999999.';
$lang['Froxlor.!error.meta[ftps].format'] = 'Admin FTP accounts limit must be a number between -1 and 999999.';
$lang['Froxlor.!error.meta[mysqls].format'] = 'Admin databases limit must be a number between -1 and 999999.';
// customer meta package error messages
$lang['Froxlor.!error.meta[package].empty'] = 'A Froxlor Package is required.';
