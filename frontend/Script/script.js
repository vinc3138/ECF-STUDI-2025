const images = document.querySelectorAll('.picture_style_02');

images.forEach(img => {
  img.addEventListener('click', () => {
    // Supprimer la classe 'zoomed' de toutes les images sauf celle cliquée
    images.forEach(other => {
      if (other !== img) {
        other.classList.remove('zoomed');
      }
    });

    // Toggle la classe zoomed sur l'image cliquée
    img.classList.toggle('zoomed');
  });
});


/**
 * Valider un mot de passe
 * 
 * Si le mot de passe n'est pas assez sécurisé, retournez false
 * 
 * @param mdp
 * @return Boolean


function validateMDP(mdp){
  var Reg = new RegExp(/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/);
  return Reg.test(mdp);
}

const mot_de_passe = document.querySelectorAll('password');

if(validateMDP(mot_de_passe)){
  alert('MDP valide !');
} else {
  alert('MDP non valide !');
}*/