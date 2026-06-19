#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import zeep
import getpass
import sys

class UserClient:
    def __init__(self):
        # En mode non-WSDL, on utilise l'URL du serveur directement
        self.wsdl = 'http://localhost:8000/soap_server.php'
        self.client = None
        self.token = None
        self.user_id = None
        self.role = None
        
    def connect(self):
        try:
            # Utiliser ServiceProxy au lieu de Client pour non-WSDL
            from zeep import Client
            self.client = Client(self.wsdl)
            print("✅ Connexion au service SOAP réussie")
            return True
        except Exception as e:
            print(f"❌ Erreur de connexion : {e}")
            return False
    
    def login(self, login, password):
        try:
            result = self.client.service.authenticate(login, password)
            if result['success']:
                self.token = result['token']
                self.role = result['role']
                self.user_id = result['id']
                print(f"✅ Authentification réussie ! Rôle : {self.role}")
                return True
            else:
                print(f"❌ Échec : {result.get('message', 'Erreur inconnue')}")
                return False
        except Exception as e:
            print(f"❌ Erreur : {e}")
            return False
    
    def list_users(self):
        try:
            users = self.client.service.listUsers(self.token)
            print("\n👥 Liste des utilisateurs :")
            print("-" * 60)
            print(f"{'ID':<5} {'Login':<20} {'Rôle':<15}")
            print("-" * 60)
            for user in users:
                print(f"{user['id']:<5} {user['login']:<20} {user['role']:<15}")
            print("-" * 60)
        except Exception as e:
            print(f"❌ Erreur : {e}")
    
    def add_user(self, login, password, role='visiteur'):
        try:
            result = self.client.service.addUser(self.token, login, password, role)
            if result['success']:
                print(f"✅ Utilisateur ajouté avec l'ID : {result['id']}")
            else:
                print(f"❌ Erreur : {result.get('error', 'Inconnue')}")
        except Exception as e:
            print(f"❌ Erreur : {e}")
    
    def delete_user(self, user_id):
        try:
            result = self.client.service.deleteUser(self.token, user_id)
            if result['success']:
                print(f"✅ Utilisateur {user_id} supprimé")
            else:
                print(f"❌ Erreur : {result.get('error', 'Inconnue')}")
        except Exception as e:
            print(f"❌ Erreur : {e}")
    
    def run(self):
        if not self.connect():
            return
        
        print("\n🔐 Authentification requise")
        login = input("Login : ")
        password = getpass.getpass("Mot de passe : ")
        
        if not self.login(login, password):
            return
        
        while True:
            print("\n" + "=" * 50)
            print("📋 MENU PRINCIPAL")
            print("=" * 50)
            print("1. Lister les utilisateurs")
            if self.role == 'administrateur':
                print("2. Ajouter un utilisateur")
                print("3. Supprimer un utilisateur")
            print("0. Quitter")
            print("-" * 50)
            
            choix = input("Votre choix : ")
            
            if choix == '1':
                self.list_users()
            elif choix == '2' and self.role == 'administrateur':
                login = input("Login : ")
                password = getpass.getpass("Mot de passe : ")
                role = input("Rôle (visiteur/editeur/administrateur) : ") or 'visiteur'
                self.add_user(login, password, role)
            elif choix == '3' and self.role == 'administrateur':
                try:
                    user_id = int(input("ID de l'utilisateur à supprimer : "))
                    self.delete_user(user_id)
                except ValueError:
                    print("❌ ID invalide")
            elif choix == '0':
                print("👋 Au revoir !")
                break
            else:
                print("❌ Choix invalide")

if __name__ == '__main__':
    client = UserClient()
    client.run()