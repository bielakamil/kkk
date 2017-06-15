

//$(document).ready(function($){
//               
//                $('#phone').mask("999 999 999",{placeholder:" "});
//                $('#pesel').mask("99 99 99 99999",{placeholder:" "});
//                $('#pkk').mask("99999 99999 99999 99999",{placeholder:" "});
//    
//    
//                
//            });


window.onload = function ()
{   
    
$('#input-search').select2({
        placeholder: 'Szukaj',
        width: '100%',
        ajax: {
            url: "../script/search.php",
            dataType: 'json',
            quietMillis: 100,
            data: function (params) {
              return {
                q: params.term, // search term
                page: params.page
              };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function(obj) {
                        return { id: obj.id, 
                                text: obj.text,
                                category: obj.category,
                               };
                    })
                };
            },
            cache: true,

            },

    });

    
$('#select2').select2({})    
    
$('#input-search').on("select2:select", function (e) { 
   window.location.href = "../page/uczen.php?id=" + this.value;
});   
    
$('#input-search').on("select2:open", function (e) { 
    
    $('#search').removeClass('col-xs-1');
    $('#search').addClass('col-xs-12');          
    $('#icons').removeClass('col-xs-11');
    $('#icons').addClass('hidden-xs');
    
    $('#search').removeClass('col-sm-4');
    $('#search').addClass('col-sm-8');
    $('#icons').removeClass('col-sm-8');
    $('#icons').addClass('col-sm-4');
    
    
    
    
});  
    
$('#input-search').on("select2:close", function (e) { 
    
    $('#search').addClass('col-xs-1');
    $('#search').removeClass('col-xs-12');
    $('#icons').addClass('col-xs-11');
    $('#icons').removeClass('hidden-xs');
    
    
    $('#search').addClass('col-sm-4');
    $('#search').removeClass('col-sm-8');
    $('#icons').addClass('col-sm-8');
    $('#icons').removeClass('col-sm-4');
    
    
    
    
});        
    
$("#notifications-open").click( function() {
    
    if ($('.notifications').hasClass('active'))
    {
        $('.notifications').removeClass('active');
    } else
    {
        $('.notifications').addClass('active');
        $('.small_menu').removeClass('active');
        $('.news').removeClass('active');
    }
    
});  
    
$("#small-menu-open").click( function() {
    
    if ($('.small_menu').hasClass('active'))
    {
        $('.small_menu').removeClass('active');
    } else
    {
        $('.small_menu').addClass('active');
        $('.notifications').removeClass('active');
        $('.news').removeClass('active');
    }
    
});    
    
$("#news-open").click( function() {
    
    if ($('.news').hasClass('active'))
    {
        $('.news').removeClass('active');
    } else
    {
        $('.news').addClass('active');
        $('.notifications').removeClass('active');
        $('.small_menu').removeClass('active');
    }
    
});  
    
$("#mobile-menu-open").click( function() {
    
        $('.news').removeClass('active');
        $('.notifications').removeClass('active');
        $('.small_menu').removeClass('active');
    
        $('#head').addClass('hidden-xs');
        $('#head').addClass('hidden-sm');    
    
    
        $('#menu').removeClass('hidden-xs');
        $('#menu').removeClass('hidden-sm');
    
    
}); 
    
$("#mobile-menu-close").click( function() {
    
        $('.news').removeClass('active');
        $('.notifications').removeClass('active');
        $('.small_menu').removeClass('active');
    
        $('#head').removeClass('hidden-xs');
        $('#head').removeClass('hidden-sm');    
    
    
        $('#menu').addClass('hidden-xs');
        $('#menu').addClass('hidden-sm');
    
    
});      
     
    $("#loader-wrapper").fadeOut();
    
}

    

function today (a){

    
    var data = document.getElementById('date').value;
    window.alert(data);

    var zapytanie = "";
    zapytanie = new XMLHttpRequest ();
    zapytanie.onreadystatechange = function (){
        if (zapytanie.readyState == 4 && zapytanie.status == 200)
            {
                
                if (Display == "none")
                {
                    document.getElementById("liczbaPowiadomien").innerHTML = zapytanie.responseText;
                }  
            }
    }
    
   
    
    if (a == "back")
    {
        zapytanie.open ("GET", "../script/today_ajax.php?data=" + data, true);
    } else
    {
        zapytanie.open ("GET", "../script/read_all_powiadomienia.php?stan=1", true);
    }
    zapytanie.send ();
    

    
}

