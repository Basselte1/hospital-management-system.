<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture Chambre</title>

    <style>
        *{
            margin:0; padding:0; box-sixing:border-box;
        }

        body{
            font-family:DejaVu Sans, sans-serif;
            font-size:11px;
            line-height:1.4;
            color:#000;
        }
    </style>
</head>
<body>

    <div style="display: table; width: 100%;">
         <div style="display: table-cell; width: 100%; padding: 2%; vertical-align: top;">


            <div class="notices">
                <h6>LA CAISSE: {{ ($printer['prenom'] ?? '') . ' ' . ($printer['name'] ?? '') }}</h6>
                <h6>Douala, {{ isset($patient['created_at']) ? \Carbon\Carbon::parse($patient['created_at'])->format('d/m/Y') : date('d/m/Y') }}</h6>
            </div>
        </div>


        <footer style=" margin-top: 40px;">
            Centre Medico-chirurgical d'urologie situe a  la Valle Douala Manga Bell Douala-Bali.<br>
            TEL: (+ 237) 233 423 389 / 674 068 988 / 698 873 945<br>
            SITE WEB: http://www.cmcu-cm.com
        </footer> 
    </div>
    

</body>
</html>