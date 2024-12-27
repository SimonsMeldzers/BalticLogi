## (Lai visas saites strādātu pareizi, projekta mapei jābūt nosauktai BalticLogi)

## Importēt datu bāzi:
 - Atveriet phpMyAdmin.
 - Importējiet failu `balticlogi.sql' jaunā datu bāzē.
 - Programmā phpMyAdmin atveriet cilni Importēt, atlasiet failu un noklikšķiniet uz "Aiziet".

## Konfigurēt projektu:
 - Atjauniniet datu bāzes datus failā `src/connect_db.php', lai tie atbilstu lokālajam iestatījumam.
 Piemēram:
 $servername = "127.0.0.1:3307";
 $username = "admin"; 
 $password = "password123";        
 $dbname = "balticlogi";

## Palaidst serveri:
 - Ievietojiet projekta mapi sava tīmekļa servera saknes direktorijā (piemēram, `htdocs` XAMPP).
 - Sāciet savu tīmekļa serveri un dodieties uz `http://localhost/BalticLogi/public/`. 
 

## Mājaslapas administratora dati:
 - Izmantojiet administratora datus lai piekļūtu pie mājaslapas rediģēšanas funkcionalitātes:
 - E-pasts: admin@balticlogi.lv
 - Parole: admin123