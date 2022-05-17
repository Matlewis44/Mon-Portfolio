function verif()
		{
			var nomUtilisateur = document.forms["modifierMotDePasse"]["nomUtilisateur"].value;
			return confirm('Voulez-vous modifier le mot de passe de l\'utilisateur ' + nomUtilisateur + ' ?');
		}