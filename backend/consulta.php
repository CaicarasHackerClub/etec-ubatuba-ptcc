<?php
  include_once 'Sql.class.php';
  include_once 'Consulta.class.php';

  $cons = new Consulta();
  $fila = new Fila();
  $sql = new Sql();

  date_default_timezone_set('America/Sao_Paulo');

  if (isset($_POST['consulta']) || isset($_POST['adicionar']) || isset($_POST['remover']) || isset($_POST['limpar'])) {
    $query = "SELECT * FROM pessoa
      INNER JOIN paciente ON paciente.pessoa_pes_id = pessoa.pes_id
      INNER JOIN atendimento ON atendimento.ate_pac_id = paciente.pac_id
      INNER JOIN triagem ON triagem.tri_ate_id = atendimento.ate_id
      WHERE triagem.tri_id = " . $_POST['triId'] . ";";

    $pac = $sql->fetch($query);

    $st = "UPDATE triagem SET tri_status = 4 WHERE tri_id = " . $pac['tri_id'];
    $sql->inserir($st);

    $data = new Datetime($pac['tri_data']);

    $org = $pac['tri_orgaos_vitais'] == 1 ? "Sim" : "Não";

    $dados = $fila->getCor($pac['tri_classe_risco']);
    $class = $dados[0];

    $idade = date('Y') - $pac['pes_data'];

    $tipoSanguineo = $sql->selecionar("SELECT tis_nome FROM tipo_sanguineo WHERE tis_id = " . $pac['tri_tipo_sanguineo'] . ";");

    ?>
      <h2> Dados da triagem </h2>

      <table>
        <tr>
          <th>Data</th>
          <td> <?php echo $data->format('d/m/Y'); ?> </td>
        </tr>
        <tr>
          <th>Hora</th>
          <td> <?php echo $pac['tri_hora']; ?> </td>
        </tr>
      </table>

      <table>
        <tr>
          <th>#</th>
          <td> <?php echo $pac['tri_id']; ?></td>
        </tr>
        <tr>
          <th>Classificação de risco</th>
          <td> <?php echo $class; ?> </td>
        </tr>
        <tr>
          <th>Nome</th>
          <td> <?php echo $pac['pes_nome']; ?> </td>

        </tr>
        <tr>
          <th>Idade</th>
          <td> <?php echo $idade ?> anos </td>
        </tr>
        <tr>
          <th>Peso</th>
          <td> <?php echo $pac['tri_peso']; ?> kg </td>
        </tr>
        <tr>
          <th>Altura</th>
          <td> <?php echo $pac['tri_altura']; ?> m </td>
        </tr>
        <tr>
          <th>Temperatura</th>
          <td> <?php echo $pac['tri_temperatura']; ?> ºC </td>
        </tr>
        <tr>
          <th>Pressão</th>
          <td> <?php echo $pac['tri_pressao']; ?> mmHg </td>
        </tr>
        <tr>
          <th>Batimento</th>
          <td> <?php echo $pac['tri_batimento']; ?> bpm </td>
        </tr>
        <tr>
          <th>Respiração</th>
          <td> <?php echo $pac['tri_respiracao']; ?> rpm </td>
        </tr>
        <tr>
          <th>Oxigenação</th>
          <td> <?php echo $pac['tri_oxigenacao']; ?> % </td>
        </tr>
        <tr>
          <th>Dor</th>
          <td> <?php echo $pac['tri_dor']; ?>/10 </td>
        </tr>
        <tr>
          <th>Tipo sanguíneo</th>
          <td> <?php echo $tipoSanguineo; ?> </td>
        </tr>
        <tr>
          <th>Comprometimento dos orgãos vitais</th>
          <td> <?php echo $org; ?> </td>
        </tr>
        <tr>
          <th>Doenças</th>
          <td> <?php echo $pac['tri_doenca']; ?> </td>
        </tr>
        <tr>
          <th>Remédios</th>
          <td> <?php echo $pac['tri_remedio']; ?> </td>
        </tr>
        <tr>
          <th>Sintomas</th>
          <td> <?php echo $pac['tri_sintomas']; ?> </td>
        </tr>
        <tr>
          <th>Reclamação</th>
          <td> <?php echo $pac['tri_reclamacao']; ?> </td>
        </tr>
      </table>

      <form action="consulta.php" method="post">
        <input type="hidden" name="triId" value="<?php echo $pac['tri_id'] ?>">
        <input type="hidden" name="chegada" value="<?php echo date('H:m:i') ?>">
        <input type="hidden" name="data" value="<?php echo date('Y-m-d') ?>">
        <label> Reclamação: </label>
        <input type="text" name="reclamacao"> <br>
        <label> Sintomas: </label>
        <input type="text" name="sintomas"> <br>
        <label> Diagnóstico presuntivo: </label>
        <input type="text" name="diagnostico"> <br>
        <label> Encaminhamento: </label>
        <?php $sql->selectbox('encaminhamento'); ?>
        <label> Comentário: </label>
        <input type="text" name="comentario"> <br>

        <h3> Receita </h3>

        <?php
        $r = 0;

        if (isset($_POST['adicionar'])) {
          $r = $_POST['med'] + 1;
        } elseif (isset($_POST['remover']) && $_POST['med'] > 0) {
          $r = $_POST['med'] - 1;
        } elseif (isset($_POST['limpar'])) {
          $r = 0;
        }
        ?>
        <input type="hidden" name="med" value="<?php echo $r ?>">
        <input type="submit" name="adicionar" value="Adicionar medicamento">
        <input type="submit" name="remover" value="Remover medicamento">
        <input type="submit" name="limpar" value="Limpar"> <br>
        <?php

        for ($i = 1; $i <= $r; $i++) {
          if ($i < 10) {
            $i = "0" . $i;
          }

          echo "
          <label> Medicamento " . $i . ": </label>
          <input type='number' size='3' name='quantidade" . $i . "'>
          <select name='unidade" . $i . "'>
            <option> mg </option>
            <option> g </option>
            <option> mL </option>
            <option> L </option>
          </select> de
          <input type='text' name='medicamento" . $i . "'> a cada
          <input type='number' size='2' name='tempo" . $i . "'>
          <select name='unidadeTempo" . $i . "'>
            <option> horas </option>
            <option> dias </option>
          </select> durante
          <input type='number' name='periodo" . $i . "'> dias
          <br>
          ";
        }

        ?>

        <input type="submit" name="cancelar" value="Cancelar consulta">
        <input type="submit" name="encerrar" value="Encerrar consulta">

      </form>
    <?php
  } elseif (isset($_POST['encerrar']) || isset($_POST['cancelar'])) {
    $chegada = $_POST['chegada'];
    $data = $_POST['data'];
    $saida = date('H:m:i');
    $reclamacao = isset($_POST['reclamacao']) ? trim($_POST['reclamacao']) : "";
    $sintomas = isset($_POST['sintomas']) ? trim($_POST['sintomas']) : "";
    $diagnostico = isset($_POST['diagnostico']) ? trim($_POST['diagnostico']) : "";
    $encaminhamento = $_POST['encaminhamento'];
    $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : "";
    $triId = $_POST['triId'];

    $cons->setChegada($chegada);
    $cons->setData($data);
    $cons->setSaida($saida);
    $cons->setReclamacao($reclamacao);
    $cons->setSintomas($sintomas);
    $cons->setDiagnostico($diagnostico);
    $cons->setEncId($encaminhamento);
    $cons->setComentario($comentario);
    $cons->setTriId($triId);
    $cons->setMedId(1);

    $mensagem = "";

    if (isset($_POST['encerrar'])) {
      $st = "UPDATE triagem SET tri_status = 5 WHERE tri_id = " . $cons->getTriId();

      $query = "INSERT INTO consulta(con_hora_chegada, con_hora_saida, con_data, con_reclamacao,
        con_doenca, con_diagnostico, con_enc_id, con_comentario, con_tri_id, con_med_id) VALUES('"
        . $cons->getChegada() . "', '" . $cons->getSaida() . "', '" . $cons->getData() . "', '"
        . $cons->getReclamacao() . "', '" . $cons->getSintomas() . "', '" . $cons->getDiagnostico() . "', "
        . $cons->getEncId() . ", '" . $cons->getComentario() . "', " . $cons->getTriId() . ", " . $cons->getMedId() . ");";

      if ($sql->num("SELECT con_id FROM consulta WHERE con_tri_id = " . $cons->getTriId() . ";") == 0) {
        $con = $sql->conecta();

        mysqli_query($con, $query) or die("Erro");

        if ($_POST['med'] > 0) {
          include_once 'Receita.class.php';
          $rec = new Receita();

          for ($i = 1; $i <= $_POST['med']; $i++) {
            if ($i < 10) {
              $i = "0" . $i;
            }

            $rec->setQuantidade($_POST['quantidade' . $i]);
            $rec->setUnidade($_POST['unidade' . $i]);
            $rec->setMedicamento($_POST['medicamento' . $i]);
            $rec->setTempo($_POST['tempo' . $i]);
            $rec->setUnidadeTempo($_POST['unidadeTempo' . $i]);
            $rec->setPeriodo($_POST['periodo' . $i]);

            $rec->montar();
          }

          echo $rec->getReceita() . "<br>";

          $rec->setData(date('Y-m-d'));
          $rec->setConsultaId(mysqli_insert_id($con));

          $queryReceita = "INSERT INTO receita(rec_data, rec_prescricao, rec_consulta) VALUES('"
            . $rec->getData() . "', '" . $rec->getReceita() . "', " . $rec->getConsultaId() . ");";

          $sql->inserir($queryReceita);
        }

        mysqli_close($con);

        $mensagem = "Inserido com sucesso.";
      } else {
        $mensagem = "O paciente já passou pela consulta.";
      }

    } elseif (isset($_POST['cancelar'])) {
      $st = "UPDATE triagem SET tri_status = 6 WHERE tri_id = " . $cons->getTriId();
      $mensagem = "Consulta cancelada.";
    }

    $sql->inserir($st);
    echo $mensagem;
  } else {
    $query = "SELECT * FROM pessoa
      INNER JOIN paciente ON paciente.pessoa_pes_id = pessoa.pes_id
      INNER JOIN atendimento ON atendimento.ate_pac_id = paciente.pac_id
      INNER JOIN triagem ON triagem.tri_ate_id = atendimento.ate_id
      WHERE triagem.tri_status = 3";

    $res = $sql->inserir($query);

    ?>

    <table border="1">
      <thead>
        <th> # </th>
        <th> Nome </th>
        <th> Classificação </th>
        <th> Tempo de espera </th>
      </thead>
      <tbody>
        <?php

        if ($sql->num($query) == 0) {
          echo
          "<tr>
            <td colspan='4'> Não há ninguém aguardando atendimento </td>
          </tr>";
        } else {
          while ($pac = mysqli_fetch_array($res)) {
            $espera = $fila->calc($pac['tri_data'], $pac['tri_hora']);
            $cor = $fila->getCor($pac['tri_classe_risco']);
            $class = $cor[0];

            echo
            "<form action='consulta.php' method='post'>
              <tr>
                <td>" . $pac['tri_id'] . "</td>
                <td>" . $pac['pes_nome'] . "</td>
                <td>" . $class . "</td>
                <td>" . $espera  . "/" . $cor[1] . " min </td>
                <input type='hidden' value='" . $pac['tri_id'] . "' name='triId'>
                <td> <input type='submit' name='consulta' value='Iniciar consulta'> </td>
              </tr>
            </form>";
          }
        }

        ?>
      </tbody>
    </table>

    <?php
  }
