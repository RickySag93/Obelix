# Creazione latex da database di Use cases e Requirements

Nota: non è inserito il file sql perchè non considerato definitivo

Gli script vanno eseguiti su un webserver su cui esista il database **Trender**.


**latexifier_re.php** crea un file latex che contiene gli use case impaginati. Tutti gli use case che includono un diagramma iniziano in una nuova pagina.
**latexifier_uc.php** crea un file latex che contiene la tabella multipagina che descrive i requisititi. Vengono divisi in 3 parti: funzionali, di qualità e dichiarativi.