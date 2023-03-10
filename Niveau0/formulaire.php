<div id="wrapper" >
            <main>
                <article>
                    <h2>Poster un message</h2>
                    <?php
                    /**
                     * BD
                     */
                    $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
                    /**
                     * TRAITEMENT DU FORMULAIRE
                     */
                    // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
                    // si on recoit un champs email rempli il y a une chance que ce soit un traitement
                    $enCoursDeTraitement = isset($_SESSION['connected_id']);
                    if ($enCoursDeTraitement)
                    {
                        // on ne fait ce qui suit que si un formulaire a été soumis.
                        // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                        // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                        echo "<pre>" . print_r($_POST, 1) . "</pre>";
                        // et complétez le code ci dessous en remplaçant les ???
                        $authorId = $_SESSION['connected_id'];

                        $date = new DateTime();

                        //Etape 3 : Petite sécurité
                        // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                        $authorId = intval($mysqli->real_escape_string($authorId));
                        //Etape 4 : construction de la requete

                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validation'])) {
                            if (!empty($_POST['message'])) { 
                                $postContent = $_POST['message'];
                                $postContent = $mysqli->real_escape_string($postContent);
                                $lInstructionSql = "INSERT INTO posts (user_id, content, created) VALUES ('".$authorId."', '".$postContent."', '".$date->format('Y-m-d H:i:s')."')";
                                if (!$postContent) {
                                    $errors[] = 'There is no message';
                                } else {
                                    $ok = $mysqli->query($lInstructionSql);
                                    if ( ! $ok)
                                    {
                                        echo "Impossible d'ajouter le message: " . $mysqli->error;
                                    } else
                                    {
                                        echo "Message posté en tant que :" . $_SESSION["connected_id"];
                                    }
                                }
                            }
                        }
                    }
                    ?>                     
                    <form action="" method="post">
                        <input type='hidden' name='???' value='achanger'>
                        <dl>
                            <dt><label for='message'>Message</label></dt>
                            <dd><textarea name='message'></textarea></dd>
                        </dl>
                        <input type='submit' name="validation">
                    </form>               
                </article>
            </main>
        </div>
    </body>
</html>
