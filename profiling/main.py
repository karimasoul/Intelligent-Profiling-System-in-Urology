import sys
import pandas as pd
from sklearn.preprocessing import OneHotEncoder
from sklearn.ensemble import GradientBoostingClassifier
import lightgbm as lgb
from db_connection import connect_to_db


actePrincipale = sys.argv[1]
acteSecondaire = sys.argv[2]
org = sys.argv[3]
cat = sys.argv[4]
date_debut = sys.argv[5]
date_fin = sys.argv[6]


# Charger les données à partir de la base de données
conn = connect_to_db()

try:
    # Query ID de "actePrincipale"
    query = f"SELECT id_apap FROM activite_pratique_acteprincipal WHERE nom_actePrincipal = '{actePrincipale}'"
    cursor = conn.cursor()
    cursor.execute(query)
    id_apap = cursor.fetchone()[0]  # Fetching the first element from the tuple

    # Query2  ID de "acteSecona"
    query2 = f"SELECT id_apas FROM activite_pratique_actesecondaire WHERE nom_acteSecondaire = '{acteSecondaire}'"
    cursor.execute(query2)
    id_apas = cursor.fetchone()[0]

    # Query3  ID de "org"
    query3 = f"SELECT id_aporg FROM activite_pratique_organe WHERE nom_org = '{org}'"
    cursor.execute(query3)
    id_org = cursor.fetchone()[0]

    # Query4  ID pour "cat"
    query4 = f"SELECT id_apc FROM activite_pratique_categorie WHERE nom_cat = '{cat}'"
    cursor.execute(query4)
    id_cat = cursor.fetchone()[0]

    #niveau_data
    niveau_data = pd.read_sql_query("""
            SELECT niveau, id_resid
            FROM annee_res_univ
        """, connect_to_db())

except Exception as e:
    print("An error occurred during database queries:", e)
finally:

    conn.close()

# Prétraitement des données
# Charger les données d'entraînement à partir de la base de données
try:

    import time

    # Enregistrer le temps de début
    start_time = time.time()


    train_data = pd.read_sql_query("""
        SELECT id_resid, id_apap, id_aporg, id_apas, id_apc, medecin_valid, role_resid 
        FROM relation_actp_resid_med
        WHERE medecin_valid IS NOT NULL
        AND (id_resid NOT IN (
            SELECT DISTINCT id_resid
            FROM demandes_conge
            WHERE (date_debut <= %s AND date_fin >= %s) AND accepter=1  # Vérifie si la période de congé intersecte la période de l'acte
        ) OR NOT EXISTS (
            SELECT 1
            FROM demandes_conge
            WHERE (date_debut <= %s AND date_fin >= %s)
        ))
    """, connect_to_db(), params=(date_fin, date_debut, date_fin, date_debut))

    #print("Train data shape:", train_data.shape)



    # Fusionner train_data et niveau_data sur la colonne id_resid
    train_data = pd.merge(train_data, niveau_data, on='id_resid', how='inner')


    # Entraîner un modèle  pour chaque rôle
    models = {}
    #train_data = train_data.sample(frac=0.5, random_state=42)
    for role in train_data['role_resid'].unique():
        # Filtrer les données pour le rôle actuel
        role_data = train_data[train_data['role_resid'] == role]
        X = role_data[['id_apap', 'id_apas', 'id_aporg', 'id_apc']]
        y = role_data['id_resid']

        # Encoder les variables de catégorie avec handle_unknown='ignore'
        encoder = OneHotEncoder(handle_unknown='ignore')
        X_encoded = encoder.fit_transform(X)

        # Entraîner le modèle de lgbm



        lgbm_params = {
            'verbose': -1,  # supprimer les msg infos
        }
        model = lgb.LGBMClassifier(**lgbm_params)
        model.fit(X_encoded, y)
        models[role] = {'model': model, 'encoder': encoder}

    # Prédire les résidents pour tous les rôles
    all_predicted_residents = []
    for role, model_info in models.items():
        data = [[id_apap, id_apas, id_org, id_cat]]
        encoder = model_info['encoder']
        model = model_info['model']
        X_data = encoder.transform(data)
        predicted_probabilities = model.predict_proba(X_data)[0]
        all_predicted_residents.extend(
            [(role, resid, prob) for resid, prob in zip(model.classes_, predicted_probabilities)])

    # Trier les résidents prédits par score de prédiction
    sorted_predicted_residents = sorted(all_predicted_residents, key=lambda x: x[2], reverse=True)

    # Attribuer les résidents aux rôles et le résident ne doit pas etre utilisé deux fois
    assigned_residents = {}
    used_residents = set()
    for role, resid, _ in sorted_predicted_residents:
        if resid not in used_residents:
            assigned_residents[role] = resid
            used_residents.add(resid)


    # les résidents à des rôles
    roles_order = ['Operateur aide', 'Acte Maitrise', 'Aide Operateur', 'Observateur']
    sorted_residents = [assigned_residents[role] for role in roles_order if role in assigned_residents]

    # output
    for i, resident in enumerate(sorted_residents):
        print(f"Resident {i + 1}: {resident}")



    # Enregistrer le temps de fin
    end_time = time.time()

    # Calculer la durée totale d'exécution
    execution_time = end_time - start_time

    # Imprimer la durée d'exécution
    #print("Temps d'exécution:", execution_time, "secondes")

except Exception as e:
    print("An error occurred during data processing:", e)