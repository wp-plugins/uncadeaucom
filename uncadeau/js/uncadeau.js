var $jq = jQuery.noConflict();

var uncadeauWP = {

    append : false,
    btn : null,
    data : {},
    
    getLinked : function(partner_id,keywords){
                    uncadeauWP.loading("#wpuncadeauCom");
                    var args = {};
                    args.q = keywords;
                    args.partner = partner_id;
                    var i=0;
                    args.extern=1;
                    args.f="";
                    $jq.getJSON("http://uncadeau.com/partners/rechercheWp.htm?var=?",args);
                    uncadeauWP.data = args;
                    return false;
    },
    retourJSON : function(data){
        $jq("#wpuncadeauCom").empty().append(data.html);
    },
    loading : function(elem){
        $jq(elem).prepend('<div class="loading"></div>');
    }

}


