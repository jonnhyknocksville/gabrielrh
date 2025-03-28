/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

const $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function () {
  $('[data-toggle="popover"]').popover();
})



document.addEventListener('DOMContentLoaded', function () {
  $("#deleteButton").on('click', function () {
    $("#myModal").modal("show");
  });
  $("#btnSup").on('click', function () {
    $("#myModal").modal("hide");
  });


});


const loadVideo = (iframe) => {
  const cid = "UCee3DpFHYPezAlmnkLtPMRg";
  const channelURL = encodeURIComponent(`https://www.youtube.com/feeds/videos.xml?channel_id=${cid}`)
  const reqURL = `https://api.rss2json.com/v1/api.json?rss_url=${channelURL}`;

  fetch(reqURL)
    .then(response => response.json())
    .then(result => {
      console.log(result)
      const videoNumber = iframe.getAttribute('vnum')
      const link = result.items[videoNumber].link;
      const id = link.substr(link.indexOf("=") + 1);
      iframe.setAttribute("src", `https://youtube.com/embed/${id}?modestbranding=1&autohide=1&showinfo=0&controls=0`);
    })
    .catch(error => console.log('error', error));
}

const iframes = document.getElementsByClassName('latestVideoEmbed');
for (let i = 0, len = iframes.length; i < len; i++) {
  loadVideo(iframes[i]);
}




$(document).ready(function ($) {
  // console.log('hello world');
  // $('.count-number').counterUp({
  //     delay: 10,
  //     time: 10000
  // });

  $(".add-element").click(function () {
    const elemToDuplicate = $(this).prev().find("input[type=text]").clone();
    console.log(elemToDuplicate);
    $(this).prev().append(elemToDuplicate);
  });

});

// fonction copier le lien pour partage

$("#btnclipboard").on("click", function () {
  let copyText = document.querySelector("#clipboard");
  copyText.select();
  copyText.setSelectionRange(0, 99999);

  document.execCommand("copy");
  // alert("texte copié: " + copyText.value)
})

$('.updateClientPaid').on('change', function () {

  const month = $(this).parent().parent().find('.month').val();
  const year = $(this).parent().parent().find('.year').val();

  let checked;
  if (!$(this).is(':checked')) {
    checked = 0;
    $(this).removeClass("checked");
  } else {
    checked = 1;
    $(this).addClass("checked");
  }


  let myData = {
    clientId: $(this).parent().parent().find(".clientId").val(),
    paid: checked,
  };


  $.ajax({
    url: `/profile/invoices/paid/${month}/${year}`,
    data: myData,
    type: "POST",
    async: true,
    success: function (data) {
    },
    error: function (xhr, ajaxOptions, thrownError) {

    }

  });

});


$('.updateInvoiceSent').on('change', function () {

  const month = $(this).parent().parent().find('.month').val();
  const year = $(this).parent().parent().find('.year').val();

  let checked;
  if (!$(this).is(':checked')) {
    checked = 0;
    $(this).removeClass("checked");
  } else {
    checked = 1;
    $(this).addClass("checked");
  }

  console.log("checked" + checked);

  let myData = {
    clientId: $(this).parent().parent().find(".clientId").val(),
    sent: checked,
  };


  $.ajax({
    url: `/profile/invoices/sent/${month}/${year}`,
    data: myData,
    type: "POST",
    async: true,
    success: function (data) {
    },
    error: function (xhr, ajaxOptions, thrownError) {

    }

  });

});


$('.updateTeacherPaid').on('change', function () {

  const month = $(this).parent().parent().find('.month').val();

  let checked;
  if (!$(this).is(':checked')) {
    checked = 0;
    $(this).removeClass("checked");
  } else {
    checked = 1;
    $(this).addClass("checked");
  }

  let myData = {
    teacherId: $(this).parent().parent().find(".teacherId").val(),
    paid: checked,
  };


  $.ajax({
    url: `/profile/invoices/teacher/paid/${month}`,
    data: myData,
    type: "POST",
    async: true,
    success: function (data) {
    },
    error: function (xhr, ajaxOptions, thrownError) {

    }

  });

});