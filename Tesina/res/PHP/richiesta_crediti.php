<?php
    session_start();

    require('connection.php');
    require('funzioni.php');

    $connessione = new mysqli($host, $user, $password, $db);

    //mi prendo i valori passati tramite campo hidden
    $crediti = $_POST['crediti'];
    $id = $_POST['id'];
    $dataT = $_POST['data'];    //dataXML

    $pathXml = ('../XML/richieste_crediti.xml');

    $document = new DOMDocument();
    $document->load($pathXml);

    $richieste_doc = $document->getElementsByTagName('richiesta');

    //in questo caso la richiesta è accettata
    if (isset($_POST['bottone_si'])) {

        //allora prima di tutto devo aggiornare i campi nella tabella
        //mi prendo i crediti attuali, sommo quelli della richiesta e aggiorno
        $query = "SELECT umn.crediti FROM utenteDati ud  INNER JOIN utenteMangaNett umn ON ud.id = umn.id  WHERE umn.id = '$id'";

        $result = $connessione->query($query);

        //Verifico se la query ha restituito risultati
        if ($result) {

            //Estraggo il risultato come un array associativo
            $row = $result->fetch_assoc();
        } 
        
        else {
            echo "Errore nella query: " . $connessione->error;
        }
        
        //sommo
        $totale = $crediti + $row['crediti']; 

        //aggiorno
        $query_update = "UPDATE utenteMangaNett 
                        SET crediti = '$totale' 
                        WHERE id = '$id'";

        $result_up = $connessione->query($query_update);

        //Verifico se la query ha restituito risultati
        if (!$result_up) {
            echo "Errore nella query: " . $connessione->error;
        } 
        
        //bene ora aggiorno il documento e poi è fatta
        //bene, richiesta rifiutata, bisogna modificare l'esito e per fare ciò mi trovo la richiesta con id e dataT
        $richieste = getRichiesteCr($pathXml);

        foreach($richieste as $richiesta){
 
            if($richiesta['IDUtente'] == $id){
 
                if($richiesta['dataRichiesta'] == $dataT){
                    $richiesta['risposta'] = 1; 
                    break;
                }
            }
        }

        //bene ora ho aggiornato l'array, tocca modificare ora il file xml
        foreach($richieste_doc as $richiesta_doc){
             
            //mi prendo l'id e la data come identificativo della richiesta corrente
            $id_doc = $richiesta_doc->getElementsByTagName('IDUtente')->item(0)->nodeValue;
            $data_doc = $richiesta_doc->getElementsByTagName('dataRichiesta')->item(0)->nodeValue;
 
            if($id === $id_doc){
 
                if($dataT === $data_doc){
                     
                    //superati questi controlli ora mi trovo nel posto giusto e aggiorno la richiesta con l'esito negativo
                    $richiesta_doc->getElementsByTagName('IDUtente')->item(0)->nodeValue = $richiesta['IDUtente'];
                    $richiesta_doc->getElementsByTagName('quantita')->item(0)->nodeValue = $richiesta['quantita'];
                    $richiesta_doc->getElementsByTagName('dataRichiesta')->item(0)->nodeValue = $richiesta['dataRichiesta'];
                    $richiesta_doc->getElementsByTagName('risposta')->item(0)->nodeValue = $richiesta['risposta'];
 
                    break;
                }
 
            }
        }


    } 
    
    //in questo caso la richiesta è rifutata
    else if (isset($_POST['bottone_no'])) {

        //bene, richiesta rifiutata, bisogna modificare l'esito e per fare ciò mi trovo la richiesta con id e dataT
        $richieste = getRichiesteCr($pathXml);

        foreach($richieste as $richiesta){

            if($richiesta['IDUtente'] == $id){

                if($richiesta['dataRichiesta'] == $dataT){
                    $richiesta['risposta'] = -1; 
                    break;
                }
            }
        }
        //bene ora ho aggiornato l'array, tocca modificare ora il file xml

        foreach($richieste_doc as $richiesta_doc){
            
            //mi prendo l'id e la data come identificativo della richiesta corrente
            $id_doc = $richiesta_doc->getElementsByTagName('IDUtente')->item(0)->nodeValue;
            $data_doc = $richiesta_doc->getElementsByTagName('dataRichiesta')->item(0)->nodeValue;

            if($id === $id_doc){

                if($dataT === $data_doc){
                    
                    //superati questi controlli ora mi trovo nel posto giusto e aggiorno la richiesta con l'esito negativo
                    $richiesta_doc->getElementsByTagName('IDUtente')->item(0)->nodeValue = $richiesta['IDUtente'];
                    $richiesta_doc->getElementsByTagName('quantita')->item(0)->nodeValue = $richiesta['quantita'];
                    $richiesta_doc->getElementsByTagName('dataRichiesta')->item(0)->nodeValue = $richiesta['dataRichiesta'];
                    $richiesta_doc->getElementsByTagName('risposta')->item(0)->nodeValue = $richiesta['risposta'];

                    break;
                }

            }
        }
        
    }

    // Salva il documento XML aggiornato nel file
    $document->save($pathXml);

    mysqli_close($connessione);

    header('Location:../../richiesta_crediti.php');

?>