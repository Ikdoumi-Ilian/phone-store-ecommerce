import csv
import os
import json
import numpy as np
from sklearn.linear_model import Perceptron

DOSSIER = os.path.dirname(os.path.abspath(__file__))

TRAIN_DIR = os.path.join(DOSSIER, "sampled_train")
META_FILE = os.path.join(DOSSIER, "annotations_metadata.csv")
STOPWORD_FILE = os.path.join(DOSSIER, "stopwords.txt")

LABELS = {
    "hate": 0,
    "nohate": 1
}


def load_labels():
    labels = {}

    with open(META_FILE, newline="", encoding="utf-8-sig") as fichier:
        for ligne in csv.DictReader(fichier):
            file_id = ligne["file_id"].strip()
            label = ligne["label"].strip().lower()

            if label in LABELS:
                labels[file_id] = LABELS[label]

    return labels


def read_dataset():
    labels = load_labels()
    dataset = []

    for nom in sorted(os.listdir(TRAIN_DIR)):
        if not nom.endswith(".txt"):
            continue

        file_id = os.path.splitext(nom)[0]

        if file_id not in labels:
            continue

        chemin = os.path.join(TRAIN_DIR, nom)

        with open(
            chemin,
            encoding="utf-8",
            errors="replace"
        ) as fichier:
            texte = fichier.read().strip()

        dataset.append((texte, labels[file_id]))

    return dataset


def load_stopword():
    stopwords = set()

    with open(STOPWORD_FILE, encoding="utf-8-sig") as fichier:
        for ligne in fichier:
            mot = ligne.strip().lower()

            if mot:
                stopwords.add(mot)

    return stopwords


def uniformize_text(text, stopwords):
    symboles = "!.,;:.&\"'()[]{@}<>?@#$%^*+-=~`|/\\_1234567890\n\t"

    accents = "éèàîïêâëÉÈÀÎÏÊÂËùûüÙÛÜôöÔÖîïÎÏ"
    sansaccents = "eeaiieaeeeaiieaeuuuuuuooooiiii"

    for symbole in symboles:
        text = text.replace(symbole, " ")

    for i in range(len(accents)):
        text = text.replace(accents[i], sansaccents[i])

    text = text.lower()

    mots_conserves = []

    for mot in text.split():
        if mot not in stopwords:
            mots_conserves.append(mot)

    return " ".join(mots_conserves)


def buidVector(dataset):
    liste_mots = {}

    for texte, label in dataset:
        for mot in texte.split():
            if mot not in liste_mots:
                liste_mots[mot] = 0

            liste_mots[mot] += 1

    return liste_mots


def buildIndex(ordreMot):
    index = {}

    for i in range(len(ordreMot)):
        index[ordreMot[i]] = i

    return index


def tfidf(text, nbOccurence, ordreMot, indexListMot):
    mots = text.split()
    vecteur = np.zeros(len(ordreMot))

    if not mots:
        return vecteur

    for mot in mots:
        index = indexListMot[mot]
        vecteur[index] += 1 / len(mots) * 1 / nbOccurence[mot]

    return vecteur


if __name__ == "__main__":
    data = read_dataset()

    if not data:
        raise ValueError(
            "Aucun texte chargé. Vérifiez le dossier et le CSV."
        )

    print(len(data), "textes chargés")

    stopwords = load_stopword()
    print(len(stopwords), "stopwords chargés")

    data_uniformized = []

    for texte, label in data:
        texte_nettoye = uniformize_text(texte, stopwords)
        data_uniformized.append((texte_nettoye, label))

    listeMots = buidVector(data_uniformized)

    if not listeMots:
        raise ValueError("Aucun mot conservé après le nettoyage.")

    ordreVecteur = list(listeMots.keys())
    indexListMot = buildIndex(ordreVecteur)

    print(len(ordreVecteur), "mots différents")

    tfidf_vectors = []
    tfidf_labels = []

    for texte, label in data_uniformized:
        vecteur = tfidf(
            texte,
            listeMots,
            ordreVecteur,
            indexListMot
        )

        tfidf_vectors.append(vecteur)
        tfidf_labels.append(label)

    print("Entraînement du perceptron...")

    clf = Perceptron(
        tol=1e-8,
        random_state=0,
        verbose=True,
        max_iter=10000
    )

    clf.fit(tfidf_vectors, tfidf_labels)

    poids = {}

    for i in range(len(ordreVecteur)):
        mot = ordreVecteur[i]
        poids[mot] = float(clf.coef_[0][i])

    chemin_poids = os.path.join(DOSSIER, "poids.json")

    with open(chemin_poids, "w", encoding="utf-8") as fichier:
        json.dump(
            poids,
            fichier,
            ensure_ascii=False,
            indent=4
        )

    parametres = {
        "biais": float(clf.intercept_[0]),
        "occurrences": listeMots,
        "stopwords": sorted(stopwords),
        "labels": LABELS
    }

    chemin_parametres = os.path.join(
        DOSSIER,
        "parametres_modele.json"
    )

    with open(chemin_parametres, "w", encoding="utf-8") as fichier:
        json.dump(
            parametres,
            fichier,
            ensure_ascii=False,
            indent=4
        )

    print("Entraînement terminé.")
    print("Fichier créé :", chemin_poids)
    print("Fichier créé :", chemin_parametres)
    