<?php
    include("header.php");

    $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");

?>
<?php
    $abonnement = "wesh fonctionne stp";
    
    $userId = $_GET['user_id'];
    $connectedId = $_SESSION['connected_id']; 
    $isFollowing = false; 
    $checkFollow = "SELECT * FROM FOLLOWERS WHERE followed_user_id = '$userId' AND following_user_id = '$connectedId'";
    $resultCheckFollow = $mysqli->query($checkFollow);
    if ($resultCheckFollow -> num_rows > 0){
        $isFollowing = true ;
    }
    if ( ! $isFollowing)
    {
        $abonnement = "S'abonner";
    } else {
        $abonnement = "Se désabonner";
    } 
?>
        <div id="wrapper">
            <?php
            /**
             * Etape 1: Le mur concerne un utilisateur en particulier
             * La première étape est donc de trouver quel est l'id de l'utilisateur
             * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
             * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
             * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
             */
            $userId =intval($_GET['user_id']);
            ?>
            <aside>
                <?php
                /**
                 * Etape 3: récupérer le nom de l'utilisateur
                 */                
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
                //echo "<pre>" . print_r($user, 1) . "</pre>";

                if (isset($_POST['follow'])) {
                    $userId = intval($_SESSION['connected_id']);
                    $followId = $_GET["user_id"];
                    if($abonnement == "S'abonner"){
                    // Pour rester sur la session connectée quand on visite le profil d'un autre user
                    $newFollower = "INSERT INTO followers (followed_user_id, following_user_id)
                                    VALUES ('$followId', '$userId')";
                    // On ajoute un nouvel abonné dans la BDD 
                    $mysqli->query($newFollower);
                    header("Refresh:0");
                    } else {
                        $unfollow = "DELETE FROM followers WHERE followed_user_id = '$followId' AND following_user_id = '$userId'";
                        $mysqli->query($unfollow);
                        header("Refresh:0");
                    }
                }


                ?>

                
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les message de l'utilisatrice :  <?php echo $user['alias'] ?> 
                        (n° <?php echo $userId ?>)
                    </p>
                </section>

                <?php

                

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['follow'])) {

                    $userToFollow = intval($mysqli->real_escape_string($userToFollow));
                    $userFollowing = intval($mysqli->real_escape_string($userFollowing));

                    // Variables pour injecter les informations dans la base de donner
                    $lInstructionSql = "INSERT INTO followers (followed_user_id, following_user_id ) VALUES ('".$userToFollow."','".$userFollowing."')";

                    $LetsFollow = $mysqli->query($lInstructionSql);

                    if ( ! $LetsFollow)
                    {
                        echo "Impossible de follower: " . $mysqli->error;
                    } else
                    {
                        echo "Victoire je follow $userId";
                    }


                }
                
                ?>

                <section>
                    <p> 
                        <?php 
                        //echo "l'Id de l'utilisateur que je souhaite suivre : $userToFollow";
                        //echo "<br />";
                       // echo "Mon Id : $userFollowing";
                        ?>
                    </p>
                </section>
                <?php
                if ( "$userId" !== "$connectedId"){
                    ?>
                    <form action= "" method="post">
                    <input type="submit" name="follow" value="<?php echo $abonnement ?>">
                    </form>  
                <?php
                }
                ?>
            </aside>
            <main>

            <?php
                if ($_SESSION['connected_id'] == $userId) {
                    include_once("formulaire.php") ;
                }
            ?>
                <?php
                /**
                 * Etape 3: récupérer tous les messages de l'utilisatrice
                 */
                $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias as author_name, posts.user_id,   
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                /**
                 * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
                 */
                while ($post = $lesInformations->fetch_assoc())
                {

                    //echo "<pre>" . print_r($post, 1) . "</pre>";
                    ?>                
                    <article>
                        <h3>
                            <?php echo $post['created'] ?> 
                        </h3>
                        <address> <a href="wall.php?user_id=<?php echo $post['user_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                        <div>
                            <?php echo $post['content'] ?> 
                        </div>                                            
                        <footer>
                            <small>♥  <?php echo $post['like_number'] ?> </small>
                            <a href=""> <?php echo $post['taglist'] ?> </a>,
                        </footer>
                    </article>
                <?php } ?>


            </main>
        </div>
    </body>
</html>
