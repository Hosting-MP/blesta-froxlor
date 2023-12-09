<?php
/**
 * de_at language for the froxlor module
 */
// Basics
$lang['Froxlor.name'] = 'Froxlor';
$lang['Froxlor.description'] = 'Froxlor Server Manager';
$lang['Froxlor.module_row'] = 'Server';
$lang['Froxlor.module_row_plural'] = 'Servers';
$lang['Froxlor.module_group'] = 'Server Gruppe';
$lang['Froxlor.tab_stats'] = 'Statistik';
$lang['Froxlor.tab_client_stats'] = 'Statistiken';
$lang['Froxlor.tab_client_actions'] = 'Aktionen';

// Module management
$lang['Froxlor.add_module_row'] = 'Server hinzufügen';
$lang['Froxlor.add_module_group'] = 'Server Gruppe hinzufügen';
$lang['Froxlor.manage.module_rows_title'] = 'Servers';
$lang['Froxlor.manage.module_groups_title'] = 'Server Gruppen';
$lang['Froxlor.manage.module_rows_heading.name'] = 'Server Name';
$lang['Froxlor.manage.module_rows_heading.hostname'] = 'Hostname';
$lang['Froxlor.manage.module_rows_heading.accounts'] = 'Konten';
$lang['Froxlor.manage.module_rows_heading.options'] = 'Optionen';
$lang['Froxlor.manage.module_groups_heading.name'] = 'Gruppenname';
$lang['Froxlor.manage.module_groups_heading.servers'] = 'Server Anzahl';
$lang['Froxlor.manage.module_groups_heading.options'] = 'Optionen';
$lang['Froxlor.manage.module_rows.count'] = '%1$s / %2$s'; // %1$s is the current number of accounts, %2$s is the total number of accounts available
$lang['Froxlor.manage.module_rows.edit'] = 'Bearbeiten';
$lang['Froxlor.manage.module_groups.edit'] = 'Bearbeiten';
$lang['Froxlor.manage.module_rows.delete'] = 'Löschen';
$lang['Froxlor.manage.module_groups.delete'] = 'Löschen';
$lang['Froxlor.manage.module_rows.confirm_delete'] = 'Bist du dir sicher, dass du diesen Server löschen willst?';
$lang['Froxlor.manage.module_groups.confirm_delete'] = 'Bist du dir sicher, dass du diese Server Gruppe löschen willst?';
$lang['Froxlor.manage.module_rows_no_results'] = 'Es gibt keine Server hier.';
$lang['Froxlor.manage.module_groups_no_results'] = 'Es gibt keine Server Gruppen hier.';


$lang['Froxlor.order_options.first'] = 'Erster nicht vollständig gefüllter Server';
$lang['Froxlor.order_options.roundrobin'] = 'Ausgeglichen zwischen den Servern';

// Add row
$lang['Froxlor.add_row.box_title'] = 'Froxlor Server hinzufügen';
$lang['Froxlor.add_row.basic_title'] = 'Basis Einstellungen';
$lang['Froxlor.add_row.notes_title'] = 'Notizen';
$lang['Froxlor.add_row.name_server_btn'] = 'Weitere Nameserver hinzufügen';
$lang['Froxlor.add_row.name_server_col'] = 'Nameserver';
$lang['Froxlor.add_row.name_server_host_col'] = 'Hostname';
$lang['Froxlor.add_row.name_server'] = 'Nameserver %1$s'; // %1$s is the name server number (e.g. 3)
$lang['Froxlor.add_row.remove_name_server'] = 'Entfernen';
$lang['Froxlor.add_row.add_btn'] = 'Server hinzufügen';

