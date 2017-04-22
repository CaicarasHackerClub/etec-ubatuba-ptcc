<?php
  include_once 'Sql.class.php';
  include_once 'Fila.class.php';

  $sql = new Sql();
  $fila = new Fila();

  $fila->setPacMax(5);

  $sel = $fila->getSel();
  $tempo = $fila->getTempo();

  $con = $sql->conecta();

  // $fila->setPac(7, 1, 0, 0);
  // $fila->chamar();

  for ($i = 0; $i < count($sel); $i++) {
    $res = mysqli_query($con, $sel[$i]);

    while ($pac = mysqli_fetch_array($res)) {
      $fila->atualizar();

      $data = $pac['tri_data'];
      $hora = $pac['tri_hora'];

      $fila->setPac($pac['tri_id'], $pac['id_paciente'], $fila->calc($data, $hora), $tempo[$i]);

      if ($fila->getEmConsulta() < $fila->getPacMax() || $fila->getEspera() >= $fila->getTempoMax()) {
          $fila->chamar();
      } else {
          $fila->cat();
      }
    }
  }

  mysqli_close($con);

  echo "Pessoas em consulta: " . $fila->getEmConsulta() . "<br>";
  echo "Pessoas em espera: " . $fila->getNaFila() . "<br>";

  $fila->imprimir();
