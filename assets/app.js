/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

const $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
})



document.addEventListener('DOMContentLoaded', function() {
    $("#deleteButton").on('click',function(){
      $("#myModal").modal("show");
    });
    $("#btnSup").on('click',function(){
        $("#myModal").modal("hide");
      });
    
    
});






//
// pop up
//
// document.addEventListener('DOMContentLoaded', function() {
//     const TButton = document.getElementById('fromButton');
//     const TCommande = document.getElementById('fromCommande');
//     const Taccept = document.getElementById('accept');
//     const Tinform = document.getElementById('inform');
//     const Trefuse = document.getElementById('refuse');

//     TButton.addEventListener('click', function() {
//         TCommande.style.display = 'block';
//     });

//     Taccept.addEventListener('click', function() {
//         TCommande.style.display = 'none';
//     })

//     Tinform.addEventListener('click', function() {
//         TCommande.style.display = 'none';
//     })
    
//     Trefuse.addEventListener('click', function() {
//         TCommande.style.display = 'none';
//     })

// });

//

//
//counter stat
//


$(document).ready(function($) {
    console.log('hello world');
    // $('.count-number').counterUp({
    //     delay: 10,
    //     time: 10000
    // });
});