$lang['Froxlor.edit_row.box_title'] = 'Froxlor Server bearbeiten';
$lang['Froxlor.edit_row.basic_title'] = 'Basis Einstellungen';
$lang['Froxlor.edit_row.notes_title'] = 'Notizen';
$lang['Froxlor.edit_row.name_server_btn'] = 'Weiteren Nameserver hinzufügen';
$lang['Froxlor.edit_row.name_server_col'] = 'Nameserver';
$lang['Froxlor.edit_row.name_server_host_col'] = 'Hostname';
$lang['Froxlor.edit_row.name_server'] = 'Nameserver %1$s'; // %1$s is the name server number (e.g. 3)
$lang['Froxlor.edit_row.remove_name_server'] = 'Entfernen';
$lang['Froxlor.edit_row.add_btn'] = 'Server bearbeiten';

$lang['Froxlor.row_meta.server_name'] = 'Server Name';
$lang['Froxlor.row_meta.host_name'] = 'Hostname';
$lang['Froxlor.row_meta.api_key'] = 'API Key';
$lang['Froxlor.row_meta.api_secret'] = 'API Secret';
$lang['Froxlor.row_meta.use_ssl'] = 'SSL für den Aufruf der API verwenden (empfohlen)';
$lang['Froxlor.row_meta.account_limit'] = 'Kontenlimit';
$lang['Froxlor.row_meta.otl_time_limit'] = 'Gültigkeitsdauer Direktlogin-Link (in Sekunden)';

// Package fields
$lang['Froxlor.package_fields.package'] = 'Froxlor Packet';
$lang['Froxlor.package_fields.account_type'] = 'Kontotyp';
$lang['Froxlor.package_fields.account_types_customer'] = 'Kunde';
$lang['Froxlor.package_fields.account_types_admin'] = 'Reseller';
$lang['Froxlor.package_fields.admin_limit_customers'] = 'Kundenlimit (-1 für unendlich)';
$lang['Froxlor.package_fields.admin_limit_diskspace'] = 'Speicherplatzlimit in MiB (-1 für unendlich)';
$lang['Froxlor.package_fields.admin_limit_traffic'] = 'Bandbreitenlimit in GiB (-1 für unendlich)';
$lang['Froxlor.package_fields.admin_limit_domains'] = 'Domains Limit (-1 für unendlich)';
$lang['Froxlor.package_fields.admin_limit_subdomains'] = 'Subdomains Limit (-1 für unendlich)';
$lang['Froxlor.package_fields.admin_limit_emails'] = 'E-Mail Adressenlimit (-1 für unendlich)';
$lang['Froxlor.package_fields.admin_limit_email_accounts'] = 'E-Mail Kontenlimit (-1 für unendlich)';
$lang['Froxlor.package_fields.admin_limit_email_forwarders'] = 'E-Mail-Weiterlungen Limit (-1 für unendlich)';
$lang['Froxlor.package_fields.admin_limit_ftps'] = 'FTP Kontenlimit (-1 für unendlich)';
$lang['Froxlor.package_fields.admin_limit_mysqls'] = 'MySql Datenbanken Limit (-1 für unendlich)';
$lang['Froxlor.package_fields.sub_domains'] = 'Aktiviere das Verkaufen von Sub-Domains';
$lang['Froxlor.package_fields.sub_domains_enable'] = 'Ja';
$lang['Froxlor.package_fields.sub_domains_disable'] = 'Nein';
$lang['Froxlor.package_fields.domains_list'] = 'Verfügbare Domains';
$lang['Froxlor.package_fields.tooltip.domains_list'] = 'Gibt eine Liste von Domain-Namen ein, die zur Erstellung von Sub-Domains verwendet werden dürfen. z.B. "domain1.de,domain2.de,domain3.de"';
$lang['Froxlor.package_fields.api_allowed'] = 'Erlaube API Zugriff';
$lang['Froxlor.package_fields.api_allowed_enable'] = 'Ja';
$lang['Froxlor.package_fields.api_allowed_disable'] = 'Nein';
$lang['Froxlor.package_fields.tooltip.api_allowed'] = 'Durch das aktivieren kann der Kunde von außerhalb über die API mit seinem Konto interagieren. Im deaktivierten Zustand ist eine Bedienung nur über das Webinterface möglich.';

