function kill_iframe(iframe_id){
    var iframe = document.getElementById(iframe_id);
    var parent = iframe.parentElement;
    parent.removeChild(iframe);
    setTimeout(function(){}, 1000);
    parent.appendChild(iframe); 
}