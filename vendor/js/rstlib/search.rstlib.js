
function rstSearch(value_to_search,inside,isDisplayed=true){

  let toSearch = value_to_search,
      potentialSearch = document.getElementsByClassName(inside);


  if(toSearch == ""){

    isDisplayed = isDisplayed ? "" : "none";

    for(i=0 ; i<potentialSearch.length ; i++){
      potentialSearch[i].style.display = isDisplayed;
    }
    
  }

  
  //You can edit this part to in order to ask for minimum number of characters
  /*
  else if(toSearch.length > 0 && toSearch.length < 3){
    document.getElementById("tosearch").value="";
    document.getElementById("tosearch").placeholder="At least 3 letters";
  }
  */
  
  else{
   
    let found = false;
    
    for(i=0 ; i<potentialSearch.length ; i++){
       
       if(potentialSearch[i].innerText.toLowerCase().includes(toSearch.toLowerCase())){
          potentialSearch[i].style.display="";
          found = true;
       }
       
       else{
          potentialSearch[i].style.display="none";
       }
       
    }
    
    
    if(!found){

      //You can edit this part to push "no result found" into an element in the DOM
      //document.getElementById("info02").innerHTML = "no result found...";

    }
    
  
  }

}