var $messages = $('.messages-content'),
    d, h, m,
    i = 0;
    verification_flag = 0;
    status = 0;
    user_id = 0;

$(window).load(function() {
  $messages.mCustomScrollbar();
  setTimeout(function() {
    verificationConv();
  }, 100);
});


function updateScrollbar() {
  $messages.mCustomScrollbar("update").mCustomScrollbar('scrollTo', 'bottom', {
    scrollInertia: 10,
    timeout: 0
  });
}

function setDate(){
  d = new Date()
  if (m != d.getMinutes()) {
    m = d.getMinutes();
    $('<div class="timestamp">' + d.getHours() + ':' + m + '</div>').appendTo($('.message:last'));
    $('<div class="checkmark-sent-delivered">&check;</div>').appendTo($('.message:last'));
    $('<div class="checkmark-read">&check;</div>').appendTo($('.message:last'));
  }
}

function insertMessage(msg_user) {
  // msg_user = $('.message-input').val();
  if ($.trim(msg_user) == '') {
    return false;
  }
  $('<div class="message message-personal">' + msg_user + '</div>').appendTo($('.mCSB_container')).addClass('new');
  setDate();
  $('.message-input').val(null);
  updateScrollbar();
  setTimeout(function() {
    // getResponse(msg_user);
  if ((msg_user>=0 && msg_user<=6) && i > 0){
    menu(msg_user);
    // i==0;
  }
  // else if (msg_user==0){
  //   fakeMessage(msg_user);
  // }
  // else if(i == 0){
  //   i=i+1;
  //   verificationConv(msg_user);
  //   if(verification_flag==1){
  //     verification(msg_user);
  //   }
  // }
  else if(i == 0){
    if (verification_flag==1){
      verification(msg_user);
    }
  }
  else if(i > 0){
    getResponse(msg_user);
  }
  }, 1000 + (Math.random() * 20) * 100);
}

var Fake = [
  'Pilih Nomor untuk menentukan topik : <br>1. Bea Masuk'+
  '<br>2. Pajak'+
  '<br>3. Ekspor-Impor'+
  '<br>4. Pengiriman Barang'+
  '<br>5. Larangan dan pembatasan'+
  '<br>6. Istilah pada Dokumen'+
  '<br>0. Menu Utama'
  // 'Ada yang bisa saya bantu?',
  // 'Silahkan sertakan kode unik NOA anda untuk memulai percakapan',
  // 'Silahkan sertakan nomor airwaybill anda',
  // 'Hal apa yang ingin ditanyakan?'
];

function fakeMessage(msg_user) {
  if ($('.message-input').val() != '') {
    return false;
  }
  $('<div class="message loading new"><figure class="avatar"><img src="C:/xampp/htdocs/chatbot/img/profile.png" /></figure><span></span></div>').appendTo($('.mCSB_container'));
  updateScrollbar();
  console.log(msg_user);
  setTimeout(function() {
    $('.message.loading').remove();
    $('<div class="message new"><figure class="avatar"><img src="C:/xampp/htdocs/chatbot/img/profile.png" /></figure>' + Fake[i] + '</div>').appendTo($('.mCSB_container')).addClass('new');
    setDate();
    updateScrollbar();
    i = 2;
    verification_flag=0;
  }, 1000 + (Math.random() * 20) * 100);

}

var question = [
  'Hello. I am Chatbot. First, Please provide your name and your company name (name/company name)'
]

function verificationConv(msg_user){
  if ($('.message-input').val() != '') {
    return false;
  }
  $('<div class="message loading new"><figure class="avatar"><img src="C:/xampp/htdocs/chatbot/img/profile.png" /></figure><span></span></div>').appendTo($('.mCSB_container'));
  updateScrollbar();
  console.log(msg_user);
  setTimeout(function() {
    $('.message.loading').remove();
    $('<div class="message new"><figure class="avatar"><img src="C:/xampp/htdocs/chatbot/img/profile.png" /></figure>' + question[i] + '</div>').appendTo($('.mCSB_container')).addClass('new');
    setDate();
    updateScrollbar();
    verification_flag = 1;
  }, 1000 + (Math.random() * 20) * 100);
}

function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}

