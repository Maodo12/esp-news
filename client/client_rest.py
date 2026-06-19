#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import requests
import json
import sys

class UserClientRest:
    def __init__(self):
        self.base_url = 'http://localhost:8000'
        
    def list_articles(self):
        try:
            response = requests.get(f'{self.base_url}/api.php?action=articles&format=json')
            if response.status_code == 200:
                data = response.json()
                print("\n📰 Liste des articles :")
                print("-" * 60)
                for article in data:
                    print(f"ID: {article['id']} - {article['titre']} ({article['categorie_libelle']})")
                print("-" * 60)
                print(f"Total: {len(data)} article(s)")
            else:
                print(f"❌ Erreur : {response.status_code}")
        except Exception as e:
            print(f"❌ Erreur : {e}")
    
    def list_articles_by_categorie(self, categorie_id):
        try:
            response = requests.get(f'{self.base_url}/api.php?action=articles_categorie&id={categorie_id}&format=json')
            if response.status_code == 200:
                data = response.json()
                if not data:
                    print(f"\n📰 Aucun article dans cette catégorie")
                    return
                print(f"\n📰 Articles de la catégorie {categorie_id} :")
                print("-" * 60)
                for article in data:
                    print(f"ID: {article['id']} - {article['titre']}")
                print("-" * 60)
                print(f"Total: {len(data)} article(s)")
            else:
                print(f"❌ Erreur : {response.status_code}")
        except Exception as e:
            print(f"❌ Erreur : {e}")
    
    def get_statistiques(self):
        try:
            response = requests.get(f'{self.base_url}/api.php?action=articles_categories&format=json')
            if response.status_code == 200:
                data = response.json()
                print("\n📊 Statistiques par catégorie :")
                print("-" * 60)
                total = 0
                for item in data:
                    print(f"  {item['categorie']} : {item['total']} article(s)")
                    total += item['total']
                print("-" * 60)
                print(f"Total : {total} article(s)")
            else:
                print(f"❌ Erreur : {response.status_code}")
        except Exception as e:
            print(f"❌ Erreur : {e}")
    
    def run(self):
        print("\n" + "=" * 50)
        print("📰 ESP NEWS - Client REST")
        print("=" * 50)
        
        while True:
            print("\n" + "=" * 50)
            print("📋 MENU PRINCIPAL")
            print("=" * 50)
            print("1. 📰 Lister tous les articles")
            print("2. 📂 Articles par catégorie")
            print("3. 📊 Statistiques par catégorie")
            print("0. 🚪 Quitter")
            print("-" * 50)
            
            choix = input("Votre choix : ")
            
            if choix == '1':
                self.list_articles()
            elif choix == '2':
                try:
                    print("\n1. Sport")
                    print("2. Santé")
                    print("3. Education")
                    print("4. Politique")
                    cat_id = int(input("ID de la catégorie (1-4) : "))
                    if 1 <= cat_id <= 4:
                        self.list_articles_by_categorie(cat_id)
                    else:
                        print("❌ ID invalide (1-4)")
                except ValueError:
                    print("❌ Veuillez entrer un nombre")
            elif choix == '3':
                self.get_statistiques()
            elif choix == '0':
                print("👋 Au revoir !")
                break
            else:
                print("❌ Choix invalide")

if __name__ == '__main__':
    client = UserClientRest()
    client.run()