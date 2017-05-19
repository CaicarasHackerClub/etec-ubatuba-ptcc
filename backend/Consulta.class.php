<?php

include_once 'Fila.class.php';

class Consulta extends Fila {
  private $chegada;
  private $saida;
  private $data;
  private $comentario;
  private $triId;
  private $medId;
  private $encId;

  public function setChegada($chegada) {
    $this->chegada = $chegada;
  }

  public function getChegada() {
    return $this->chegada;
  }

  public function setSaida($saida) {
    $this->saida = $saida;
  }

  public function getSaida() {
    return $this->saida;
  }

  public function setData($data) {
    $this->data = $data;
  }

  public function getData() {
    return $this->data;
  }

  public function setComentario($comentario) {
    $this->comentario = $comentario;
  }

  public function getComentario() {
    return $this->comentario;
  }

  public function setTriId($triId) {
    $this->triId = $triId;
  }

  public function getTriId() {
    return $this->triId;
  }

  public function setMedId($medId) {
    $this->medId = $medId;
  }

  public function getMedId() {
    return $this->medId;
  }

  public function setEncId($encId) {
    $this->encId = $encId;
  }

  public function getEncId() {
    return $this->encId;
  }
}
