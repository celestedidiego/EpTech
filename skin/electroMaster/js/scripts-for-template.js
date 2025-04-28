/*
// Funzione per aggiornare il numero quando si fa clic sul pulsante
function Aggiungi() {
    var numeroAttuale = parseInt(document.getElementById("numeroAcquisti").innerText);
    var nuovoNumero = numeroAttuale + 1;
    document.getElementById("numeroAcquisti").innerText = nuovoNumero;
}
*/
document.querySelectorAll('input[name="productType"]').forEach((elem) => {
    elem.addEventListener("change", function(event) {
      var prezzoasta = document.querySelector('.prezzo-asta');
      var datainizioasta = document.querySelector('.data-inizio-asta');
      var datafineasta = document.querySelector('.data-fine-asta');
      var pricefornew = document.querySelector('.price-for-new');
      if (event.target.value === 'usato') {
        prezzoasta.style.display = 'block';
        datainizioasta.style.display = 'block';
        datafineasta.style.display = 'block';
        pricefornew.style.display = 'none';
        document.getElementById('data-inizio-asta').required = true;
        document.getElementById('data-fine-asta').required = true;
        document.getElementById('prezzo-asta').required = true;
        document.getElementById('pricefornew').required = false;
      } else if(event.target.value === 'nuovo'){
        prezzoasta.style.display = 'none';
        datainizioasta.style.display = 'none';
        datafineasta.style.display = 'none';
        pricefornew.style.display = 'block';
        document.getElementById('data-inizio-asta').required = false;
        document.getElementById('data-fine-asta').required = false;
        document.getElementById('prezzo-asta').required = false;
        document.getElementById('pricefornew').required = true;
      }
    });
  });
document.querySelectorAll('input[name="userType"]').forEach((elem) => {
    elem.addEventListener("change", function(event) {
      var sellerFields = document.querySelector('.seller-fields');
      if (event.target.value === 'venditore') {
        sellerFields.style.display = 'block';
      } else {
        sellerFields.style.display = 'none';
      }
    });
  });
document.addEventListener('DOMContentLoaded', function () {
    const confirmationPopup = document.getElementById('confirmationPopup');
    const confirmInput = document.getElementById('confirmInput');
    const confirmBtn = document.getElementById('confirmBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const deleteBtn = document.getElementById('deleteBtn');

    deleteBtn.addEventListener('click', function () {
        confirmationPopup.style.display = 'flex';
    });

    cancelBtn.addEventListener('click', function () {
        confirmationPopup.style.display = 'none';
    });

    confirmBtn.addEventListener('click', function (event) {
        
        if (confirmInput.value === 'CONFERMA') {
            alert('Account eliminato definitivamente.');
            confirmationPopup.style.display = 'none';
        } else {
            alert('Per favore, digita "CONFERMA" correttamente.');
            event.preventDefault();
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    
  const deleteProdBtns = document.querySelectorAll('.deleteProdBtns');
  const formProd = document.getElementById('formProd');

  deleteProdBtns.forEach(function(deleteProdBtn) {
      deleteProdBtn.addEventListener('click', function () {
          const productId = this.getAttribute('id');
          const confirmProdInput = document.getElementById('confirmProdInput');
          const confirmProdBtn = document.getElementById('confirmProdBtn');
          const cancelProdBtn = document.getElementById('cancelProdBtn');
          const confirmationProdPopup = document.getElementById('confirmationProdPopup');
          formProd.setAttribute('action', `/TekHub/gestioneProdotti/eliminaProdotto/${productId}`);

          confirmationProdPopup.style.display = 'flex';

          cancelProdBtn.addEventListener('click', function () {
              confirmationProdPopup.style.display = 'none';
              confirmProdInput.value = ''; // Clear the input field
          });

          confirmProdBtn.addEventListener('click', function (event) {
              if (confirmProdInput.value === 'CONFERMA') {
                  confirmationProdPopup.style.display = 'none';
              } else {
                  event.preventDefault();
              }
          });

      });
  });
});

document.addEventListener('DOMContentLoaded', function () {
    $('.alert-success').click(function() {
        $(this).fadeOut('slow', function() {
            $('.alert-success').remove(); // Rimuove il div dal DOM dopo il fade out
        });
    });
});