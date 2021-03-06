<?php
//CALCULANDO DIAS NORMAIS
      //LISTA DE FERIADOS NO ANO
      function Feriados($ano,$posicao){
         $dia = 86400;
         $datas = array();
         $datas['pascoa'] = easter_date($ano);
         $datas['sexta_santa'] = $datas['pascoa'] - (2 * $dia);
         $datas['carnaval'] = $datas['pascoa'] - (47 * $dia);
         $datas['corpus_cristi'] = $datas['pascoa'] + (60 * $dia);
         $feriados = array (
            '24/02',
            '25/02',
			'26/02',	
			// Navegantes
            date('d/m',$datas['carnaval']),
            date('d/m',$datas['sexta_santa']),
            date('d/m',$datas['pascoa']),           
			'18/04',
            '08/05',				
            date('d/m',$datas['corpus_cristi']),
            '20/09', // Revolução Farroupilha \m/
            '12/10',
            '02/11',
            '15/11',
            '25/12',
         );
         
      return $feriados[$posicao]."/".$ano;
      }      

      //FORMATA COMO TIMESTAMP
      function dataToTimestamp($data){
         $ano = substr($data, 6,4);
         $mes = substr($data, 3,2);
         $dia = substr($data, 0,2);
      return mktime(0, 0, 0, $mes, $dia, $ano);  
      } 

      //SOMA 01 DIA
      function Soma1dia($data){
         $ano = substr($data, 6,4);
         $mes = substr($data, 3,2);
         $dia = substr($data, 0,2);
      return   date("d/m/Y", mktime(0, 0, 0, $mes, $dia+1, $ano));
      }
      
      function SomaDiasUteis($xDataInicial,$xSomarDias){
         for($ii=1; $ii<=$xSomarDias; $ii++){
            
            $xDataInicial=Soma1dia($xDataInicial); //SOMA DIA NORMAL
            /*
			0 = Domingo, 
			1 = Segunda,
			2 = terca
			3 = quarta,
			4 = quinta ,
			5 = sexta,
			6 = sabado, */
            //VERIFICANDO SE EH DIA DE TRABALHO
            if(date("w", dataToTimestamp($xDataInicial))=="0"){
               //SE DIA FOR DOMINGO OU FERIADO, SOMA +1
               $xDataInicial=Soma1dia($xDataInicial);
			   
               
            }else if(date("w", dataToTimestamp($xDataInicial))=="6"){
               //SE DIA FOR SABADO, SOMA +2
               $xDataInicial=Soma1dia($xDataInicial);
               $xDataInicial=Soma1dia($xDataInicial);
               
            }
			
			else{
               //senaum vemos se este dia eh FERIADO
               for($i=0; $i<=12; $i++){
                  if($xDataInicial==Feriados(date("Y"),$i)){
                    $xDataInicial=Soma1dia($xDataInicial);
					$xDataInicial=Soma1dia($xDataInicial);
                  }
               }
            }
         }
      return $xDataInicial;
      }
?>