// Service fields
$lang['Froxlor.service_field.domain'] = 'Domain';
$lang['Froxlor.service_field.tooltip.domain'] = 'Durch eine Änderung der Domain wird die aktuelle Domain mit ihren Einstellungen gelöscht. Die geänderte Domain wird als neue Domain mit Standardeinstellungen hinzugefügt.';
$lang['Froxlor.service_field.sub_domain'] = 'Sub-Domain';
$lang['Froxlor.service_field.username'] = 'Benutzername';
$lang['Froxlor.service_field.password'] = 'Passwort';
$lang['Froxlor.service_field.confirm_password'] = 'Passwort bestätigen';
$lang['Froxlor.service_field.textnotice'] = 'Notiz an den Kunden';

// Service management
$lang['Froxlor.tab_stats.info_title'] = 'Informationen';
$lang['Froxlor.tab_stats.info_heading.field'] = 'Feld';
$lang['Froxlor.tab_stats.info_heading.value'] = 'Wert';
$lang['Froxlor.tab_stats.info.customers'] = 'Kunden';
$lang['Froxlor.tab_stats.info.diskspace'] = 'Speicherplatz';
$lang['Froxlor.tab_stats.info.traffic'] = 'Bandbreite';
$lang['Froxlor.tab_stats.info.domains'] = 'Domains';
$lang['Froxlor.tab_stats.info.subdomains'] = 'Subdomains';
$lang['Froxlor.tab_stats.info.emails'] = 'E-mail Adressen';
$lang['Froxlor.tab_stats.info.email_accounts'] = 'E-mail Konten';
$lang['Froxlor.tab_stats.info.email_forwarders'] = 'E-mail Weiterleitungen';
$lang['Froxlor.tab_stats.info.ftps'] = 'FTP Konten';
$lang['Froxlor.tab_stats.info.mysqls'] = 'MySQL Datenbanken';
$lang['Froxlor.tab_stats.info.documentroot'] = 'Document Root';
$lang['Froxlor.tab_stats.info.api_allowed'] = 'API Zugriff';
$lang['Froxlor.tab_stats.info.enabled'] = 'Aktiviert';
$lang['Froxlor.tab_stats.info.disabled'] = 'Deaktiviert';
$lang['Froxlor.tab_stats.bandwidth_title'] = 'Bandbreite';
$lang['Froxlor.tab_stats.bandwidth_heading.used'] = 'Benutzt';
$lang['Froxlor.tab_stats.bandwidth_heading.limit'] = 'Limit';
$lang['Froxlor.tab_stats.bandwidth_value'] = '%1$s'; // %1$s is the amount of bandwidth in MB
$lang['Froxlor.tab_stats.bandwidth_unlimited'] = 'unbegrenzt';
$lang['Froxlor.tab_stats.disk_title'] = 'Speicher';
$lang['Froxlor.tab_stats.disk_heading.used'] = 'Benutzt';
$lang['Froxlor.tab_stats.disk_heading.limit'] = 'Limit';
$lang['Froxlor.tab_stats.disk_value'] = '%1$s'; // %1$s is the amount of disk in MB
$lang['Froxlor.tab_stats.disk_unlimited'] = 'unbegrenzt';


// Client actions
$lang['Froxlor.tab_client_actions.change_password'] = 'Passwort ändern';
$lang['Froxlor.tab_client_actions.field_froxlor_password'] = 'Passwort';
$lang['Froxlor.tab_client_actions.field_froxlor_confirm_password'] = 'Passwort bestätigen';
$lang['Froxlor.tab_client_actions.field_password_submit'] = 'Passwort aktualisieren';


