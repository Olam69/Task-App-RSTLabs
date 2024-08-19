

// Quick footer year
// USAGE
/*
  footer_year('my_class');
*/

function footer_year(output_class) {
  let dd = new Date();
  output_class = document.getElementsByClassName(output_class);
  for(let x of output_class) x.innerText = dd.getFullYear(); 
}






// Smoothscroll
// N.B: Requires jQuery due to .animate() method

