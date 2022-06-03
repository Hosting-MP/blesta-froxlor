<?php
Configure::set('Froxlor.email_templates', [
    'en_us' => [
        'lang' => 'en_us',
        'text' => 'Your Froxlor account is now active, details below:

Domain: {service.froxlor_domain}
Username: {service.froxlor_username}
Password: {service.froxlor_password}

To log into Froxlor please visit https://{module.host_name}.
If you want to take advantage of the DNS features, please update your name servers as soon as possible to the following:

Nameserver: <ns1>
Nameserver: <ns2>

Thank you for your business!',
        'html' => '<p>Your Froxlor account is now active, details below:</p>
<p>Domain: {service.froxlor_domain}<br />Username: {service.froxlor_username}<br />Password: {service.froxlor_password}</p>
<p>To log into Froxlor please visit https://{module.host_name}.<br />Please update your name servers as soon as possible to the following:</p>
<p>{% for name_server in module.name_servers %}<br />Name server: {name_server}{% endfor %}</p>
<p>Thank you for your business!</p>'
    ],
    'de_de' => [
        'lang' => 'de_de',
        'text' => 'Ihr Froxlor Konto wurde mit folgenden Details aktiviert:

Domain: {service.froxlor_domain}
Benutzername: {service.froxlor_username}
Passwort: {service.froxlor_password}

Sie können sich unter folgender Adresse in Froxlor anmelden: https://{module.host_name}
Wenn Sie die DNS Funktionalitäten nutzen möchten, aktualisieren Sie Ihre Nameserver-Einstellungen sobald wie möglich:

Nameserver: <ns1>
Nameserver: <ns2>

Vielen Dank für Ihre Bestellung!',
        'html' => '<p>Ihr Froxlor Konto wurde mit folgenden Details aktiviert:</p>
<p>Domain: {service.froxlor_domain}<br />Benutzername: {service.froxlor_username}<br />Passwort: {service.froxlor_password}</p>
<p>Sie können sich unter folgender Adresse in Froxlor anmelden: https://{module.host_name}.<br />Wenn Sie die DNS Funktionalitäten nutzen möchten, aktualisieren Sie Ihre Nameserver-Einstellungen sobald wie möglich:</p>
<p><br />Nameserver: <ns1><br />Nameserver: <ns2></p>
<p>Vielen Dank für Ihre Bestellung!</p>'
    ],
    'de_at' => [
        'lang' => 'de_at',
        'text' => 'Dein Froxlor Konto wurde mit folgenden Details aktiviert:

Domain: {service.froxlor_domain}
Benutzername: {service.froxlor_username}
Passwort: {service.froxlor_password}

Du kannst dich unter folgender Adresse in Froxlor anmelden: https://{module.host_name}
Wenn Du die DNS Funktionalitäten nutzen möchtest, aktualisiere Deine Nameserver-Einstellungen sobald wie möglich:

Nameserver: <ns1>
Nameserver: <ns2>

Vielen Dank für Deine Bestellung!',
        'html' => '<p>Dein Froxlor Konto wurde mit folgenden Details aktiviert:</p>
<p>Domain: {service.froxlor_domain}<br />Benutzername: {service.froxlor_username}<br />Passwort: {service.froxlor_password}</p>
<p>Du kannst dich unter folgender Adresse in Froxlor anmelden: https://{module.host_name}.<br />Wenn Du die DNS Funktionalitäten nutzen möchtest, aktualisiere Deine Nameserver-Einstellungen sobald wie möglich:</p>
<p><br />Nameserver: <ns1><br />Nameserver: <ns2></p>
<p>Vielen Dank für Deine Bestellung!</p>'
    ]
]);
