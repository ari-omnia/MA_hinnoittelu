function validateForm() {
  var fkuvaus = document.forms["jasenlajienhallinta"]["jasenlaji_kuvaus"].value;
  if (fkuvaus == "") {
    alert("Kuvaus on annettava");
    return false;
  }

  var fjasenlaji = document.forms["jasenlajienhallinta"]["jasenlaji"].value;
  if (fjasenlaji == "") {
    alert("Jäsenlaji on annettava");
    return false;
  }

  var fjasenmaksu = document.forms["jasenlajienhallinta"]["jasenmaksu"].value;
  var fjasenmaksu_vuodessa = document.forms["jasenlajienhallinta"]["jasenmaksu_vuodessa"].value;

  // If jasenmaksu = YES
  if (fjasenmaksu == "1" && (fjasenmaksu_vuodessa == "" || fjasenmaksu_vuodessa == "0,00" || fjasenmaksu_vuodessa == "0,0" || fjasenmaksu_vuodessa == "0")) {
    alert("Jos jäsenmaksu on kyllä on myös jäsenmaksu vuodessa annettava");
    return false;
  }

  // If jasenmaksu = NO
  if (fjasenmaksu_vuodessa == "0,00" || fjasenmaksu_vuodessa == "0,0" || fjasenmaksu_vuodessa == "0") {
     fjasenmaksu_vuodessa = "";
  }
  if (fjasenmaksu == "0" && (fjasenmaksu_vuodessa != "" )) {
    alert("Jos jäsenmaksua ei ole, ei myöskään jäsenmaksu vuodessa ole sallittu");
    return false;
  }



}