// Client Service management
$lang['Froxlor.tab_client_stats.info_title'] = 'Information';
$lang['Froxlor.tab_client_stats.info_heading.field'] = 'Feld';
$lang['Froxlor.tab_client_stats.info_heading.value'] = 'Wert';
$lang['Froxlor.tab_client_stats.info.customers'] = 'Kunden';
$lang['Froxlor.tab_client_stats.info.diskspace'] = 'Speicherplatz (in MB)';
$lang['Froxlor.tab_client_stats.info.traffic'] = 'Bandbreite (in MB)';
$lang['Froxlor.tab_client_stats.info.domains'] = 'Domains';
$lang['Froxlor.tab_client_stats.info.subdomains'] = 'Subdomains';
$lang['Froxlor.tab_client_stats.info.emails'] = 'E-mail Adressen';
$lang['Froxlor.tab_client_stats.info.email_accounts'] = 'E-mail Konten';
$lang['Froxlor.tab_client_stats.info.email_forwarders'] = 'E-mail Weiterleitungen';
$lang['Froxlor.tab_client_stats.info.ftps'] = 'FTP Konten';
$lang['Froxlor.tab_client_stats.info.mysqls'] = 'MySQL Datenbanken';
$lang['Froxlor.tab_client_stats.info.documentroot'] = 'Document Root';
$lang['Froxlor.tab_client_stats.bandwidth_title'] = 'Bandbreite benutzt (Monat bis jetzt)';
$lang['Froxlor.tab_client_stats.disk_title'] = 'Speicherplatzbelegung';
$lang['Froxlor.tab_client_stats.usage'] = '(%1$s / %2$s)'; // %1$s is the amount of resource usage, %2$s is the resource usage limit
$lang['Froxlor.tab_client_stats.usage_unlimited'] = '(%1$s /∞)'; // %1$s is the amount of resource usage


// Service info
$lang['Froxlor.service_info.username'] = 'Benutzername';
$lang['Froxlor.service_info.password'] = 'Passwort';
$lang['Froxlor.service_info.server'] = 'Server';
$lang['Froxlor.service_info.options'] = 'Optionen';
$lang['Froxlor.service_info.option_login'] = 'Einloggen';


// Tooltips
$lang['Froxlor.service_field.tooltip.username'] = 'Du kannst das Feld für den Benutzernamen leer lassen um automatisch einen zu generieren.';
$lang['Froxlor.service_field.tooltip.password'] = 'Du kannst das Feld für das Passwort leer lassen um automatisch eins zu generieren.';


// Errors
$lang['Froxlor.!error.server_name_valid'] = 'Du musst einen Servernamen angeben.';
$lang['Froxlor.!error.host_name_empty'] = 'Der Hostname darf nicht leer sein.';
$lang['Froxlor.!error.host_name_length'] = 'Der Hostname muss zwischen 1 und 128 Zeichen lang sein.';
$lang['Froxlor.!error.host_name_valid'] = 'Der Hostname scheint ungültig zu sein.';
$lang['Froxlor.!error.api_key_valid'] = 'Der API Key scheint ungültig zu sein.';
$lang['Froxlor.!error.api_secret_valid'] = 'Der API Secret scheint ungültig zu sein.';
$lang['Froxlor.!error.api_secret_valid_connection'] = 'Es konnte keine Verbindung zum Server hergestellt werden. Bitte prüfe, dass der Hostname, API Key und API Secret richtig sind.';
$lang['Froxlor.!error.account_limit_valid'] = 'Das Feld für das Kontenlimit muss leer gelassen werden (für unbegrenzt) oder mit einer Zahl gefüllt werden.';
$lang['Froxlor.!error.otl_time_limit_valid'] = 'Das Feld für die Gültigkeitsdauer des Direktlogin-Links muss mit einer Zahl zwischen 10 und 120 Sekunden gefüllt werden.';
$lang['Froxlor.!error.api.internal'] = 'Ein interner Fehler ist aufgetreten oder der Server antwortet nicht.';
$lang['Froxlor.!error.module_row.missing'] = 'Ein interner Server ist aufgetreten. Die Modulspalte (module row) fehlt.';