function edit_uczen_kurs (){
    var kurs = document.getElementById('kurs').value;
    
    var input_jazdy = document.getElementById('jazdy');
    var input_cena = document.getElementById('cena');
    var ilosc_jazd = document.getElementById('jazda-' + kurs).value;
    var ilosc_koszt = document.getElementById('cena-' + kurs).value;    
    if (kurs > 0)
    {
        input_jazdy.setAttribute('disabled','disabled');
        input_jazdy.value = ilosc_jazd;
        input_cena.setAttribute('disabled','disabled');
        input_cena.value = ilosc_koszt;
    } else
    {
        input_jazdy.removeAttribute('disabled');
        input_jazdy.value = ilosc_jazd;
        input_cena.removeAttribute ('disabled');
        input_cena.value = ilosc_koszt;
    }
}




function profil_menu ()
{
    var menu = document.getElementById('profil-menu');
    
    if (menu.style.display == 'block')
    {
        menu.style.display = 'none';
    } else
    {
        menu.style.display = 'block';
    }
}

function wyswietl_menu (obj)
{
    window.alert();
}

function showPowiadomienia () {
    var powText = document.getElementById('powiadomienia');
    var powCircle = document.getElementById('liczbaPowiadomien');
    var powDisplay = document.getElementById('pow-text');
    var Display = powDisplay.style.display;
    
    if (Display == "none")
    {
        powDisplay.style.display = 'block';
    }
    
    if (Display == 'block')
    {
        powDisplay.style.display = 'none';
    }
    
    WyczyscWszystkiePowiadomienia ();
}



function showMenu()
{

    
    var menuDisplay = document.getElementById('profile_menu');
    var Display = menuDisplay.style.display;

    if (Display == "none")
    {
        menuDisplay.style.display = 'block';
    }
    
    if (Display == 'block')
    {
        menuDisplay.style.display = 'none';
    }
}






function search_wyklad (obiekt)
{
   
    var id = obiekt.id;
    var value = obiekt.value;
    var zapytanie = "";
    var obiekt_id = document.getElementById(id + '-id');
    obiekt_id.value = 0;
    zapytanie = new XMLHttpRequest ();
    zapytanie.onreadystatechange = function (){
        if (zapytanie.readyState == 4 && zapytanie.status == 200)
            {
                document.getElementById(id + '-text').innerHTML = zapytanie.responseText;
            }
    }
    zapytanie.open ("GET", "../script/search-wyklad.php?search=" + value + '&id=' + id , true);
    zapytanie.send ();
    
}

function select_wyklad (id, obiekt, uczen)
{
    var input_hiden = document.getElementById(obiekt + '-id');
    var input = document.getElementById(obiekt);
    var text = document.getElementById(obiekt + '-text');
    var kontener = document.getElementById('add_obecnosc');
    var input_ile = document.getElementById('ile');
    var ile = input_ile.value;
    
    ile++;
    input.value = uczen;
    input_hiden.value = id;
    text.innerHTML = '';
    input_ile.value = ile;
    
    var znacznik = document.createElement('input');
	znacznik.setAttribute('type', 'text');
	znacznik.setAttribute('id', 'uczen' + ile);
	znacznik.setAttribute('autocomplete', 'off');
	znacznik.setAttribute('oninput', 'search_wyklad(this)');
	kontener.appendChild(znacznik);
    
    var znacznik_hidden = document.createElement('input');
	znacznik_hidden.setAttribute('type', 'hidden');
	znacznik_hidden.setAttribute('name', 'uczen' + ile + '-id');
	znacznik_hidden.setAttribute('id', 'uczen' + ile + '-id');
	znacznik_hidden.setAttribute('value', '0');
	kontener.appendChild(znacznik_hidden);
    
    var znacznik = document.createElement('div');
	znacznik.setAttribute('class', 'wyklad-obecnosc');
	znacznik.setAttribute('id', 'uczen' + ile + '-text');
	kontener.appendChild(znacznik);
    
}

function egzamin_in ()
{
    window.alert();
}

    function oba()
    {
        wyrownaj();
        jazdaChange();
    }

