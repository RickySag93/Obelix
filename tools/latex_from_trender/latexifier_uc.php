<?php

include_once("tracciamento.php");


$db = new database();
// WHERE usecase  LIKE \"%-%\"
$ans = $db->query("SELECT * FROM Usecases");

$data = array();
while($res = $ans->fetch_assoc()){
   /*   $txt = '';// '\FloatBarrier'."\n";

      $txt .= $res["usecase"] ."\n";
      if($res["imagePath"]==''){
      $txt .= "NOIMAGE\n";
      }
      $data[$res["usecase"]] = $txt;

    */

   $uc = $res["usecase"];
   $dad = $res["dad"];
   $title = check_punto($res["title"]);
   $des = check_punto($res["description"]);
   $pre = check_punto($res["precondition"]);
   $post = check_punto($res["postcondition"]);
   $img = $res["imagePath"];
   $imgdid = check_punto($res["didascalia"]);
   $act = check_punto($res["actors"]);
   $scen = $res["scene"];
   $altsc = $res["alternativeScene"];

   //////////////////////////////
   // Latex non permette più di un punto nell'estensione dei file immagine
   $img = str_replace(".","_",$img);
   $img = str_replace("_png",".png",$img);
   //////////////////////////////
   // '\FloatBarrier'."\n";
   $txt = '';
   if($img != '' && !preg_match('/^UC0$/',$uc)){
      $txt .= "\\clearpage\n\n";
   }
   $txt .= "\\subsection{Caso d'uso $uc: $title}\n";
   $txt .= "\\begin{itemize}\n";


   if($img != ''){
      $txt .=<<<EOF
   \\FloatBarrier
   \\begin{figure}[ht]
   \\centering
   \\includegraphics[scale=0.45]{img/$img}
   \\caption{{$imgdid}}
\\end{figure}
\\FloatBarrier

EOF;
   }
   $txt .= "\\item[]\\textbf{Descrizione:} $des\n";
   $txt .= "\\item[]\\textbf{Attori:} $act \n";
   $txt .= "\\item[]\\textbf{Precondizione:} $pre \n";
   $txt .= "\\item[]\\textbf{Postcondizione:} $post \n";
   if($scen != ''){
      $txt .= "\\item[]\\textbf{Scenario:}\n";
      $txt .= "$scen \n";
   }
   $txt .= "\\end{itemize}\n\n";
   // Elimina gli a capo stile windows creati da PHPmyadmin
   $txt = str_replace("\r\n","\n",$txt);
   $txt = str_replace("\r","\n",$txt);
   $data[$uc] = $txt;
}


// elenco delle chiavi da riordinare
$ks = array_keys($data);
// riordino con la mia funzione  di confronto
usort($ks,"compare_uc");
$FH = fopen("uccases.tex", "w");
// scrivere nell'ordine giusto
foreach($ks as $k){
   fwrite($FH,$data[$k]);
}
fclose($FH);


?>
