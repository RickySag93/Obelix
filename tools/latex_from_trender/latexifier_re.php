<?php

include_once("tracciamento.php");



$db = new database();
// WHERE usecase  LIKE \"%-%\"
$ans = $db->query("SELECT * FROM Requirements");

$fun = array();
$qua = array();
$dic = array();
while($res = $ans->fetch_assoc()){

   $req = $res["requirement"];
   $imp = $res["importanza"];
   $tipo = $res["tipo"];
   $des = check_punto($res["description"]);
   $src = $res["source"];

   echo $req ."           ". $des ."<br/>";

   //////////////////////////////
   /*
      utilità
      Ob: Obbligatori
      De: Desiderabili
      Op: Opzionali
      tipo:
      Fu: Funzionale
      Qu: Qualitativo
      Di: Dichiarativo
    */
   $imp = trim($imp);
   $tipo = trim($tipo);
   $txt = 'R'. ucfirst($imp) . ucfirst($tipo). $req;
   switch($imp){
      case 'ob':
         $imp = 'Obbligatorio';
         break;
      case 'de':
         $imp = 'Desiderabile';
         break;
      case 'op':
         $imp = 'Opzionale';
         break;
      default:
         die("Importanza non impostata correttamente: <$imp>\n\n");
   }

   switch($tipo){
      case 'fu':
         $tipo = 'Funzionale';
         break;
      case 'qu':
         $tipo = 'Qualitativo';
         break;
      case 'di':
         $tipo = 'Dichiarativo';
         break;
      default:
         die("Tipologia non impostata correttamente: <$tipo>\n\n");
   }

   $txt .= ' & \\makecell{' . "$imp \\\\ $tipo} & $des & \\makecell{";;
   // fonti:
   $txt .= implode('\\\\',explode('+',$src))."}\\\\\n\\hline\n";
   // Elimina gli a capo stile windows creati da PHPmyadmin
   $txt = str_replace("\r\n","\n",$txt);
   $txt = str_replace("\r","\n",$txt);
   switch($tipo){
      case 'Funzionale':
         $fun[$req] = $txt;
         break;
      case 'Qualitativo':
         $qua[$req] = $txt;
         break;
      case 'Dichiarativo':
         $dic[$req] = $txt;
         break;
      default:
         die("crimson death\n\n");
   }
}

//p{0.2\columnwidth}p{0.6\textwidth}p{0.2\columnwidth}
// {|l|c|l|c|}
$inizioTabella =<<<EOF
\\begin{center}
\\begin{longtable}{|
*{1}{>{\centering\arraybackslash}p{2.5cm}|}
*{1}{>{\centering\arraybackslash}p{2cm}|}
*{1}{>{\centering\arraybackslash}p{5cm}|}
*{1}{>{\centering\arraybackslash}p{2.5cm}|}}
\\hline \\textbf{Requisito} & \\textbf{Tipologia} & \\textbf{Descrizione} & \\textbf{Fonti}\\\\
\\hline \\endhead
\\hline \\endfoot

EOF;

$fineTabella =<<<EOF
\\hline
\\end{longtable}
\\end{center}
EOF;

$FH = fopen("requirements.tex", "w");


if(count($fun)){
   $inizio = "\\subsection{Requisiti funzionali}\n\n";
   fwrite($FH,$inizio);
   // elenco delle chiavi da riordinare
   $ks = array_keys($fun);
   // riordino con la mia funzione  di confronto
   usort($ks,"compare_uc");

   // scrivere nell'ordine giusto
   fwrite($FH,$inizioTabella."\n");
   foreach($ks as $k){
      fwrite($FH,$fun[$k]."\n");
   }
   fwrite($FH,$fineTabella."\n");
}
if(count($qua)){
   $inizio = "\\subsection{Requisiti qualitativi}\n\n";
   fwrite($FH,$inizio);
   // elenco delle chiavi da riordinare
   $ks = array_keys($qua);
   // riordino con la mia funzione  di confronto
   usort($ks,"compare_uc");

   // scrivere nell'ordine giusto
   fwrite($FH,$inizioTabella."\n");
   foreach($ks as $k){
      fwrite($FH,$qua[$k]."\n");
   }
   fwrite($FH,$fineTabella."\n");
}
if(count($dic)){
   $inizio = "\\subsection{Requisiti dichiarativi}\n\n";
   fwrite($FH,$inizio);
   // elenco delle chiavi da riordinare
   $ks = array_keys($dic);
   // riordino con la mia funzione  di confronto
   usort($ks,"compare_uc");

   // scrivere nell'ordine giusto
   fwrite($FH,$inizioTabella."\n");
   foreach($ks as $k){
      fwrite($FH,$dic[$k]."\n");
   }
	fwrite($FH,$fineTabella."\n");
}


fclose($FH);

?>