function jazdaChange()
{

    var czas_jazd = document.getElementById('czas_jazd').value;
    var koniec_jazd = document.getElementById('koniec_jazda');
    var array = czas_jazd.split(':');
    
    var godzina_jazd = parseInt(array[0]);
    var minuty_jazd = parseInt(array[1]);
    
    console.log("Czas trwania " + godzina_jazd + ":" + minuty_jazd);
    
    var start = document.getElementById('start_jazda').value;

    var array = start.split(':');
    var godzina_start = parseInt(array[0]);
    var minuty_start = parseInt(array[1]);
    
    console.log("Początek " + godzina_start + ":" + minuty_start);
    
    var godzina_koniec = godzina_jazd + godzina_start;
    var minuty_koniec = minuty_jazd + minuty_start;
    
    

    if (minuty_koniec >= 60)
    {
        godzina_koniec++;
        minuty_koniec = minuty_koniec-60;
    }
    
    if (godzina_koniec >= 24)
    {
        godzina_koniec = godzina_koniec-24;
    }
    
    if (minuty_koniec < 10)
    {
        minuty_koniec = '0' + minuty_koniec;
    }
    if (godzina_koniec < 10)
    {
        godzina_koniec = '0' + godzina_koniec;
    }
    

    console.log("Koniec " + godzina_koniec + ":" + minuty_koniec + ":00");
    
    
    var time = godzina_koniec + ":" + minuty_koniec;
    koniec_jazd.value = time;
    
}

function add_wyklad_miejsce ()
{
    var oMiejsce = document.getElementById('miejsce');
    var oText = document.getElementById('wyklad-tr');
    if (oMiejsce.value == 0)
    {
        oText.style.display = 'table-row';
    } else
    {
        oText.style.display = 'none'; 
    }
}

function flitr_all_jazdy ()
{
    
    var data_start = document.getElementById('data_start').value;
    var data_end = document.getElementById('data_end').value;
    var ile_samochod = document.getElementById('ile_samochod').value;
    var uczen = document.getElementById('uczen').value;  
    var all_jazdy  = document.getElementById('all_jazdy');
    var stan1  = document.getElementById('stan_1').checked;
    var stan2  = document.getElementById('stan_2').checked;
    var stan3  = document.getElementById('stan_3').checked;
    var stan4  = document.getElementById('stan_4').checked;
    var instruktor  = document.getElementById('instruktor').value;
    

    
    var link = '../script/flitr_all_jazdy.php?data_start=' + data_start + '&data_end=' + data_end + '&uczen=' + uczen + '&stan1=' + stan1 + '&stan2=' + stan2 + '&stan3=' + stan3 + '&stan4=' + stan4 + '&samochod=' + ile_samochod + '&instruktor=' + instruktor;
    
    
    for (var x=1;x<=ile_samochod;x++)
    {
        var car = document.getElementById('car_' + x).checked;
        var car_id = document.getElementById('car_id_' + x).value;
        link = link + '&car_id_' + x + '=' + car_id;
        link = link + '&car_' + x + '=' + car;    
    }

    var zapytanie = "";
    zapytanie = new XMLHttpRequest ();
    zapytanie.onreadystatechange = function (){
        if (zapytanie.readyState == 4 && zapytanie.status == 200)
            {
                all_jazdy.innerHTML = zapytanie.responseText;
            }
    }
    zapytanie.open ("GET", link  , true);
    zapytanie.send ();

}

function add_uczen_pesel ()
{
    
    var pesel = document.getElementById('pesel').value;
    var oDataUrodzenia = document.getElementById('data_urodzenia');
    var plec = document.getElementById('plec');
    if (pesel.length == 11)
    {
        var pesel_plec = pesel[9];
        if (pesel_plec%2 == 0)
        {
            var inner = '<option value="2"> Kobieta </option> <option value="1"> Mężczyzna </option>';
            window.alert(inner);
            plec.innerHTML = inner;
        } else
        {
            var inner = '<option value="1" selected> Mężczyzna </option> <option value="2"> Kobieta </option>';
            plec.innerHTML = inner;
        }
        var rok = '19' + pesel.substring(0,2);    
        var miesiac = pesel.substring(2,4);
        var dzien = pesel.substring(4,6);
        oDataUrodzenia.value = rok + '-' + miesiac + '-' + dzien;
        wiek();
    }
}


function add_user_kurs ()
{
    var kurs = document.getElementById('kurs');
    var i_jazdy = document.getElementById('jazdy');
    var i_cena = document.getElementById('cena');
    
    var id_kurs = kurs.value;
    if (id_kurs > 0)
    {
        var cena = document.getElementById('cena-' + id_kurs);
        var jazdy = document.getElementById('jazdy-' + id_kurs);
        i_cena.value = cena.value;
        i_jazdy.value = jazdy.value;
        i_cena.setAttribute('disabled','true');
        i_jazdy.setAttribute('disabled','true');
    } else
    {
        i_cena.removeAttribute('disabled');
        i_jazdy.removeAttribute('disabled');
    }
}

