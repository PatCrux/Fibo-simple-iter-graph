<!DOCTYPE html>
<!--
    Affiche un formulaire, permet l'entrée d'un nombre et affiche la suite de Fibonacci correspondante
    Méthode de calcul iterative
    Modèle objet
-->
<html>
    <head>
        <meta charset="UTF-8">
        <style>
        h1 {
          font-family: arial, sans-serif;
        }
        h2 {
          font-family: arial, sans-serif;
        }
        table {
          font-family: arial, sans-serif;
          border-collapse: collapse;
          width: 100%;
        }

        td, th {
          border: 1px solid #dddddd;
          text-align: left;
          padding: 8px;
        }

        tr:nth-child(even) {
          background-color: #dddddd;
        }
        </style>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    </head>
    <body>
        <h1>Serie de Fibonacci</h1>
        <h2>Calcul itératif</h2>
        <table style="width:100%">
        <tr>
          <th>Données d'entrée</th>
          <th>Résultats</th>
        </tr>
        <tr>
            <td>
                <!--
                    Affiche le formulaire
                -->
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <label>Nombre d'éléments :</label><input type="text" name="rang_fibo" id="number"/><br><br>
                <input name="form" type="submit" value="Calculer"/>
                </form>
            </td>
            <td>
                <?php
                    // Fonction de Fibonacci, utilisée de manière iterative
                    class Fibo
                    {
                        private $result = array(); // Résultats stoqués pour affichage graphique
                        
                        // Vérifie si le formulaire a été 'soumis'
                        // Si oui, récupère le nombre entré par l'utilisateur, et affiche la suite de Fibonacci correspondante
                        public function Initialize ()
                        {
                            $index = -1;             // jusqu'à quel rang voulons calculer la suite de Fibonacci ?
                            if ($_SERVER["REQUEST_METHOD"]=="POST")
                            {
                                $index= $_POST['rang_fibo'];
                                if (is_numeric($index))
                                {
                                    if ($index>0)
                                        $index = intval ($index);
                                    else
                                        $index = -1;    // Retourne code d'erreur
                                }
                                else
                                    $index=-1;       // Retourne code d'erreur
                            }
                            return $index;
                        }

                        // Calcule et affiche la suite de Fibonacci jusqu'au rang $index
                        public function ComputeAndDisplay($index)
                        {
                            $num1= 1;
                            $num2= 1;
                            echo '0';
                            $counter= 1;
                            while($counter < $index)
                            {
                                echo ', '.$num1;
                                $this->result [$counter-1] = $num1;        // Stoque le résultat pour l'affichage du graphique
                                $num3= $num2 + $num1;
                                $num1= $num2;
                                $num2= $num3;
                                $counter= $counter+1;
                            }
                        }
						
						// Retourne la valeur de Fibonacci calculée par ComputeAndDisplay
						public function getResult ($idx)
						{
							if ($idx<count ($this->result))
								return $this->result[$idx];
							else
								return 0;
						}
                    }
                    
                    // Flux principal
                    $Fibonacci = new Fibo ();                           // Crée instance de l'objet Fibo
                    $rank = $Fibonacci->Initialize ();                  // Cherche l'entrée utilisateur et la valide
                    if ($rank>=0)                                       // Si entrée ok :
                    {
                        $Fibonacci->ComputeAndDisplay($rank);           // Calcule et affiche suite de Fibonacci correspondante

                        echo '<script>
                                google.charts.load(\'current\', {packages: [\'corechart\', \'line\']});
                                google.charts.setOnLoadCallback(drawChart);
                                function drawChart() {
                                var data = google.visualization.arrayToDataTable([
                                [\'Rang\', \'Valeur\'],
                                [1,0]
                              ]);';

                              for ($i=0; $i<$rank-1; $i++)
                                echo 'data.addRow ([' . ($i+2) . ',' . $Fibonacci->getResult($i) . ']); ';

                              echo 'var options = {
                                title: \'Valeurs de Fibonacci\',
                                legend: \'none\',
                                vAxis: {
                                title: \'Valeur\',
                                },
                                hAxis: {
                                title: \'Rang\',
                                },
                              };

                              var chart = new google.visualization.LineChart(document.getElementById(\'chart_div\'));

                              chart.draw(data, options);
                              }
                        </script>';
                    }
                    else                                                // Sinon
                        echo 'Veuillez svp entrer un nombre positif, plus grand que zéro';   // Affiche message d'erreur
                 ?>
            </td>
        </tr>
      </table>
      <div id="chart_div" style="width: 900px; height: 500px"></div>
    </body>
</html>
