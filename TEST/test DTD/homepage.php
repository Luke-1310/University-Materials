<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>Form Libri</title>
</head>

<body>

    <form action="inserisci_libro.php" method="post">
        
        <fieldset>
            <legend>Libro</legend>
            
            <div>
                <label for="isbn">ISBN:</label>
                <input type="text" id="isbn" name="isbn" required>
            </div>
            
            <div>
                <label for="rating">Rating:</label>
                <select id="rating" name="rating">
                    <option value="sufficiente">Sufficiente</option>
                    <option value="buono">Buono</option>
                    <option value="ottimo">Ottimo</option>
                </select>
            </div>
            
            <div>
                <label for="nomeAutore">Nome Autore:</label>
                <input type="text" id="nomeAutore" name="nomeAutore" required>
            </div>
            
            <div>
                <label for="cognomeAutore">Cognome Autore:</label>
                <input type="text" id="cognomeAutore" name="cognomeAutore" required>
            </div>
            
            <div>
                <label for="titolo">Titolo:</label>
                <input type="text" id="titolo" name="titolo" required>
            </div>
            
            <div>
                <label for="anno">Anno:</label>
                <input type="number" id="anno" name="anno">
            </div>
            
            <div>
                <label for="editore">Editore:</label>
                <input type="text" id="editore" name="editore" required>
            </div>
            
            <div>
                <input type="submit" value="Aggiungi Libro">
            </div>
        </fieldset>
    </form>

</body>
