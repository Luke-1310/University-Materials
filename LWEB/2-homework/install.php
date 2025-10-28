<?php

require_once('res/PHP/connection.php');

// Crea la connessione col server
$conn = new mysqli($host, $user, $password);

//Controllo sulla connessione
if($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Crea il database se non esiste già
$sql = "CREATE DATABASE IF NOT EXISTS $db";

if ($conn->query($sql) === FALSE) {
    echo "Errore nella creazione del database " . $conn->error;
}

// Seleziona il database con cui vogliamo operare
$conn = new mysqli($host, $user, $password, $db);

function convert_img($path, $conn){
    
    $img_bin = file_get_contents($path);

    // Codifica l'immagine in binario utilizzando base64
    $img_base64 = base64_encode($img_bin);
    $img = $conn->real_escape_string($img_base64);

    return $img;
}

//Crea la tabella utente se non esistente
//E' necessario mettere al campo id 'AUTO_INCREMENT' altrimenti inserito il secondo utente darebbe errore
//N.B. è essenziale porre l'accento grave ` al posto dell'apostrofo ' altrimenti darebbe errore -_-
$tab_utente = "CREATE TABLE IF NOT EXISTS `utente` (
    `id` int(11) NOT NULL AUTO_INCREMENT,               
    `username` varchar(30) NOT NULL,
    `email` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    PRIMARY KEY (id)
)";

if ($conn->query($tab_utente) === FALSE) {
    echo "Errore nella creazione della tabella utente " . $conn->error;
}

//Crea la tabella libro se non esistente
$tab_libro = "CREATE TABLE IF NOT EXISTS `libro`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `titolo` varchar(50) NOT NULL,
    `ISBN13` varchar(13) NOT NULL,
    `lunghezza` int(4) NOT NULL,
    `data_uscita` date NOT NULL,
    `immagine` BLOB NOT NULL,
    `autore` varchar(50) NOT NULL,
    PRIMARY KEY (id)
   
)";

if ($conn->query($tab_libro) === FALSE) {
    echo "Errore nella creazione della tabella libro " . $conn->error;
}

//Crea la tabella recensione se non esistente
$tab_recensione = "CREATE TABLE IF NOT EXISTS `recensione`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `utente_ID` int(11) NOT NULL,
    `libro_ID` int(11) NOT NULL,
    `testo` varchar(800) NOT NULL,
    `voto` decimal(2,1) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (utente_ID) REFERENCES utente(id),
    FOREIGN KEY (libro_ID) REFERENCES libro(id)
)";

if ($conn->query($tab_recensione) === FALSE) {
    echo "Errore nella creazione della tabella utente " . $conn->error;
}

$ins_ut = "INSERT INTO `utente` (`username`, `email`, `password`) VALUES
('Luke', 'luke@gmail.com', '" . password_hash('123', PASSWORD_DEFAULT) . "'),
('Ale_Col', 'ale@gmail.com', '" . password_hash('123', PASSWORD_DEFAULT) . "')";

if ($conn->query($ins_ut) === FALSE) {
    echo "Errore nell'inserimento degli utenti " . $conn->error;
}

$img1 = convert_img('res/IMG_GIF/copertina_spy.jpg', $conn);
$img2 = convert_img('res/IMG_GIF/copertina_900.jpg', $conn);
$img3 = convert_img('res/IMG_GIF/copertina_cane.jpg', $conn);
$img4 = convert_img('res/IMG_GIF/copertina_dost.jpg', $conn);

$ins_lib = "INSERT INTO `libro` (`titolo`, `ISBN13`, `lunghezza`, `data_uscita`, `immagine`, `autore`) VALUES
('Spy x Family 1', '9788418271236', 216, '2023-05-06', '". $img1 . "' ,'Tastuya Endo'), 
('Novecento', '9783455000955', 62, '1994-02-01', '". $img2 . "' ,'Alessandro Baricco'),
('Il fotocane', '9788868364601', 211, '2018-06-12', '". $img3 . "' ,'Stephen King'),
('I fratelli Karamazov', '8807900792', 1114, '1879-01-01', '". $img4 . "' ,'Fedor Dostoevjski')"; 

if ($conn->query($ins_lib) === FALSE) {
    echo "Errore nell'inserimento dei libri " . $conn->error;
}

$ins_rec = "INSERT INTO `recensione` (`utente_ID`, `libro_ID`, `testo`, `voto`) VALUES
('1', '1', 'Pensavo fosse un manga passeggero, uno dei tanti, ed invece questo fumetto mi ha davvero conquistato! Esso racconta la storia di una spia, Twilight, che per compiere la sua missione si ritrova costretto ad inscenare una falsa famiglia insieme ad una moglie assassina e ad una figlia telepate! Il bello di questa strana famiglia è che nessuno dei membri conosce la vera identità di ciascuno ed è proprio questo il punto focale del manga, cioè che alla fine della fiera nessuno conosce davvero chi gli sta accanto. La storia dei protagonisti è poi condita con scene comiche al limite del surreale rendendo quindi il manga a tratti molto leggero ma a tratti molto profondo e riflessivo. Estremamente consigliato!', 9.5), 
('2', '4', 'Da leggere assolutamente. Per Dostoevskij Hermann Hesse scriveva: Egli ha quella conoscenza della felicità, della saggezza e armonia, che però non si trovano lungo facili sentieri, ma lampeggiano a tratti lungo la via che costeggia la voragine, che non si colgono sorridendo, ma solo tra lacrime ed esausti di dolore. (...) Ci sono dei punti dove, da un mare di miseria e di squallore, si accende, commovente, infantile e delicato oltre ogni dire, qualcosa che è come la percezione del senso del mondo, come la certezza di una redenzione.Ed è infatti così che Dostoevskij riesce a descrivere i fratelli al lettore, profondamente facendolo entrare nella  psicologia dei personaggi e delle vicende che li riguardano.', 9.5)"; 

if ($conn->query($ins_rec) === FALSE) {
    echo "Errore nell'inserimento delle recensioni " . $conn->error;
}

//alla fine della creazione siverrà reindirizzati alla homepage
header('Location: homepage.php');

// Chiude la connessione al database
$conn->close();

?>