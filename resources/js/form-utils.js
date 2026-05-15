


/*
*   Bloque l'incrementation et decrementation involontaire par scroll de la 
*   souris sur tous les champs "input" de type  "Number" du Dom.
*/

function disableNumberInputScroll()  {
    document.querySelectorAll('input[type="number"]').forEach(function (input) {
        input.addEventListener('wheel', function (e) {
            e.preventDefault();
        }, {
            passive: false
        });
    });
}

export { disableNumberInputScroll };