function menu(msg_user){
  if ($('.message-input').val() != '') {
    return false;
  }
  if(msg_user==0){
    i=0;
    fakeMessage(msg_user);
  }
  else{
    $('<div class="message loading new"><figure class="avatar"><img src="C:/xampp/htdocs/chatbot/img/profile.png" /></figure><span></span></div>').appendTo($('.mCSB_container'));
    updateScrollbar();
    console.log(msg_user);
    setTimeout(function() {
      $('.message.loading').remove();
      if (msg_user==1){
        var replay = '<button id="bm" onclick="send(id)" class="button button5">Bea Masuk</button>'+
        '<button id="ndbpm" onclick="send(id)" class="button button5">NDBPM</button>';
        // i = 1;
      }
      else if(msg_user==2){
        replay = '<button id="ppn" onclick="send(id)" class="button button5">PPN</button>'+
        '<br><button id="pph" onclick="send(id)" class="button button5">PPH</button>'+
        '<button id="dutytax" onclick="send(id)" class="button button5">Duty Tax</button>'+
        '<button id="fasilitas" onclick="send(id)" class="button button5">Fasilitas perpajakan</button>'+
        '<br><button id="ppnbm" onclick="send(id)" class="button button5">PPNBM</button>';
        // i = 1;
      }
      else if(msg_user==3){
        replay = '<button id="import" onclick="send(id)" class="button button5">Import</button>'+
        '<button id="kepabeaan" onclick="send(id)" class="button button5">Waktu kepabeaan</button>';
        // i = 1;
      }
      else if(msg_user==4){
        replay = '<button id="pelaporan_uang" onclick="send(id)" class="button button5">Pelaporan uang</button>'+
        '<button id="rokok_alkohol" onclick="send(id)" class="button button5">Rokok dan minuman beralkohol</button>'+
        '<button id="hewan" onclick="send(id)" class="button button5">Hewan Peliharaan</button>'+
        '<button id="cukai" onclick="send(id)" class="button button5">Cukai</button>'+
        '<button id="jenis_bkc" onclick="send(id)" class="button button5">Jenis Barang Kena Cukai (BKC)</button>'+
        '<button id="ketentuan" onclick="send(id)" class="button button5">Ketentuan</button>'+
        '<button id="penanganan" onclick="send(id)" class="button button5">Penanganan</button>'+
        '<button id="prosedur" onclick="send(id)" class="button button5">Prosedur</button>';
        // i = 1;
      }
      else if(msg_user==5){
        replay = '<button id="lartas" onclick="send(id)" class="button button5">Larangan dan Pembatasan</button>'+
        '<button id="instansi_lartas" onclick="send(id)" class="button button5">Instansi yang menerapkan lartas</button>';
        // i = 1;
      }
      else if(msg_user==6){
        replay = '<button id="cif" onclick="send(id)" class="button button5">CIF</button>'+
        '<button id="invoice" onclick="send(id)" class="button button5">Invoice</button>'+
        '<button id="noa" onclick="send(id)" class="button button5">NOA</button>'+
        '<button id="fob" onclick="send(id)" class="button button5">FOB</button>'+
        '<button id="freight" onclick="send(id)" class="button button5">Freight</button>';
        // i = 1;
      }
      $('<div class="message new"><figure class="avatar"><img src="C:/xampp/htdocs/chatbot/img/profile.png" /></figure>' + replay + '</div>').appendTo($('.mCSB_container')).addClass('new');
      setDate();
      updateScrollbar();
      i=2;
    }, 1000 + (Math.random() * 20) * 100);
  }
}

function send(id){
  msg_user = document.getElementById(id).innerHTML;
  i = 2;
  insertMessage(msg_user);
}

function getResponse(msg_user) {
  if (msg_user == '') {
    return false;
  }
  $('<div class="message loading new"><figure class="avatar"><img src="../img/profile.png" /></figure><span></span></div>').appendTo($('.mCSB_container'));
  updateScrollbar();

  // let userText = $("#message-input").val();
  // msg_user = $("#pesan_input").val();
  console.log(msg_user);
  // $("#message-input").val("");
  $.get("/get", { msg: msg_user }).done(function(data) {
    setTimeout(function() {
      $('.message.loading').remove();
      $('<div class="message new"><figure class="avatar"><img src="../img/profile.png" /></figure>' + data + '</div>').appendTo($('.mCSB_container')).addClass('new');
      setDate();
      updateScrollbar();
    }, 1000 + (Math.random() * 20) * 100);
  });
  i=2;
}
function verification(msg_user){
  if (msg_user == '') {
    return false;
  }
  $('<div class="message loading new"><figure class="avatar"><img src="../img/profile.png" /></figure><span></span></div>').appendTo($('.mCSB_container'));
  updateScrollbar();

  // let userText = $("#message-input").val();
  // msg_user = $("#pesan_input").val();
  console.log(msg_user, i);
  // $("#message-input").val("");
  $.get("/verification", { i: i, msg: msg_user}).done(function(data) {
    setTimeout(function() {
      // text = "You are verified";
      // status = 1;
      $('.message.loading').remove();
      if(data=='1'){
        $('<div class="message new"><figure class="avatar"><img src="../img/profile.png" /></figure>You are verified</div>').appendTo($('.mCSB_container')).addClass('new');
      }
      else{
        $('<div class="message new"><figure class="avatar"><img src="../img/profile.png" /></figure>You are not verified</div>').appendTo($('.mCSB_container')).addClass('new');
      }
      setDate();
      updateScrollbar();
      if(data=='1'){
        fakeMessage(msg_user);
      }
      else{
        verificationConv(msg_user);
      }
    }, 1000 + (Math.random() * 20) * 100);
  });
}

$('#form-chat').on('submit',function(e){
  e.preventDefault();
  var message = $('#pesan_input').val();
  // console.log(message);
  insertMessage(message);
})
// $("#pesan_input").keypress(function(e) {
//   // alert('alert');
//   console.log($(this).val());
//   //if enter key is pressed
//       // if(e.which == 13) {
//       //     getResponse();
//       // }
// });

$('.button').click(function(){
  $('.menu .items span').toggleClass('active');
   $('.menu .button').toggleClass('active');
});