$lang['Froxlor.!error.froxlor_domain.format'] = 'Bitte einen gültigen Domainnamen eingeben, z.B. domain.de.';
$lang['Froxlor.!error.froxlor_domain.valid'] = 'Ungültiger Domain-Name';
$lang['Froxlor.!error.froxlor_sub_domain.format'] = 'Bitte einen gültigen Sub-Domainnamen eingeben, z.B. subdomain1.';
$lang['Froxlor.!error.froxlor_sub_domain.availability'] = 'Diese Sub-Domaine ist leider nicht mehr verfügbar. Bitte wähle eine andere.';
$lang['Froxlor.!error.froxlor_username.format'] = 'Der Benutzername darf nur aus Buchstaben und Zahlen bestehen und nicht mit einer Zahl anfangen.';
$lang['Froxlor.!error.froxlor_username.length'] = 'Der Benutzername muss zwischen einem und 16 Zeichen lang sein.';
$lang['Froxlor.!error.froxlor_password.valid'] = 'Das Passwort muss mindestens acht zeichen lang sein.';
$lang['Froxlor.!error.froxlor_password.matches'] = 'Passwort und Passwortbestätigung stimmen nicht überein.';

// general meta package error messages
$lang['Froxlor.!error.meta[account_type].valid'] = 'Es können nur Customer oder Admin Konnten erstellt werden.';
$lang['Froxlor.!error.meta[sub_domains].valid'] = 'Die Subdomain kann nur aktiviert oder deaktiviert werden.';
$lang['Froxlor.!error.meta[domains_list].valid'] = 'Die Domain Liste ist keine gültige CSV Liste.';
$lang['Froxlor.!error.meta[account_limit].valid'] = 'Das Kontenlimit ist kein gültiger Wert.';
$lang['Froxlor.!error.meta[otl_time_limit].valid'] = 'Das Zeitlimit für die Gültigkeit des Direktlogin-Links ist kein gültiger Wert.';
$lang['Froxlor.!error.meta[api_allowed].valid'] = 'Der API Zugriff kann nur aktiviert oder deaktiviert werden.';
// admin meta package error messages
$lang['Froxlor.!error.meta[customers].format'] = 'Das Admin Kundenlimit muss eine Zahl zwischen -1 und 999999 sein.';
$lang['Froxlor.!error.meta[diskspace].format'] = 'Die Speicherplatzangabe für das Admin Konto muss eine Zahl zwischen -1 und 104857600 sein.';
$lang['Froxlor.!error.meta[traffic].format'] = 'Die Bandbreitenangabe für das Admin Konto muss eine Zahl zwischen -1 und 102400 sein.';
$lang['Froxlor.!error.meta[domains].format'] = 'Das Admin Domainslimit muss eine Zahl zwischen -1 und 999999 sein.';
$lang['Froxlor.!error.meta[subdomains].format'] = 'Das Admin Subdomainslimit muss eine Zahl zwischen -1 und 999999 sein.';
$lang['Froxlor.!error.meta[emails].format'] = 'Das Admin Emailadressenlimit muss eine Zahl zwischen -1 und 999999 sein.';
$lang['Froxlor.!error.meta[email_accounts].format'] = 'Das Admin Emailkontenlimit muss eine Zahl zwischen -1 und 999999 sein.';
$lang['Froxlor.!error.meta[email_forwarders].format'] = 'Das Admin Emailweiterleitungenlimit muss eine Zahl zwischen -1 und 999999 sein.';
$lang['Froxlor.!error.meta[ftps].format'] = 'Das Admin FTP-Kotenlimit muss eine Zahl zwischen -1 und 999999 sein.';
$lang['Froxlor.!error.meta[mysqls].format'] = 'Das Admin Datenbankenlimit muss eine Zahl zwischen -1 und 999999 sein.';
// customer meta package error messages
$lang['Froxlor.!error.meta[package].empty'] = 'Ein Packet in/von Froxlor wird benötigt.';