function wiek ()
{
    var oDataUrodzenia = document.getElementById('data_urodzenia');
    var oWiek = document.getElementById('age');
    var data = oDataUrodzenia.value;
    var myDate = new Date(data); 
        var now = new Date();
        var month_of_birth=myDate.getMonth();
        var day_of_birht=myDate.getDay();
        var year_of_birth=myDate.getYear();
        var now_month = now.getMonth();
        var now_day = now.getDay();
        var now_year = now.getYear();
        var age = now_year - year_of_birth;
    if (now_month < month_of_birth) {
        age--;
    } else if ((now_month == month_of_birth) && (now_day < day_of_birht)) {
        age--;
        if (age < 0) {
            age = 0;
        }
    }
    oWiek.value = age + ' lat';
    
}

function paliwo_cena ()
{
    var cena = document.getElementById('cena');
    var paliwo = document.getElementById('paliwo');
    var paliwo_tr = document.getElementById('paliwo-r');
    var paliwo_td = document.getElementById('paliwo-l');
    
    if (cena.value > 0 && paliwo.value > 0 )
    {
        paliwo_tr.style.display = 'inline';
        var zalitr = cena.value/paliwo.value;
        zalitr = zalitr.toFixed(2);
        paliwo_td.innerHTML = '1 litr = ' + zalitr + ' zł';
    } else
    {
        paliwo_tr.style.display = 'none';
    }
}

function show_menu(){

    var head = document.getElementById('head');
    var nav = document.getElementById('menu');
    
    nav.style.display = 'block';
    head.style.display = 'none';
}

function close_menu (){
    var head = document.getElementById('head');
    var nav = document.getElementById('menu');
    
    nav.style.display = 'none';
    head.style.display = 'block';
}

function ajax_update_zadanie (obj)
{
    var id = $(obj).attr('id');
    var ocena = $(obj).val();
    console.log("ID " + id);
    console.log("Ocena " + ocena);
    
    switch (ocena)
        {
        case "0":
            {
                var text = "Brak danych";
                break;
            }
        case "1":
            {
                var text = "Do poprawy";
                break;
            }
        case "2":
            {
                var text = "Słabo";
                break;
            }
        case "3":
            {
                var text = "Dobrze";
                break;
            }
        case "4":
            {
                var text = "Rewelacyjnie";
                break;
            }
    }
    
    $('.postep-' + id).html(text);
    
    var link = '../script/ajax-podglad-jazd.php?id=' + id + '&ocena=' + ocena;
    zapytanie = new XMLHttpRequest ();
    zapytanie.onreadystatechange = function (){
        if (zapytanie.readyState == 4 && zapytanie.status == 200)
            {
                
            }
    }
    zapytanie.open ("GET", link  , true);
    zapytanie.send ();
    
    var category = $(obj).attr('category');
    
    console.log("Kategoria " + category);
    
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();

    if(dd<10) {
        dd='0'+dd
    } 


    var month = ['styczeń','luty','marzec','kwiecień','maj','czerwiec','lipiec','sierpień','wrzesień','październik','listopad','grudzień'];

    var date = dd + ' ' + month[mm-1] + ' ' + yyyy;
    console.log (month[mm]);
    console.log(date);
    
    $('.date-' + id).html(date);
    
    podglad_jazd_kategoria(category);
 
}

function podglad_jazd_kategoria (category)
{
    var procent_text = $('.procent-' + category);
    
    
    var category_input = $('*[category="' + category + '"]');
    var category_lenght = category_input.length;
    
    console.log("Ilośc zależności " + category_lenght);
    
    
    var procent = 0;
    var pkt = 0;
    var max = 0;
    
    for (var x = 0;x<category_lenght;x++)
    {
        var value = $(category_input[x]).val();
        var i_max = $(category_input[x]).attr('max');
        pkt = pkt + parseInt(value);
        max += parseInt(i_max);
    }
    
    console.log("Zdobyte pkt " + pkt);
    console.log("Maksymalnie pkt " + max);
    
    procent = Math.round((pkt/max*100));
    procent_text.html(procent + '%');
    
}

function check_extra_drive ()
{
    var select = document.getElementById('kurs');
    var id = select.value;
    console.log(id);
    var pole = $('#niestandardowy_kurs');
    if (id == 0)
    {
        pole.css('display','block');       
    } else
    {
        pole.css('display','none');
    }
    
}


if ("geolocation" in navigator) {
  navigator.geolocation.getCurrentPosition(function(position) {
      console.log(position.coords.latitude)
      console.log(position.coords.longitude)
});
} else {
  /* geolocation IS NOT available */
}










