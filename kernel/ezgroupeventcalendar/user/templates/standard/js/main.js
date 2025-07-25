//Mouseovers\\



function changeImages() {

    for (var i=0; i<changeImages.arguments.length; i+=2) {

      document[changeImages.arguments[i]].src = eval(changeImages.arguments[i+1] + ".src");

        }

  }



topBarAnimeOff = new Image();

topBarAnimeOff.src = "../skin1/images/header/bar/top_bar_anime.gif";

topBarAnimeOn = new Image();

topBarAnimeOn.src = "../skin1/images/header/bar/top_bar_anime_on.gif";



topBarHentaiOff = new Image();

topBarHentaiOff.src = "../skin1/images/header/bar/top_bar_hentai.gif";

topBarHentaiOn = new Image();

topBarHentaiOn.src = "../skin1/images/header/bar/top_bar_hentai_on.gif";



topBarMaskedRiderOff = new Image();

topBarMaskedRiderOff.src = "../skin1/images/header/bar/top_bar_masked_rider.gif";

topBarMaskedRiderOn = new Image();

topBarMaskedRiderOn.src = "../skin1/images/header/bar/top_bar_masked_rider_on.gif";



topBarLiveActionOff = new Image();

topBarLiveActionOff.src = "../skin1/images/header/bar/top_bar_live_action.gif";

topBarLiveActionOn = new Image();

topBarLiveActionOn.src = "../skin1/images/header/bar/top_bar_live_action_on.gif";



topBarAudioCdsOff = new Image();

topBarAudioCdsOff.src = "../skin1/images/header/bar/top_bar_audio_cds.gif";

topBarAudioCdsOn = new Image();

topBarAudioCdsOn.src = "../skin1/images/header/bar/top_bar_audio_cds_on.gif";



topBarCalendarsOff = new Image();

topBarCalendarsOff.src = "../skin1/images/header/bar/top_bar_calendars.gif";

topBarCalendarsOn = new Image();

topBarCalendarsOn.src = "../skin1/images/header/bar/top_bar_calendars_on.gif";



topBarSpecialsOff = new Image();

topBarSpecialsOff.src = "../skin1/images/header/bar/top_bar_specials.gif";

topBarSpecialsOn = new Image();

topBarSpecialsOn.src = "../skin1/images/header/bar/top_bar_specials_on.gif";

//Menus\\

        var menus = [

                new ypSlideOutMenu("menu1", "right", 85, 105, 300, 200),

                new ypSlideOutMenu("menu2", "right", 60, 120, 300, 200)

        ]



        for (var i = 0; i < menus.length; i++) {

                menus[i].onactivate = new Function("document.getElementById('act" + i + "').className='active';");

                menus[i].ondeactivate = new Function("document.getElementById('act" + i + "').className='';");

        }