<?php
include_once("Sql.class.php");
$sql = new Sql;

if ($_SESSION['form'] == 1) {
  $tipo = "cadastro.php?acao=cadastro&passo=3";
} elseif ($_SESSION['form'] == 2) {
  $tipo = "cadastro.php?acao=cadastro&passo=6";
} else {
  $tipo = "cadastro.php?acao=cadastro&passo=3";
}

$maxFun = "SELECT MAX(fun_id) AS fun_id FROM funcionario";
$idFun = $sql->selecionar($maxFun);
$selFun = "SELECT * FROM funcionario WHERE fun_id='" . $idFun . "';";
$funcionario = $sql->fetch($selFun);

$selUsu = "SELECT * FROM usuario WHERE funcionario_id='" . $idFun . "';";
$usuario = $sql->fetch($selUsu);
?>
<form class="Form" action="<?=$tipo?>" method="post">
  <h1 class="titulo">Funcionário</h1>
  <div class="group-form group-form-cadastro">
    <label class="lbl_class">Cargo:</label>
    <?php
    if ($_SESSION['form'] == 1) {
    echo "<select class=\"select\" name=\"fun_cargo\">";
    echo  "<option class=\"option\" value=\"recepcao\">Recepcionista</option>";
    echo  "<option class=\"option\" value=\"medico\">Médico</option>";
    echo  "<option class=\"option\" value=\"enfermeiro\">Enfermeiro</option>";
    echo  "<option class=\"option\" value=\"funcionario\">Funcionário</option>";
    echo "</select>";

    } else {
      echo "<input class=\"inp_class\" type=\"text\" name=\"fun_cargo\" size=\"28\"
            disabled value = " . $funcionario[1] . "><br>";
    }
    ?>
    <br>
  </div>
  <div class="group-form group-form-cadastro">
    <label class="lbl_class">Setor:</label>
    <?php
    if ($_SESSION['form'] == 1) {
      $sql->selectbox("setor");
    } else {
      echo "<input class=\"inp_class\" type=\"text\" name=\"fun_setor\" size=\"28\"
            disabled value = " . $funcionario[2] . "><br>";
    }
    ?>
  </div>
  <div class="group-form group-form-cadastro">
    <label class="lbl_class">Horario:</label>
    <?php
      if ($_SESSION['form'] == 1) {
        $dis = "";
        $val = "";
      } else {
        $dis = " disabled";
        $val = " value=\"" . $funcionario[3] . "\"";
      }
      ?>
    <input class="inp_class" type="time" name="fun_horario" size="28" <?=$dis . $val; ?>><br>
  </div>
  <div class="group-form group-form-cadastro">
    <label class="lbl_class">Inscrição:</label>
    <?php
      if ($_SESSION['form'] == 1) {
        $dis = "";
        $val = "";
      } else {
        $dis = " disabled";
        $val = " value=\"" . $funcionario[4] . "\"";
      }
    ?>
    <input class="inp_class" type="text" name="fun_inscricao" size="28" <?=$dis . $val; ?>><br>
  </div>
  <div class="group-form group-form-cadastro">
    <label class="lbl_class">Turno:</label>
    <?php
      if ($_SESSION['form'] == 1) {
      echo "<select class=\"select\" name=\"fun_turno\">";
      echo "<option class=\"option\" value=\"manha\">Manhã</option>";
      echo "<option class=\"option\" value=\"tarde\">Tarde</option>";
      echo "<option class=\"option\" value=\"noite\">Noite</option>";
      echo "</select><br>";
      } else {
        echo "<input class=\"inp_class\" type=\"text\" name=\"fun_turno\" size=\"28\"
            disabled value = " . $funcionario[5] . "><br>";
      }
    ?>
  </div>
  <div class="group-form group-form-cadastro">
    <label class="lbl_class">E-mail:</label>
    <?php
      if ($_SESSION['form'] == 1) {
        $dis = "";
        $val = "";
      } else {
        $dis = " disabled";
        $val = " value=\"" . $usuario[2] . "\"";
      }
    ?>
    <input class="inp_class" type="text" name="usu_email" size="28" <?=$dis . $val; ?>><br>
  </div>
  <div class="group-form group-form-cadastro">
    <label class="lbl_class">Senha:</label>
    <?php
      if ($_SESSION['form'] == 1) {
        $dis = "";
        $val = "";
      } else {
        $dis = " disabled";
        $val = " value=\"" . $usuario[1] . "\"";
      }
    ?>
    <input class="inp_class" type="password" name="usu_senha" size="28" <?=$dis . $val; ?>><br>
  </div>
  <?php
    if ($_SESSION['form'] == 2 || $_SESSION['form'] == 3) {
      echo "<input id=\"0\" type=\"button\" value=\"Alterar\">";
    }
  ?>
  <input class="inp_class" type="submit" value="Proximo">
</form>
