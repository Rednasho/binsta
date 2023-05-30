# Handleiding Installatie Binsta - Joran Valk

## Installatie
1. Voer de volgende commando uit in een plek waar je het project wil hebben staan:
    - git clone git@bitlab.bit-academy.nl:a3425e22-7e10-11ea-944d-cec41367f4e7/c2e0a481-a3a4-45c6-81f1-cfdc258e3517/Binsta-3584a052-ab1404e3.git binsta
2. Ga naar je schijf -> windows -> system32 -> drivers -> etc -> hosts en zet onderaan dit bestand het volgende:
    - 127.0.0.1 binsta.test
    - Sla op met administrator
3. Ga terug naar je schijf -> xampp -> apache -> conf -> extra -> httpd-vhosts.conf en plak het onderstaande onderaan het bestand:
    - Wijzig {locatie van de Repo} naar de volledige pad van waar je repo staat!
    - `<VirtualHost *:80>`\
	    `DocumentRoot "{locatie van de Repo}"\`
	    `ServerName binsta.test\`
	    `<Directory "{locatie van de Repo}">\`
		    `Options Indexes FollowSymLinks Includes ExecCGI\`
        	`AllowOverride All`\
        	`Order allow,deny`\
        	`Allow from all`\
		    `Require all granted`\
	    `</Directory>`\
    `</VirtualHost>`
4. Sla het bestand op en indien actief start je Apache opnieuw op.
5. Ga nu naar je browser en typ in: http://binsta.test
6. Je bevind je nu op de homepagina, als het goed is wertkt alles nu.




