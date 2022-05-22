# invoisiz
Simple app to manage invoices

Jednoduchá demo aplikace na správu faktur (invoices).
Vytváření dodavatelů (contractors) a odběratelů (clients). 
Vytvoření nového klienta přes knihovnu napojenou na ARES (získání údajů zadáním IČ).
Stahování faktur do PDF.
Systém uživatelských práv.

-----REQUIREMENTS-----

php >= 8.0

-----INSTALLATION-----
1. clone project
2. create directories temp and log with permissions to write
3. run composer install
4. create config/local.neon and set database credentials
5. run database migration in database/import.sql
6. login to app with username: woody@email.cz and password: password
