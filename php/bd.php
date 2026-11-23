<?php
function getBD() {
    $bdd = new PDO('mysql:host=localhost;dbname=phonestore;charset=utf8', 'root', 'root');
    return $bdd;
}
function nettoyerPanierTampon($bdd, $idClient = null){
    $rep = $bdd->query("SELECT * FROM panier_tampon WHERE date_ajout <= DATE_SUB(NOW(), INTERVAL 5 MINUTE)");
    $expirees = $rep->fetchAll(PDO::FETCH_ASSOC);
    $rep->closeCursor();

    if(count($expirees) == 0){
        return;
    }

    $majStock = $bdd->prepare("UPDATE articles SET quantite = quantite + ? WHERE id_art = ?");
    $supprime = $bdd->prepare("DELETE FROM panier_tampon WHERE id = ?");
    $verifStock = $bdd->prepare("SELECT quantite FROM articles WHERE id_art = ?");

    foreach($expirees as $ligne){
        $verifStock->execute([$ligne["id_art"]]);
        $stock = $verifStock->fetch(PDO::FETCH_ASSOC);
        $verifStock->closeCursor();

        if($stock && $stock["quantite"] <= 5){
            $majStock->execute([$ligne["quantite"], $ligne["id_art"]]);
            $supprime->execute([$ligne["id"]]);
            // ********************

            if($idClient !== null && $ligne["id_client"] == $idClient && isset($_SESSION["panier"])){
                $reste = $bdd->prepare("SELECT COALESCE(SUM(quantite), 0) AS total FROM panier_tampon WHERE id_art = ? AND id_client = ?");
                $reste->execute([$ligne["id_art"], $idClient]);
                $ligneReste = $reste->fetch(PDO::FETCH_ASSOC);
                $reste->closeCursor();

                $quantiteRestante = (int)$ligneReste["total"];

                foreach($_SESSION["panier"] as $i => $article){
                    if($article["id_art"] == $ligne["id_art"]){
                        if($quantiteRestante <= 0){
                            unset($_SESSION["panier"][$i]);
                        } else {
                            $_SESSION["panier"][$i]["qte"] = $quantiteRestante;
                        }
                    }
                }
                $_SESSION["panier"] = array_values($_SESSION["panier"]);
            }
        }
    }
}
// ********************
?>
