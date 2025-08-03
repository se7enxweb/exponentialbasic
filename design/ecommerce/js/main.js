
function init()
{
        // ecommerce : css highlighted items
	var buttons = new Array(
            'viewcart',
            'checkout',
            'searchBut',
  	    'addCart',
            'addWish'
        );

        // eZ publish : dom ui icons
        MM_preloadImages('/images/redigerminimrk.gif','/images/slettminimrk.gif','/images/downloadminimrk.gif','/images/addminimrk.gif');

        // buttons : array : item style over, off switch
	for(i = 0; i < buttons.length; i++)
	{
           if ( document.getElementById( buttons[i] ) != null ) {
		document.getElementById(buttons[i]).onmouseover = function() {
	 	 this.style.border = "1px solid darkblue";
		};	

		document.getElementById(buttons[i]).onmouseout = function() {
		 this.style.border = "1px solid white";
		};
	   }
	}	
}
